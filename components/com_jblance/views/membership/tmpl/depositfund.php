<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	02 April 2012
 * @file name	:	views/membership/tmpl/depositfund.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Fund Deposit Form (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 JHTML::_('behavior.framework');
 JHTML::_('behavior.formvalidation');
 JHTML::_('behavior.tooltip');
 //JHTML::script('jbvalidate.js', 'components/com_jblance/js/');
 
 $model = $this->getModel();
 $config =& JblanceHelper::getConfig();
 
 $currencysym = $config->currencySymbol;
 $minFund  	= $config->fundDepositMin;
?>
<script language="javascript" type="text/javascript">
<!--
	function validateForm(f){
		var valid = document.formvalidator.isValid(f);
		var minFund = parseInt('<?php echo $minFund; ?>');

		if($('amount').get('value') < minFund){
			alert('<?php echo JText::sprintf('COM_JBLANCE_MINIMUM_DEPOSIT_AMOUNT_IS', $currencysym, $minFund); ?>');
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

		
	/* window.addEvent('domready', function() {
		$('amount').addEvent('keyup', function(event){
			var inputstr = $('amount').get('value');
			if(inputstr.length > 0){
				var tot = parseFloat('<?php echo $price_amount?>' * $('amount').get('value'));
				$('tot').set('html', '<b>'+tot+'</b>');
				$('cred').set('html', $('amount').get('value'));
				$('cartTotal').setStyle('display', 'block');
			}
			else {
				$('cartTotal').setStyle('display', 'none');
			}
		});
	});	*/
//-->	
</script>
<form action="index.php" method="post" name="userForm" id="userForm" class="form-validate minheight" onsubmit="return validateForm(this);">
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_DEPOSIT_FUNDS'); ?></div>
	<table width="100%" cellpadding="0" cellspacing="0" class="jbltable">
			<tr>
				<td class="key">
					<label for="amount"><?php echo JText::_('COM_JBLANCE_DEPOSIT_AMOUNT'); ?>:</label>
				</td>
				<td>
					<?php echo $currencysym; ?>&nbsp;
					<input type="text" name="amount" id="amount" size="6" class="inputbox required validate-numeric" /><br>
					<em>(<?php echo JText::sprintf('COM_JBLANCE_MINIMUM_DEPOSIT_AMOUNT_IS', $currencysym, number_format($minFund, 2, '.', '' )); ?>)</em>
				</td>
			</tr>
			<tr>
				<td class="key">
					<label for="gateway"><?php echo JText::_('COM_JBLANCE_PAYMENT_METHOD'); ?>:</label>
				</td>
				<td>
					<?php 
						$list_paymode = $model->getSelectPaymode('gateway', '', '');
						echo $list_paymode;
					?>						
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" value="<?php echo JText::_('COM_JBLANCE_CONTINUE')?>" class="button" />
				</td>
			</tr>
	</table>
	<input type="hidden" name="option" value="com_jblance" />			
	<input type="hidden" name="task" value="membership.savedepositfund" />	
	<?php echo JHTML::_('form.token'); ?>
</form>