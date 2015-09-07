EasySocial.module( 'site/toolbar/login' , function($){

	var module 				= this;

	EasySocial.require()
	.library( 'popbox' )
	.done(function($){

		EasySocial.Controller(
			'Toolbar.Login',
			{
				defaultOptions:
				{
					"{dropdown}"		: "[data-toolbar-login-dropdown]"
				}
			},
			function(self){ return{ 

				init: function()
				{
					var html = self.dropdown().html();

					// Remove the temporary dropdown.
					self.dropdown().remove();

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
					$( popbox.tooltip ).find( 'label' ).on( 'click' , function( event )
					{
						// Prevent propagation
						event.stopPropagation();
					});
					// $( popbox.tooltip ).implement( EasySocial.Controller.Toolbar.Login.User );
				}
			}}
		);

		module.resolve();
	});

});
