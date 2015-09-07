
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/

EasySocial.require()
.library( 'dialog' )
.done(function($)
{

	$( '[data-feeds-create]' ).on( 'click' , function()
	{
		EasySocial.dialog({
			content	: EasySocial.ajax( "apps/user/feeds/views/feeds/form" , { 'id' : '<?php echo $app->id;?>' } ),
			bindings : 
			{
				"{saveButton} click" : function()
				{
					// Get the feed title and feed url
					var title 	= $( '[data-feeds-form-title]' ).val(),
						url 	= $( '[data-feeds-form-url]' ).val();

					EasySocial.ajax( 'apps/user/feeds/controllers/feeds/save' ,
					{
						"title"	: title,
						"url"	: url,
						"id"	: "<?php echo $app->id;?>"
					})
					.done(function( contents )
					{
						// Close dialog
						EasySocial.dialog().close();

						$( '[data-feeds-lists]' ).append( contents );

						$( '[data-feeds-empty]' ).hide();
					});
				}
			}
		});
	});

	$( '[data-feeds-lists]' ).on( 'click' , '[data-feeds-item-remove]' , function()
	{
		var id 		= $( this ).parents( '.feed-item' ).data( 'id' ),
			parent	= $( this ).parents( '.feed-item' );

		EasySocial.dialog(
		{
			content	: EasySocial.ajax( "apps/user/feeds/views/feeds/confirmDelete" , { 'id' : '<?php echo $app->id;?>' } ),
			bindings : 
			{
				"{deleteButton} click" : function()
				{
					EasySocial.ajax( 'apps/user/feeds/controllers/feeds/delete' ,
					{
						"id"		: "<?php echo $app->id;?>",
						"feedId"	: id
					})
					.done(function()
					{
						EasySocial.dialog().close();

						$( parent ).remove();

						if( $( '[data-feeds-lists]' ).children().length == 0 )
						{
							$( '[data-feeds-empty]' ).show();
						}
					});
				}
			}
		});

	});

});
