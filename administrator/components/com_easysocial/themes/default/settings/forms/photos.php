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

// Photo quality
$photoQuality	= array();

for( $i = 0; $i <= 100; $i += 10 )
{
	$message	= $i;
	$message	= $i == 0 ? JText::sprintf( 'COM_EASYSOCIAL_PHOTOS_SETTINGS_UPLOAD_QUALITY_LOW' , $i ) : $message;
	$message	= $i == 50 ? JText::sprintf( 'COM_EASYSOCIAL_PHOTOS_SETTINGS_UPLOAD_QUALITY_MEDIUM' , $i ) : $message;
	$message	= $i == 100 ? JText::sprintf( 'COM_EASYSOCIAL_PHOTOS_SETTINGS_UPLOAD_QUALITY_HIGH' , $i ) : $message;

	$photoQuality[]	= array( 'text' => $message , 'value' => $i );
}

echo $settings->renderPage(
	$settings->renderColumn(
		$settings->renderSection(
			$settings->renderHeader( 'General' ),
			$settings->renderSetting( 'Enable Photos', 'photos.enabled', 'boolean', array( 'help' => true ) ),
			$settings->renderSetting( 'Photo Pagination', 'photos.pagination.photo', 'input', array( 'help' => true ) )
		)
	),
	$settings->renderColumn(
		$settings->renderSection(
			$settings->renderHeader( 'Uploader' ),
			$settings->renderSetting( 'Photo Quality', 'photos.quality', 'list', array( 'options' => $photoQuality , 'help' => true , 'info' => true ) ),
			$settings->renderSetting( 'Upload Limit', 'photos.uploader.maxsize', 'input' , array( 'help' => true , 'class' => 'input-mini center' , 'unit' => true ) )
		)
	)
);
