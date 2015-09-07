
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/

EasySocial.require()
.script( 'admin/reports/reporters' )
.done(function($)
{
	$( '[data-reporters]' ).implement( EasySocial.Controller.Reports.Reporters );
});