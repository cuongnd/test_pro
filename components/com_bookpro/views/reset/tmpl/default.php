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
<div class="reset">


	<form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_bookpro&task=reset.request'); ?>" method="post" class="form-validate form-horizontal">
        <p><?php echo JText::_('Please enter the email address for your account. A verification code will be sent to you. Once you have received the verification code, you will be able to choose a new password for your account.'); ?></p>
        
         <fieldset>
                       <div class="control-group">
                            <div class="control-label">
                                <label title="" class="hasTooltip required invalid" for="jform_email" id="jform_email-lbl" data-original-title="&lt;strong&gt;Email Address&lt;/strong&gt;&lt;br /&gt;Please enter the email address associated with your User account.&lt;br /&gt;A verification code will be sent to you. Once you have received the verification code, you will be able to choose a new password for your account." aria-invalid="true">Email Address:<span class="star">&nbsp;*</span></label>                    </div>
                            <div class="controls">
                                <input type="text" aria-required="true" required="required" size="30" class="validate-username required invalid" value="" id="jform_email" name="jform[email]" aria-invalid="true">                    </div>
                        </div>
                        
                            <div class="control-group">
                            <div class="control-label">
                                                    </div>
                            <div class="controls">
                                                    </div>
                        </div>
         </fieldset>
         
         <div class="form-actions">
            <button class="btn btn-primary validate" type="submit"><?php echo JText::_('JSUBMIT'); ?></button>
                <input type="hidden" name="return" value="<?php echo JRequest::getVar('return',0) ;?>" />
                <input type="hidden" name="Itemid" value="<?php echo JRequest::getVar('Itemid',0) ;?>" />
            <?php echo JHTML::_('form.token'); ?>      
         </div>         
	</form>
</div>
