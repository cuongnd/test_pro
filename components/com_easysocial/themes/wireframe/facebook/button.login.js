
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/

EasySocial.require()
.script( 'oauth/facebook' )
.done( function($)
{
	$( '[data-oauth-facebook]' ).implement( EasySocial.Controller.OAuth.Facebook,
	{
		appId 	: "<?php echo $appId;?>",
		url		: "<?php echo $authorizeURL;?>"
	});

});
