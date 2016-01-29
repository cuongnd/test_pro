<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	11 September 2012
 * @file name	:	views/user/tmpl/notify.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Email Notification settings (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

 $model = $this->getModel();
 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
?>
<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_EMAIL_SETTINGS'); ?></div>
<?php
include_once(JPATH_COMPONENT.'/views/profilemenu.php');
?>
<form action="index.php" method="post" name="userFormNotify"  class="form-validate" enctype="multipart/form-data">
	<table class="jbltable" width="100%">
		<tr><td style="width:350px;"></td><td></td></tr>
		<!-- All the notifications are instant -->
		<tr style="display:none;">
			<td class="key">
				<label for="username"><?php echo JText::_('COM_JBLANCE_FREQUENCY_OF_UPDATES'); ?>:</label>
			</td>
			<td>
				<?php echo  $model->getSelectUpdateFrequency('frequency', $this->row->frequency ? $this->row->frequency : 'instantly'); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php echo JText::_('COM_JBLANCE_RECEIVE_INDIVIDUAL_NOTIFICATIONS_WHEN'); ?>,
			</td>
		</tr>
		<tr>
			<td class="key">
				<label><?php echo JText::_('COM_JBLANCE_NOTIFY_WHEN_RELEVANT_PROJECT_GETS_POSTED'); ?></label>:
			</td>
			<td>
				<?php echo $select->YesNoBool('notifyNewProject', $this->row->notifyNewProject); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label><?php echo JText::_('COM_JBLANCE_NOTIFY_BID_WON_CHOSEN_BY_BUYER'); ?></label>:
			</td>
			<td>
				<?php echo $select->YesNoBool('notifyBidWon', $this->row->notifyBidWon); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label><?php echo JText::_('COM_JBLANCE_NOTIFY_NEW_FORUM_MESSAGE'); ?></label>:
			</td>
			<td>
				<?php echo $select->YesNoBool('notifyNewForumMessage', $this->row->notifyNewForumMessage); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label><?php echo JText::_('COM_JBLANCE_NOTIFY_NEW_PRIVATE_MESSAGE'); ?></label>:
			</td>
			<td>
				<?php echo $select->YesNoBool('notifyNewMessage', $this->row->notifyNewMessage); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label><?php echo JText::_('COM_JBLANCE_NOTIFY_BID_NEW_ACCEPTED_DENIED'); ?></label>:
			</td>
			<td>
				<?php echo $select->YesNoBool('notifyBidNewAcceptDeny', $this->row->notifyBidNewAcceptDeny); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2" class="jb-aligncenter">
				<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SAVE'); ?>" class="button" />
				<input type="button" onclick="javascript:history.back()" value="<?php echo JText::_('COM_JBLANCE_CANCEL'); ?>" class="button"/>
			</td>
		</tr>
	</table>
	
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="user.savenotify" />		
	<input type="hidden" name="id" value="<?php echo $this->row->id;?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>