EasySocial.module('site/comments/item', function($) {
	var module = this;

	EasySocial.require()
		.language(
			'COM_EASYSOCIAL_COMMENTS_STATUS_SAVE_ERROR',
			'COM_EASYSOCIAL_COMMENTS_STATUS_LOADING',
			'COM_EASYSOCIAL_COMMENTS_STATUS_LOAD_ERROR',
			'COM_EASYSOCIAL_COMMENTS_STATUS_DELETING',
			'COM_EASYSOCIAL_COMMENTS_STATUS_DELETE_ERROR',
			'COM_EASYSOCIAL_LIKES_LIKE',
			'COM_EASYSOCIAL_LIKES_UNLIKE'
		)
		.done(function() {
			/**
			 *	Item controller
			 */
			EasySocial.Controller('Comments.Item', {
				defaultOptions: {
					'id'			: 0,

					'isNew'			: false,

					'{frame}'		: '[data-comments-item-frame]',

					'{avatar}'		: '[data-comments-item-avatar]',

					'{commentFrame}': '[data-comments-item-commentFrame]',

					'{author}'		: '[data-comments-item-author]',

					'{action}'		: '[data-comments-item-actions]',
					'{edit}'		: '[data-comments-item-actions-edit]',
					'{delete}'		: '[data-comments-item-actions-delete]',
					'{spam}'		: '[data-comments-item-actions-spam]',

					'{comment}'		: '[data-comments-item-comment]',

					'{meta}'		: '[data-comments-item-meta]',

					'{date}'		: '[data-comments-item-date] a',

					'{like}'		: '[data-comments-item-like]',
					'{likeCount}'	: '[data-comments-item-likeCount]',

					'{editFrame}'	: '[data-comments-item-editFrame]',
					'{editInput}'	: '[data-comments-item-edit-input]',
					'{editSubmit}'	: '[data-comments-item-edit-submit]',
					'{editStatus}'	: '[data-comments-item-edit-status]',

					'{statusFrame}'	: '[data-comments-item-statusFrame]'
				}
			}, function(self) { return {
				init: function() {
					// Initialise comment id
					self.options.id = self.element.data('id');

					// Register self into the registry of comments
					self.parent.registerComment(self);

					// Add the status plugin
					// self.status = self.addPlugin('status');

					// Using add Controller instead of addPlugin because the parent should reference the item's parent, not the item itself
					self.status = self.element.addController('EasySocial.Controller.Comments.Item.Status', {
						controller: {
							parent: self.parent,
							item: self
						}
					})
				},

				'{like} click': function(el) {
					if(el.enabled()) {
						// Disable the like button
						el.disabled(true);

						// Send the like to the server
						self.likeComment()
							.done(function(liked, count, string) {

								// Enable the button
								el.enabled(true);

								// Set the likes count
								self.likeCount().text(count);

								// Strip off tags from the like text
								string = $('<div></div>').html(string).text();

								// Set the like text
								self.likeCount().attr('data-original-title', string);

								// Set the like button text
								self.like().find('a').text($.language(liked ? 'COM_EASYSOCIAL_LIKES_UNLIKE' : 'COM_EASYSOCIAL_LIKES_LIKE'));
							})
							.fail(function() {

							});
					}
				},

				likeComment: function() {
					return EasySocial.ajax('site/controllers/comments/like', {
						id: self.options.id
					});
				},

				'{likeCount} click': function() {
					EasySocial.dialog({
						content: self.getLikedUsers()
					});
				},

				getLikedUsers: function() {
					return EasySocial.ajax('site/controllers/comments/likedUsers', {
						id: self.options.id
					});
				},

				'{edit} click': function(el) {
					if(el.enabled()) {

						// Disable the edit button
						el.disabled(true);

						// Trigger commentEditLoading event
						self.trigger('commentEditLoading', [self.options.id]);

						self.getRawComment()
							.done(function(comment) {

								// Trigger commentEditLoaded event
								self.trigger('commentEditLoaded', [self.options.id], comment);

								// Set the edit input to the raw comment value
								self.editInput().val(comment);

								// Focus on the edit input
								self.editInput().focus();

								// Init expanding textarea
								self.editInput().expandingTextarea();
							})
							.fail(function(msg) {

								// Trigger commentEditLoadError event
								self.trigger('commentEditLoadError', [self.options.id, msg]);
							});
					}
				},

				getRawComment: function() {
					return EasySocial.ajax('site/controllers/comments/getRawComment', {
						id: self.options.id
					});
				},

				'{editInput} keyup': function(el, event) {
					if(event.which == 13 || event.which == 27) {
						switch(event.which) {
							case 13:
								if(!(event.shiftKey || event.ctrlKey || event.altKey || event.metaKey)) {
									self.submitEdit();
								}

								break;

							case 27:
								// Trigger commentEditCancel event
								self.trigger('commentEditCancel', [self.options.id]);

								break;
						}

						// Enable the edit button
						self.edit().enabled(true);
					}
				},

				'{editSubmit} click': function() {
					self.submitEdit();
				},

				submitEdit: function() {
					// Get and trim the edit value
					var input = $.trim(self.editInput().val());

					// Do not proceed if value is empty
					if(input == '') {
						return false;
					}

					// Trigger commentEditSaving event
					self.trigger('commentEditSaving', [self.options.id, input]);

					// Send the edit to the server
					self.saveEdit(input)
						.done(function(comment) {

							// Trigger commentEdited event
							self.trigger('commentEditSaved', [self.options.id, comment]);

							// Update the comment content
							self.comment().html(comment);

							self.edit().enabled(true);
						})
						.fail(function(msg) {

							// Trigger commentEditError event
							self.trigger('commentEditSaveError', [self.options.id, msg]);
						});
				},

				saveEdit: function(input) {
					return EasySocial.ajax('site/controllers/comments/update', {
						id: self.options.id,
						input: input
					});
				},

				'{delete} click': function(el) {
					if(el.enabled()) {

						// Disable the button first
						el.disabled(true);

						// Prepare the item properly first
						self.frame().hide();
						self.commentFrame().show();

						// Clone the whole item to place in the dialog
						var comment = self.element.clone();

						EasySocial.dialog({
							content: EasySocial.ajax('site/views/comments/confirmDelete', {
								id: self.options.id
							}),
							selectors: {
								"{deleteButton}"  : "[data-delete-button]",
								"{cancelButton}"  : "[data-cancel-button]"
							},
							bindings: {
								"{deleteButton} click": function() {

									// Close the dialog
									EasySocial.dialog().close();

									// Trigger commentDeleting event on parent to announce to sibling frames
									self.parent.trigger('commentDeleting', [self.options.id]);

									// Trigger commentDeleting event on self to announce to child frames
									self.trigger('commentDeleting');

									// Send delete command to server
									self.deleteComment()
										.done(function() {

											// Trigger commentDeleted event on parent, since this element will be remove, no point triggering on self
											self.parent.trigger('commentDeleted', [self.options.id]);

											// Enable the button
											el.enabled(true);
										})
										.fail(function(msg) {

											// Trigger commentDeleteError event on parent to announce to sibling frames
											self.parent.trigger('commentDeleteError', [self.options.id, msg]);

											// Trigger commentDeleteError event on self to announce to child frames
											self.trigger('commentDeleteError', [self.options.id, msg]);
										});
								},

								"{cancelButton} click": function() {

									// Close the dialog
									EasySocial.dialog().close();

									// Enable the button
									el.enabled(true);
								}
							}
						});
					}
				},

				deleteComment: function() {
					return EasySocial.ajax('site/controllers/comments/delete', {
						id: self.options.id
					});
				}
			} });

			/**
			 *	Status frame controller
			 */
			EasySocial.Controller('Comments.Item.Status', {
				defaultOptions: {
					'{frame}'		: '[data-comments-item-frame]',

					'{statusFrame}'	: '[data-comments-item-statusFrame] div',

					'{commentFrame}': '[data-comments-item-commentFrame]',

					'{editFrame}'	: '[data-comments-item-editFrame]'
				}
			}, function(self) { return {

				// commentEditLoading(id)
				// commentEditLoaded(id, rawcomment)
				// commentEditLoadError(id, errormsg)
				// commentEditCancel(id)
				// commentEditSaving(id, newcomment)
				// commentEditSaved(id, newcomment)
				// commentEditSaveError(id, errormsg)
				// commentDeleting(id)
				// commentDeleted(id)
				// commentDeleteError(id, errormsg)

				init: function() {

				},

				setStatus: function(html) {
					self.frame().hide();

					self.statusFrame().html(html);

					self.statusFrame().show();
				},

				'{self} commentEditLoading': function() {
					self.setStatus($.language('COM_EASYSOCIAL_COMMENTS_STATUS_LOADING'));
				},

				'{self} commentEditLoaded': function() {
					self.frame().hide();

					self.editFrame().show();
				},

				'{self} commentEditLoadError': function() {
					self.setStatus($.language('COM_EASYSOCIAL_COMMENTS_STATUS_LOAD_ERROR'));
				},

				'{self} commentEditCancel': function() {
					self.frame().hide();

					self.commentFrame().show();
				},

				'{self} commentEditSaving': function() {
					self.setStatus($.language('COM_EASYSOCIAL_COMMENTS_STATUS_SAVING'));
				},

				'{self} commentEditSaved': function() {
					self.frame().hide();

					self.commentFrame().show();
				},

				'{self} commentEditSaveError': function() {
					self.setStatus($.language('COM_EASYSOCIAL_COMMENTS_STATUS_SAVE_ERROR'));
				},

				'{self} commentDeleting': function() {
					self.setStatus($.language('COM_EASYSOCIAL_COMMENTS_STATUS_DELETING'));
				},

				'{self} commentDeleteError': function(el, event, id, msg) {
					msg = msg || $.language('COM_EASYSOCIAL_COMMENTS_STATUS_DELETE_ERROR')
					self.setStatus(msg);
				}
			} });

			module.resolve();
		});
});
