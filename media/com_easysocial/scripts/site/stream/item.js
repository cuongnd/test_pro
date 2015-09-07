EasySocial.module( 'site/stream/item' , function(){

	var module	= this;

	EasySocial.require()
	.library( 'dialog' )
	.done(function($){

		EasySocial.Controller(
		'Stream.Item',
		{
			defaultOptions:
			{
				// Properties
				id 			: "",
				context 	: "",

				// Elements
				"{deleteFeed}"	: "[data-stream-delete]",
				"{hideLink}"	: "[data-stream-hide]",
				"{unHideLink}"	: "[data-stream-show]",

				"{hideAppLink}"	: "[data-stream-hide-app]",
				"{unHideAppLink}"	: "[data-stream-show-app]",

				"{hideNotice}"	: "[data-stream-hide-notice]",

				"{actions}"		: "[data-streamItem-actions]",
				"{contents}"	: "[data-streamItem-contents]",

				"{streamData}"	: "[data-stream-item]",

				"{likes}"			: "[data-likes-action]",
				"{counterBar}"		: "[data-stream-counter]",
				"{likeContent}" 	: "[data-likes-content]",
				"{repostContent}" 	: "[data-repost-content]",

				"{share}"			: "[data-repost-action]",

				// for stream comment
				"{streamCommentLink}" 	: "[data-stream-action-comments]",
				"{streamCommentBlock}" 	: "[data-comments]"
			}
		},
		function( self )
		{
			return {

				init: function()
				{

					// Set the stream's unique id.
					self.options.id 		= self.element.data( 'id' );
					self.options.context 	= self.element.data( 'context' );
					self.options.ishidden 	= self.element.data( 'ishidden' );

					// Render core actions
					// self.initActions();

				},

				plugins: {},


				"{likes} onLiked": function(el, event, data) {

					//need to make the data-stream-counter visible
					self.counterBar().removeClass( 'hide' );

				},

				"{likes} onUnliked": function(el, event, data) {

					var isLikeHide 		= self.likeContent().hasClass('hide');
					var isRepostHide 	= self.repostContent().hasClass('hide');

					if( isLikeHide && isRepostHide )
					{
						self.counterBar().addClass( 'hide' );
					}
				},

				"{share} create": function(el, event, itemHTML) {

					//need to make the data-stream-counter visible
					self.counterBar().removeClass( 'hide' );

				},


				"{streamCommentLink} click" : function()
				{
					self.streamCommentBlock().toggle();
				},

				/**
				 * Executes when a stream action is clicked.
				 */
				"{actions} click" : function( el , event )
				{
					// Remove active class on all action links
					self.actions().removeClass( 'active' );

					// Add active class on itself.
					$( el ).addClass( 'active' );
				},

				/**
				 * Delete a stream item
				 */

				 "{deleteFeed} click" : function()
				 {
					var uid = self.options.id

					EasySocial.dialog({
						content		: EasySocial.ajax( 'site/views/stream/confirmDelete' ),
						bindings	:
						{
							"{deleteButton} click" : function()
							{
								EasySocial.ajax( 'site/controllers/stream/delete',
								{
									"id"		: uid,
								})
								.done(function( html )
								{

									EasySocial.dialog({
										content: html
									});

									self.element.fadeOut();

									// close dialog box.
									//EasySocial.dialog().close();
								})
								.fail(function( message ){

									EasySocial.dialog({
										content: message
									});


								});

							}
						}
					});

				 },


				/**
				 * Hide's a stream item.
				 */
				"{hideLink} click" : function()
				{
					// Add hide class
					self.streamData().addClass( 'es-feed-loading' );

					EasySocial.ajax( 'site/controllers/stream/hide',
					{
						"id"		: self.options.id
					})
					.done(function( html )
					{
						self.streamData().removeClass( 'es-feed-loading' );

						self.streamData().hide();
						self.element.append( html );
					})
					.fail(function( message ){

					});
				},

				/**
				 * Hide's a stream item.
				 */
				"{hideAppLink} click" : function()
				{
					// self.actions().trigger( "onHideStream" , self.options.id );
					EasySocial.ajax( 'site/controllers/stream/hideapp',
					{
						"context"		: self.options.context
					})
					.done(function( html )
					{
						// self.streamData().hide();
						// self.element.append( self.view.hiddenItem() );

						// hide itself.
						self.streamData().hide();

						// hide all feeds that belong to this context.
						$( '.stream-context-' + self.options.context ).addClass('hide-stream');

						self.element.append( html );

					})
					.fail(function( message ){
						console.log( message );
					});
				},

				/**
				 * unHide's a stream item.
				 */
				"{unHideLink} click" : function()
				{

					EasySocial.ajax( 'site/controllers/stream/unhide',
					{
						"id"		: self.options.id
					})
					.done(function()
					{
						self.hideNotice().remove();
						self.streamData().show();

					})
					.fail(function( message ){
						console.log( message );
					});
				},

				/**
				 * unHide's a stream item.
				 */
				"{unHideAppLink} click" : function()
				{

					EasySocial.ajax( 'site/controllers/stream/unhideapp',
					{
						"context"		: self.options.context
					})
					.done(function()
					{
						self.hideNotice().remove();

						//show itself.
						self.streamData().show();

						// show all the items with same context
						$( '.stream-context-' + self.options.context ).removeClass('hide-stream');

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
