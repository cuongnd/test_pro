EasySocial.module( 'site/profile/profile' , function($){

	var module 	= this;

	EasySocial.require()
	.script( 'site/profile/header' )
	.library( 'history' )
	.done(function($){

		EasySocial.Controller(
			'Profile',
			{
				defaultOptions:
				{
					// The current user being viewed
					id 	: null,

					// Elements
					"{header}"	: "[data-profile-header]",

					// App item
					"{app}"		: "[data-profile-apps-item]",
					"{action}"	: "[data-profile-apps-menu]",

					// Contents
					"{contents}"	: "[data-profile-real-content]"

				}
			},
			function( self )
			{
				return {

					init : function()
					{
						// Get the user's id.
						self.options.id 	= self.element.data( 'id' );

						// Implement profile header.
						self.header().implement( EasySocial.Controller.Profile.Header ,
						{
							"{parent}"	: self
						});

						// Implement the apps
						self.app().implement( EasySocial.Controller.Profile.Apps.Item ,
							{
								"{parent}"	: self
							});

					},

					"{app} click" : function( el )
					{
						// Remove active class.
						self.app().removeClass( 'active' );

						// Add active class to this current item.
						$( el ).addClass( 'active' );
					},

					updateContent : function( content )
					{
						self.element.removeClass("loading");

						self.contents().html( content );
					},

					loading: function()
					{
						// self.contents().html( self.view.loading({}) );
						self.contents().html("");
						self.element.addClass("loading");
					}
				}
			}
		);

		EasySocial.Controller(
			'Profile.Apps.Item',
			{
				defaultOptions :
				{

				}
			},
			function( self )
			{
				return {
					init : function()
					{
						self.options.layout 		= self.element.data( 'layout' );
						self.options.id 			= self.element.data( 'id' );
						self.options.url 			= self.element.data( self.options.layout + '-url' );
						self.options.namespace 		= self.element.data( 'namespace' );
						self.options.title 			= self.element.data( 'title' );
						self.options.description	= self.element.data( 'description' );
						self.options.appId 			= self.element.data( 'app-id' );

					},

					"{self} click" : function( el , event )
					{
						// Prevent from bubbling up.
						event.preventDefault();

						// If this is a canvas layout, redirect the user to the canvas view.
						if( self.options.layout == 'canvas' )
						{
							window.location 	= self.options.url;
							return;
						}

						// If this is an embedded layout, we need to play around with the push state.
						History.pushState( {state:1} , self.options.title , self.options.url );

						// Send a request to the dashboard to update the content from the specific app.
						EasySocial.ajax( self.options.namespace ,
						{
							"id"		: self.options.id,
							"view"		: "profile",
							"appId"		: self.options.appId
						},
						{
							beforeSend 	: function()
							{
								// Notify the dashboard that it's starting to fetch the contents.
								self.parent.loading();
							}
						})
						.done( function( contents )
						{
							// Update the content with proper value
							self.parent.updateContent( contents );
						})
						.fail(function( messageObj ){

							return messageObj;

						});
					}
				}
			});

		module.resolve();
	});

});
