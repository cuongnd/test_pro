EasySocial.module( 'site/profile/about', function($){
	var module = this;

	EasySocial.require().script('field').done(function($) {
		EasySocial.Controller('Profile.About', {
			defaultOptions: {
				userid			: null,

				'{stepItem}'	: '[data-profile-about-step-item]',
				'{stepContent}'	: '[data-profile-about-step-content]',

				'{fieldItem}'	: '[data-profile-about-fields-item]'
			}
		}, function(self) {
			return {
				init: function() {
					self.fieldItem().addController('EasySocial.Controller.Field.Base', {
						userid: self.options.userid,
						mode: 'display'
					});
				},

				'{stepItem} click': function(el, ev) {
					var target = el.data('for');

					self.stepItem().removeClass('active');

					el.addClass('active');

					self.stepContent().trigger('activateTab', [target]);
				},

				'{stepContent} activateTab': function(el, ev, target) {
					var id = el.data('id');

					el.toggleClass('active', target == id);
				}
			}
		});

		module.resolve();
	});
});
