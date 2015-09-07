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
?>
<div class="row-fluid mb-10 mt-10">
	<div class="span2 text-center">
		<img src="<?php echo $app->getIcon( SOCIAL_APPS_ICON_LARGE );?>" />
	</div>

	<div class="span10">
		<div class="app-title">
			<a href="<?php echo FRoute::apps( array( 'layout' => 'canvas' , 'id' => $app->id , 'userid' => $actor->getAlias() , 'customView' => 'item' , 'schedule_id' => $calendar->id ) );?>"><h4><?php echo $calendar->get( 'title' );?></h4></a>
		</div>
		<div>
			<span><?php echo $calendar->getStartDate()->format( 'jS M Y g:iA'); ?></span> - 
			<span><?php echo $calendar->getEndDate()->format( 'jS M Y g:iA' );?></span>
			<?php if( $calendar->all_day ){ ?>
			( <?php echo JText::_( 'APP_CALENDAR_ALL_DAY_EVENT' ); ?> )
			<?php } ?>
		</div>

		<div class="mb-20 mt-20 app-description"><?php echo $calendar->get( 'description' ); ?></div>

	</div>
</div>