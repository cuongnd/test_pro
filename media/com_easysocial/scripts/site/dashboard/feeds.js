EasySocial.module( 'site/dashboard/feeds' , function($){

	var module 				= this;

	EasySocial.require()
	.library( 'history' )
	.done(function($){

		EasySocial.Controller(
			'Dashboard.Feeds',
			{
				defaultOptions:
				{
					"{item}"	: "[data-dashboardFeeds-item]"
				}
			},
			function(self){

				return{

					init : function()
					{
						// Implement each feed links.
						self.item().implement( EasySocial.Controller.Dashboard.Feeds.Item ,
						{
							"{parent}"		: self,
							"{dashboard}"	: self.parent
						});
					}
				}
			});

		EasySocial.Controller(
			'Dashboard.Feeds.Item',
			{
				defaultOptions:
				{
				}
			},
			function(self)
			{

				return{

					init : function()
					{
					},

					/**
					 * Fires when a feed link is clicked.
					 */
					"{self} click" : function()
					{
						//remove no-stream class if any
						$('.es-streams').removeClass( 'no-stream' );

						var type 	= self.element.data( 'type' ),
							id		= self.element.data( 'id' ),
							url 	= self.element.data( 'url' ),
							title 	= self.element.data( 'title' ),
							desc 	= self.element.data( 'description' );

						// if( type == 'me' )
						// {
						// 	// clear the new feed notification counter.
						// 	$( '[data-dashboard-feeds]' ).find('li:first-child').removeClass( 'has-notice' );
						// }


						// clear new feed counter
						self.element.removeClass( 'has-notice' );

						// If this is an embedded layout, we need to play around with the push state.
						History.pushState( {state:1} , title , url );

						// Notify the dashboard that it's starting to fetch the contents.
						self.dashboard.content().html("");
						self.dashboard.updatingContents();

						self.element.addClass( 'loading' );

						EasySocial.ajax( 'site/controllers/dashboard/getStream' ,
						{
							"type"	: type,
							"id"	: id,
							"view"  : 'dashboard',
						})
						.done(function( contents, count )
						{
							self.dashboard.updateHeading( title , desc );

							if( count == 0)
							{
								$('.es-streams').addClass( 'no-stream' );
							}

							self.dashboard.updateContents( contents );
						})
						.fail( function( messageObj ){

							return messageObj;
						})
						.always(function(){

							self.element.removeClass( 'loading' );

						});


					}
				}
			});
		module.resolve();
	});

});
