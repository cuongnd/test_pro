EasySocial.ready(function($){

	// Set the task to the correct task.
	$( '.installUpload' ).bind( 'click' , function()
	{
		$( '.pointsForm input[name=task]' ).val( 'upload' );
		$( '.pointsForm' ).submit();
	});

});