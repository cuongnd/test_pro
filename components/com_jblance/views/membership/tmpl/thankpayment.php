<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	08 August 2012
 * @file name	:	views/membership/tmpl/thankpayment.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Thanks page after payment (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 JHTML::_('behavior.modal');
 
 $app  =& JFactory::getApplication();
 $type = $app->input->get('type', '', 'string');
?>
<!-- display pages based on the type -->
<?php if($type == 'cancel') : ?>
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_PAYMENT_CANCELLED'); ?></div>
	<p>
		<span class="font16"><?php echo JText::_('COM_JBLANCE_YOUR_PAYMENT_IS_CANCELLED'); ?></span>
		</p>
<?php else : ?>
	<div class="jbl_h3title"><?php echo JText::_('COM_JBLANCE_THANK_YOU'); ?></div>
	
	<table width="100%" cellpadding="0" cellspacing="0" class="jbltable">
		<tr>
			<th colspan="2"><span class="font16"><?php echo JText::_('COM_JBLANCE_WE_THANK_YOU_FOR_YOUR_PAYMENT'); ?></span></th>
		</tr>
		<tr>		
			<td class="key">
				<label for="amount"><?php echo JText::_('COM_JBLANCE_ITEM_NAME'); ?>:</label>
			</td>
			<td><?php echo $this->row->itemName; ?></td>
		</tr>
		<tr>		
			<td class="key">
				<label for="amount"><?php echo JText::_('COM_JBLANCE_INVOICE_NO'); ?>:</label>
			</td>
			<td><?php echo $this->row->invoiceNo; ?></td>
		</tr>
		<tr>
			<td class="key">
				<label for="name"><?php echo JText::_('COM_JBLANCE_APPROVED'); ?>:</label>
			</td>
			<td><img src="components/com_jblance/images/s<?php echo $this->row->status;?>.png" alt="Status"></td>
		</tr>
		<tr>
			<td class="key">
				<label for="name"><?php echo JText::_('COM_JBLANCE_PAY_MODE'); ?>:</label>
			</td>
			<td><?php echo $this->row->gateway; ?></td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<div class="fr">
					<a rel="{handler: 'iframe', size: {x: 650, y: 500}}" href="<?php echo $this->row->lnk_invoice; ?>" class="modal jbbutton"><span><?php echo JText::_('COM_JBLANCE_PRINT_INVOICE'); ?></span></a>
					<a href="<?php echo $this->row->lnk_continue; ?>" class="jbbutton"><span><?php echo JText::_('COM_JBLANCE_CONTINUE'); ?></span></a>
				</div>
			</td>
		</tr>
	</table>
<?php endif; ?>