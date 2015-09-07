/**
 * @version		$Id$
 * @author		NooTheme
 * @package		Joomla.Site
 * @subpackage	mod_noo_slider
 * @copyright	Copyright (C) 2013 NooTheme. All rights reserved.
 * @license		License GNU General Public License version 2 or later; see LICENSE.txt, see LICENSE.php
 */

(function($) {
	'use strict';
	$.fn.nooSliderLite = function(options) {
		options = $.extend({
			btnPrev: null,
			btnNext: null,
			btnGo: null,
			mouseWheel: false,
			auto: 1,
			interval:200,
			speed: 1000,
			easing: null,
			vertical: false,
			direction:'right',
			circular: true,
			visible: 5,
			start: 0,
			scroll: 1,
			action:'click',
			beforeStart: null,
			afterEnd: null,
			image_slider: 0,
			thumbHeight: 120
		}, options || {});

		return this.each(function() {
    	
			var running = false;
			var animCss = options.vertical ? 'top' : 'left';
			var sizeCss = options.vertical ? 'height' : 'width';
			
			var container = $(this);
			var div = $('div#wapper.noo-slider-wapper',this);
			var ul = $('ul.noo-slider-inner', div);
			var li = $('li.noo-slider-item', ul);
			var itemLength = li.size();
			var itemVisible = options.visible;
			
			if(!li){
				return;
			}
			
			if(itemVisible > itemLength) itemVisible = itemLength; // if number of slide is lower than visible items
			
			
			var isRun = false;
			
			if(options.circular) {
				ul.prepend(li.slice(itemLength - itemVisible).clone())
				.append(li.slice(0, itemVisible).clone());
				options.start += itemVisible;
			}

			var li = $('li.noo-slider-item', ul);
			var contentInner	= $('div.noo-content-slider', li);
			
			var itemLength = li.size();
			var curr = options.start;
			
			var itemWidth = Math.round( container.width() / itemVisible, 0);
			
			div.css('visibility', 'visible');
			
			ul.css({
				margin: '0', 
				padding: '0', 
				position: 'relative', 
				'list-style-type': 'none', 
				'z-index': '1'
			});
			
			li.css({
				overflow: 'hidden',
				'float': options.vertical ? 'none' : 'left',
				'padding':'5px',
				'margin':'0'
			});
			
			div.css({
				overflow: 'hidden', 
				position: 'relative', 
				'z-index': '2', 
				left: '0px'
			});
			
			container.css({
				overflow: 'hidden', 
				position: 'relative', 
				'z-index': '2', 
				left: '0px'
			});
			
			if(!options.vertical){
				li.css('width', itemWidth - 10);
			}
			
			// LuanND set equal height for all li item
			var highestBox = 0;
			if (options.image_slider) {
				ul.each(function(){
					highestBox = options.thumbHeight;
					$('.noo-slider-image',this).height(highestBox);
				});
			} else {
				ul.each(function(){
					$('.noo-content-slider', this).each(function() {
						if($(this).height() > highestBox)
							highestBox = $(this).height(); 
					});
					highestBox += 10; // padding: 10px;
					$('.noo-content-slider',this).height(highestBox);
				});
			}
			
			var outerBoxHeight	= highestBox + 32; // padding: 10px; margin: 5px; border: 1px;

			
			var liSize = options.vertical ? outerBoxHeight : itemWidth;   
			var ulSize = liSize * itemLength;                  
			var divSize = liSize * itemVisible;
			
			ul.css(sizeCss, ulSize + 'px').css(animCss, - (curr * liSize));
			
			if(options.vertical){
				div.css(sizeCss, divSize + 'px');  
			}else{
				div.css(sizeCss, divSize + 'px');  
				container.css(sizeCss, divSize + 'px');
			}
			if(options.btnPrev)
				$(options.btnPrev).bind(options.action,function() {
					//stop();
					if(options.direction == 'up' || options.direction == 'left'){
						return prev();
					}else{
						return next();
					}
				});

			if(options.btnNext)
				$(options.btnNext).bind(options.action,function() {
					//stop();
					if(options.direction == 'up' || options.direction == 'left'){
						return next();
					}else{
						return prev();
					}
				});

			if(options.btnGo)
				$.each(options.btnGo, function(i, val) {
					$(val).click(function() {
						return go(options.circular ? options.visible+i : i);
					});
				});

			if(options.mouseWheel && div.mousewheel)
				div.mousewheel(function(e, d) {
					return d>0 ? go(curr-options.scroll) : go(curr+options.scroll);
				});

			if(options.auto)
				play();

			function vis() {
				return li.slice(curr).slice(0,itemVisible);
			};
			function play(){
				stop();
				if(options.direction == 'up' || options.direction == 'left'){
					isRun = setInterval(function() {
						next();
					},options.interval);
				}else{
					isRun = setInterval(function() {
						prev();
					},options.interval);
				}
			};
			function next(){
				return go(curr+options.scroll)
			};
			function prev(){
				return go(curr-options.scroll)
			};
			function stop(){
				clearInterval(isRun);
				isRun = null;
			};
			function go(to) {
				if(!running) {

					if(options.beforeStart)
						options.beforeStart.call(this, vis());

					if(options.circular) {            
						if(to<=options.start-itemVisible-1) {    
							ul.css(animCss, -((itemLength-(itemVisible*2))*liSize) + 'px');
                        
							curr = to==options.start-itemVisible-1 ? itemLength-(itemVisible*2)-1 : itemLength-(itemVisible*2)-options.scroll;
						} else if(to>=itemLength-itemVisible+1) { 
							ul.css(animCss, -( (itemVisible) * liSize ) + 'px' );
                        
							curr = to==itemLength-itemVisible+1 ? itemVisible+1 : itemVisible+options.scroll;
						} else curr = to;
					} else {                    
						if(to<0 || to>itemLength-itemVisible) return;
						else curr = to;
					}                           

					running = true;

					ul.animate(
						animCss == "left" ? {
							left: -(curr*liSize)
						} : {
							top: -(curr*liSize)
						} , options.speed, options.easing,
						function() {
							if(options.afterEnd)
								options.afterEnd.call(this, vis());
							running = false;
						}
						);
                
					if(!options.circular) {
						$(options.btnPrev + "," + options.btnNext).removeClass("disabled");
						$( (curr-options.scroll<0 && options.btnPrev)
							||
							(curr+options.scroll > itemLength-itemVisible && options.btnNext)
							||
							[]
							).addClass("disabled");
					}

				}
				return false;
			};
		});
	};

	function css(el, prop) {
		return parseInt($.css(el[0], prop)) || 0;
	};
	function width(el) {
		return  el[0].offsetWidth + css(el, 'marginLeft') + css(el, 'marginRight');
	};
	function height(el) {
		return el[0].offsetHeight + css(el, 'marginTop') + css(el, 'marginBottom');
	};

})(jQuery);