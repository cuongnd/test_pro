<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	06 August 2012
 * @file name	:	views/membership/tmpl/plandetail.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Shows plan details (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 $model = $this->getModel();
 
 $config =& JblanceHelper::getConfig();
 $dformat = $config->dateFormat;
 $currencysym = $config->currencySymbol;
?>
<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PLAN_DETAILS'); ?></div>

<fieldset class="jblfieldset">
	<legend><?php echo JText::_('COM_JBLANCE_SUBSCR_INFO'); ?></legend>
	<table width="100%" cellpadding="0" cellspacing="0" class="jbltable">
		<tr>		
			<td class="key">
				<label for="amount"><?php echo JText::_('COM_JBLANCE_INVOICE_NO'); ?>:</label>
				<td><?php echo $this->row->invoiceNo; ?></td>
			</td>
		</tr>
		<tr>
			<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_PLAN_NAME'); ?>:</label></td>
			<td><?php echo $this->row->name; ?></td>
		</tr>
		<tr>
			<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_APPROVED'); ?>:</label></td>
			<td><img src="components/com_jblance/images/s<?php echo $this->row->approved;?>.png" alt="Status"></td>
		</tr>
		<tr>
			<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_DATE_BUY'); ?>:</label></td>
			<td>
				<?php echo $this->row->date_buy != "0000-00-00 00:00:00" ? JHTML::_('date', $this->row->date_buy, $dformat.' H:i:s', true) :  "&nbsp;"; ?>
			</td>
		</tr>
		<tr>
			<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_PLAN_DURATION'); ?>:</label></td>
			<td>
				<?php echo $this->row->date_approval != "0000-00-00 00:00:00" ? JHTML::_('date', $this->row->date_approval, $dformat) :  "&nbsp;"; ?> &harr; <?php echo $this->row->date_expire != "0000-00-00 00:00:00" ? JHTML::_('date', $this->row->date_expire, $dformat) :  "&nbsp;"; ?>
			</td>
		</tr>
	</table>
</fieldset>
<fieldset class="jblfieldset">
	<legend><?php echo JText::_('COM_JBLANCE_PAYMENT_INFO'); ?></legend>
	<table width="100%" cellpadding="0" cellspacing="0" class="jbltable">
		<tr>		
			<td class="key">
				<label for="amount"><?php echo JText::_('COM_JBLANCE_TAX'); ?>:</label>
				<td><?php echo $this->row->tax_percent; ?>%</td>
			</td>
		</tr>
		<tr>
			<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_TOTAL_AMOUNT'); ?>:</label></td>
			<td><?php echo JblanceHelper::formatCurrency($this->row->price, $currencysym); ?></td>
		</tr>
		<tr>
			<td class="key"><label for="name"><?php echo JText::_('COM_JBLANCE_PAY_MODE'); ?>:</label></td>
			<td><?php echo JblanceHelper::getGwayName($this->row->gateway); ?></td>
		</tr>
	</table>
</fieldset>
<fieldset class="jblfieldset">
	<legend><?php echo JText::_('COM_JBLANCE_FUND_INFO'); ?></legend>
	<table width="100%" cellpadding="0" cellspacing="0" class="jbltable">
		<tr>		
			<td class="key">
				<label for="amount"><?php echo JText::_('COM_JBLANCE_BONUS_FUND'); ?>:</label>
				<td><?php echo JblanceHelper::formatCurrency($this->row->fund, $currencysym); ?></td>
			</td>
		</tr>
		<?php 
			$infos = $model->buildPlanInfo($this->row->planid);
			$html = "";
			foreach($infos as $info){
				$html .= "<tr>";
				$html .= "<td class=key><label>".$info->key."</label>:</td>";
				$html .= "<td>".$info->value."</td>";
				$html .= "</tr>";
			}
			echo $html;
			?>
	</table>
</fieldset>