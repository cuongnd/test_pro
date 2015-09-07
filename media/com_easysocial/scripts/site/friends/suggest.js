EasySocial.module( 'site/friends/suggest' , function($){

	var module 	= this;

	EasySocial.require()
	.view( 'site/friends/suggest.item' )
	.library( 'textboxlist' )
	.language( 'COM_EASYSOCIAL_FRIENDS_REQUEST_SENT' )
	.done(function($){


		EasySocial.Controller(
			'Friends.Suggest.User',
			{
				defaultOptions:
				{
					"{addButton}"		: "[data-friend-suggest-add]",
					"{button}"			: "[data-friend-suggest-button]"

				}
			},
			function( self ){

				return {
					init: function(){
						// console.log( 'friends suggest');
					},

					"{addButton} click" : function( el ){

						// Implement controller on add friend.
						EasySocial.ajax( 'site/controllers/friends/request' ,
						{
							"id"	: self.element.data( 'uid' )
						})
						.done( function( friendId )
						{
							// replace the button with done message.
							self.button().html( $.language('COM_EASYSOCIAL_FRIENDS_REQUEST_SENT') );

						})
						.fail(function( message )
						{
							self.button().html( message );
						});
					}

				}

			}

		);

		EasySocial.Controller("Friends.Suggest",
		{
			defaultOptions:
			{
				max: null,
				exclusive: true,
				exclusion: [],
				minLength: 1,
				highlight: true,
				name: "uid[]",

				view: {
					suggestItem: "site/friends/suggest.item"
				}
			}
		},
		function(self) { return {

			init: function() {

				var options = self.options;

				// Implement the textbox list on the implemented element.
				self.element
					.textboxlist({
						name: options.name,
						max: options.max,
						plugin: {
							autocomplete: {

								exclusive: options.exclusive,
								minLength: options.minLength,
								highlight: options.highlight,
								showLoadingHint: true,
								showEmptyHint: true,

								query: function(keyword) {

									return EasySocial.ajax("site/controllers/friends/suggest", {search: keyword});
								}
							}
						}
					})
					.textboxlist("enable");
			},

			"{self} filterItem": function(el, event, item) {

				var html = 
					self.view.suggestItem(true, {
						item: item,
						name: self.options.name
					});

				item.title    = item.screenName;
				item.menuHtml = html;
				item.html     = html;

				return item;
			},			

			"{self} filterMenu": function(el, event, menu, menuItems, autocomplete, textboxlist) {

				// Get list of excluded users
				var items = textboxlist.getAddedItems(),
					users = $.pluck(items, "id"),
					users = users.concat(self.options.exclusion);

				menuItems.each(function(){

					var menuItem = $(this),
						item = menuItem.data("item");

					// If this user is excluded, hide the menu item
					menuItem.toggleClass("hidden", $.inArray(item.id.toString(), users) > -1);
				});
			}

		}});

		module.resolve();
	});

});

