EasySocial.module( 'site/toolbar/profile' , function($){

	var module 				= this;

	EasySocial.require()
	.library( 'popbox' )
	.done(function($){

		EasySocial.Controller(
			'Toolbar.Profile',
			{
				defaultOptions:
				{
					"{dropdown}"		: "[data-toolbar-profile-dropdown]"
				}
			},
			function(self){ return{ 

				init: function()
				{
					var html = self.dropdown().html();

					// Implement popbox when the profile button is initiated
					self.element.popbox(
					{
						content 	: html,
						id			: "es-wrap",
						type		: "toolbar",
						toggle 		: "click"
					})
					.attr("data-popbox", "");

				},

				"{self} popboxActivate" : function( el , event , popbox )
				{
					$( popbox.tooltip ).implement( EasySocial.Controller.Toolbar.Profile.Logout );
				}
			}}
		);

		EasySocial.Controller(
			'Toolbar.Profile.Logout',
			{
				defaultOptions: 
				{
					// Elements within this container.
					"{logoutForm}"		: "[data-toolbar-logout-form]",
					"{logoutButton}"	: "[data-toolbar-logout-button]"
				}
			},
			function(self)
			{
				return{ 
					/**
					 * Logs user out from the site.
					 */
					logout: function()
					{
						self.logoutForm().submit();
					},

					"{logoutButton} click" : function()
					{
						self.logout();
					}
				}
			});

		module.resolve();
	});

});
