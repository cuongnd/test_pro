EasySocial.module("photos", function($){

	var module = this;

	EasySocial.require()
		.script("photos/viewer")
		.done(function(){

			EasySocial.Controller("Photos",
			{
				defaultOptions: {

					view: {
						popup:  "site/photos/popup",
						viewer: "site/photos/popup.viewer"
					},

					"{photo}"       : "[data-es-photo]",

					"{popup}"       : "[data-photo-popup]",
					"{viewer}"      : "[data-photo-viewer]",
					"{closeButton}" : "[data-popup-close-button]"
				},

				// Schema for photo object
				photo: {
					data   : {},   // Exported data from photo table
					image  : {},   // Holds detached image element. image[variation] = element.
					content: null, // Detached html elment containing photo content
					tags   : []    // Array of tag objects
				}
			},
			function(self) { return {

				init: function() {

					// Popup frame
					// self.popupElement = self.view.popup();
				},

				createAvatar: function(id, options) {

					if (id===undefined) return;

					if (!options) options = {};

					var avatarOptions = {id: id};

					if (options.redirect) {
						avatarOptions.redirect = options.redirect;
						delete options.redirect;
					}

					if (options.uid) {
						avatarOptions.uid = options.uid;
						delete options.uid;
					}

					EasySocial.dialog(
						$.extend({
					    	content: EasySocial.ajax("site/views/photos/avatar", avatarOptions)
						}, options)
					);
				},

				selectPhoto: function(options) {

					var task = $.Deferred(),
						dialog = EasySocial.ajax("site/views/albums/dialog"),
						browser = EasySocial.require().script("albums/browser").done();

					// Show a loading indicator first
					EasySocial.dialog(
						$.extend({
						    content: task
						}, options)
					);

					$.when(browser, dialog)
						.done(function(){
							dialog.done(function(html){
								task.resolve(html);
							});
						});
				},

				display: function(photoId, playlist, options) {

					var photoId = photoId + '', // Ensure it is string
						popup = self.popup(),
						viewer = self.viewer();

					// Remove existing viewer.
					// The viewer will listen to the destroyed event
					// and detach all photo content & image elements.
					viewer.remove();

					// Show popup
					self.show();

					// Create a new viewer
					self.currentViewer =
						self.view.viewer()
							.appendTo(popup)
							.addController(
								"EasySocial.Controller.Photos.Viewer",
								$.extend({
									mode: "popup",
									initialPhoto: photoId,
									playlist: playlist
								}, options)
							);
				},

				// Stores array of exported album table
				albums: {},

				albumLoaders: {},

				getAlbum: function(albumId, reload) {

					var albumId = albumId + '', // Ensure it is string
						loaders = self.albumLoaders,
						loader  = loaders[albumId];

					if (reload || !loader || loader.state()=="failed") {

						loader = loaders[albumId] =
							EasySocial.ajax(
								"site/controllers/albums/getAlbum",
								{
									id: albumId
								}
							)
							.done(function(album) {
								self.albums[albumId] = album;
							});
					}

					return loader.promise();
				},

				// Stores array of exported photo table
				photos: {},

				photoLoaders: {},

				getPhoto: function(photoId, reload) {

					var photoId = photoId + '', // Ensure it is string
						loaders = self.photoLoaders,
						loader  = loaders[photoId];

					if (reload || !loader || loader.state()=="failed") {

						loader = loaders[photoId] =
							EasySocial.ajax(
								"site/controllers/photos/getPhoto",
								{
									id: photoId,
									attr: ["content", "tags"]
								}
							)
							.done(function(photo){

								self.photos[photoId] = photo;

								var content = photo.content;
									photo.content = {};

								$.each(content, function(mode, content) {
									photo.content[mode] = $($.trim(content)).appendTo(self.element).detach();
								});
							});
					}

					return loader.promise();
				},

				images: {},

				imageLoaders: {},

				getImage: function(photoId, size, reload) {

					var photoId     = photoId + '', // Ensure it is string
						loaders     = self.imageLoaders,
						loaderSizes = loaders[photoId] || (loaders[photoId] = {}),
						loader      = loaderSizes[size];

					if (reload || !loader || loader.state()=="failed") {

						loader = loaderSizes[size] = $.Deferred();

						self.getPhoto(photoId)
							.done(function(photo) {

								var url = photo.sizes[size].url;

								$.Image.get(url)
									.done(function(image){

										var images     = self.images,
											imageSizes = images[photoId] || (images[photoId] = {});
											imageSizes[size] = image;

										loader.resolve(image);
									})
									.fail(loader.reject);
							})
							.fail(loader.reject);
					}

					return loader.promise();
				},

				show: function() {

					self.popupElement
						.appendTo("body")
						.addClass("active")
						.trigger("show");
				},

				hide: function() {

					self.popupElement
						.removeClass("active")
						.trigger("hide")
						.detach();
				},

				"{self} click": function(el, event) {
					if (event.target===self.popup()[0]) {
						self.hide();
					}
				},

				// Album playlist
				//
				// <div data-es-album="4">
				//     <a data-es-photo="499">
				// </div>

				// Element-based playlist
				//
				// <div data-es-photos>
				//     <a data-es-photo="1">
				//     <a data-es-photo="2">
				//     <a data-es-photo="3">
				// </div>

				// Custom playlist
				// Ideal for large playlist where not all items are shown.
				//
				// <div data-es-photos="400,401,402,403,405,406,407,408">
				//     <a data-es-photo="400">
				//     <a data-es-photo="401">
				//     <a data-es-photo="402">
				//     <a data-es-photo="403">
				//     <a data-es-photo="404">
				//     <!-- The rest of the thumbnails not shown, but the popup will have it. -->
				// </div>

				"{photo} click": function(photo, event) {

					// Get photo id
					var photoId = photo.data("es-photo");

					// Album playlist
					var album = photo.parents("[data-es-album]");
					if (album.length > 0) {
						var albumId = album.data("es-album");
						self.display(photoId, albumId);
						return event.preventDefault();
					}

					// Custom playlist
					var photos = photo.parents("[data-es-photos]");

					if (photos.length > 0) {

						// This is for photo containers with unfinished photo items
						// <div data-es-photos="34,35,36,37">
						var playlist = photos.data("es-photos");

						if (playlist!=="" && playlist!==undefined) {
							playlist = playlist.split(",");
							self.display(photoId, playlist);
							return event.preventDefault();
						}

						// This is for photos containers with element-based playlist
						playlist = [];
						photos.find("[data-es-photo]").each(function(i, e){
							playlist.push($(this).data("es-photo"));
						});
						self.display(photoId, playlist);
						return event.preventDefault();
					}
				},

				"{closeButton} click": function() {
					self.hide();
				},

				like: function(photoId) {

					return EasySocial.ajax(
						"site/controllers/photos/like",
						{
							id: photoId
						}
					);
				}

			}});

			module.resolve();
		});
});
