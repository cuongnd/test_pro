
EasySocial.module("photos/tags", function($){

	var module = this;

	// Non essential dependencies
	EasySocial.require()
		.library("scrollTo")
		.done();

	EasySocial.require()
		.done(function(){

			var Controller =
			EasySocial.Controller("Photos.Tags",
			{
				hostname: "tags",

				defaultOptions: {

					"{viewport}"    : "[data-photo-tag-viewport]",
					"{tagItem}"     : "[data-photo-tag-item]",
					"{tagButton}"   : "[data-photo-tag-button]",

					"{tagListItemGroup}": "[data-photo-tag-list-item-group]",
					"{tagListItem}"     : "[data-photo-tag-list-item]"
				}
			},
			function(self) { return {

				init: function() {

					self.setLayout();
				},

				imageLoaders: {},

				setLayout: function(callback) {

					var imageHolder   = self.photo.image(),
						imageUrl      = $.uri(imageHolder.css("backgroundImage")).extract(0),
						imageLoaders  = self.imageLoaders,
						imageLoader   = imageLoaders[imageUrl] || (self.imageLoaders[imageUrl] = $.Image.get(imageUrl));

					imageLoader
						.done(function(imageEl, image){

							self.viewport()
								.css(
									$.Image.resizeWithin(
										image.width,
										image.height,
										imageHolder.width(),
										imageHolder.height()
									)
								);

							callback && callback();
						});
				},

				getTagItem: function(tagId) {
					return self.tagItem().filterBy("photoTagId", tagId);
				},

				getTagListItem: function(tagId) {
					return self.tagListItem().filterBy("photoTagId", tagId);
				},

				getTaggedUsers: function() {

					var users = [];

					self.tagListItem("[data-photo-tag-uid]")
						.each(function(){
							users.push($(this).data("photoTagUid"));
						});

					return $.uniq(users);
				},

				activateTag: function(tagId) {

					self.getTagItem(tagId)
						.addClass("active");

					self.getTagListItem(tagId)
						.addClass("active");
				},

				deactivateTag: function(tagId) {

					self.getTagItem(tagId)
						.removeClass("active");

					self.getTagListItem(tagId)
						.removeClass("active");
				},

				"{tagListItem} click": function(el) {

					var method = (el.hasClass('active') ? "deactivate" : "activate") + "Tag",
						tagId  = el.data("photoTagId");

					// Toggle tag
					self[method](tagId);
				},

				"{tagListItem} mouseover": function(el) {

					self.getTagItem(el.data("photoTagId"))
						.addClass("focus");
				},

				"{tagListItem} mouseout": function(el) {

					self.getTagItem(el.data("photoTagId"))
						.removeClass("focus");
				},

				"{self} tagRemove": function(el, event, task, tagId) {

					task.done(function(){

						// Remove tag item
						self.getTagItem(tagId).remove();

						// Remove tag list item
						self.getTagListItem(tagId).remove();
					});
				},

				"{self} photoRotate": function(el, event, task, angle, photo) {

					task.done(function(photoObj, tags){

						setTimeout(function(){

							self.setLayout(function(){

								var tagItems = self.tagItem();

								$.each(tags, function(i, tag){

									var tagItem = tagItems.filterBy("photoTagId", tag.id);

									tagItem
										.css({
											width : (tag.width  * 100) + "%",
											height: (tag.height * 100) + "%",
											top   : (tag.top    * 100) + "%",
											left  : (tag.left   * 100) + "%"
										});
								});

							});

						}, 1);

					});

				}
			}});

			module.resolve(Controller);

		});
});
