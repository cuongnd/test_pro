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
<div class="reset-complete<?php echo $this->pageclass_sfx?>">

	
    <form action="<?php echo JRoute::_('index.php?option=com_bookpro&task=reset.complete'); ?>" method="post" class="form-validate">

                <p><?php echo JText::_('COM_USERS_RESET_COMPLETE_LABEL'); ?></p>        
        <fieldset>
            <dl>
                            <dt><label title="" class="hasTooltip required" for="jform_password1" id="jform_password1-lbl" data-original-title="&lt;strong&gt;Password&lt;/strong&gt;&lt;br /&gt;Enter your new password">Password:<span class="star">&nbsp;*</span></label></dt>
                <dd><input type="password" aria-required="true" required="required" 99="" size="30" class="validate-password required" autocomplete="off" value="" id="jform_password1" name="jform[password1]"></dd>
                            <dt><label title="" class="hasTooltip required" for="jform_password2" id="jform_password2-lbl" data-original-title="&lt;strong&gt;Confirm Password&lt;/strong&gt;&lt;br /&gt;Confirm your new password">Confirm Password:<span class="star">&nbsp;*</span></label></dt>
                <dd><input type="password" aria-required="true" required="required" 99="" size="30" class="validate-password required" autocomplete="off" value="" id="jform_password2" name="jform[password2]"></dd>
                        </dl>
        </fieldset>
        
        <div>
            <button class="validate btn btn-medium btn-primary" type="submit"><?php echo JText::_('JSUBMIT'); ?></button>
                <input type="hidden" name="return" value="<?php echo JRequest::getVar('return',0) ;?>" />
                <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid',0) ;?>" />
           <?php echo JHtml::_('form.token'); ?>       
           
        </div>

	</form>
</div>
