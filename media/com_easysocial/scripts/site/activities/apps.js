EasySocial.module( 'site/activities/apps' , function($){

	var module	= this;

	EasySocial.require()
	.view( 'site/loading/small' )
	.language( 'COM_EASYSOCIAL_ACTIVITY_APPS_UNHIDE_SUCCESSFULLY' )
	.done(function($){


		EasySocial.Controller(
			'Activities.Apps.List',
			{
				defaultOptions:
				{
					// Elements
					"{item}"	: "[data-hidden-app-item]",


					// loading gif
					view :
					{
						loadingContent 	: "site/loading/small"
					}

				}
			},
			function( self ){
				return {

					init : function()
					{
						self.item().implement( EasySocial.Controller.Activities.Apps.Item );
					}

				}
			});


		EasySocial.Controller(
			'Activities.Apps.Item',
			{
				defaultOptions:
				{
					// Properties
					id 			: "",
					context 	: "",

					"{unhideLink}" : "[data-hidden-app-unhide]",

					"{content}" : "[data-hidden-app-content]",

					// loading gif
					view :
					{
						loadingContent 	: "site/loading/small"
					}

				}
			},
			function( self ){
				return {

					init : function()
					{
						self.options.id 		= self.element.data( 'id' );
						self.options.context 	= self.element.data( 'context' );
					},

					"{unhideLink} click" : function(){

						EasySocial.ajax( 'site/controllers/activities/unhideapp',
						{
							"context"		: self.options.context,
							"id" 			: self.options.id
						})
						.done(function()
						{
							self.content().html( $.language( 'COM_EASYSOCIAL_ACTIVITY_APPS_UNHIDE_SUCCESSFULLY' ) );

						})
						.fail(function( message ){
							console.log( message );
						});

					}

				}
			});



		module.resolve();
	});

});
