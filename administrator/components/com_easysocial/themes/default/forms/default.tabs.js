
EasySocial.ready(function($)
{
	$( '[data-form-tabs]' ).on( 'click' , function(){

		// Check to see if there's any data-tab-active input
		var currentInput 	= $( '[data-tab-active]' );

		if( currentInput )
		{
			var selected 	= $( this ).data( 'item' );

			currentInput.val( selected );
		}

	});

});
