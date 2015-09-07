EasySocial.module('site/conversations/api', function($){

	var module = this;

	EasySocial.require()
		.library('dialog')
		.done(function(){

			// Data API
			$(document)
				.on('click.es.conversations.compose', '[data-es-conversations-compose]', function(){

					

					var element 	= $(this),
						userId 		= element.data( 'es-conversations-id')


					EasySocial.dialog(
					{
						"content"	: EasySocial.ajax( 'site/views/conversations/composer' , { "id" : userId } ),
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
										"content"	: EasySocial.ajax( 'site/views/conversations/sent' , { "id" : userId }),
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
				})

			module.resolve();
		});
});