<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	15 July 2012
 * @file name	:	views/message/tmpl/report.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Report Items (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHTML::_('behavior.framework');
 JHTML::_('behavior.formvalidation');
 
 $app  =& JFactory::getApplication();
 $model = $this->getModel();
 
 $report = $app->input->get('report', '', 'string');
 $link 	 = $app->input->get('link', '', 'string');
 $id 	 = $app->input->get('id', 0, 'int');
 
 //redirect if the reporting is disabled
 $config =& JblanceHelper::getConfig();
 $enableReporting = $config->enableReporting;
 
 if(!$enableReporting){
 	$app = JFactory::getApplication();
 	$msg = JText::_('COM_JBLANCE_REPORTING_DISABLED');
 	$app->redirect(base64_decode($link), $msg);
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
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_REPORT_THIS'); ?></div>
	<table width="100%" cellpadding="0" cellspacing="0" class="jbltable">
		<tr>
			<td class="key">
				<label for="category"><?php echo JText::_('COM_JBLANCE_URL_VIOLATION'); ?>:</label>
			</td>
				<td><?php echo base64_decode($link); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="category"><?php echo JText::_('COM_JBLANCE_REPORT_CATEGORY'); ?>:</label>
			</td>
				<td><?php echo $model->getSelectReportCategory(); ?>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="message"><?php echo JText::_('COM_JBLANCE_MESSAGE'); ?>:</label>
			</td>
			<td>
				<textarea name="message" id="message" rows="8" cols="50"  class="inputbox required"></textarea>	
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SUBMIT'); ?>" class="button" />
			</td> 
		</tr>
	</table>
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="message.savereport" />	
	<input type="hidden" name="reportitemid" value="<?php echo $id; ?>" />
	<input type="hidden" name="report" value="<?php echo $report; ?>" />
	<input type="hidden" name="link" value="<?php echo $link; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>