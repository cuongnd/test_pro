EasySocial.module("story/locations", function($){

	var module = this;

	EasySocial
		.require()
		.library(
			"gmaps",
			"scrollTo"
		)
		.view(
			"apps/user/locations/suggestion"
		)
		.language(
			"COM_EASYSOCIAL_LOCATION_PERMISSION_ERROR"
		)
		.done(function(){

			// Constants
			var KEYCODE = {
				BACKSPACE: 8,
				COMMA: 188,
				DELETE: 46,
				DOWN: 40,
				ENTER: 13,
				ESCAPE: 27,
				LEFT: 37,
				RIGHT: 39,
				SPACE: 32,
				TAB: 9,
				UP: 38
			};

			EasySocial.Controller("Story.Locations",
				{
					defaultOptions: {

						view: {
							suggestion: "apps/user/locations/suggestion"
						},

						map: {
							lat: 0,
							lng: 0
						},

						"{form}": "[data-story-location-form]",

						"{textField}"   : "[data-story-location-textField]",
						"{detectButton}": "[data-story-location-detect-button]",

						"{autocomplete}": "[data-story-location-autocomplete]",
						"{suggestions}": "[data-story-location-suggestions]",
						"{suggestion}": "[data-story-location-suggestion]",

						"{viewport}": "[data-story-location-viewport]",

						"{textbox}": "[data-story-location-textbox]",

						"{removeButton}": "[data-story-location-remove-button]"
					}
				},
				function(self) { return {

					init: function() {

						// I have access to:
						// self.panelButton()
						// self.panelContent()

						// Only show auto-detect button if the browser supports geolocation
						if (navigator.geolocation) {
							self.detectButton().show();
						}

						// Allow textfield input only when controller is implemented
						self.textField().removeAttr("disabled");
					},

					"{window} resize": $.debounce(function(){

						var currentLocation = self.currentLocation;

						if (!currentLocation) return;

						var viewport = self.viewport();

						if (viewport.data("width") !== viewport.width()) {

							var coords = currentLocation.geometry.location,
								lat = coords.lat(),
								lng = coords.lng();

							self.navigate(lat, lng);
						}

					}, 250),

					navigate: function(lat, lng) {

						var viewport = self.viewport(),
							width    = viewport.width(),
							height   = viewport.height(),
							url =
								$.GMaps.staticMapURL({
									size: [width, height],
									lat: lat,
									lng: lng,
									markers: [
										{lat: lat, lng: lng}
									]
								});

						self.viewport()
							.css({
								backgroundImage: $.cssUrl(url)
							})
							.data({
								width: width,
								height: height
							});

						self.form().addClass("has-location");
					},

					// Memoized locations
					locations: {},

					lastQueryAddress: null,

					"{textField} keypress": function(textField, event) {

						switch (event.keyCode) {

							case KEYCODE.UP:

								var prevSuggestion = $(
									self.suggestion(".active").prev(self.suggestion.selector)[0] ||
									self.suggestion(":last")[0]
								);

								// Remove all active class
								self.suggestion().removeClass("active");

								prevSuggestion
									.addClass("active")
									.trigger("activate");

								self.suggestions()
									.scrollTo(prevSuggestion, {
										offset: prevSuggestion.height() * -1
									});

								event.preventDefault();

								break;

							case KEYCODE.DOWN:

								var nextSuggestion = $(
									self.suggestion(".active").next(self.suggestion.selector)[0] ||
									self.suggestion(":first")[0]
								);

								// Remove all active class
								self.suggestion().removeClass("active");

								nextSuggestion
									.addClass("active")
									.trigger("activate");

								self.suggestions()
									.scrollTo(nextSuggestion, {
										offset: nextSuggestion.height() * -1
									});

								event.preventDefault();

								break;

							case KEYCODE.ENTER:

								var activeSuggestion = self.suggestion(".active"),
									location = activeSuggestion.data("location");
									self.set(location);

								self.hideSuggestions();
								break;

							case KEYCODE.ESCAPE:
								self.hideSuggestions();
								break;
						}

					},

					"{textField} keyup": function(textField, event) {

						switch (event.keyCode) {

							case KEYCODE.UP:
							case KEYCODE.DOWN:
							case KEYCODE.LEFT:
							case KEYCODE.RIGHT:
							case KEYCODE.ENTER:
							case KEYCODE.ESCAPE:
								// Don't repopulate if these keys were pressed.
								break;

							default:
								var address = $.trim(textField.val());

								if (address==="") {
									self.form().removeClass("has-location");
									self.hideSuggestions();
								}

								// if (address==self.lastQueryAddress) return;

								var locations = self.locations[address];

								// If this location has been searched before
								if (locations) {

									// And set our last queried address to this address
									// so that it won't repopulate the suggestion again.
									self.lastQueryAddress = address;

									// Just use cached results
									self.suggest(locations);

								// Else ask google to find it out for us
								} else {

									self.lookup(address);
								}
								break;
						}
					},

					lookup: $._.debounce(function(address){

						self.textbox().addClass("busy");

						$.GMaps.geocode({
							address: address,
							callback: function(locations, status) {

								self.textbox().removeClass("busy");

								if (status=="OK") {

									// Store a copy of the results
									self.locations[address] = locations;

									// Suggestion locations
									self.suggest(locations);

									self.lastQueryAddress = address;
								}
							}
						});

					}, 250),

					suggest: function(locations) {

						var suggestions = self.suggestions();

						// Clear location suggestions
						suggestions
							.empty();

						if (locations.length < 0) return;

						$.each(locations, function(i, location){

							// Create suggestion and append to list
							self.view.suggestion({
									location: location
								})
								.data("location", location)
								.appendTo(suggestions);
						});

						self.showSuggestions();
					},

					showSuggestions: function() {

						self.focusSuggestion = true;

						self.element.find(".es-story-footer")
							.addClass("swap-zindex");

						setTimeout(function(){

							self.autocomplete().addClass("active");
						}, 500);
					},

					hideSuggestions: function() {

						self.focusSuggestion = false;

						self.autocomplete().removeClass("active");

						setTimeout(function(){

							if (self.focusSuggestion) return;

							self.element.find(".es-story-footer")
								.removeClass("swap-zindex");

						}, 500);
					},

					"{suggestion} activate": function(suggestion, event) {

						var location = suggestion.data("location");

						self.navigate(
							location.geometry.location.lat(),
							location.geometry.location.lng()
						);
					},

					"{suggestion} mouseover": function(suggestion) {

						// Remove all active class
						self.suggestion().removeClass("active");

						suggestion
							.addClass("active")
							.trigger("activate");
					},

					"{suggestion} click": function(suggestion, event) {

						var location = suggestion.data("location");

						self.set(location);

						self.hideSuggestions();
					},

					set: function(location) {

						self.currentLocation = location;

						self.navigate(
							location.geometry.location.lat(),
							location.geometry.location.lng()
						);

						var address = location.formatted_address;

						self.textField().val(address);

						self.lastQueryAddress = address;

						var caption = location.formatted_address;

						self.story.addPanelCaption("locations", caption);

						self.form().addClass("has-location");
					},

					unset: function() {

						self.currentLocation = null;

						self.textField().val('');

						self.story.removePanelCaption("locations");

						self.viewport().css("backgroundImage", "");

						self.form().removeClass("has-location");
					},

					activatePanel: function() {

						setTimeout(function(){
							self.textField().focus();
						}, 500);
					},

					deactivatePanel: function() {

						var location = self.currentLocation;

						if (location) {
							self.set(location);
						}
					},

					detectTimer: null,

					"{detectButton} click": function() {

						var story = self.story,
							textbox = self.textbox();
							textbox.addClass("busy");

						clearTimeout(self.detectTimer);

						self.detectTimer = setTimeout(function(){
							story.setMessage($.language("COM_EASYSOCIAL_LOCATION_PERMISSION_ERROR"));
							textbox.removeClass("busy");
						}, 8000);

						$.GMaps.geolocate({
							success: function(position) {

								story.clearMessage();

								$.GMaps.geocode({
									lat: position.coords.latitude,
									lng: position.coords.longitude,
									callback: function(locations, status){
										if (status=="OK") {
											self.suggest(locations);
											self.textField().focus();
										}
									}
								});
							},
							error: function(error) {
								story.setMessage(error.message, "error");
							},							
							always: function() {
								clearTimeout(self.detectTimer);
								textbox.removeClass("busy");
							}
						});

					},

					"{removeButton} click": function() {
						self.unset();
						self.hideSuggestions();
					},

					"{story} save": function(event, element, save) {

						var currentLocation = self.currentLocation;

						if (!currentLocation) return;

						save.addData(self, {
							short_address    : currentLocation.address_components[0].long_name,
							formatted_address: currentLocation.formatted_address,
							lat              : currentLocation.geometry.location.lat(),
							lng              : currentLocation.geometry.location.lng()
						});
					},

					"{story} clear": function() {

						self.unset();

						self.hideSuggestions();
					}

				}}
			);

			// Resolve module
			module.resolve();

		});

});
