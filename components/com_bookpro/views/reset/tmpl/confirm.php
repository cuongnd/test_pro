<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.formvalidation');
?>
<div class="reset-confirm">

	<form action="<?php echo JRoute::_('index.php?option=com_bookpro&task=reset.confirm'); ?>" method="post" class="form-validate">
    <p><?php echo JText::_('COM_USERS_RESET_CONFIRM_LABEL'); ?></p>
        <fieldset>
            <dl>
                            <dt><label title="" class="hasTooltip required invalid" for="jform_username" id="jform_username-lbl" data-original-title="&lt;strong&gt;Username&lt;/strong&gt;&lt;br /&gt;Enter your username" aria-invalid="true">Username:<span class="star">&nbsp;*</span></label></dt>
                <dd><input type="text" aria-required="true" required="required" size="30" class="required invalid" value="" id="jform_username" name="jform[username]" aria-invalid="true"></dd>
                            <dt><label title="" class="hasTooltip required" for="jform_token" id="jform_token-lbl" data-original-title="&lt;strong&gt;Verification Code&lt;/strong&gt;&lt;br /&gt;Enter the password reset verification code you received by email.">Verification Code:<span class="star">&nbsp;*</span></label></dt>
                <dd><input type="text" aria-required="true" required="required" size="32" class="required" value="" id="jform_token" name="jform[token]"></dd>
                        </dl>
        </fieldset>
        

		<div>
			<button type="submit" class="validate btn btn-medium btn-primary"><?php echo JText::_('JSUBMIT'); ?></button>
			    <input type="hidden" name="return" value="<?php echo JRequest::getVar('return',0) ;?>" />
                <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid',0) ;?>" />
            <?php echo JHtml::_('form.token'); ?>
		</div>
	</form>
</div>
