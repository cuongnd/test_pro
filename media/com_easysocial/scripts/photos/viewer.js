EasySocial.module("photos/viewer", function($){

	var module = this;

	// Non-essential dependencies
	EasySocial.require()
		.script(
			"photos/tagger",
			"photos/avatar"
		);

	EasySocial.require()
		.library(
			"image",
			"tinyscrollbar"
		)
		.done(function(){

			EasySocial.Controller("Photos.Viewer",
			{
				defaultOptions: {

					view: {
					},

					"{title}"             : "[data-photo-title]",
					"{caption}"           : "[data-photo-caption]",
					"{date}"              : "[data-photo-date]",
					"{viewport}"          : "[data-photo-viewport]",
					"{image}"             : "[data-photo-image]",
					"{photoItem}"         : "[data-photo-item]",

					"{nextButton}"        : "[data-photo-next-button]",
					"{prevButton}"        : "[data-photo-prev-button]",

					"{thumbnailsHolder}"  : "[data-photo-thumbnails-holder]",

					"{contentHolder}"     : "[data-photo-content-holder]",
					"{content}"           : "[data-photo-content]",
					"{contentViewport}"   : "[data-photo-content-viewport]",

					"{comments}"          : "[data-comments]",
					"{commentInput}"      : "[data-comments-form-input]",

					"{likeButton}"        : "[data-photo-like-button]",
					"{commentButton}"     : "[data-photo-comment-button]",

					"{likesHolder}"       : "[data-photo-likes-holder]",
					"{commentsHolder}"    : "[data-photo-comments-holder]",

					size: "large",
					throttle: 250,
					mode: 'inline'
				}
			},
			function(self) { return {

				init: function() {

					if (self.options.mode=="inline") {
						self.initial = true;
					}

					self.update();

					// Add tagger plugin
					EasySocial.module("photos/tagger")
						.done(function(){
							self.tagger = self.addPlugin("tagger", EasySocial.Controller.Photos.Tagger);
						});

					// Add avatar plugin
					EasySocial.module("photos/avatar")
						.done(function(){
							self.avatar = self.addPlugin("avatar", EasySocial.Controller.Photos.Avatar);
						});
				},

				update: function() {

					var playlist     = self.playlist = self.options.playlist,
						initialPhoto = self.options.initialPhoto;

					// If an album id was given, get album
					if (!$.isArray(playlist)) {

						var albumId = playlist;

						EasySocial.photos.getAlbum(albumId)
							.done(function(album){

								self.options.playlist = $.map(album.photos, function(photo) {
									return photo.id + '';
								});

								self.update();
							});

						EasySocial.ajax(
							"site/views/photos/thumbnails",
							{
								albumId: albumId
							})
							.done(function(thumbnailsHtml){

								self.thumbnailsHolder()
									.html(thumbnailsHtml);
							});

						return;
					}

					// Get index of initial photo
					var i = $._.indexOf(playlist, initialPhoto);

					// Just in case the initial photo could not be found.
					if (i < 0) i = 0;

					self.displayItem(i);
				},

				"{window} resize": $._.debounce(function() {

					self.setLayout();
				}, 100),

				setLayout: function() {
					self.setContentLayout();
					self.setImageLayout();
				},

				setImageLayout: function() {

					var viewport = self.viewport(),
						image = self.image();

					image.css(
						$.Image.resizeWithin(
							image.data("width"),
							image.data("height"),
							viewport.width(),
							viewport.height()
						)
					);
				},

				setContentLayout: function() {

					if (self.options.mode!=="popup") return;

					self.contentViewport()
						.height(self.content().height());

					self.content()
						.tinyscrollbar_update();
				},

				getPhoto: function(i) {
					return EasySocial.photos.photos[self.playlist[i]];
				},

				getImage: function(i) {
					return (EasySocial.photos.images[self.playlist[i]] || {})[self.options.size];
				},

				currentItem: 0,

				currentId: null,

				gotoItem: function(n) {

					var playlist = self.playlist,
						max      = playlist.length - 1,
						i        = self.currentItem + n;

					if (i < 0)   i = max;
					if (i > max) i = 0;

					return i;
				},

				displayItem: $._.debounce(function(i) {
					self.currentItem = i;
					self.currentId = self.playlist[i];
					self.trigger("displayItem", [i]);
				}, 25),

				"{self} displayItem": function(el, event, i) {

					// Show loading indicator
					self.viewport().addClass("loading");

					self.renderContent(i);
					self.renderImage(i);
				},

				renderContent: function(i) {

					if (self.initial) return;

					// Detach any existing content
					self.content().detach();

					var photo = self.getPhoto(i),
						photoId = self.playlist[i];

					// If the photo exist
					if (photo) {

						var mode = self.options.mode;

						// Display content immediately
						self.contentHolder()
							.append(photo.content[mode]);

						if (mode=="popup") {
							// Set up tiny scrollbar on comments
							self.content()
								.tinyscrollbar();
						}

						self.setContentLayout();

						// Trigger renderContent event
						self.trigger("renderContent", [i]);

					} else {

						setTimeout(function(){

							// The current requested photo has changed, stop.
							// This happens when user is clicking next/prev button quickly.
							if (self.currentId!==photoId) return;

							EasySocial.photos.getPhoto(photoId)
								.done(function(photo) {
									self.renderContent(i);
								})
								.fail(function() {

									// TODO: Show error message
								});

						}, self.options.throttle);
					}
				},

				renderImage: function(i) {

					// Detach any existing image
					self.image().detach();

					var photo    = self.getPhoto(i),
						photoId  = self.playlist[i],
						image    = self.getImage(i),
						size     = self.options.size,
						viewport = self.viewport();

					if (image) {

						// Append image to viewport invisibly
						image
							.addClass("es-photo-image")
							.removeClass("active")
							.attr("data-photo-image", "")
							.prependTo(viewport);

						// Disable transition on intial photo
						if (self.initial) {
							image.removeClass("initial");
						}

						// Resize the image to fit within viewport
						self.setImageLayout();

						// Remove loading indicator
						viewport.removeClass("loading");

						// Show image.
						// It is placed in a setTimeout to ensure
						// transition queue is cleared up.
						setTimeout(function(){

							image.addClass("active").removeClass("initial");

							// Trigger renderImage event
							self.trigger("renderImage", [i, photo, image]);

							self.reset();

						}, 1);

					} else {

						setTimeout(function(){

							// The current requested photo has changed, stop.
							// This happens when user is clicking next/prev button quickly.
							if (self.currentId!==photoId) return;

							EasySocial.photos.getImage(photoId, size)
								.done(function(image){

									// To note that this is the first time
									// showing this image
									image.addClass("initial");

									self.renderImage(i);
								})
								.fail(function(){
									// TODO: Show error message
									// console.log("error loading image");
								})
								.always(function(){

									// Remove loading indicator
									viewport.removeClass("loading");
								});

						}, self.options.throttle);
					}
				},

				reset: function() {

					// Remove css inserted image (initial image)
					self.viewport()
						.removeAttr("style")
						.removeClass("no-transition");

					// And we're done for the initial silent cycle
					self.initial = false;
				},

				"{nextButton} click": function() {
					self.reset();
					self.displayItem(self.gotoItem(1));
				},

				"{prevButton} click": function() {
					self.reset();
					self.displayItem(self.gotoItem(-1));
				},

				"{comments} newCommentSaved": function() {

					if (self.options.mode!=="popup") return;

					self.content()
						.tinyscrollbar_update("bottom");
				},

				"{comments} commentDeleted": function() {

					if (self.options.mode!=="popup") return;

					self.content()
						.tinyscrollbar_update();
				},

				"{comments} oldCommentsLoaded": function() {

					if (self.options.mode!=="popup") return;

					self.content()
						.tinyscrollbar_update();
				},

				"{commentButton} click": function() {

					if (self.options.mode=="popup") {
						self.content()
							.tinyscrollbar_update("bottom");
					}

					self.commentInput()
						.focus();
				},

				"{likeButton} click": function() {

					EasySocial.photos.like(self.currentId)
						.done(function(like) {

							// self.likeCount()
							// 	.html(like.count);

							self.likeButton()
								.toggleClass("liked", like.state);

							self.likesHolder()
								.html(like.html);
						});
				}

			}});

			module.resolve();

		});
});
