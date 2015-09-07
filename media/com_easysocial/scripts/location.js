EasySocial.module("location", function($) {

var module = this;

// require: start
EasySocial.require()
	.library( "ui/autocomplete" )
	.view( "site/location/delete.confirmation" )
	.done(function(){


		EasySocial.Controller(
			"Location.Form",
			{
				defaultOptions: {

					// Map properties
					language				: 'en',
					initialLocation			: null,
					mapType					: "ROADMAP",
					staticMap 				: 'https://www.google.com/maps?q=',
					showTip 				: true,

					// Location input
					"{locationInput}"		: "[data-locationForm-input]",

					// Location geographics (Longitude / Latitude display)
					"{locationCoordinates}"	: "[data-locationForm-coordinates]",
					"{locationLatitude}"	: "[data-locationForm-latitude]",
					"{locationLongitude}"	: "[data-locationForm-longitude]",
					"{latitudeDisplay}"		: "[data-locationForm-latitudeDisplay]",
					"{longitudeDisplay}"	: "[data-locationForm-longitudeDisplay]",

					// Map display
					"{locationMap}"			: "[data-locationForm-map]",
					"{locationMapWrapper}"	: ".locationMapWrapper",
					
					"{viewStaticMap}"		: ".viewStaticMap",

					// Buttons
					"{editGeographic}"		: "[data-locationForm-edit]",
					"{updateGeographic}"	: "[data-locationForm-update]",
					"{cancelUpdateGeographic}" : "[data-locationForm-cancel]",
					"{searchButton}"		: "[data-locationForm-searchAddress]",
					"{autoDetectButton}"	: "[data-locationForm-autodetect]",
					"{clearLocation}"		: "[data-locationForm-clear]"
				}
			},

			function(self) {

				return {

					init: function()
					{

						var mapReady = $.uid("ext");

						window[mapReady] = function() {
							$.___GoogleMaps.resolve();
						}

						if (!$.___GoogleMaps)
						{
							$.___GoogleMaps = $.Deferred();

							EasySocial.require()
							.script( { prefetch: false }, "http://maps.googleapis.com/maps/api/js?sensor=true&language=" + self.options.language + "&callback=" + mapReady );
						}

						// Defer instantiation of controller until Google Maps library is loaded.
						$.___GoogleMaps.done(function()
						{
							self._init();
						});
					},

					_init: function() {

						self.geocoder		= new google.maps.Geocoder();

						self.hasGeolocation = navigator.geolocation!==undefined;

						if (!self.hasGeolocation)
						{
							self.autoDetectButton().remove();
						}
						else
						{
							self.autoDetectButton().show();
						}

						// Apply auto complete on the address input.
						self.locationInput()
							.autocomplete(
							{
								delay: 300,
								minLength: 0,
								source: self.retrieveSuggestions,
								select: function(event, ui)
								{
									self.locationInput()
										.autocomplete("close");

									self.setLocation(ui.item.location);
								}
							})
							.prop("disabled", false);

						self.autocomplete = self.locationInput().autocomplete("widget");

						self.autocomplete.addClass("location-suggestion");

						var initialLocation = $.trim(self.options.initialLocation);

						if (initialLocation)
						{
							self.getLocationByAddress( initialLocation, function(location)
							{
									self.setLocation(location[0]);
							});
						};

						self.busy(false);
					},

					// Adds a loader class on the location input.
					busy: function(isBusy)
					{
						self.locationInput().toggleClass("loading", isBusy);
					},

					getUserLocations: function(callback)
					{
						self.getLocationAutomatically(function(locations)
						{
							self.userLocations = self.buildDataset(locations);
							
							callback && callback(locations);
						});
					},

					getLocationByAddress: function(address, callback)
					{
						self.geocoder.geocode({ "address" : address }, callback );
					},

					getLocationByCoords: function(latitude, longitude, callback)
					{
						self.geocoder.geocode( { "location" : new google.maps.LatLng(latitude, longitude) }, callback);
					},

					getLocationAutomatically: function(success, fail) {

						if (!navigator.geolocation) {
							return fail("ERRCODE", "Browser does not support geolocation or do not have permission to retrieve location data.")
						}

						navigator.geolocation.getCurrentPosition(
							// Success
							function(position) {
								self.getLocationByCoords(position.coords.latitude, position.coords.longitude, success)
							},
							// Fail
							fail
						);
					},

					renderMap: function(location, tooltipContent)
					{
						// Add loading
						self.busy(true);

						self.locationMapWrapper().show();

						var map	= new google.maps.Map(
							self.locationMap()[0],
							{
								zoom: 15,
								center: location.geometry.location,
								mapTypeId: google.maps.MapTypeId[self.options.mapType],
								disableDefaultUI: true
							}
						);

						var marker = new google.maps.Marker(
							{
								draggable: true,
								position: location.geometry.location,
								center	: location.geometry.location,
								title	: location.formatted_address,
								map		: map
							}
						);

						if( self.showTip )
						{
							var infoWindow = new google.maps.InfoWindow({ content: tooltipContent });

							google.maps.event.addListener(map, "tilesloaded", function() {
								infoWindow.open(map, marker);
								self.busy(false);
							});
						}

						// Add listener event when drag is end so we can update the latitude and longitude.
						google.maps.event.addListener(marker, 'dragend', function ( event ) {

							self.getLocationByCoords( this.getPosition().lat() , this.getPosition().lng() , function(locations){
								
								self.userLocations = self.buildDataset(locations);
								self.suggestUserLocations();
								// self.locationInput().val();
							});
							
							// Update the new latitude and longitude values.
							self.locationLatitude().val( this.getPosition().lat() );
							self.locationLongitude().val( this.getPosition().lng() );

							// Update the new latitude and longitude display values. This is not the input.
							self.latitudeDisplay().html( this.getPosition().lat() );
							self.longitudeDisplay().html( this.getPosition().lng() );
						});

					},

					setLocation: function(location) {

						if (!location) return;

						self.locationResolved = true;

						self.lastResolvedLocation = location;

						self.locationInput()
							.val(location.formatted_address);

						self.locationLatitude()
							.val(location.geometry.location.lat());

						self.locationLongitude()
							.val(location.geometry.location.lng());

						self.latitudeDisplay()
							.html( location.geometry.location.lat() );

						self.longitudeDisplay()
							.html( location.geometry.location.lng() );

						self.renderMap(location, location.formatted_address);

						// Trigger when the location is selected.
						self.trigger( 'locationSelected' , [location] );
					},

					/**
					 * Removes any detected location from the form.
					 */
					removeLocation: function()
					{
						self.locationResolved = false;

						// Empty the address input.
						self.locationInput().val('');

						// Empty the coordinates
						self.locationLatitude().val('');
						self.locationLongitude().val('');

						// Remove the display values
						self.latitudeDisplay().html('');
						self.longitudeDisplay().html( '' );

						// Hide the map.
						self.locationMapWrapper().hide();

						// Hide the coordinates.
						self.locationCoordinates().hide();
					},

					buildDataset: function(locations) {

						var dataset = $.map(locations, function(location){
							return {
								label: location.formatted_address,
								value: location.formatted_address,
								location: location
							};
						});

						return dataset;
					},

					retrieveSuggestions: function(request, response) {

						self.busy(true);

						var address = request.term,

							respondWith = function(locations) {
								response(locations);
								self.busy(false);
							};

						// User location
						if (address=="") {

							respondWith(self.userLocations || []);

						// Keyword search
						} else {

							self.getLocationByAddress(address, function(locations) {

								respondWith(self.buildDataset(locations));
							});
						}
					},

					suggestUserLocations: function() {

						if (self.hasGeolocation && self.userLocations) {
	//						self.removeLocation();

							self.locationInput()
								.autocomplete("search", "");
						}

						self.busy(false);
					},

					"{locationInput} blur": function() {

						// Give way to autocomplete
						setTimeout(function(){

							var address = $.trim(self.locationInput().val());

							// Location removal
							if (address=="") {

								self.removeLocation();

							// Unresolved location, reset to last resolved location
							} else if (self.locationResolved) {

								if (address != self.lastResolvedLocation.formatted_address) {

									self.setLocation(self.lastResolvedLocation);
								}
							} else {
								self.removeLocation();
							}

						}, 250);
					},

					// Trigger when a location is selected from the autocomplete list.
					"{self} locationSelected": function( el , event , location ) {

						// Find the geographic data and display to the user.
						self.locationCoordinates().show();

						// Allow triggers
						self.options.onLocationSelected && self.options.onLocationSelected(location);

						// Show the view larger map
						self.viewStaticMap().attr( 'href' , self.options.staticMap + encodeURIComponent( location.formatted_address ) );
					},

					"{cancelUpdateGeographic} click" : function()
					{
						self.editGeographic().click();
					},

					"{editGeographic} click" : function(){
						self.locationCoordinates().toggleClass( 'editMode' );
					},

					"{updateGeographic} click" : function(){

						// Updating location.
						self.editGeographic().click();
					},

					/**
					 * When user wants to remove the location.
					 */
					"{clearLocation} click" : function()
					{
						self.removeLocation();
					},

					/**
					 * Prompts user to share their location.
					 */
					"{autoDetectButton} click": function()
					{
						// Add a busy indicator to the input.
						self.busy( true );

						if (self.hasGeolocation && !self.userLocations)
						{
							self.getUserLocations(self.suggestUserLocations);

							return;
						}

						self.suggestUserLocations();
					}
				}
		});

		EasySocial.Controller(

			"Location.Map",

			{
				defaultOptions: {
					animation: 'drop',
					language: 'en',
					useStaticMap: false,
					disableMapsUI: true,
					
					// Show address in a tooltip.
					showTip: true,

					// fitBounds = true will disobey zoom
					// single location with fitBounds = true will set zoom to max (by default from Google)
					// locations.length == 1 will set fitBounds = false unless explicitly specified
					// locations.length > 1 will set fitBounds = true unless explicitly specified
					zoom: 5,
					fitBounds: null,

					minZoom: null,
					maxZoom: null,

					// location in center has to be included in locations array
					// center will default to first object in locations
					// latitude and longitude always have precedence over address
					// {
					// 	"latitude": latitude,
					// 	"longitude": longitude,
					// 	"address": address
					// }
					center: null,

					// address & title are optional
					// latitude and longitude always have precedence over address
					// title will default to geocoded address
					// first object will open info window
					// [
					// 	{
					// 		"latitude": latitude,
					// 		"longitude": longitude,
					// 		"address": address,
					// 		"title": title
					// 	}
					// ]
					locations: [],

					// Default map type to be road map. Can be overriden.
					mapType: "ROADMAP",

					width: 500,
					height: 400,

					"{locationMap}": ".locationMap",
					"{locationMapWrapper}"	: ".locationMapWrapper",

					// Actions
					"{removeUserLocation}"	: '.removeUserLocation',

					// Views
					view : {
						deleteConfirmation	: 'site/location/delete.confirmation'
					}
				}
			},

			function(self) { return {

				init: function() {
					self.mapLoaded = false;

					var mapReady = $.uid("ext");

					window[mapReady] = function() {
						$.___GoogleMaps.resolve();
					}

					if(self.options.useStaticMap == true) {
						var language = '&language=' + String(self.options.language);
						var dimension = '&size=' + String(self.options.width) + 'x' + String(self.options.height);
						var zoom = '&zoom=' + String(self.options.zoom);
						var center = '&center=' + String(parseFloat(self.options.locations[0].latitude).toFixed(6)) + ',' + String(parseFloat(self.options.locations[0].longitude).toFixed(6));
						var maptype = '&maptype=' + google.maps.MapTypeId[ self.options.mapType ];
						var markers = '&markers=';
						var url = 'http://maps.googleapis.com/maps/api/staticmap?sensor=false' + language + dimension;

						if(self.options.locations.length == 1) {
							markers += String(parseFloat(self.options.locations[0].latitude).toFixed(6)) + ',' + String(parseFloat(self.options.locations[0].longitude).toFixed(6));

							url += zoom + center + maptype + markers;
						} else {
							var temp = new Array();
							$.each(self.options.locations, function(i, location) {
								temp.push(String(parseFloat(location.latitude).toFixed(6)) + ',' + String(parseFloat(location.longitude).toFixed(6)));
							})
							markers += temp.join('|');

							url += markers + maptype;
						}

						self.locationMap().html('<img src="' + url + '" />');
						self.locationMapWrapper().show();

						self.busy(false);
					} else {
						var mapReady = $.uid("ext");

						window[mapReady] = function() {
							$.___GoogleMaps.resolve();
						}

						if (!$.___GoogleMaps) {

							$.___GoogleMaps = $.Deferred();

							EasySocial.require()
								.script(
									{prefetch: false},
									"http://maps.googleapis.com/maps/api/js?sensor=true&language=" + self.options.language + "&callback=" + mapReady
								);
						}

						// Defer instantiation of controller until Google Maps library is loaded.
						$.___GoogleMaps.done(function() {
							self._init();
						});
					}
				},

				_init: function() {

					// initialise fitBounds according to locations.length
					if(self.options.fitBounds === null) {
						if(self.options.locations.length == 1) {
							self.options.fitBounds = false;
						} else {
							self.options.fitBounds = true;
						}
					}

					// initialise disableMapsUI value to boolean
					self.options.disableMapsUI = Boolean(self.options.disableMapsUI);

					// initialise all location object
					self.locations = new Array();
					$.each(self.options.locations, function(i, location) {
					    if( location.latitude != 'null' && location.longitude != 'null' ) {
							self.locations.push(new google.maps.LatLng(location.latitude, location.longitude));
						}
					});

					if(self.locations.length > 0) {
						self.renderMap();
					}

					self.busy(false);
				},

				busy: function(isBusy) {
					self.locationMap().toggleClass("loading", isBusy);
				},

				renderMap: function() {
					self.busy(true);

					self.locationMapWrapper().show();

					var latlng;

					if(self.options.center) {
						latlng = new google.maps.LatLng(center.latitude, center.longitude);
					} else {
						latlng = self.locations[0];
					}

					self.map = new google.maps.Map(
						self.locationMap()[0],
						{
							zoom: parseInt( self.options.zoom ),
							minZoom: parseInt( self.options.minZoom ),
							maxZoom: parseInt( self.options.maxZoom ),
							center: latlng,
							mapTypeId: google.maps.MapTypeId[ self.options.mapType ],
							disableDefaultUI: self.options.disableMapsUI
						}
					);

					google.maps.event.addListener(self.map, "tilesloaded", function() {
						if(self.mapLoaded == false) {
							self.mapLoaded = true;
							self.loadLocations();
						}
					});
				},

				loadLocations: function() {
					self.bounds = new google.maps.LatLngBounds();
					self.infoWindow = new Array();

					var addLocations = function() {
						$.each(self.locations, function(i, location) {
							self.bounds.extend(location);
							var placeMarker = function() {
								self.addMarker(location, self.options.locations[i]);
							}

							setTimeout(placeMarker, 100 * ( i + 1 ) );
						});

						if(self.options.fitBounds) {
							self.map.fitBounds(self.bounds);
						}
					};

					setTimeout(addLocations, 500);
				},

				addMarker: function(location, info) {
					if (!location) return;

					var marker = new google.maps.Marker(
						{
							position: location,
							map: self.map
						}
					);

					marker.setAnimation(google.maps.Animation.DROP);


					self.addInfoWindow(marker, info)
				},

				addInfoWindow: function(marker, info) {

					if( !self.showTip )
					{
						return;
					}

					var content = info.content;

					if(!content) {
						content = info.address;
					}

					var infoWindow = new google.maps.InfoWindow();
					infoWindow.setContent(content);
					self.infoWindow.push(infoWindow);

					if(self.options.locations.length > 1) {
						google.maps.event.addListener(marker, 'click', function() {
							$.each(self.infoWindow, function(i, item) {
								item.close();
							});
							infoWindow.open(self.map, marker);
						});
					} else {
						google.maps.event.addListener(marker, 'click', function() {
							infoWindow.open(self.map, marker);
						});

						infoWindow.open(self.map, marker);
					}
				},
				"{removeUserLocation} click" : function(){

					// @TODO: Run some ajax calls to remove this.
					var id 			= self.element.data( 'id' );
						content		= self.view.deleteConfirmation({});
					$.dialog({
						content : content,
						buttons : [{

							name 	: $.language( 'COM_EASYSOCIAL_YES_BUTTON' ),
							click	: function(){
								EasySocial.ajax( 'site/controllers/location/delete' , {
									"id"	: id
								}, function(){

									// Hide the dialog
									$.dialog().close();
									
									// Remove the entire map section.
									self.element.remove();
								});
							}
						},
						{
							name	: $.language( 'COM_EASYSOCIAL_CANCEL_BUTTON' ),
							click	: function(){
								$.dialog().close();
							}
						}
						]
					})


				}
			}}
		);

		module.resolve();

	});
// require: end
});
