<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

echo $settings->renderPage(
	$settings->renderColumn(
		$settings->renderSection(
			$settings->renderHeader( 'General' ),
			$settings->renderSetting( 'Allow registrations', 'registrations.enabled' , 'boolean' , array( 'help' => true ) ),
			$settings->renderSetting( 'Use Email as Username', 'registrations.emailasusername', 'boolean', array( 'help' => true ) )
		),
		$settings->renderSection(
			$settings->renderHeader( 'Email' ),
			$settings->renderSetting( 'Show clear password', 'registrations.email.password' , 'boolean' , array( 'help' => true ) )
		)
	),

	$settings->renderColumn(
		$settings->renderSection(
			$settings->renderHeader( 'Stream' ),
			$settings->renderSetting( 'Announce when new user registers', 'registrations.stream.create' , 'boolean' , array( 'help' => true ) )
		)
	)
);
