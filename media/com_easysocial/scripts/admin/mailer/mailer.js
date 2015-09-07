EasySocial.module( 'admin/mailer/mailer' , function($) {

	var module = this;

	EasySocial.Controller(
		'Mailer',
		{
			defaultOptions :
			{
				"{item}"	: "[data-mailer-item]"
			}
		},
		function( self )
		{
			return {
				init : function()
				{
					self.item().implement( EasySocial.Controller.Mailer.Item );
				}
			}
		});

	EasySocial.Controller(
		'Mailer.Item',
		{
			defaultOptions :
			{
				"{preview}"	: "[data-mailer-item-preview]"
			}
		},
		function( self )
		{
			return {
				init : function()
				{
					self.options.id 	= self.element.data( 'id' );
				},

				"{preview} click" : function( el , event )
				{
					EasySocial.dialog(
					{
						content 	: EasySocial.ajax( 'admin/views/mailer/preview' , { 'id' : self.options.id } )
					})
					console.log( self.options.id );
					// EasySocial.dialog(
					// {
					// 	title 		: $.language( 'COM_EASYSOCIAL_MAILER_DIALOG_PREVIEW_TITLE' ),
					// 	content 	: $.rootPath + 'administrator/index.php?option=com_easysocial&view=mailer&layout=preview&tmpl=component&id=' + self.options.id,
					// 	width 		: 700,
					// 	height 		: 680,
					// 	buttons 	:
					// 	[
					// 		{
					// 			name 	: $.language( 'COM_EASYSOCIAL_CLOSE_BUTTON' ),
					// 			classNames : "btn btn-es",
					// 			click	: function()
					// 			{
					// 				EasySocial.dialog().close();
					// 			}
					// 		}
					// 	]
					// });
				}
			}
		});

	module.resolve();

});