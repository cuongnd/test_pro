EasySocial.module( 'site/conversations/composer' , function($){

	var module 	= this;

	EasySocial.require()
	.library( 'expanding' )
	.script( 'site/friends/suggest' , 'uploader/uploader' , 'location' )
	.language(
		'COM_EASYSOCIAL_CONVERSATIONS_ERROR_EMPTY_RECIPIENTS',
		'COM_EASYSOCIAL_CONVERSATIONS_ERROR_EMPTY_MESSAGE'
	)
	.done(function($){

		EasySocial.Controller(
			'Conversations.Composer',
			{
				defaultOptions:
				{
					// Determines if these features should be enabled.
					attachments 		: true,
					location 			: true,

					// Uploader properties.
					extensionsAllowed	: "",

					// File uploads
					"{uploader}"		: "[data-composer-attachment]",

					// Location service.
					"{location}"		: "[data-composer-location]",

					// The text editor.
					"{editor}"			: "[data-composer-editor]",

					// Wrapper for suggest to work.
					"{friendSuggest}"		: "[data-friends-suggest]",

					"{recipients}"		: "input[name=uid\\[\\]]",

					"{recipientRow}"	: "[data-composer-recipients]",
					"{messageRow}"		: "[data-composer-message]",

					// Submit button
					"{submit}"			: "[data-composer-submit]"
				}
			},
			function( self ){
				return {

					init: function()
					{
						// Initialize the participants textbox.
						self.initSuggest();

						// Initialize editor.
						self.initEditor();

						// Initialize uploader
						if( self.options.attachments )
						{
							self.initUploader();
						}

						// Initialize location
						if( self.options.location )
						{
							self.initLocation();	
						}
					},

					/**
					 * Initializes the location form.
					 */
					initLocation: function()
					{
						self.location().implement( EasySocial.Controller.Location.Form );
					},

					/**
					 * Resets the conversation form.
					 */
					resetForm: function()
					{
						self.editor().val('');
					},

					/**
					 * Initializes the uploader.
					 */
					initUploader: function()
					{
						// Implement uploader controller.
						self.uploader().implement( EasySocial.Controller.Uploader ,
						{
							// We want the uploader to upload automatically.
							temporaryUpload	: true,
							query 			: "type=conversations",
							type 				: 'conversations',
							extensionsAllowed : self.options.extensionsAllowed
						});

						if( EasySocial.environment == 'development' )
						{
							console.log( 'Extensions Allowed: ' + self.options.extensionsAllowed );
							console.log( 'Maximum individual file size: ' + self.options.maxSize );
						}
					},

					/**
					 * Initializes and converts the normal textbox into a suggest list.
					 */
					initSuggest: function()
					{
						self.friendSuggest()
							.addController(EasySocial.Controller.Friends.Suggest);
					},

					/**
					 * Initializes the editor
					 *
					 */
					initEditor : function()
					{
						self.editor().expandingTextarea();
					},

					/**
					 * Check for errors on the conversation form.
					 */
					checkErrors: function()
					{
						if( self.recipients().length <= 0 )
						{
							self.recipientRow().addClass( 'error' );
							self.clearMessage();
							self.setMessage( $.language( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_EMPTY_RECIPIENTS' ) , 'error' );

							return true;
						}
						else
						{
							self.recipientRow().removeClass( 'error' );
						}

						if( self.editor().val() == '' )
						{
							self.messageRow().addClass( 'error' );
							self.clearMessage();
							self.setMessage( $.language( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_EMPTY_MESSAGE' ) , 'error' );

							return true;
						}
						else
						{
							self.messageRow().removeClass( 'error' );
						}

						return false;
					},

					/**
					 * Submit button.
					 */
					"{submit} click" : function( el , event )
					{
						// Prevent form submission since this is a submit button.
						event.preventDefault();

						// Check for errors on this page.
						if( self.checkErrors() )
						{
							return false;
						}

						if( self.options.attachments )
						{
							var uploaderController 	= self.uploader().controller();

							// Do not allow user to submit this when the items are still being uploaded.
							if( uploaderController.options.uploading && uploaderController.hasFiles() )
							{
								return false;
							}
						}

						// Submit the form when we're ready.
						self.element.submit();
					}
				}
			}
		);

		EasySocial.Controller(
			'Conversations.Composer.Dialog',
			{
				defaultOptions:
				{
					// Default options
					recipient 		: {},
				}
			},
			function( self ){
				return {
					init: function()
					{

					},

					"{self} click" : function()
					{
						EasySocial.dialog(
						{
							"content"	: EasySocial.ajax( 'site/views/conversations/composer' , { "id" : self.options.recipient.id } ),
							"bindings"	:
							{
								"{sendButton} click" : function()
								{
									var recipient 	= $( '[data-composer-recipient]' ).val(),
										message 	= $( '[data-composer-message]' ).val();


									EasySocial.ajax( 'site/controllers/conversations/store' ,
									{
										"uid"		: recipient,
										"message"	: message
									})
									.done(function( link )
									{
										EasySocial.dialog(
										{
											"content"	: EasySocial.ajax( 'site/views/conversations/sent' , { "id" : self.options.recipient.id }),
											"bindings"	:
											{
												"{viewButton} click" : function()
												{
													document.location 	= link;
												}
											}
										});
									})
									.fail( function( message )
									{
										self.setMessage( message );
									});
								}
							}
						});
					}
				}
		});

		module.resolve();
	});

});

