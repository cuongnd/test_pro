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
	<width>400</width>
	<height>180</height>
	<selectors type="json">
	{
		"{sendButton}"  	: "[data-send-button]",
		"{cancelButton}"  	: "[data-cancel-button]",
		"{repostContent}"  	: "[data-repost-form-content]",
		"form" 				: "[data-repost-form]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{repostContent} focus": function() {
			this.validateContent();
		},

		"{cancelButton} click": function() {
			this.parent.close();
		},

		"validateContent" : function() {

			var content = $('[data-repost-form-content]').val();

			if( content == '<?php echo JText::_( 'COM_EASYSOCIAL_REPOST_FORM_DIALOG_MSG' ); ?>' )
			{
				$('[data-repost-form-content]').val('');
			}
		}
	}
	</bindings>
	<title><?php echo JText::_('COM_EASYSOCIAL_REPOST_FORM_DIALOG_TITLE'); ?></title>
	<content>
		<div>
			<form method="post" action="" data-repost-form >
				<textarea name="content" data-repost-form-content style="width:370px;height:108px;" placeholder="<?php echo JText::_( 'COM_EASYSOCIAL_REPOST_FORM_DIALOG_MSG' ); ?>"></textarea>
			</form>
		</div>
	</content>
	<buttons>
		<button data-cancel-button type="button" class="btn btn-es"><?php echo JText::_('COM_EASYSOCIAL_CANCEL_BUTTON'); ?></button>
		<button data-send-button type="button" class="btn btn-es-primary"><?php echo JText::_('COM_EASYSOCIAL_REPOST_SUBMIT_BUTTON'); ?></button>
	</buttons>
</dialog>
