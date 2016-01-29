<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	13 March 2012
 * @file name	:	views/profilemenu.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows sub-menu on profile pages (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 $link_editportfolio	 = JRoute::_('index.php?option=com_jblance&view=user&layout=editportfolio');
 $link_email_preferences = JRoute::_('index.php?option=com_jblance&view=user&layout=notify');
 $link_edit_account		 = JRoute::_('index.php?option=com_users&view=profile&layout=edit');
?>

<div class="profileSubmenu clrfix">
	<ul class="jbResetList submenu">
		<li class="">
			<?php
			$profileInteg = JblanceHelper::getProfile();
			//$link_edit_profile = $profileInteg->getEditURL();
			$link_edit_profile = JRoute::_('index.php?option=com_jblance&view=user&layout=editprofile');
			?>
			<a href="<?php echo $link_edit_profile; ?>"><?php echo JText::_('COM_JBLANCE_EDIT_PROFILE'); ?></a>
		</li>
		<li class="">
			<?php 
			$avatars = JblanceHelper::getAvatarIntegration();
			$link_edit_picture = $avatars->getEditURL();
			?>
			<a href="<?php echo $link_edit_picture; ?>"><?php echo JText::_('COM_JBLANCE_EDIT_PICTURE'); ?></a>
		</li>
		<li class="">
			<a href="<?php echo $link_editportfolio; ?>"><?php echo JText::_('COM_JBLANCE_EDIT_PORTFOLIO'); ?></a>
		</li>
		<li class="">
			<a href="<?php echo $link_email_preferences; ?>"><?php echo JText::_('COM_JBLANCE_EMAIL_PREFERENCES'); ?></a>
		</li>
		<li class="">
			<a href="<?php echo $link_edit_account; ?>"><?php echo JText::_('COM_JBLANCE_EDIT_ACCOUNT'); ?></a>
		</li>
			
	</ul>
</div>