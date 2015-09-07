<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

// Get installation method here
$method 	= JRequest::getVar( 'method' );
$file 		= dirname( __FILE__ ) . '/installing.' . $method . '.php';

if( !JFile::exists( $file ) )
{
	echo JText::_( 'COM_EASYSOCIAL_INSTALLATION_ERROR_INVALID_INSTALLATION_METHOD' );

	return;
}


include_once( dirname( __FILE__ ) . '/installing.' . $method . '.php' );