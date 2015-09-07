/**
 * ------------------------------------------------------------------------
 * JA System Google Map plugin for J2.5 & J3.2
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

/**
 * USER GOOGLE MAP API VERSION 3
 * https://developers.google.com/maps/documentation/javascript/reference
*/

JAWidgetMap = new Class({
	initialize: function (container, defaults) {
		this.idPrefix = 'ja-widget-map';
		this.container = container;
		this.containerSV = container + '-streeview';
		this.containerR = container + '-route';
		this.containerR_height = 200;
		this.options = defaults;
		
		//
		this.context_menu = null;
		this.toolbar_control_style = null;
		this.maptype_control_style = null;
		this.GScaleControl = null;
		this.GOverviewMapControl = null;
		this.GScaleControl = null;
		this.layer = null;
		//
		
		this.createElement();
	},
	
	createElement: function(){
		var mapOptions = {};
		mapOptions.mapTypeId = this.getMapType(this.options.maptype);
		if(this.options.size) { 
			mapOptions.size = this.options.size; 
		}
		//tollbar
		if (this.options.toolbar_control_display == 1) {
			
			var controlPos = this.getPosition(this.options.toolbar_control_position);
			var toolbar_control_style;
			switch (this.options.toolbar_control_style) {
				case 'small':
					toolbar_control_style = google.maps.ZoomControlStyle.SMALL;
					break;
				case 'large':
					toolbar_control_style = google.maps.ZoomControlStyle.LARGE;
					break;
				default:
					toolbar_control_style = google.maps.ZoomControlStyle.DEFAULT;
					break;
			}
			this.toolbar_control_style = toolbar_control_style;
			
			mapOptions.zoomControl = true;
			mapOptions.zoomControlOptions = {
				style: toolbar_control_style,
				position: controlPos
			}
		} else {
			mapOptions.zoomControl = false;
			this.toolbar_control_style = null;
		}
		
		//maptype control
		if (this.options.maptype_control_display == 1) {
			var maptypeControlPos = this.getPosition(this.options.maptype_control_position);
			var maptype_control_style;
			switch (this.options.maptype_control_style) {
			case 'hierarchical':
				maptype_control_style = google.maps.MapTypeControlStyle.HORIZONTAL_BAR;
				break;
			case 'drop_down':
				maptype_control_style = google.maps.MapTypeControlStyle.DROPDOWN_MENU;
				break;
			default:
				maptype_control_style = google.maps.MapTypeControlStyle.DEFAULT;
				break;
			}
			this.maptype_control_style = maptype_control_style;
			
			mapOptions.mapTypeControl = true;
			mapOptions.mapTypeControlOptions = {
				style: maptype_control_style,
				position: maptypeControlPos
			}
		} else {
			mapOptions.mapTypeControl = false;
			this.maptype_control_style = null;
		}
		
		//scalse
		if (this.options.display_scale == 1) {
			mapOptions.scaleControl = true;
			mapOptions.scaleControlOptions = {
				position: google.maps.ControlPosition.BOTTOM_LEFT
			}
		} else {
			mapOptions.scaleControl = false;
			this.GScaleControl = null;
		}
		//overview
		if (this.options.display_overview == 1) {
			mapOptions.overviewMapControl = true;
			mapOptions.overviewMapControlOptions = {
				opened: true
			}
		} else {
			this.GOverviewMapControl = null;
		}
		
		this.objMap = new google.maps.Map($(this.container), mapOptions);
		
		//layers
		
		// Add ContextMenuControl
		if (this.options.context_menu == 1) {
			//this.context_menu = new ContextMenuControl();
			//this.objMap.addControl(this.context_menu);
		} else {
			this.context_menu = null;
		}
		
		//geo location
		this.geocoder = new google.maps.Geocoder();
		
		//direction
		this.objDirections = null;
		this.directionDisplay = null;
		if ($(this.containerR)) {
			this.objDirectionsPanel = $(this.containerR);
			this.objDirections = new google.maps.DirectionsService();
			this.directionDisplay = new google.maps.DirectionsRenderer();
			this.directionDisplay.setMap(this.objMap);
			this.directionDisplay.setPanel(this.objDirectionsPanel);
		}
		
	},
	
	resetMap: function() {
		
	},
	
	setMap: function(aOptions) {
		this.resetMap();
		
		this.options = aOptions;
		this.createElement();
	},
	
	setCenter: function (source) {
		if(source.objMap) {
			this.objMap.setCenter(source.objMap.getCenter());
			this.objMap.setZoom(source.objMap.getZoom());
		}
	},

	displayMap: function (fromA, toB, userInput) {
		
		fromA = (fromA != '' || userInput) ? fromA : this.options.from_location;
		toB = (toB != '') ? toB : this.options.to_location;
		
		if (this.objDirections != null) {
			//Clears any existing directions results, removes overlays from the map and panel, and cancels any pending load() requests. 
			this.directionDisplay.setMap(null);
		}
		
		if (toB == '') {
			alert('Please select a target Location!');
			return false;
		}
		
		if (fromA != '') {			
			//this.showLocation(toB);
			this.showDirections(fromA, toB);
		} else {
			var lat = this.options.target_lat.toFloat();
			var lon = this.options.target_lon.toFloat();
			
			if(!userInput && this.isLatLon(lat) && this.isLatLon(lon)){
				
				this.showLocation2(lat, lon);
			}else{
				this.showLocation(toB);
			}
		}
		
	},

	showLocation: function (address) {
		this.hideRoute();
		// hide route
		var lvZoom = this.options.zoom;
		var info = this.options.to_location_info.trim();
		var objMap = this.objMap;
		
		if (this.geocoder) {
			this.geocoder.geocode( { 'address': address}, function (results, status) {
				if (status != google.maps.GeocoderStatus.OK) {
					alert(address + " not found");
				} else {
					objMap.setCenter(results[0].geometry.location);
					objMap.setZoom(lvZoom);
					var marker = new google.maps.Marker({
						position: results[0].geometry.location,
						map: objMap,
						draggable: true
					});
					if(info != '') {
						 var infowindow = new google.maps.InfoWindow({
							content: info
						});
						google.maps.event.addListener(marker, 'click', function() {
						  infowindow.open(objMap,marker);
						});
					}
				}
			});
		}

	},

	showLocation2: function (lat, lon) {
		this.hideRoute();
		
		var lvZoom = this.options.zoom;
		var info = this.options.to_location_info.trim();
		var objMap = this.objMap;
		
		var point = new google.maps.LatLng(lat, lon);
		objMap.setCenter(point);
		objMap.setZoom(lvZoom);
		var marker = new google.maps.Marker({
						position: point,
						map: objMap,
						draggable: true
					});
		if(info != '') {
			google.maps.event.addListener(marker, 'click', function() {
			  infowindow.open(objMap,marker);
			});
		}
	},

	showDirections: function (fromAddress, toAddress) {
		if (this.objDirections != null) {
			var directionDisplay = this.directionDisplay;
			var objMap = this.objMap;
			var request = {
			  origin: fromAddress,
			  destination: toAddress,
			  travelMode: google.maps.DirectionsTravelMode.DRIVING
			};
			this.objDirections.route(request, function(response, status) {
			  if (status == google.maps.DirectionsStatus.OK) {
				directionDisplay.setDirections(response);
				directionDisplay.setMap(objMap)
			  }
			});
		}
	},
	
	isLatLon: function(number) {
		return (number == 0.00 || number.toString() == "NaN") ? false : true;
	},

	showRoute: function (height) {
		if($(this.containerR)) {
			if (!$(this.containerR).fx) {
				$(this.containerR).fx = new Fx.Tween($(this.containerR));
			}
			$(this.containerR).fx.start('height', height);
		}
	},
	hideRoute: function () {
		if($(this.containerR)) {
			if (!$(this.containerR).fx) {
				$(this.containerR).fx = new Fx.Tween($(this.containerR));
			}
			$(this.containerR).fx.start('height', 0);
		}
	},

	handleNoFlash: function (errorCode) {
		if (errorCode == FLASH_UNAVAILABLE) {
			alert("Error: Flash doesn't appear to be supported by your browser");
			return;
		}
	},
	
	getMapType: function(type) {
		switch(type) {
			case 'satellite': maptype = google.maps.MapTypeId.SATELLITE; break;
			case 'hybrid': maptype = google.maps.MapTypeId.HYBRID; break;
			case 'physical': maptype = google.maps.MapTypeId.TERRAIN; break;
			default: maptype = google.maps.MapTypeId.ROADMAP; break;
		}
		return maptype;
	},
	
	getPosition: function(pos) {
		/**
		+----------------+
		+ TL    TC    TR +
		+ LT          RT +
		+                +
		+ LC          RC +
		+                +
		+ LB          RB +
		+ BL    BC    BR +
		+----------------+ 
		*/
		switch(pos) {
			case 'TL': position = google.maps.ControlPosition.TOP_LEFT; break;
			case 'TC': position = google.maps.ControlPosition.TOP_CENTER; break;
			case 'TR': position = google.maps.ControlPosition.TOP_RIGHT; break;
			
			case 'LT': position = google.maps.ControlPosition.LEFT_TOP; break;
			case 'RT': position = google.maps.ControlPosition.RIGHT_TOP; break;
			
			case 'LC': position = google.maps.ControlPosition.LEFT_CENTER; break;
			case 'RC': position = google.maps.ControlPosition.RIGHT_CENTER; break;
			
			case 'LB': position = google.maps.ControlPosition.LEFT_BOTTOM; break;
			case 'RB': position = google.maps.ControlPosition.RIGHT_BOTTOM; break;
			
			case 'BL': position = google.maps.ControlPosition.BOTTOM_LEFT; break;
			case 'BC': position = google.maps.ControlPosition.BOTTOM_CENTER; break;
			case 'BR': position = google.maps.ControlPosition.BOTTOM_RIGHT; break;
			
			default: position = google.maps.ControlPosition.TOP_RIGHT; break;
		}
		return position;
	}
});
