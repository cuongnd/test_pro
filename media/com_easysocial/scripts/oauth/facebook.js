EasySocial.module( 'oauth/facebook', function($) {
	
	var module = this;

	EasySocial.require()
	.done(function() {

		EasySocial.Controller( 'OAuth.Facebook',
		{
			defaultOptions :
			{
				"{login}"	: "[data-oauth-facebook-login]",
				"{revoke}"	: "[data-oauth-facebook-revoke]",

				"{pushInput}"	: "[data-oauth-facebook-push]"
			}
		},
		function( self )
		{
			return {
				init : function()
				{
				},

				openDialog : function( url )
				{
					var left	= (screen.width/2)-( 300 /2),
						top		= (screen.height/2)-( 300 /2);
						
					window.open( url , "" , 'scrollbars=no,resizable=no,width=300,height=300,left=' + left + ',top=' + top );
				},

				"{pushInput} change" : function( el )
				{
					var enabled 	= $(el).val();
					
					if( enabled == 1 && self.options.requestPush )
					{
						self.openDialog( self.options.addPublishURL )
					}

					if( enabled == 0 )
					{
						self.openDialog( self.options.revokePublishURL );
					}
				},

				"{login} click" : function()
				{
					self.openDialog( self.options.url );
				},

				"{revoke} click" : function()
				{
					var callback 	= self.element.data( 'callback' );
					
					EasySocial.dialog(
					{
						content 	: EasySocial.ajax( 'site/views/oauth/confirmRevoke' , { "client" : 'facebook' , "callbackUrl" : callback } )
					});
				}
			}
		});

		module.resolve();
	});

}); // module end
