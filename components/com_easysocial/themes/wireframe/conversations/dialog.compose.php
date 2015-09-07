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
<dialog>
	<width>500</width>
	<height>250</height>
	<selectors type="json">
	{
		"{cancelButton}"	: "[data-cancel-button]",
		"{sendButton}"		: "[data-send-button]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{cancelButton} click": function()
		{
			this.parent.close();
		}
	}
	</bindings>
	<title><?php echo JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_COMPOSE_DIALOG_TITLE' ); ?></title>
	<content>
		<div class="es-wrapper">
			<div class="small es-user-name">
				<?php echo JText::_( 'COM_EASYSOCIAL_CONVERSATIONS_COMPOSE_TO' ); ?>:

				<span>
					<a href="<?php echo $recipient->getPermalink();?>">
						<img src="<?php echo $recipient->getAvatar();?>" width="24" height="24" title="<?php echo $this->html( 'string.escape' , $recipient->getName() );?>" />
						<strong><?php echo $recipient->getName();?></strong>
					</a>
				</span>
			</div>

			<div class="composer-textarea input-wrap mt-20">
				<textarea class="input-shape" name="message" data-composer-editor style="width: 100%;height: 100px" data-composer-message></textarea>
			</div>

			<input type="hidden" id="recipient" value="<?php echo $recipient->id;?>" data-composer-recipient />
		</div>
	</content>
	<buttons>
		<button data-cancel-button type="button" class="btn btn-es">
			<?php echo JText::_( 'COM_EASYSOCIAL_CANCEL_BUTTON' ); ?>
		</button>
		<button data-send-button type="button" class="btn btn-es-primary">
			<?php echo JText::_( 'COM_EASYSOCIAL_SEND_BUTTON' ); ?>
		</button>
	</buttons>
</dialog>

