EasySocial.module("story/friends", function($){

	var module = this;

	// $.template("easysocial/story/linkItem", '<div class="es-story-linkitem" data-story-linkItem><h6><a href="[%= url %]">[%= info.title %]</a></h6><p>[%= info.description %][%= JSON.stringify(info) %]</p><a class="ies-cancel-2 remove-linkitem" data-story-removeLinkItem></a>');

	EasySocial.require()
		.library(
			// "mentions",
			"textboxlist"
		)
		.language(
			"COM_EASYSOCIAL_WITH_FRIENDS",
			"COM_EASYSOCIAL_AND_ONE_OTHER",
			"COM_EASYSOCIAL_AND_MANY_OTHERS"
		)
		.done(function(){

			EasySocial.Controller("Story.Friends",
				{
					defaultOptions: {

						"{friendList}": ".es-story-friends-textbox",

						showSummary: false,
						summarizeNamesAfter: 2, // people
						concatNamesWith: ', ', // character
					}
				},
				function(self) { return {

					init: function() {

						// I have access to:
						// self.panelButton()
						// self.panelContent()

						// Friend tagging
						self.friendList()
							.textboxlist({
								plugin: {
									autocomplete: {
										exclusive : true,
										cache	  : false,
										query     : self.search,
										filterItem: self.createMenuItem
									}
								}
							});

						// Friend mentioning
						// self.story.textField()
						// 	.mentionsInput({
						// 	    elastic: false,
						// 	    minChars: 1,
						// 		onDataRequest: self.mention
						// 	});
					},

					search: function(keyword) {

						var users = self.getTaggedUsers();

						return EasySocial.ajax(
								   "site/controllers/friends/suggest",
								   {
								   	   "search": keyword,
								   	   "exclude": users
								   });
					},

					getTaggedUsers: function()
					{
						var users = [];
						var items = $( "[data-textboxlist-item]" );
						if( items.length > 0 )
						{
							$.each( items, function( idx, element ) {
								users.push( $( element ).data('id') );
							});
						}

						return users;
					},

					//
					// Tagging
					//
					createMenuItem: function(item, keyword) {

						item.title = item.screenName;

						var avatar = $(new Image())
							.addClass("textboxlist-menu-avatar")
							.attr({
								src: item.avatar
							}).toHTML();

						item.html     = avatar + ' ' + item.title;
						item.menuHtml = avatar + ' ' + item.title;

						return item;
					},

					updatePanelCaption: function() {

						var options = self.options,
							friendList = self.friendList().controller("textboxlist"),
							addedItems = friendList.getAddedItems();

						var total = addedItems.length,
							limit = options.summarizeNamesAfter,
							sliceAt = Math.max(limit - 1, total - 1),
							balance = total - limit,
							caption = total;

						if (options.showFriendSummary) {
							caption =
								$.language(
									"COM_EASYSOCIAL_WITH_FRIENDS",
									$.map(addedItems.slice(0, sliceAt), function(item){
										return item.screenName;
									}).join(options.concatNamesWith)
								);

							if (balance == 1) {
								caption += $.language("COM_EASYSOCIAL_AND_ONE_OTHER");
							}

							if (balance > 1) {
								caption += $.language("COM_EASYSOCIAL_AND_MANY_OTHERS", balance);
							}
						}

						self.story.addPanelCaption("friends", caption);
					},

					"{friendList} addItem": function() {
						self.updatePanelCaption();
					},

					"{friendList} removeItem": function() {
						self.updatePanelCaption();
					},

					//
					// Mentions
					//
					mention: function (mode, query, callback) {

						self.search(query)
							.done(function(users){

								var friends = [];

								$.each(users, function(i, user){

									friends.push({
										id: user.id,
										name: user.screenName,
										avatar: user.avatar,
										type: 'contact'
									});
								});

								callback(friends);
							});
					},

					"{story} save": function(el, event, save) {

						var friendList = self.friendList().controller("textboxlist");

						var tags =
							friendList.getAddedItems().map(function(friend){
								return friend.id;
							});

						save.addData(self, {
							tags: tags
						});

						// self.story.textField()
						// 	.mentionsInput("val", function(markup){
						// 		save.data.friend_markup = markup;
						// 	})
						// 	.mentionsInput("getMentions", function(mentions){
						// 		save.data.friend_mentions = $.map(mentions, function(friend){
						// 			return friend.id;
						// 		});
						// 	});
					},

					"{story} clear": function() {

						var friendList = self.friendList().controller("textboxlist");

						friendList.clearItems();
					}
				}}
			);

			// Resolve module
			module.resolve();

		});

});
