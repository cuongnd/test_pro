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
<div class="es-story" data-story="<?php echo $story->id;?>" data-story-form>

	<div class="es-story-container">
		<div class="es-story-wrap">
			<div class="es-story-header es-story-section" data-story-header>
				<div class="es-story-sidebar">
					<div class="es-avatar es-avatar-small es-stream-avatar">
						<img src="<?php echo $this->my->getAvatar();?>" alt="<?php echo $this->html('string.escape', $this->my->getName());?>" />
					</div>
				</div>
				<div class="es-story-content">
					<div class="es-story-textbox">
						<textarea class="es-story-textfield" data-story-textField autocomplete="off" placeholder="<?php echo JText::_('COM_EASYSOCIAL_STORY_PLACEHOLDER'); ?>"></textarea>
					</div>
					<div
						class="es-story-attachment-buttons"
						data-story-attachment-buttons>
							<div
								class="es-story-attachment-button for-text active"
								data-story-attachment-button
								data-story-plugin-name="text"
								data-story-attachment-clear-button
								>
								<i class="ies-pencil"></i><span><?php echo JText::_('COM_EASYSOCIAL_STORY_TEXT'); ?></span>
							</div>
						<?php foreach ($story->attachments as $attachment) { ?>
							<div
								class="es-story-attachment-button <?php echo $attachment->button->classname; ?>"
								data-story-attachment-button
								data-story-plugin-name="<?php echo $attachment->name; ?>"
								><?php echo $attachment->button->html; ?></div>
						<?php } ?>
					</div>
				</div>
			</div>

			<div class="es-story-body es-story-section" data-story-body>
				<?php if ($story->attachments) { echo $this->includeTemplate('site/story/attachment'); } ?>
			</div>

			<div class="es-story-footer es-story-section" data-story-footer>
				<?php echo $this->includeTemplate('site/story/panel'); ?>
			</div>

			<input type="hidden" name="target" data-story-target value="<?php echo $story->getTarget();?>" />
		</div>
		<i class="loading-indicator"></i>
	</div>
</div>
