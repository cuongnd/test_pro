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
			$settings->renderHeader( 'Stream' ),
			$settings->renderSetting( 'Insert stream on adding app' , 'apps.stream.add' , 'boolean' , array( 'help' => true ) )
			// ,$settings->renderSetting( 'Insert stream on removing app', 'apps.stream.remove', 'boolean', array( 'help' => true , 'class' => "input-full") )
		)
	),
	$settings->renderColumn(
		$settings->renderSection(
			$settings->renderHeader( 'Terms and conditions' ),
			$settings->renderSetting( 'Require acceptence of terms' , 'apps.tnc.required' , 'boolean' , array( 'help' => true ) ),
			$settings->renderSetting( 'Terms and conditions message', 'apps.tnc.message', 'textarea', array( 'help' => true , 'class' => "input-full" , 'translate' => true ) )
		)
	)
);
