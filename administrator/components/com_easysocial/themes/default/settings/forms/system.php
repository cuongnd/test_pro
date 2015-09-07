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

	// Prepare all the options here
	$dstOptions = array();
	for( $i = -4 ; $i <= 4; $i++ ) {
		$dstOptions[] = array( 'text' => $i . ' ' . JText::_( 'COM_EASYSOCIAL_SYSTEM_SETTINGS_DAYLIGHT_SAVING_OFFSET_HOURS' ), 'value' => $i );
	}

	$envOptions = array(
		$settings->makeOption( 'Environment Development', 'development' ),
		$settings->makeoption( 'Environment Optimized', 'optimized' ),
		$settings->makeoption( 'Environment Static', 'static' ),
		'help' => true,
		'info' => true
	);

	$compressOptions = array(
		$settings->makeOption( 'Compression Compressed', 'compressed' ),
		$settings->makeoption( 'Compression Uncompressed', 'uncompressed' ),
		'help' => true,
		'info' => true
	);

	$processEmailText = $settings->renderSettingText( 'Send Email on page load', 'info' ) . ' <a href="#">' . $settings->renderSettingText( 'Send Email on page load', 'learn more' ) . '</a>';

	echo $settings->renderPage(
		$settings->renderColumn(
			$settings->renderSection(
				$settings->renderHeader( 'System Settings' ),
				$settings->renderSetting( 'Environment', 'general.environment', 'list', $envOptions ),
				$settings->renderSetting( 'Javascript Compression', 'general.mode', 'list', $compressOptions ),
				$settings->renderSetting( 'Profiler', 'general.profiler' , 'boolean' , array( 'help' => true ) )
			)
		),
		$settings->renderColumn()
	);
