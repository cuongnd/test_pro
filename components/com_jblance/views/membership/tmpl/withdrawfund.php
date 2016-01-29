<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	03 April 2012
 * @file name	:	views/membership/tmpl/withdrawfund.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Withdraw Fund Form (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHTML::_('behavior.framework');
 JHTML::_('behavior.formvalidation');
 //JHTML::script('jbvalidate.js', 'components/com_jblance/js/');
 
 $app  	=& JFactory::getApplication();
 $model = $this->getModel();
 $doc 	=& JFactory::getDocument();
 $doc->addScript("components/com_jblance/js/utility.js");
 
 $user = JFactory::getUser();
 $config =& JblanceHelper::getConfig();
 $currencysym = $config->currencySymbol;
 $minWithdraw  	= $config->withdrawMin;
 
 $step 	  = $app->input->get('step', '1', 'int');
 $gateway = $app->input->get('gateway', '', 'string');
 if($gateway)
 	$gwInfo = JblanceHelper::getPaymodeInfo($gateway);
?>
<script language="javascript" type="text/javascript">
<!--
function validateForm(f){
	var valid = document.formvalidator.isValid(f);
	var minWithdraw = parseInt('<?php echo $minWithdraw; ?>');

	if($('amount').get('value') < minWithdraw){
		alert('<?php echo JText::sprintf('COM_JBLANCE_MINIMUM_WITHDRAW_AMOUNT_IS', $currencysym, $minWithdraw); ?>');
		return false;				
	}
	else {
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
	
}
//-->
</script>
<form action="index.php" method="post" name="userForm" id="userForm" class="form-validate minheight" onsubmit="return validateForm(this)">

	<?php if($step == 1) : ?>
		<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_WITHDRAW_FUNDS'); ?></div>
		<table width= "100%" style="border-collapse: collapse" class="border">
			<tr class="jbl_rowhead">
				<th width="35%"><?php echo JText::_('COM_JBLANCE_METHOD'); ?></th>
				<th width="40%"><?php echo JText::_('COM_JBLANCE_DESCRIPTION'); ?></th>
				<th width="15%"><?php echo JText::_('COM_JBLANCE_FEE'); ?></th>
			</tr>
			<?php
			$k = 0;
			for($i=0, $n=count($this->paymodes); $i < $n; $i++){
				$mode = $this->paymodes[$i]; 
				$link_step2	 = JRoute::_('index.php?option=com_jblance&view=membership&layout=withdrawfund&step=2&gateway='.$mode->gwcode); ?>
			<tr class="jbl_<?php echo "row$k"; ?>">
				<td>
					<a href="<?php echo $link_step2; ?>"><?php echo $mode->gateway_name; ?></a>
				</td>
				<td>
					<?php echo $mode->withdrawDesc; ?>
				</td>
				<td>
					<?php echo $currencysym.number_format($mode->withdrawFee, 2, '.', '' ); ?>
				</td>
			</tr>
			<?php
				$k = 1 - $k;
			}
			?>	
		</table>
	<?php elseif($step == 2) : ?>
		<div class="jbl_h3title"><?php echo JText::sprintf('COM_JBLANCE_WITHDRAW_VIA', $gwInfo->gateway_name); ?></div>
		<table width="100%" class="jbltable">
			<tr>
				<td class="key">
					<label><?php echo JText::_('COM_JBLANCE_PAYMENT_METHOD'); ?>:</label>
				</td>
				<td>
					<?php echo $gwInfo->gateway_name;	?>						
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="amount"><?php echo JText::_('COM_JBLANCE_WITHDRAW_AMOUNT'); ?>:</label>
				</td>
				<td>
					<?php echo $currencysym; ?>&nbsp;
					<input type="text" name="amount" id="amount" size="10" class="inputbox required validate-numeric" /><br>
					<em>(<?php echo JText::_('COM_JBLANCE_YOUR_BALANCE').' : '.$currencysym.' '.JblanceHelper::getTotalFund($user->id); ?>)</em><br>
					<em>(<?php echo JText::_('COM_JBLANCE_MIN_AMOUNT').' : '.$currencysym.' '.number_format($minWithdraw, 2, '.', '' ); ?>)</em><br>
				</td>
			</tr>
			<?php if($gateway == 'paypal') : ?>
			<tr>
				<td class="key">
					<label><?php echo JText::_('COM_JBLANCE_WITHDRAWAL_FEE'); ?>:</label>
				</td>
				<td>
					<?php echo $currencysym.' '.number_format($gwInfo->withdrawFee, 2, '.', '' ); ?>	
					<input type="hidden" name=withdrawFee value="<?php echo $gwInfo->withdrawFee; ?>" />			
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="paramsPaypalEmail"><?php echo JText::_('COM_JBLANCE_PAYPAL_ACCOUNT_EMAIL'); ?>:</label>
				</td>
				<td>
					<input type="text" name="params[paypalEmail]" id="paramsPaypalEmail" size="30" class="inputbox required validate-email" />
				</td>
			</tr>
			<?php endif; ?>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" value="<?php echo JText::_('COM_JBLANCE_SUBMIT')?>" class="button" />
				</td>
			</tr>
		</table>
	<?php elseif($step == 3) : ?>

	<?php endif; ?>
	
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="membership.savewithdrawfund" />
	<input type="hidden" name="gateway" value="<?php echo $gateway; ?>" />
	<?php echo JHTML::_('form.token'); ?>
</form>