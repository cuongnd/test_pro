EasySocial.module( 'site/users/users' , function($){

	var module 				= this;

	EasySocial.require()
	.library( 'history' )
	.view(
		'site/loading/small'
	)
	.done(function($){

		EasySocial.Controller(
			'Users',
			{
				defaultOptions :
				{
					"{content}"	: "[data-users-content]",
					"{listing}"	: "[data-users-listing]",
					"{sort}"	: "[data-users-sort]",
					"{filter}"	: "[data-users-filter]",
					"{items}"	: "[data-users-item]",
					"{pagination}" : "[data-users-pagination]",

					view :
					{
						loading 	: 'site/loading/small'
					}
				}
			},
			function( self )
			{
				return {

					init : function()
					{
						// Implement user item controller
						self.initUserController();
					},

					initUserController : function()
					{
						self.items().implement( EasySocial.Controller.Users.Item ,
						{
							"{parent}"	: self
						});
					},

					"{filter} click" : function( el , event )
					{
						event.preventDefault();

						// Remove any active states for filters and sort items
						self.sort().removeClass( 'active' );
						self.filter().each(function(){
							$(this).parent().removeClass( 'active' );
						});

						// Add active class to the current filter item.
						$( el ).parent().addClass( 'active' );

						// Get the sort type.
						var filter 	= $( el ).data( 'filter' );

						self.options.filter 	= filter;
						$( el ).route();

						// Add loading state to the content.
						self.listing().html( self.view.loading() );

						// Set the first sort item as the active item
						self.sort( ':first' ).addClass( 'active' );

						// Perform the ajax call to retrieve the new users listing
						EasySocial.ajax( 'site/controllers/users/getUsers',
						{
							"filter" 			: filter,
							"showpagination"	: 1
						})
						.done(function( output )
						{
							self.content().html( output );

							// Re-apply controller
							self.initUserController();
						});
					},

					"{sort} click" : function( el , event )
					{
						event.preventDefault();

						// Get the sort type
						var type 	= $( el ).data( 'type' );

						$( el ).route();

						// Add the active state on the current element.
						self.sort().removeClass( 'active' );

						$( el ).addClass( 'active' );

						// Add loading state to the content.
						self.listing().html( self.view.loading() );

						// Remove pagination
						self.pagination().remove();
						
						EasySocial.ajax( 'site/controllers/users/getUsers' ,
						{
							"sort"				: type,
							"filter"			: self.options.filter,
							"isSort" 			: true,
							"showpagination" 	: 1
						})
						.done(function(contents)
						{


							self.listing().html( contents );

							// Re-apply controller
							self.initUserController();
						});

					}
				}
			});

		EasySocial.Controller(
		'Users.Item',
		{
			defaultOptions:
			{
				id 					: null,
				"{addFriend}"		: "[data-users-add-friend]",
				"{friendsButton}" 	: "[data-users-friends-button]",
				"{compose}"			: "[data-users-friends-compose]",
				"{unfriend}"		: "[data-users-friends-unfriend]"
			}
		},
		function( self )
		{
			return {

				init: function()
				{
					self.options.id 	= self.element.data( 'id' );
				},

				"{addFriend} click" : function( el , event )
				{
					// Add a loading state to the button
					$( el ).addClass( 'btn-loading' );

					// Append loading state on the button
					EasySocial.ajax( 'site/controllers/friends/request' ,
					{
						"viewCallback"	: "usersRequest",
						"id"		: self.options.id
					})
					.done(function( pendingButton )
					{
						// Replace the button
						$( el ).replaceWith( pendingButton );

						// Remove the loading state from the button
						$( el ).removeClass( 'btn-loading' );
					});
				}

			}
		});

		module.resolve();
	});


});
