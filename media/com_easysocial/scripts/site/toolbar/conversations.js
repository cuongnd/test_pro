EasySocial.module( 'site/toolbar/conversations' , function($){

	var module 				= this;

	EasySocial.require()
	.view( 'site/loading/small' )
	.library( 'tinyscrollbar' )
	.done(function($){

		EasySocial.Controller(
			'Notifications.Conversations',
			{
				defaultOptions:
				{

					// Check every 10 seconds by default.
					interval	: 30,

					// Views
					view	:
					{
						loadingIcon		: 'site/loading/small'
					},

					// Elements within this container.
					"{counter}"			: "[data-notificationConversation-counter]",
					"{dropdown}"		: "[data-notificationConversation-dropdown]",
					"{loader}"			: "[data-notificationConversation-loader]",

					// Notification items
					"{items}"			: "[data-notificationConversation-items]",

					"{scrollBar}"		: "[data-notificationConversation-scrollbar]"
				}
			},
			function(self){ return{

				init: function()
				{
					// Start the automatic checking of new notifications.
					self.startMonitoring();
				},

				/**
				 * Start running checks.
				 */
				startMonitoring: function()
				{
					var interval 	= self.options.interval * 1000;

					// Debug
					if( EasySocial.debug )
					{
						// console.info( 'Start monitoring conversation notifications at interval of ' + self.options.interval + ' seconds.' );
					}

					self.options.state	= setTimeout( self.check , interval );
				},

				/**
				 * Stop running any checks.
				 */
				stopMonitoring: function()
				{
					// Debug
					if( EasySocial.debug )
					{
						// console.info( 'Stop monitoring conversation notifications.' );
					}

					clearTimeout( self.options.state );
				},


				"{self} showDropdown" : function()
				{
					// Perform an ajax call to retrieve notification items.
					EasySocial.ajax( 'site/controllers/notifications/getConversationItems',
					{
						usemax: 1,
						// filter: "unread",

						beforeSend: function()
						{
							// Show loader
							self.loader().show();

							// Hide items first.
							self.items().hide();
						}
					})
					.done(function( content ){

						// Hide loader.
						self.loader().hide();

						self.items().html( content );

						// Show items.
						self.items().show();

						// Apply tinyscrollbar on the dropdown.
						self.scrollBar().tinyscrollbar();
					});
				},


				/**
				 * Check for new updates
				 */
				check: function()
				{

					// Stop monitoring so that there wont be double calls at once.
					self.stopMonitoring();

					var interval 	= self.options.interval * 1000;

					// Needs to run in a loop since we need to keep checking for new notification items.
					setTimeout( function(){

						EasySocial.ajax( 'site/controllers/notifications/getConversationCounter' , {},
						{
							type : "jsonp"
						})
						.done( function( total ){

							if( total > 0 )
							{
								// Add new notice on the toolbar
								self.element.addClass( 'has-notice' );

								// Update the counter's count.
								self.counter().html( total );
							}
							else
							{
								self.element.removeClass( 'has-notice' );
							}

							// Continue monitoring.
							self.startMonitoring();
						});

					}, interval );

				},

				"{dropdown} click" : function( el , event )
				{
					// Disallow clicking of events to trigger parent items.
					event.stopPropagation();
				}
			}}
		);

		module.resolve();
	});

});
