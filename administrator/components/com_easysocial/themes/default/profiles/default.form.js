EasySocial.require()
.script( 'utilities/alias' )
.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_SAVE_ERROR')
.done(function($){

	// Apply alias generator here.
	$( '.profileForm' ).implement( 'EasySocial.Controller.Utilities.Alias' ,
	{
		"{source}"	: "#title",
		"{target}"	: "#alias"
	});

	$.Joomla( 'submitbutton' , function( task )
	{
		<?php if( $profile->id ){ ?>
		if( task == 'save' || task == 'savenew' || task == 'apply' )
		{
			var deferreds = [];

			// Execute the saving of the custom fields here. Once it is done, only then submit it to the server.
			deferreds.push($('.profileFieldForm').controller().save());

			$.when.apply(null, deferreds)
				.done(function() {
					// Submit the form.
					$.Joomla( 'submitform' , [ task ] );
				})
				.fail(function(msg) {
					if(msg === undefined) {
						msg = $.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_SAVE_ERROR');
					}

					EasySocial.dialog({
						content: msg
					})
				});


			return false;
		}
		<?php } ?>

		if( task == 'cancel' )
		{
			window.location.href	= '<?php echo JURI::root();?>administrator/index.php?option=com_easysocial&view=profiles';

			return;
		} 
		$.Joomla( 'submitform' , [ task ] );
	});
});
