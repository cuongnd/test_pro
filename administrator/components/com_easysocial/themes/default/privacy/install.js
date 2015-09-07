EasySocial.ready(function($){

	// Set the task to the correct task.
	$( '.installUpload' ).bind( 'click' , function()
	{
		$( '.privacyForm input[name=task]' ).val( 'upload' );
		$( '.privacyForm' ).submit();
	});

});
