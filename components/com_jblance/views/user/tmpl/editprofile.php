<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	22 March 2012
 * @file name	:	views/user/tmpl/editprofile.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Edit profile (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHtml::_('behavior.framework', true);
 JHTML::_('behavior.formvalidation');
 JHTML::_('behavior.tooltip');
 //JHTML::script('jbvalidate.js', 'components/com_jblance/js/');

 JblanceHelper::getMultiSelect('id_category', JText::_('COM_JBLANCE_SEARCH_SKILLS'));
 
 $user=& JFactory::getUser();
 $model = $this->getModel();
 $select = JblanceHelper::get('helper.select');		// create an instance of the class SelectHelper
 
 $jbuser = JblanceHelper::get('helper.user');		// create an instance of the class UserHelper
 $userInfo = $jbuser->getUserGroupInfo($user->id, null);
 
 $config =& JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;	
 $currencycod = $config->currencyCode;	
 
 JText::script('COM_JBLANCE_CLOSE');
?>
<script language="javascript" type="text/javascript">
<!--
	function validateForm(f){
		if (document.formvalidator.isValid(f)) {
			f.check.value='<?php echo JSession::getFormToken(); ?>';//send token
	    }
	    else {
		    var msg = '<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'); ?>';
			alert(msg);
			return false;
	    }
		return true;
	}
//-->
</script>
<form action="index.php" method="post" name="userGroup" class="form-validate" onsubmit="return validateForm(this);">
<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_EDIT_PROFILE'); ?></div>
	<?php
	include_once(JPATH_COMPONENT.'/views/profilemenu.php');
	?>
	<fieldset class="jblfieldset">
		<legend><?php echo JText::_('COM_JBLANCE_USER_INFORMATION'); ?></legend>
		<table class="jbltable">
			<tr>
				<td class="key"><label for="username"><?php echo JText::_('COM_JBLANCE_USERNAME'); ?>:</label>
				</td>
				<td>
					<?php echo  $this->userInfo->username; ?>
				</td>
			</tr>
			<tr>
				<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_NAME'); ?><span class="redfont">*</span>:</label>
				</td>
				<td>
					<input class="inputbox required" type="text" name="name" id="name" size="50" maxlength="100" value="<?php echo $this->userInfo->name; ?>" />
				</td>
			</tr>
			<!-- Company Name should be visible only to users who can post project -->
			<?php if($userInfo->allowPostProjects) : ?>
			<tr>
				<td class="key"><label for="biz_name"><?php echo JText::_('COM_JBLANCE_BUSINESS_NAME'); ?><span class="redfont">*</span>:</label>
				</td>
				<td>
					<input class="inputbox required" type="text" name="biz_name" id="biz_name" size="50" maxlength="100" value="<?php echo $this->userInfo->biz_name; ?>" />
				</td>
			</tr>
			<?php endif; ?>
			<!-- Skills and hourly rate should be visible only to users who can work/bid -->
			<?php if($userInfo->allowBidProjects) : ?>
			<tr>
				<td class="key"><label for="rate"><?php echo JText::_('COM_JBLANCE_HOURLY_RATE'); ?><span class="redfont">*</span>:</label>
				</td>
				<td>
					<?php echo $currencysym; ?> 
					<input class="inputbox required" type="text" name="rate" id="rate" size="6" maxlength="10" value="<?php echo $this->userInfo->rate; ?>" />
					<?php echo $currencycod.' / '.JText::_('COM_JBLANCE_HOUR'); ?>
				</td>
			</tr>
			<tr>
				<td class="key"><label for="id_category"><?php echo JText::_('COM_JBLANCE_SKILLS'); ?>:</label>
				</td>
				<td >						
					<?php 
					$attribs = 'class="inputbox required" size="20" multiple ';
					$categtree = $select->getSelectCategoryTree('id_category[]', explode(',', $this->userInfo->id_category), 'COM_JBLANCE_PLEASE_SELECT', $attribs, '', true);
					echo $categtree; ?>
				</td>
			</tr>
			<?php endif; ?>
		</table>
	</fieldset>
	
	<!-- Show the following profile fields only for JoomBri Profile -->
	<?php 
	$joombriProfile = false;
	$profileInteg = JblanceHelper::getProfile();
	$profileUrl = $profileInteg->getEditURL();
	if($profileInteg instanceof JoombriProfileJoombri){
		$joombriProfile = true;
	}
	
	if($joombriProfile){
	
		$fields = JblanceHelper::get('helper.fields');		// create an instance of the class fieldsHelper
		
		$parents = array();$children = array();
		//isolate parent and childr
		foreach($this->fields as $ct){
			if($ct->parent == 0)
				$parents[] = $ct;
			else
				$children[] = $ct;
		}
			
		if(count($parents)){
			foreach($parents as $pt){ ?>
		<fieldset class="jblfieldset">
			<legend><?php echo JText::_($pt->field_title); ?></legend>
			<table class="jbltable" width="100%">
				<?php
				foreach($children as $ct){
					if($ct->parent == $pt->id){ ?>
				<tr>
					<td class="key">
						<?php
						$labelsuffix = '';
						if($ct->field_type == 'Checkbox') $labelsuffix = '[]'; //added to validate checkbox
						?>
						<label for="custom_field_<?php echo $ct->id.$labelsuffix; ?>"><?php echo JText::_($ct->field_title); ?><span class="redfont"><?php echo ($ct->required)? '*' : ''; ?></span>:</label>
					</td>
					<td>
						<?php $fields->getFieldHTML($ct, $user->id); ?>
					</td>
					
				</tr>
				<?php
					}
				} ?>
			</table>
		</fieldset>
				<?php
			}
		}
	}	//end of $joombriProfile 'if'
	else {
		echo JText::sprintf('COM_JBLANCE_CLICK_HERE_FOR_OTHER_PROFILE', $profileUrl).'<BR>';
	}
	?>
	
	<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SAVE'); ?>" class="button" />
	<input type="hidden" name="option" value="com_jblance">
	<input type="hidden" name="task" value="user.saveprofile">
	<input type="hidden" name="id" value="<?php echo $this->userInfo->id; ?>">
	<?php echo JHTML::_('form.token'); ?>
</form>	
