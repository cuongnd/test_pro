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
<div class="es-story-sidebar"></div>
<div class="es-story-content">
	<div
		class="es-story-panel-contents"
		data-story-panel-contents>
		<?php foreach ($story->panels as $panel) { ?>
			<div
				class="es-story-panel-content <?php echo $panel->content->classname; ?> for-<?php echo $panel->name; ?>"
				data-story-panel-content
				data-story-plugin-name="<?php echo $panel->name; ?>"
				><?php echo $panel->content->html; ?></div>
		<?php } ?>
	</div>
	<div
		class="es-story-panel-buttons"
		data-story-panel-buttons>
<!--
		<div class="es-story-friends-textbox textboxlist">
			<input type="text" class="textboxlist-textField" autocomplete="off" placeholder="Who are you with?" data-textboxlist-textfield="">
		</div>
 		<div class="es-story-friends">
			<i class="ies-users"></i>
			<span data-friend-count>0</span>
		</div>
		<div class="es-story-location">
			<i class="ies-location-2"></i> Kuala Lumpur, Malaysia <a href="">Edit</a>
		</div> -->
		<!--  -->

		<?php foreach ($story->panels as $panel) { ?>
			<div
				class="es-story-panel-button <?php echo $panel->button->classname; ?> pull-left sentence for-<?php echo $panel->name; ?>"
				data-story-panel-button
				data-story-plugin-name="<?php echo $panel->name; ?>"
				><b class="es-story-panel-button-arrow"></b><?php echo $panel->button->html; ?></div>
		<?php } ?>

		<div class="es-story-core-buttons">
			<button class="btn btn-es-primary es-story-submit" data-story-submit><?php echo JText::_("COM_EASYSOCIAL_STORY_SHARE"); ?></button>
			<div class="es-story-privacy solid" data-story-privacy><?php echo Foundry::privacy()->form( null , SOCIAL_TYPE_STORY , $this->my->id , 'story.view', true ); ?></div>
		</div>
	</div>
</div>
