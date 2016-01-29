<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	02 April 2012
 * @file name	:	views/membership/tmpl/escrow.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Escrow Payment Form (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHTML::_('behavior.framework');
 JHTML::_('behavior.formvalidation');
 //JHTML::script('jbvalidate.js', 'components/com_jblance/js/');
 
 $doc =& JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/utility.js");
 
 $user = JFactory::getUser();
 $config =& JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
?>
<script language="javascript" type="text/javascript">
<!--
	function validateForm(f){
		var valid = document.formvalidator.isValid(f);
		
		if(valid == true){
			f.check.value='<?php echo JSession::getFormToken(); ?>';//send token
	    }
	    else {
			var msg = '<?php echo JText::_('COM_JBLANCE_FIEDS_HIGHLIGHTED_RED_COMPULSORY'); ?>';
	    	if($('amount').hasClass('invalid')){
		    	msg = msg+'\n\n* '+'<?php echo JText::_('COM_JBLANCE_PLEASE_ENTER_AMOUNT_IN_NUMERIC'); ?>';
		    }
			alert(msg);
			return false;
	    }
		return true;
	}

	function updateReason(){

		if($('full_payment_option').checked || $('partial_payment_option').checked){
			$('projectBox').setStyle('display', 'table-row');
			$('project_id').addClass('required');
			
		}
		else if($('other_reason_option').checked){
			//$('recipient').set('readonly', false);
         	//$('amount').set('readonly', false);
			$('projectBox').setStyle('display', 'none');
			$('project_id').removeClass('required').removeProperty('required').set('value', '');
		}
		
		
	}
//-->
</script>
<form action="index.php" method="post" name="userFormProject" id="userFormProject" class="form-validate" onsubmit="return validateForm(this);">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_ESCROW_PAYMENT'); ?></div>
	<table width="100%" cellpadding="0" cellspacing="0" class="jbltable">
		<tr>
			<td colspan="2">
				<strong><?php echo JText::_('COM_JBLANCE_PLEASE_SELECT_ONE_OF_THE_FOLLOWING'); ?>:</strong>
				<p>
					<input type="radio" name="reason" id="full_payment_option" value="full_payment" checked onclick="updateReason();"> <?php echo JText::_('COM_JBLANCE_FULL_FINAL_PAYMENT_FOR_COMPLETED_PROJECT'); ?><br />
					<input type="radio" name="reason" id="partial_payment_option" value="partial_payment" onclick="updateReason();"> <?php echo JText::_('COM_JBLANCE_PARTIAL_PAYMENT_FOR_PROJECT'); ?><br />
					<input type="radio" name="reason" id="other_reason_option" value="other" onclick="updateReason();"> <?php echo JText::_('COM_JBLANCE_OTHER_REASON'); ?><br />
				</p>
				<div class="lineseparator"></div>
			</td>
		</tr>
		<tr id="projectBox">
			<td class="key">
				<label for="project_id"><?php echo JText::_('COM_JBLANCE_PROJECT'); ?>:</label>
			</td>
			<td>
				<?php echo $this->lists; ?>
				<input type="hidden" name="proj_balance" id="proj_balance" value="" />
				<strong><div id="proj_balance_div" class="dis-inl-blk"></div></strong>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="recipient"><?php echo JText::_('COM_JBLANCE_USERNAME'); ?>:</label>
			</td>
			<td>
				<input type="text" name="recipient" id="recipient" value="" class="inputbox required" onchange="checkUsername(this);" />
				<div id="status_recipient" class="dis-inl-blk"></div>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="amount"><?php echo JText::_('COM_JBLANCE_AMOUNT'); ?>:</label>
			</td>
			<td>
				<?php echo $currencysym; ?>&nbsp;
				<input type="text" name="amount" id="amount" size="10" class="inputbox required validate-numeric" />
				<em>(<?php echo JText::_('COM_JBLANCE_YOUR_BALANCE').' : '.$currencysym.' '.JblanceHelper::getTotalFund($user->id); ?>)</em>
			</td>
		</tr>
		<tr>
			<td class="key">
				<label for="note"><?php echo JText::_('COM_JBLANCE_NOTES'); ?>:</label>
			</td>
			<td>
				<input type="text" name="note" id="note" size="60" class="inputbox" />
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<input type="submit" value="<?php echo JText::_('COM_JBLANCE_TRANSFER'); ?>" class="button" />
			</td>
		</tr>
	</table>
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="membership.saveescrow" />
	<?php echo JHTML::_('form.token'); ?>
</form>