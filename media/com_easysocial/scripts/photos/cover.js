EasySocial.module("photos/cover", function($){

	var module = this;

	EasySocial.require()
		.library("image")
		.done(function(){

			EasySocial.Controller("Photos.Cover",
			{
				defaultOptions: {
					"{image}"        : "[data-cover-image]",
					"{editButton}"   : "[data-cover-edit-button]",
					"{doneButton}"   : "[data-cover-done-button]",
					"{cancelButton}" : "[data-cover-cancel-button]",
					"{uploadButton}" : "[data-cover-upload-button]",
					"{selectButton}" : "[data-cover-select-button]",
					"{removeButton}" : "[data-cover-remove-button]",
					"{menu}"         : "[data-cover-menu]"
				}
			},
			function(self) { return {

				init: function() {

					// Automatically enable cover editing if not manually disabled
					// if (!self.options.disabled) { self.start("url"); }

					self.setLayout();

					if (self.element.hasClass("editing")) {
						self.enable();
					}
				},

				"{window} resize": $.debounce(function() {
					self.setLayout();
				}, 250),

				"{editButton} click": function() {
					self.enable();
				},

				"{cancelButton} click": function() {
					self.disable();
				},

				ready: false,

				disabled: true,

				toggle: function() {
					self[(self.disabled) ? "enable" : "disable"]();
				},

				enable: function() {
					self.setLayout();
					self.disabled = false;
					self.element.addClass("editing");
				},

				disable: function() {
					self.disabled = true;
					self.element.removeClass("editing");

					var profileUrl = 
						$.uri(window.location.href)
							.deleteQueryParam("cover_id")
							.toString();

					History.pushState({state: 0}, window.title, profileUrl);
				},				

				imageLoaders: {},

				setLayout: function() {

					var cover = self.image(),
						image = self.setLayout.image;

					// Ensure cover viewport is always on 3:1 aspect ratio
					var viewportWidth = self.element.width(),
						viewportHeight = viewportWidth / 3;
						self.element.height(viewportHeight);

					if (!image) {

						// Extract image url from cover
						var url = $.uri(cover.css("backgroundImage")).extract(0);

						// If no url given, stop.
						if (!url) return;

						// Load image
						var imageLoaders = self.imageLoaders,
							imageLoader = 
								(imageLoaders[url] || (imageLoaders[url] = $.Image.get(url)))
									.done(function(image) {

										// Set it as current image
										self.setLayout.image = image;

										// Then set layout again
										self.setLayout();
									});

							return;
					}

					// Get measurements
					var imageWidth  = image.data("width"),
						imageHeight = image.data("height"),
						coverWidth  = cover.width(),
						coverHeight = cover.height(),
						size = $.Image.resizeProportionate(
							imageWidth, imageHeight,
							coverWidth, coverHeight,
							"outer"
						);

					self.availableWidth  = coverWidth  - size.width;
					self.availableHeight = coverHeight - size.height;
				},

				setCover: function(id, url) {

					// Show loading indicator
					self.element.addClass("loading");

					// Make sure the image has been properly loading
					$.Image.get(url)
						.done(function(){

							self.image()
								.data("photoId", id)
								.css({
									backgroundImage: $.cssUrl(url),
									backgroundPosition: "50% 50%"
								});

							// Reset position
							self.x = 0.5;
							self.y = 0.5;

							self.enable();
						})
						.fail(function(){
							self.disable();
						})
						.always(function(){

							self.element.removeClass("loading");
						});	
				},

				drawing: false,

				moveCover: function(dx, dy, image) {

					// Optimization: Pass in reference to image
					// so we don't have to query all the time.
					if (!image) { image = self.image(); }

					var w = self.availableWidth,
						h = self.availableHeight,
						x = (w==0) ? 0 : self.x + ((dx / w) || 0),
					    y = (h==0) ? 0 : self.y + ((dy / h) || 0);

					// Always stay within 0 to 1.
					if (x < 0) x = 0; if (x > 1) x = 1;
					if (y < 0) y = 0; if (y > 1) y = 1;

					// Set position on cover
					image.css("backgroundPosition", 
						((self.x = x) * 100) + "% " +
					    ((self.y = y) * 100) + "% "
					);
				},

				x: 0.5,

				y: 0.5,

				"{image} mousedown": function(selection, event) {

					if (self.disabled) return;

					if (event.target === self.image()[0]) {
						event.preventDefault();
					}

					self.drawing = true;
					self.element.addClass("active");

					// Initial cover position
					var image = self.image(),
						position = self.image().css("backgroundPosition").split(" ");
						self.x = parseInt(position[0]) / 100;
						self.y = parseInt(position[1]) / 100;

					// Initial cursor position
					var x = event.pageX,
						y = event.pageY;

					$(document)
						.on("mousemove.movingCover mouseup.movingCover", function(event) {

							if (!self.drawing) return;

							self.moveCover(
								(x - (x = event.pageX)) * -1,
								(y - (y = event.pageY)) * -1,
								image
							);
						})
						.on("mouseup.movingCover", function() {

							$(document).off("mousemove.movingCover mouseup.movingCover");

							self.element.removeClass("active");
						});
				},

				save: function() {

					var photoId = self.image().data("photoId");

					var task = 
						EasySocial.ajax(
							"site/controllers/photos/createCover",
							{
								id: photoId,
								x: self.x,
								y: self.y
							}
						)
						.done(function(cover){

							// Set cover
							self.element
								.css({
									backgroundImage: $.cssUrl(cover.url),
									backgroundPosition: cover.position
								})
								.removeClass("no-cover");

							// Disable editing
							self.disable();
						});

					return task;
				},

				"{doneButton} click": function() {

					self.save();
				},

                "{menu} dropdownOpen": function() {
                     self.element.addClass("show-all");
                },

                "{menu} dropdownClose": function() {
                     self.element.removeClass("show-all");
                },

                "{selectButton} click": function() {

                	EasySocial.photos.selectPhoto({
                		bindings: {
                			"{self} photoSelected": function(el, event, photos) {

                				// Photo selection dialog returns an array,
                				// so just pick the first one.
                				var photo = photos[0];

                				// If no photo selected, stop.
                				if (!photo) return;

                				// Set it as cover to reposition
                				self.setCover(photo.id, photo.sizes.large);

                				this.parent.close();
                			}
                		}
                	});
                },

                "{uploadButton} click": function() {

					EasySocial.dialog({
						content: EasySocial.ajax("site/views/profile/uploadCover"),
						bindings: {
							"{self} upload": function(el, event, task, filename) {

								task.done(function(photo){
									// Set cover
									self.setCover(photo.id, photo.sizes.large.url);
								});
							}
                		}
                	});
                },

                "{removeButton} click": function() {

                	EasySocial.ajax("site/controllers/photos/removeCover")
                		.done(function(defaultCoverUrl){

							self.element
								.css({
									backgroundImage: $.cssUrl(defaultCoverUrl),
									backgroundPosition: "50% 50%"
								})
								.addClass("no-cover");

                			self.disable();
                		});
                }

			}});

			module.resolve();

		});
});