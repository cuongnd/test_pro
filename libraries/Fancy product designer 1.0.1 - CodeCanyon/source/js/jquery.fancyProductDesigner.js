/*
 * Fancy Product Designer v1.0.1
 *
 * Copyright 2011, Rafael Dery
 *
 */
 
;(function($) {

	var FancyProductDesigner = function( elem, args) {
		
		var options = $.extend({}, $.fn.fancyProductDesigner.defaults, args);
		
		var thisClass = this,
			$elem,
			$productSelection, 
			$productContainer, 
			$designSelection,
			$toolbar,
			$colorPicker,
			$fontsDropdown, 
			$editorbar,
			$products,
			$designs,
			$editorBox,
			$views,
			viewsParams,
			elementCounter = 0, 
			canvasIsSupported,
			currentProductIndex = -1,
			currentViewIndex = 0,
			currentElement = null,
			currentPrice = 0,
			dragIt = false,
			resizeIt = false,
			rotateIt = false,
			elemIsOut = false,
			defaultFont = 'Helvetica',
			inputPadding = {tb: 8, lr: 5};
		
		
		
		$elem = $(elem).addClass('fpd-container clearfix');
		$products = $elem.children('.fpd-product').remove();
		$designs = $elem.children('.fpd-design, .fpd-motive').children('img, span');
		
		//test if canvas is supported		
		var canvasTest = document.createElement('canvas');
		canvasIsSupported = Boolean(canvasTest.getContext && canvasTest.getContext('2d'));

		if(!canvasIsSupported) {
			$elem.append('<div class="fpd-browser-alert"><p>'+options.canvasAlert+'</p></div>').children('div')
			.append('<span><a href="http://www.mozilla.org/firefox/new/" title="Download Firefox" class="firefox"></a><a href="http://www.google.com/Chrome" title="Download Chrome" class="chrome"></a><a href="http://www.opera.com/download/" title="Download Opera" class="opera"></a></span>');
			
			$elem.trigger('canvasFail');
			return false;
		}	
		
		if($products.size() > 1) {
			$productSelection = $elem.append('<div class="fpd-product-selection"><a href="#" class="fpd-scroll-up ui-state-default"><span class="ui-icon ui-icon-carat-1-n"></span></a><div><ul></ul></div><a href="#" class="fpd-scroll-down ui-state-default"><span class="ui-icon ui-icon-carat-1-s"></span></a></div>').children('.fpd-product-selection').find('ul');
		}
		
		$productContainer = $elem.append('<div class="fpd-product-container"><div><div class="fpd-toolbar"></div></div><section class="clearfix"></section></div>').children('.fpd-product-container').children('div');
		
		if($designs.size() > 0) {
			$designSelection = $elem.append('<div class="fpd-design-selection"><a href="#" class="fpd-scroll-up ui-state-default"><span class="ui-icon ui-icon-carat-1-n"></span></a><div><ul class="clearfix"></ul></div><a href="#" class="fpd-scroll-down ui-state-default"><span class="ui-icon ui-icon-carat-1-s"></span></a></div>').children('.fpd-design-selection').find('ul');
			$designs.parent().remove();
		}
		
		
		$toolbar = $productContainer.children('.fpd-toolbar').append('<div class="fpd-color-picker clearfix"><input type="text" value="" disabled="disabled" /></div><select class="fpd-fonts-dropdown"></select><div class="fpd-reset ui-state-default" title="Reset"><span class="ui-icon ui-icon-refresh"></span></div><div class="fpd-deselect ui-state-default" title="Deselect"><span class="ui-icon ui-icon-closethick"></span></div>');
		$editorbar = $productContainer.parent().children('section');
		
		$colorPicker = $toolbar.children('.fpd-color-picker');
				
		//----------------------------------
		// ------- PUBLIC METHODS ----------
		//----------------------------------
		
		//get the current product with all elements
		this.getProduct = function(onlyEditableElements) {
			 onlyEditableElemets = typeof onlyEditableElements !== 'undefined' ? onlyEditableElements : false;
			
			if(elemIsOut) {
				alert(options.outOfContainmentAlert)
				return false;
			}
			
			//check if views are used
			if($productContainer.children('.fpd-views').length > 0) {
				var views = [],
					viewElements = $productContainer.children('.fpd-views').children('li');
				
				//loop through all views
				for(var i=0; i < viewElements.length; ++i) {
					var viewElement = viewElements[i],
						params,
						jsonProduct = { title: $(viewElement).children('img').attr('title'), elements : []};
					
					//check if the view already stored all elements
					if($(viewElement).data('elements') == undefined) {
						//get elements from the default view
						$($views.get(i)).children('img, span').each(function(i, item) {
							var $item = $(item),
							params = $.extend({}, options.elementParameters, $item.data('parameters'));
							
							if(onlyEditableElements) {
								if(params.colors.length > 0 || params.removable || params.draggable || params.resizable || params.rotatable) {
									var jsonItem = {};
									jsonItem.title = $item.attr('title');
									jsonItem.source = $item.attr('src');
									jsonItem.parameters = params;
									jsonProduct.elements[jsonProduct.elements.length] = jsonItem;
								}
							}
							else {
								var jsonItem = {};
								jsonItem.title = $item.attr('title');
								jsonItem.source = $item.attr('src');
								jsonItem.parameters = params;
								jsonProduct.elements[jsonProduct.elements.length] = jsonItem;
							}
							
						});
					}
					else {
						//get view from data elements
						if(onlyEditableElements) {
							var elements = [];
							$($(viewElement).data('elements')).each(function(i, item) {
								var params = item.parameters;
								if(params.colors.length > 0 || params.removable || params.draggable || params.resizable || params.rotatable) {
									elements.push(item);
								}
								
							});
							jsonProduct.elements = elements;
						}
						else {
							jsonProduct.elements = $(viewElement).data('elements');
						}
						
					}
					//push view in an array					
					views.push(jsonProduct);	
				}
				return views;
			}
			else {
				//no views are used
				var jsonProduct = { title: $products[currentProductIndex].title, elements : []};
				$productContainer.children(onlyEditableElements ? '.fpd-editable' : '.fpd-element').each(function(i, item) {
					var jsonItem = {}, 
						$item = $(item);
					jsonItem.title = $item.attr('title');
					jsonItem.source = $item.data('source');
					jsonItem.parameters = $item.data('params');
					jsonProduct.elements[i] = jsonItem;
				});
				//return an object for a single view
				return jsonProduct;
			}			
		};
		
		//add an element to the product
		this.addElement = function(type, source, title, params) {

			if(typeof params != "object") {
				alert("The element "+title+" has not a valid JSON object as parameters!");
				return false;
			}
			
			params = $.extend({}, options.elementParameters, params);
			params.source = source;
			params.originX = params.x;
			params.originY = params.y;

			var lastItemContainer = $productContainer.append('<div class="fpd-element"></div>').children('div:last')
													 .css({left: params.x, top: params.y, 'z-index': $productContainer.children('div').size()-1})
													 .attr('title', title)
													 .data('source', source)
													 .attr('id', $productContainer.children('.fpd-element').size()-1);
			
			_setRotation(lastItemContainer, params.degree);									 
													 
			//store current color and convert colors in string to array
			if(params.colors && typeof params.colors == 'string') {
				var colors = params.colors.replace(/\s+/g, '').split(',');
				params.colors = colors;
				params.currentColor = colors[0];
			}			
			
			if(type == 'image') {
				var image = new Image();
				image.src = source;
				
				image.onload = function() {
					var w = params.width ? params.width : image.width * params.scale,
						h = params.height ? params.height : image.height * params.scale;
					
					//draw a html5 canvas
					if(params.colors.length > 0) {
						_createCanvasElement(lastItemContainer, this, params.currentColor);
					}
					//just append an img
					else {
						lastItemContainer.append(image);
					}

					lastItemContainer.children('canvas, img').width(w).height(h).parent().css({width: w, height: h});
						
					if(params.colors.length > 0 || params.removable || params.draggable || params.resizable || params.rotatable) {
						_registerElementHandler(lastItemContainer);
					}
					else {
						lastItemContainer.css('pointer-events', 'none');
					}
					
					params.originWidth = w;
					params.originHeight = h;
					params.width = w;
					params.height = h;					
					lastItemContainer.data('params', params);
					
					$elem.trigger('elementAdded', [lastItemContainer]);	
				};				
				
			}
			else if(type == 'text') {
				params.text = params.text ? params.text : params.source;
				params.font = params.font ? params.font : defaultFont;
				params.textSize = params.textSize ? params.textSize  : options.textSize * params.scale;
				
				var input = $('<input type="text" value="'+params.text+'" />');
				
				lastItemContainer.append(input)
				.children('input:first')
				.css({'fontSize': params.textSize,
					  'fontFamily': params.font,	 
					  'paddingTop': inputPadding.tb, 
					  'paddingRight': inputPadding.lr, 
					  'paddingBottom': inputPadding.tb, 
					  'paddingLeft': inputPadding.lr})
			    .autoGrowInput().keyup(function() {
			    	if(currentElement) {
				    	currentElement.data('params').text = this.value;
				    	currentElement.children('input').attr('value', this.value);
			    	}
			    });
			    
			    setTimeout(function() {
			    	input.keyup();
			    }, 200);
				
				if(params.colors.length > 0) {
					input.css('color', params.currentColor);
				}
				
				_registerElementHandler(lastItemContainer);
				
				lastItemContainer.data('params', params);
				
				$elem.trigger('elementAdded', [lastItemContainer]);								
			}
			else {
				alert('Sorry. This type of element is not allowed!');
			}
			
			if(params.price) {
				currentPrice += params.price;
				$elem.trigger('priceChange', [params.price, currentPrice]);
			}
			
			if($views.size() > 0) {
				viewsParams.push({"title": title, "source": source, "parameters": params});
				$($productContainer.children('.fpd-views').children('li').get(currentViewIndex)).data('elements', viewsParams);
			}
			
		};
		
		//adds a new design to the design sidebar
		this.addDesign = function(source, title, parameters) {
			$designSelection.prepend('<li></li>').children('li:first').append('<img src="'+source+'" title="'+title+'" />').click(function() {
				var $img = $(this).children('img');
				thisClass.addElement('image', $img.attr('src'), $img.attr('title'), $img.data('parameters'));
				return false;
			}).children('img').data('parameters', parameters);
		};
		
		//returns the current price for the product
		this.getPrice = function() {
			return currentPrice;
		};
		
		//opens a pop-up window to print the product
		this.print = function() {
		
			_deselectElement();
			
			var popup = window.open('print.html','','width='+$productContainer.width()+',height='+$productContainer.height()+'');
			
			$(popup).load(function() {
				var html = $productContainer;
				popup.document.title = $products[currentProductIndex].title;
				$(popup.document.body).children('#fpd').append(html.html());
				$(popup.document.body).find('.fpd-views').remove();
				$(popup.document.body).find('canvas').each(function(index, canvas) {
				
					var id = $(canvas).parent().attr('id'),
						targetElement = $productContainer.children('div[id="'+id+'"]'),
						originCanvasContext = targetElement.children('canvas').get(0).getContext('2d'),
						canvasContext = canvas.getContext('2d'),
						color = targetElement.data('params').currentColor,
						imageData = originCanvasContext.getImageData(0, 0, originCanvasContext.canvas.width, originCanvasContext.canvas.height),
						data = imageData.data;

				    for (var i = 0; i < data.length; i += 4) {
				        data[i] = _HexToR(color);
				        data[i + 1] = _HexToG(color);
				        data[i + 2] = _HexToB(color);
				    }
				    
				 	canvasContext.putImageData(imageData, 0, 0);
					
				});
				
				setTimeout(popup.print, 1000);
			});
			
		};
		
		//removes all elements from the product container
		this.clear = function() {
			_deselectElement();
			$productContainer.children('.fpd-element').remove();
		}
		
		
		//----------------------------------
		// ------- PRIVATE METHODS ----------
		//----------------------------------
		
		//load product by index
		var _loadProduct = function(index) {
			
			if(index == currentProductIndex) {
				return false;
			}
			
			currentProductIndex = index;
			
			$productContainer.children('.fpd-views').remove();
			
			$views = $($products.get(index)).children('.fpd-product');			
			
			if($views.size() > 0) {
				$views.splice(0,0,$products.get(index));

				var $viewList = $productContainer.append('<ul class="fpd-views clearfix"></ul>').children('.fpd-views');
								
				for (var i=0; i < $views.length; ++i) {
					var $view = $($views[i]);
					$viewList.append('<li><img src="'+$view.data('thumbnail')+'" title="'+$view.attr('title')+'" /></li>');
				}
				
				_createProduct($($products.get(index)).children('img, span'));
				
				$viewList.children('li').click(function() {
					var index = $viewList.children('li').index($(this));
					if(index != currentViewIndex) {
						currentViewIndex = index;
						_createProduct($($viewList.children('li').get(index)).data('elements') == undefined ? $($views.get(index)).children('img, span') : $($viewList.children('li').get(index)).data('elements'));
					}
					
				});
			}
			else {
				_createProduct($($products.get(index)).children('img, span'));
			}
			
		};
		
		var _createProduct = function(elements) {
			
			viewsParams = [];
			elementCounter = currentPrice = 0;
			_deselectElement();
		
			$productContainer.children('.fpd-element').remove();
		
			for(var i=0; i < elements.length; ++i) {
				var $item = $(elements[i]);
				if($item.is('img,span')) {
					var params =  $item.data('parameters') == undefined ? {} : $item.data('parameters');
					thisClass.addElement( $item.is('img') ? 'image' : 'text', $item.is('img') ? $item.attr('src') : $item.text(), $item.attr('title'), params);
				}
				else {
					thisClass.addElement( elements[i].parameters.text == undefined ? 'image' : 'text', elements[i].parameters.source, elements[i].title, elements[i].parameters);
				}
				
			}
			
		}
		
		var _registerElementHandler = function(element) {
				
			element.addClass('fpd-editable').children('img, canvas, input').css('cursor', 'pointer').click(function() {
				
				_deselectElement();
				
				var elemParams = element.data('params');				
				$toolbar.show();
				
				if(element.children('input').size() > 0) {
					$fontsDropdown.show();
					$fontsDropdown.children('option[value="'+elemParams.font+'"]').prop('selected', 'selected');
					
				}
				
				currentElement = element.addClass('selected');					

				//remove element
				if(elemParams.removable) {
					currentElement.append('<button title="Remove element" class="fpd-remove ui-state-default ui-corner-all"><span class="ui-icon ui-icon-trash"></span></button>')
					.children('.fpd-remove ')
					.hammer()
					.bind("tap", function() {
						if(currentElement.data('params').price != 0) {
							currentPrice -= currentElement.data('params').price;
							$elem.trigger('priceChange', [currentElement.data('params').price, currentPrice]);
						}
						viewsParams.splice(currentElement.attr('id'), 1);
						$(this).parent().remove();						
						_deselectElement();
						
						return false;
					});
				}
				
				//drag element
				if(elemParams.draggable) {
					var tempx = tempy = null;
					currentElement.append('<button title="Drag element" class="fpd-drag ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrow-4"></span></button>')
					.children('.fpd-drag')
					.hammer({drag_min_distance: 1})
					.bind("dragstart", function(evt) {
						dragIt = true;
					});
				}
				
				//resize element
				if(elemParams.resizable) {
					currentElement.append('<button title="Resize element" class="fpd-resize ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowthick-2-se-nw"></span></button>')
					.children('.fpd-resize')
					.hammer({drag_min_distance: 1})
					.bind("dragstart", function(evt) {
						resizeIt = true;
						 
					});
				}
				
				//rotate element
				if(elemParams.rotatable) {
					currentElement.append('<button title="Rotate element" class="fpd-rotate ui-state-default ui-corner-all"><span class="ui-icon ui-icon-arrowrefresh-1-e"></span></button>')
					.children('.fpd-rotate')
					.hammer({drag_min_distance: 1})
					.bind("dragstart", function(evt) {
						rotateIt = true;
						 
					});					
				}
				
				//check for colors
				if(elemParams.colors) {
					$colorPicker.children('input').val(elemParams.currentColor);

					//color list
					if(elemParams.colors.length > 1) {
						$colorPicker.children('input').spectrum("destroy").spectrum({
							preferredFormat: "hex",
							showPaletteOnly: true,
							palette: elemParams.colors,
							change: function(color) {
								_changeColor(currentElement, color.toHexString());
							}
						});
					}
					//palette
					else {
						$colorPicker.children('input').spectrum("destroy").spectrum({
							preferredFormat: "hex",
							showInput: true,
							chooseText: "Change Color",
							change: function(color) {
								_changeColor(currentElement, color.toHexString());
							}
						});
					}
					
					$colorPicker.show();
				}
				else {
					$colorPicker.hide();
				}
				
				//check if boundingbox is calculated by another element
				if(typeof elemParams.boundingBox == "string") {
					var containment = $productContainer.children('div[title="'+elemParams.boundingBox+'"]');
					if(containment.size() > 0) {
						elemParams.boundingBox = {x: containment.position().left, y: containment.position().top, width: containment.width(), height: containment.height()};
					}
				}
				
				//append boundingbox
				if(typeof elemParams.boundingBox == "object") {
					var bb = elemParams.boundingBox;
					$productContainer.append('<div class="containment"></div>').children('.containment')
					.css({left: bb.x, top: bb.y, width: bb.width, height: bb.height, 'z-index': $productContainer.children('div').size()-1});
				}

				currentElement.children('button').disableSelection().parent().css('z-index', $productContainer.children('div').size());
				
				if(options.editorMode) { 
					_setEditorValues();
				}
				
			});
						
		};
		
		//creates a canvas from an image			
		var _createCanvasElement = function( container, image, defaultColor ) {
			
			var canvas = document.createElement('canvas'), canvasContext = canvas.getContext('2d');
			canvas.width = image.width;
			canvas.height = image.height;
			canvasContext.drawImage(image, 0, 0);
			container.append(canvas);
			
			_setCanvasColor(canvasContext, defaultColor);
			
		};
		
		var _changeColor = function(element, hex) {
			
			if(hex.length == 4) {
				hex += hex.substr(1, hex.length);
			}
			if(element.children('input').size() > 0) {
				//set color of a text element
				element.children('input').css('color', hex);
			}	
			else {
				//set color of a canvas element
				var canvas = element.children('canvas').get(0);
				_setCanvasColor(canvas.getContext('2d'), hex);
			}
			element.data('params').currentColor = hex;
			$colorPicker.children('input').spectrum("set", hex);
		}
		
		//set the color for the canvas
		var _setCanvasColor = function( context, color ) {
			var imageData = context.getImageData(0, 0, context.canvas.width, context.canvas.height);
		    var data = imageData.data;
		 
		    for (var i = 0; i < data.length; i += 4) {
		        data[i] = _HexToR(color);
		        data[i + 1] = _HexToG(color);
		        data[i + 2] = _HexToB(color);
		    }
		 
		    // overwrite original image
		    context.putImageData(imageData, 0, 0);
		    context.canvas.fillStyle = color;
		};
		
		//deselect all element
		var _deselectElement = function() {
		
			if(currentElement) {
				currentElement.css('z-index', currentElement.data('index'));
			}
			
			$productContainer.children('div').removeClass('selected').children('button').remove();
			$productContainer.children('div.containment').remove();
			$colorPicker.hide();
			$toolbar.hide();
			$fontsDropdown.hide();
			currentElement = null;
			
			if(options.editorMode) {
				$editorBox.find('p > span:nth-child(2n)').text('');
			}
		};
		
		//check if element is in the containment
		var _checkContainment = function(x, y, w, h) {
			
			var bb = currentElement.data('params').boundingBox,
				isOut = false;
			if(x < bb.x) {isOut = true;}
			if(y < bb.y) {isOut = true;}
			if((x + w) > (bb.x + bb.width)) {isOut = true;}
			if((y + h) > (bb.y + bb.height)) {isOut = true;}
			return isOut;
			
		};
		
		var _setEditorValues = function() {
			if(currentElement) {
				var params = currentElement.data('params');
				$editorBox.children('.fpd-current-element').children('span:last').text(currentElement.attr('title'));
				$editorBox.children('.fpd-position').children('span:last').text('x: ' + params.x + ', y: ' + params.y);
				$editorBox.children('.fpd-dimensions').children('span:last').text(params.width ? 'Width: ' + params.width + 'px, Height: ' + params.height +'px': 'Textsize: ' + params.textSize +'px');
			}
			
		};
		
		var _setRotation = function(element, degree) {
			element.css('-moz-transform', 'rotate('+degree+'deg)');
			element.css('-webkit-transform', 'rotate('+degree+'deg)');
			element.css('-o-transform', 'rotate('+degree+'deg)');
			element.css('-ms-transform', 'rotate('+degree+'deg)');
		};
		
		//converts hex colors ro rgb
		var _HexToR = function(h) {return parseInt((_cutHex(h)).substring(0,2),16)};
		var _HexToG = function(h) {return parseInt((_cutHex(h)).substring(2,4),16)};
		var _HexToB = function(h) {return parseInt((_cutHex(h)).substring(4,6),16)};
		var _cutHex = function(h) {return (h.charAt(0)=="#") ? h.substring(1,7):h};
		
		
		
		//----------------------------------
		// ------- CONSTRUCTER -------------
		//----------------------------------		
		
		$elem.children('div:last').css('marginRight', 0).trigger('ready');
		
		if($productSelection) {
			//append products to list
			for(var i=0; i < $products.length; ++i) {
				var $product = $($products[i]);
				$productSelection.append('<li><img src="'+$product.data('thumbnail')+'" title="'+$product.attr('title')+'" /></li>');								
			}
			
			//load product by click
			$productSelection.children('li').click(function() {
				var index = $productSelection.find('li').index($(this));
				currentElement = null;
				_loadProduct(index);
				return false;
			});
		}	
		
		if($designSelection) {
			//append designs to list
			for(var i=0; i < $designs.length; ++i) {
				thisClass.addDesign($designs[i].src, $designs[i].title, $($designs[i]).data('parameters'));
			}
		}
		
		//change font family when dropdown changes
		$fontsDropdown = $toolbar.children('.fpd-fonts-dropdown').change(function() {
			currentElement.data('params').font = this.value;
			currentElement.children('input').css('font-family', this.value);
			//timeout is needed for webkit browsers
			setTimeout(function() {
				currentElement.children('input').keyup();
			}
			, 200);
				
		});
		
		
		//append custom text button if requested
		if(options.customTexts) {
			$editorbar.append('<button title="Add custom text">'+options.customTexts+'</button>').children('button:last').click(function() {
				thisClass.addElement('text', options.defaultCustomText, options.defaultCustomText, options.customTextParamters);
				return false;
			});
		}
		
		//append element switchers
		$editorbar.append('<div class="fpd-element-switcher clearfix"><button title="Select previous editable element" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-1-w"></span></button><button title="Select next editable element" class="ui-state-default"><span class="ui-icon ui-icon-arrowthick-1-e"></span></button></div>').find('.fpd-element-switcher > button:first')
		.click(function() {
			if(currentElement) {
				if(currentElement.prevAll('.fpd-editable:first').size() == 0) {
					$productContainer.children('.fpd-editable:last').children('img, canvas, input').click();
				}
				else {
					currentElement.prevAll('.fpd-editable:first').children('img, canvas, input').click();
				}
			}
			else {
				$productContainer.children('.fpd-editable:first').children('img, canvas, input').click();
			}
			return false;
		}).parent().children('button:last')
		.click(function() {
			if(currentElement) {
				if(currentElement.nextAll('.fpd-editable:first').size() == 0) {
					$productContainer.children('.fpd-editable:first').children('img, canvas, input').click();
				}
				else {
					currentElement.nextAll('.fpd-editable:first').children('img, canvas, input').click();
				}
			}
			else {
				$productContainer.children('.fpd-editable:first').children('img, canvas, input').click();
			}
			return false;
		})
		
		
		//append fonts to dropdown
		if(options.fonts.length > 0 && options.fontDropdown) {
			defaultFont = options.fonts[0];
			options.fonts.sort();
			for(var i=0; i < options.fonts.length; ++i) {
				$fontsDropdown.append('<option value="'+options.fonts[i]+'" style="font-family: '+options.fonts[i]+';">'+options.fonts[i]+'</option>');
			}
			$fontsDropdown.children('option[value="'+defaultFont+'"]').prop('selected', 'selected');
			$fontsDropdown.show();
		}
		
		//scroll up
		$elem.find('.fpd-scroll-up').click(function() {
			var	list = $(this).next('div').children('ul'),
				offset = parseInt(list.css('top'))+options.scrollAmount > 0 ? Math.abs(parseInt(list.css('top'))) : options.scrollAmount;
				
			if(parseInt(list.css('top')) < 0 && list.is(':not(:animated)')) {
				list.animate({top: '+='+(offset)}, 200);
			}
			return false;
		});
		
		//scroll down
		$elem.find('.fpd-scroll-down').click(function() {
			var	wrapper = $(this).parent().children('div'),
				list = wrapper.children('ul'),
				offset = Math.abs(parseInt(list.css('top')))+wrapper.height()+options.scrollAmount < list.height() ? -options.scrollAmount :  Math.abs(parseInt(list.css('top')))+wrapper.height() - list.height();
			
			if(Math.abs(parseInt(list.css('top')))+wrapper.height() < list.height() && list.is(':not(:animated)')) {
				list.animate({top: '+='+(offset)}, 200);
			}
			return false;
		});		
		
		//reset element to his origin
		$toolbar.children('.fpd-reset').click(function() {
			if(currentElement) {
				var params = currentElement.data('params');
				currentElement.css({left: params.originX, top: params.originY, width: params.originWidth, height: params.originHeight});
				currentElement.children('img, canvas').width(params.originWidth).height(params.originHeight);
				currentElement.children('input').css({'fontSize': options.textSize * params.scale, 'fontFamily': defaultFont}).val(params.source).keyup();
				_setRotation(currentElement, 0);
			    if(params.colors) {
				    _changeColor(currentElement, params.colors[0]);
				    params.currentColor = params.colors[0];
			    }
			    
			    params.x = params.originX;
			    params.y = params.originY;
			    params.width = params.originWidth;
			    params.height = params.originHeight;
			    params.degree = 0;
			    currentElement.data('params', params);
			    
			    //trigger event as soon as a element is moving out of his containment
			    if(params.boundingBox) {
				   if(_checkContainment(params.x, params.y, params.width, params.height)) {
					   $elem.trigger('elementOut');
						elemIsOut = true;
				   }
				   else {
					   $elem.trigger('elementIn');
					   elemIsOut = false;
				   }
			    }
			}
		});
		
		//deselect element
		$toolbar.children('.fpd-deselect').click(function() {
			_deselectElement();
		});
		
		//handlers for the custom events
		$elem.bind('elementAdded', function(evt, div) {
		
			
			
			if(++elementCounter == $($products.get(currentProductIndex)).children('img, span').size()) {
				$elem.trigger('productCreate');
			}
		});
		
		
		$elem.bind('elementOut', function() {
			currentElement.append('<div class="fpd-warning"></div>').children('img, canvas, input').css('opacity', 0.5);
		});
		
		$elem.bind('elementIn', function() {
			currentElement.children('img, canvas, input').css('opacity', 1);
			currentElement.children('.fpd-warning').remove();
		});
		
		//mousemove handler for rotating, dragging, resizing an element
		$productContainer.hammer({drag_min_distance: 1, hold_timeout: 100}).bind("drag", function(evt) {
			if(currentElement != null) {
				var offset = currentElement.offset();
				var position = currentElement.position();
				var center_x = (offset.left) + (currentElement.width() / 2);
			    var center_y = (offset.top) + (currentElement.height() / 2);
				var mouse_x = evt.touches[0].x; 
			    var mouse_y = evt.touches[0].y;
			    var params = currentElement.data('params');
		    	if(rotateIt) {			        
			        var radians = Math.atan2(mouse_x - center_x, mouse_y - center_y);
			        var degree = (radians * (180 / Math.PI) * -1) - 130;
			        currentElement.css('-moz-transform', 'rotate('+degree+'deg)');
			        currentElement.css('-webkit-transform', 'rotate('+degree+'deg)');
			        currentElement.css('-o-transform', 'rotate('+degree+'deg)');
			        currentElement.css('-ms-transform', 'rotate('+degree+'deg)');
			        params.degree = degree;
				}
				else if(resizeIt) {
					if(currentElement.children('img, canvas').size() > 0) {
						var w = mouse_x-offset.left < options.minImageWidth ? options.minImageWidth : mouse_x-offset.left;
						if(w > options.maxImageWidth) { w = options.maxImageWidth; }
						var h = Math.round(params.originHeight * (w / params.originWidth));
						currentElement.children('img, canvas').css({width: w, height: h}).parent().width(w).height(h);
						params.width = w;
						params.height = h;
					}
					else {
						var fs = mouse_y-offset.top-20 < options.minTextSize ? options.minTextSize : mouse_y-offset.top-20;
						if(fs > options.maxTestSize) { fs = options.maxTestSize; }
						currentElement.children('input').css({'font-size': fs}).keyup();
						currentElement.data('params').textSize = fs;
					}
					
				}
				else if(dragIt) {
					currentElement.css({left: '+='+((mouse_x-currentElement.children('.fpd-drag').offset().left-4)), top: '+='+(mouse_y-currentElement.children('.fpd-drag').offset().top-4)});
					currentElement.data('params').x = currentElement.position().left;
					currentElement.data('params').y = currentElement.position().top;
				}
				if(params.boundingBox && (rotateIt || dragIt || resizeIt)) {
					var x = currentElement.get(0).getBoundingClientRect().left-$productContainer.get(0).getBoundingClientRect().left-1, 
						y = currentElement.get(0).getBoundingClientRect().top-$productContainer.get(0).getBoundingClientRect().top-1, 
						w = currentElement.get(0).getBoundingClientRect().width-3, 
						h = currentElement.get(0).getBoundingClientRect().height-2;
					if(currentElement.children('input').size() > 0) {
						x = x+inputPadding.lr, y = y+inputPadding.tb, w = w-(inputPadding.lr*2), h = h-(inputPadding.tb*2);
					}
					
					if(elemIsOut != _checkContainment(x, y, w, h)) {
						if( _checkContainment(x, y, w, h) ) {
							$elem.trigger('elementOut', [currentElement]);
							elemIsOut = true;
						}
						else {
							$elem.trigger('elementIn', [currentElement]);
							elemIsOut = false;
						}
					}
				}
				
				if(options.editorMode && (rotateIt || dragIt || resizeIt)) { 
					_setEditorValues();
				}
			}			
			
		})
		.bind("release", function(evt) {
			if($(evt.target).is('div')) {
				rotateIt = resizeIt = dragIt = false;
			}
		});
		
		if(options.editorMode) {
			$editorBox = $elem.append('<div class="fpd-editor-box"><h3>Editor Box</h3><p class="fpd-current-element"><span>Element: </span><span></span></p><p class="fpd-position"><span>Position: </span><span></span></p><p class="fpd-dimensions"><span>Dimensions: </span><span></span></p></div>').children('.fpd-editor-box');
		}
				
		//load first product
		_loadProduct(0);
				
					
	}; //plugin class ends
 	
	jQuery.fn.fancyProductDesigner = function( args ) {
		
		return this.each(function() {
		
			var element = $(this);
          
            // Return early if this element already has a plugin instance
            if (element.data('fancy-product-designer')) { return };

            var fpd = new FancyProductDesigner(this, args);

            // Store plugin object in this element's data
            element.data('fancy-product-designer', fpd);
            
		});
	};
	
	$.fn.fancyProductDesigner.defaults = {
		minImageWidth: 50, //the min. width for all image elements
		maxImageWidth: 300, //the max. width for all image elements
		minTextSize: 10, //the min. text size
		maxTestSize: 50, // the max. text size
		textSize: 18, //the default text size in px
		scrollAmount: 100, //the amount of the scrolling
		fontDropdown: true, //enable the font dropdown for texts
		fonts: ['Arial', 'Helvetica', 'Times New Roman', 'Verdana', 'Geneva'], //an array containing all available fonts
		customTexts: 'Add text', //enable the button to add custom texts to the product by enter a label or set it to false for disabling it
		defaultCustomText: "Enter your text here", // the default custom text when option customTexts is set to true
		customTextParamters: {}, //the parameters for the custom text
		editorMode: false, //enable the editor mode
		canvasAlert: 'Sorry! But your browser does not support HTML5 Canvas. Please update your browser!', //the alert when the browser is too old
		outOfContainmentAlert: 'An element is out of his containment. Please move it in his containment!', //the alert when a element is moving out of his containment
		elementParameters: {  x: 0, //the x-position
							  y: 0, //the y-position
							  colors: false, //false, a string with hex colors separated by commas for static colors or a single color value for enabling the colorpicker
							  removable: false, //false or true
							  draggable: false,  //false or true
							  rotatable: false, // false or true
							  resizable: false,  //false or true
							  scale: 1, // the scale factor
							  degree: 0, //the degree for the rotation
							  price: 0, //how much does the element cost
							  boundingBox: false //false, an element by title or an object with x,y,width,height
						   } //the default parameters for all elements (img, span)
	};

})(jQuery);



