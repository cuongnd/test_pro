EasySocial.module('site/profile/avatar' , function($){

	var module = this;

	EasySocial.Controller("Profile.Avatar",
		{
			defaultOptions: {
				"{menu}": "[data-avatar-menu]",
				"{uploadButton}": "[data-avatar-upload-button]",
				"{selectButton}": "[data-avatar-select-button]",
				"{removeButton}": "[data-avatar-remove-button]"
			}
		},
		function(self) { return {

			init: function() {
			},

			"{uploadButton} click": function() {

				EasySocial.dialog({
					content: EasySocial.ajax("site/views/profile/uploadAvatar")
				});
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

							EasySocial.photos.createAvatar(photo.id);
						}
					}
				});
			},

            "{menu} dropdownOpen": function() {
                 self.element.addClass("show-all");
            },

            "{menu} dropdownClose": function() {
                 self.element.removeClass("show-all");
            },

			"{removeButton} click": function() {

			}

		}}
	);

	module.resolve();


});
