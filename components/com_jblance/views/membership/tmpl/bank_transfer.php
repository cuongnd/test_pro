<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	21 March 2012
 * @file name	:	views/membership/tmpl/bank_transfer.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Page showing details of bank for Bank Transfer mode (jblance)
 */
 defined('_JEXEC') or die('Restricted access');

 $bank_account  	= $this->payconfig->btAccnum;
 $bank_name    		= $this->payconfig->btBankname;
 $acc_holder_name	= $this->payconfig->btAccHoldername;
 $iban				= $this->payconfig->btIBAN;
 $swift				= $this->payconfig->btSWIFT;
 $emailnotify		= $this->payconfig->btNotifyEmail;
 $faxnofity			= $this->payconfig->btNotifyFaxno;

 $app  			=& JFactory::getApplication();
 $config 		=& JblanceHelper::getConfig();
 $currencysym 	= $config->currencySymbol;
 $tax_name	 	= $config->taxName;
 $link_balance	= JRoute::_('index.php?option=com_jblance&view=membership&layout=transaction');	
 $type 			= $app->input->get('type', 'plan', 'string');
?>
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PAYMENT_INFO'); ?></div>
	<div class="plan-choose" style="width:95%; padding:10px;">
		<h2 class="jbj_manual"><?php echo JText::_('COM_JBLANCE_CART'); ?></h2>
		<table width="100%" border="0" cellspacing="2" cellpadding="4">
			<!-- ************************************************************** plan banktransfer section ******************************************* -->
			<?php if($type == 'plan') : ?>
			<thead>
			<tr>
				<th align="left"><?php echo JText::_('COM_JBLANCE_NAME'); ?></th>
				<th align="left"><?php echo JText::_('COM_JBLANCE_INVOICE_NO'); ?></th>
				<th align="left"><?php echo JText::_('COM_JBLANCE_FUND'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_TOTAL'); ?></th>
			</tr>
			</thead>
			<tr>
				<td><?php echo $this->plan->name; ?></td>
				<td><?php echo $this->subscr->invoiceNo ;?></td>
				<td align="right"><?php echo number_format($this->subscr->fund, 2, '.', ',') ;?></td>
				<td align="right"><?php echo number_format($this->subscr->price, 2, '.', ',') ;?></td>
			</tr>
			<tr>
				<td colspan="3" align="right"><?php echo $tax_name.' '.$this->subscr->tax_percent ;?>% :</td>
				<td colspan="2" align="right">
					<?php
						$taxamt = ($this->subscr->tax_percent/100) * $this->subscr->price;
						echo number_format($taxamt, 2, '.', ',');
					?>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="right"> </td>
				<td colspan="2" align="right"><hr></td>
			</tr>
			<tr>
				<td colspan="3" align="right"><?php echo JText::_('COM_JBLANCE_TOTAL'); ?> :</td>
				<td colspan="2" align="right">
					<?php
						$total = $taxamt + $this->subscr->price;
						echo '<B>'.$currencysym.' '.number_format($total, 2, '.', ',').'</B>';
					?>
				</td>
			</tr>
			<tr>
				<td colspan="7"><hr></td>
			</tr>
			<!-- ************************************************************** deposit banktransfer section ******************************************* -->
			<?php elseif($type == 'deposit') : ?>
			<thead>
			<tr>
				<th align="left"><?php echo JText::_('COM_JBLANCE_NAME'); ?></th>
				<th align="left"><?php echo JText::_('COM_JBLANCE_INVOICE_NO'); ?></th>
				<th align="left"><?php echo JText::_('COM_JBLANCE_FUND'); ?></th>
				<th><?php echo JText::_('COM_JBLANCE_TOTAL'); ?></th>
			</tr>
			</thead>
			<tr>
				<td><?php echo JText::_('COM_JBLANCE_DEPOSIT_FUNDS'); ?></td>
				<td><?php echo $this->deposit->invoiceNo ;?></td>
				<td align="right"><?php echo number_format($this->deposit->amount, 2, '.', ',') ;?></td>
				<td align="right"><?php echo number_format($this->deposit->amount, 2, '.', ',') ;?></td>
			</tr>
			<tr>
				<td colspan="3" align="right"><?php echo JText::_('COM_JBLANCE_DEPOSIT_FEE').' ('.$currencysym.$this->deposit->feeFixed.' + '.$this->deposit->feePerc.'%)' ;?>:</td>
				<td colspan="2" align="right">
					<?php
						$fee = ($this->deposit->feePerc/100)*$this->deposit->amount + $this->deposit->feeFixed;
						echo number_format($fee, 2, '.', ',');
					?>
				</td>
			</tr>
			<tr>
				<td colspan="3" align="right"> </td>
				<td colspan="2" align="right"><hr></td>
			</tr>
			<tr>
				<td colspan="3" align="right"><?php echo JText::_('COM_JBLANCE_TOTAL'); ?> :</td>
				<td colspan="2" align="right">
					<?php
						$total = $this->deposit->total;
						echo '<b>'.$currencysym.' '.number_format($total, 2, '.', ',').'</b>';
					?>
				</td>
			</tr>
			<tr>
				<td colspan="7"><hr></td>
			</tr>
			<?php endif; ?>
		</table>
	</div>
	<div class="sp20">&nbsp;</div>
	
		<table class="jbltable border" width="100%">
			<tr class="jbl_rowhead">
				<th colspan="2"><?php echo JText::_('COM_JBLANCE_BANK_ACCOUNT_INFO'); ?> </th>
			</tr>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_BANK_NAME'); ?>:</label></td>
				<td><?php echo $bank_name; ?></td>
			</tr>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_BANK_ACCOUNT_NAME'); ?>:</label></td>
				<td> <?php echo $acc_holder_name; ?></td>
			</tr>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_ACCOUNT_NO'); ?>:</label></td>
				<td><?php echo $bank_account; ?></td>
			</tr>
			<?php if(!empty($iban)): ?>
				<tr>
					<td class="key"><label><?php echo JText::_('COM_JBLANCE_IBAN'); ?>:</label></td>
					<td><?php echo $iban; ?></td>
				</tr>
			<?php endif; ?>
				<?php if(!empty($swift)): ?>
				<tr>
					<td class="key"><label><?php echo JText::_('COM_JBLANCE_SWIFT'); ?>:</label></td>
					<td><?php echo $swift; ?></td>
				</tr>
			<?php endif; ?>
			<tr class="jbl_rowhead">
			<th colspan="2"><?php echo JText::_('COM_JBLANCE_NOTIFICATION_INFO'); ?> </th>
			</tr>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_EMAIL'); ?>:</label></td>
				<td><?php echo $emailnotify; ?></td>
			</tr>
			<tr>
				<td class="key"><label><?php echo JText::_('COM_JBLANCE_FAX'); ?>:</label></td>
				<td><?php echo $faxnofity; ?></td>
			</tr>
			<tr ><th align="center" colspan="2"><input type="button" onclick="location.href='<?php echo $link_balance; ?>';" value="<?php echo '   '.JText::_('COM_JBLANCE_OK').'   '; ?>" class="button"/></th></tr>
		</table>
	
	<br/>
	
	<div class="tipbox">
		<?php echo JText::_('COM_JBLANCE_MANUAL_TRANSER_WHATS_NEXT'); ?>
	</div>