(function($){

$.fn.autoGrowInput = function(o) {

    o = $.extend({
        maxWidth: 400,
        minWidth: 10,
        comfortZone: 0
    }, o);

    this.filter('input:text').each(function(){

        var minWidth = o.minWidth || $(this).width(),
            val = '',
            input = $(this),
            testSubject = $('<tester/>').css({
                position: 'absolute',
                top: -9999,
                left: -9999,
                width: 'auto',
                fontSize: input.css('fontSize'),
                fontFamily: input.css('fontFamily'),
                fontWeight: input.css('fontWeight'),
                letterSpacing: input.css('letterSpacing'),
                whiteSpace: 'nowrap'
            }),
            check = function() {
            	testSubject.css({'font-size': input.css('fontSize'), 'font-family': input.css('fontFamily'), 'font-weight': input.css('fontWeight'), 'letter-spacing': input.css('letterSpacing') });
            	val = input.val();
                // Enter new content into testSubject
                var escaped = val.replace(/&/g, '&amp;').replace(/\s/g,' ').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                testSubject.html(escaped);

                // Calculate new width + whether to change
                var testerWidth = testSubject.width(),
                    newWidth = (testerWidth + o.comfortZone) >= minWidth ? testerWidth + o.comfortZone : minWidth;
               
               input.width(newWidth).val(val);

            };

        testSubject.insertAfter(input);

        $(this).bind('keyup keydown blur update', check);
        
        check();

    });

    return this;

};

})(jQuery);