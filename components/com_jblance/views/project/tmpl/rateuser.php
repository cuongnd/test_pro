<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	27 March 2012
 * @file name	:	views/project/tmpl/rateuser.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Rate user (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHTML::_('behavior.framework');
 JHTML::_('behavior.formvalidation');
 //JHTML::script('jbvalidate.js', 'components/com_jblance/js/');

 $model = $this->getModel();
 
 //logic to determine if rating is being done for buyer/freelancer. Because the rating form is different for both user.
 
 if($this->project->publisher_userid == $this->rate->target){
 	$rate_type = 'COM_JBLANCE_BUYER';	//we are rating the buyer
 }
 elseif($this->project->assigned_userid == $this->rate->target){
 	$rate_type = 'COM_JBLANCE_FREELANCER';	//we are rating the freelancer
 }
?>
<script language="javascript" type="text/javascript">
<!--
function validateForm(f){
	var valid = document.formvalidator.isValid(f);
	
	if(valid == true){
		f.check.value='<?php echo JSession::getFormToken(); ?>';//send token
    }
    else {
		alert('<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'); ?>');
		return false;
    }
	return true;
}
//-->
</script>
<form action="index.php" method="post" name="userFormProject" id="userFormProject" class="form-validate" onsubmit="return validateForm(this);">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_RATE_USER'); ?></div>
	<table width="100%" cellpadding="0" cellspacing="0" class="jbltable">
		<tr>
			<td class="key" style="width:200px;">
				<label><?php echo JText::_('COM_JBLANCE_PROJECT_NAME'); ?>:</label>
			</td>
				<td class="font16"><?php echo $this->project->project_title;?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<?php $target_user = JFactory::getUser($this->rate->target);
				?>
				<label><?php echo JText::_('COM_JBLANCE_NAME'); ?>:</label> 
			</td>
			<td> <?php echo $target_user->username.' ('.JText::_($rate_type).')'; ?></td>
		</tr>
		<tr>
			<td class="key">
				<label for="quality_clarity"><?php echo ($rate_type == 'COM_JBLANCE_BUYER') ? JText::_('COM_JBLANCE_CLARITY_SPECIFICATION') : JText::_('COM_JBLANCE_QUALITY_OF_WORK'); ?>:</label> 
			</td>
			<td>
				<?php $rating = $model->getSelectRating('quality_clarity', '');
				 echo $rating; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="communicate"><?php echo JText::_('COM_JBLANCE_COMMUNICATION'); ?>:</label> 
			</td>
			<td>
				<?php $rating = $model->getSelectRating('communicate', ''); 
				 echo $rating; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="expertise_payment"><?php echo ($rate_type == 'COM_JBLANCE_BUYER') ? JText::_('COM_JBLANCE_PAYMENT_PROMPTNESS') : JText::_('COM_JBLANCE_EXPERTISE'); ?>:</label> 
			</td>
			<td>
				<?php $rating = $model->getSelectRating('expertise_payment', ''); 
				 echo $rating; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="professional"><?php echo JText::_('COM_JBLANCE_PROFESSIONALISM'); ?>:</label> 
			</td>
			<td>
				<?php $rating = $model->getSelectRating('professional', ''); 
				 echo $rating; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="hire_work_again"><?php echo ($rate_type == 'COM_JBLANCE_BUYER') ? JText::_('COM_JBLANCE_WORK_AGAIN') : JText::_('COM_JBLANCE_HIRE_AGAIN'); ?>:</label> 
			</td>
			<td>
				<?php $rating = $model->getSelectRating('hire_work_again', ''); 
				 echo $rating; ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="comments"><?php echo JText::_('COM_JBLANCE_COMMENTS'); ?>:</label>
			</td>
			<td>
				<textarea name="comments" rows="8" cols="50"  class="inputbox"></textarea>	
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SUBMIT'); ?>" class="button" />
			</td> 
		</tr>
	</table>
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="project.saverateuser" />	
	<input type="hidden" name="id" value="<?php echo $this->rate->id ; ?>" />
	<input type="hidden" name="rate_type" value="<?php echo $rate_type; ?>" />
	<?php echo JHTML::_('form.token'); ?>
	</form>