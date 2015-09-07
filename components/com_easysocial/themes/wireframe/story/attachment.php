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
<div class="es-story-sidebar">
	<?php foreach ($story->attachments as $attachment) { ?>
		<div
			class="es-story-attachment-icon <?php echo $attachment->button->classname; ?>"
			data-story-attachment-icon
			data-story-plugin-name="<?php echo $attachment->name; ?>"
			><?php echo $attachment->icon->html; ?></div>
	<?php } ?>
</div>

<div class="es-story-content">
	<div class="es-story-attachment-items" data-story-attachment-items>
		<?php foreach ($story->attachments as $attachment) { ?>
			<div
				class="es-story-attachment-item <?php echo $attachment->content->classname; ?>"
				data-story-attachment-item
				data-story-plugin-name="<?php echo $attachment->name; ?>"
				>
				<?php
				/*
				Currently unused, but good to keep it for future use.

				<div class="es-story-attachment-toolbar" data-story-attachment-toolbar>
					<div class="es-story-attachment-remove-button btn btn-danger"
					     data-story-attachment-remove-button
					     data-story-plugin-name="<?php echo $attachment->name; ?>"
					     ><i class="ies-cancel-2"></i></div>
				</div>
				*/
				?>
				<div class="es-story-attachment-content" data-story-attachment-content data-story-plugin-name="<?php echo $attachment->name; ?>">
					<?php echo $attachment->content->html; ?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
