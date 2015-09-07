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

// limit options. in minute
$limitOptions = array(
		$settings->makeOption( '30 Mins', '30' ),
		$settings->makeoption( '1 Hour', '60' ),
		$settings->makeoption( '3 Hour', '180' ),
		$settings->makeoption( '6 Hour', '360' ),
		$settings->makeoption( '12 Hour', '720' ),
		$settings->makeoption( '1 Day', '1440' ),
		$settings->makeoption( '3 Day', '4320' ),
		$settings->makeoption( '7 Day', '10080' ),
		'help' => true
	);


echo $settings->renderPage(

	$settings->renderColumn(
		$settings->renderSection(
			$settings->renderHeader( 'General Features' ),
			//$settings->renderSetting( 'Follow Enabled' , 'stream.follow.enabled' , 'boolean' , array( 'help' => true ) ),
			$settings->renderSetting( 'Comments Enabled' , 'stream.comments.enabled' , 'boolean' , array( 'help' => true ) ),
			$settings->renderSetting( 'Likes Enabled' , 'stream.likes.enabled' , 'boolean' , array( 'help' => true ) ),
			$settings->renderSetting( 'Repost Enabled' , 'stream.repost.enabled' , 'boolean' , array( 'help' => true ) )
		),
		$settings->renderSection(
			$settings->renderHeader( 'Pagination' ),
			$settings->renderSetting( 'Auto Load When Scroll' , 'stream.pagination.autoload' , 'boolean' , array( 'help' => true ) ),
			$settings->renderSetting( 'Data fetch limit', 'stream.pagination.limit', 'list', $limitOptions )
		)
	),

	$settings->renderColumn(
		$settings->renderSection(
			$settings->renderHeader( 'New updates' ),
			$settings->renderSetting( 'Enabled' , 'stream.updates.enabled' , 'boolean' , array( 'help' => true )),
			$settings->renderSetting( 'Interval', 'stream.updates.interval', 'input' , array( 'help' => true , 'class' => 'center input-mini' , 'unit' => 'Seconds' ) )
		),
		$settings->renderSection(
			$settings->renderHeader( 'Aggregation' ),
			$settings->renderSetting( 'Enable', 'stream.aggregation.enabled' , 'boolean' , array( 'help' => true ) ),
			$settings->renderSetting( 'Duration', 'stream.aggregation.duration', 'input' , array( 'help' => true , 'class' => 'center input-mini' , 'unit' => 'Minutes' ) )
		)
	)

);
