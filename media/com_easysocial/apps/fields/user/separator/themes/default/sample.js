
EasySocial.module( 'field.separator', function($){

	var module = this;

	EasySocial.Controller( 'Field.Separator', 
	{
		defaultOptions: 
		{
			"{items}"	: "[data-separator-type]"
		}
	},
	function( self )
	{
		return {
			"{self} onConfigChange" : function( el , event , name , value )
			{
				if( name == 'type' )
				{
					var itemToShow 	= $( '[data-separator-' + value + ']' );

					// Hide all separators
					self.items().hide();

					// Only show the correct separator
					itemToShow.show();
				}

			}
		}
	});

	module.resolve();
});
