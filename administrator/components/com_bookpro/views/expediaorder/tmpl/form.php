<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: form.php 105 2012-08-30 13:20:09Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');


JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();

?>

<form action="index.php"
	method="post" name="adminForm" id="adminForm">

	<div class="form-horizontal">
		<div class="control-group">
			<label class="control-label" for="order_number"><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER'); ?>
			</label>
			<div class="controls">
				<input class="text_area required" type="text" name="order_number"
					id="order_number" size="60"
					value="<?php echo $this->obj->order_number; ?>" disabled="disabled" />
			</div>
		</div>


		<div class="control-group">
			<label class="control-label" for="ordertype"><?php echo JText::_('COM_BOOKPRO_ORDER_TYPE'); ?>
			</label>
			<div class="controls">
				<?php echo $this->ordertype ?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="paystatus"><?php echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_STATUS'); ?>
			</label>
			<div class="controls">
				<?php echo $this->paystatus ?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="paymethod"><?php echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_METHOD'); ?>
			</label>
			<div class="controls">
				<input class="text_area required" type="text" name="pay_method"
					id="pay_method" size="60"
					value="<?php echo $this->obj->pay_method; ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="orderstatus"><?php echo JText::_('COM_BOOKPRO_ORDER_STATUS'); ?>
			</label>
			<div class="controls form-inline">
				<?php echo $this->orderstatus ?>
				<label for="notify_customer">
			
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="notify_customer"><?php echo JText::_('COM_BOOKPRO_ORDER_NOTIFY_TO_CUSTOMER'); ?>
			</label>
			<div class="form-inline">
				<?php echo JHtmlSelect::booleanlist('notify_customer') ?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="total"><?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL'); ?>
			</label>
			<div class="controls">
				<input class="text_area required" type="text" name="total"
					id="total" size="60" maxlength="255"
					value="<?php echo $this->obj->total; ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="subtotal"><?php echo JText::_('COM_BOOKPRO_ORDER_SUB_TOTAL'); ?>
			</label>
			<div class="controls">
				<input class="text_area required" type="text" name="subtotal"
					id="subtotal" size="60" maxlength="255"
					value="<?php echo $this->obj->subtotal; ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="service_fee"><?php echo JText::_('COM_BOOKPRO_ORDER_SEVICE_FEE'); ?>
			</label>
			<div class="controls">
				<input class="text_area required" type="text" name="service_fee"
					id="service_fee" size="60" maxlength="255"
					value="<?php echo $this->obj->service_fee; ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="discount"><?php echo JText::_('COM_BOOKPRO_ORDER_DISCOUNT'); ?>
			</label>
			<div class="controls">
				<input class="text_area required" type="text" name="discount"
					id="discount" size="60" maxlength="255"
					value="<?php echo $this->obj->discount; ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="tx_id"><?php echo JText::_('COM_BOOKPRO_ORDER_TRANSACTION_ID'); ?>
			</label>
			<div class="controls">
				<input class="text_area required" type="text" name="tx_id"
					id="tx_id" size="60" maxlength="255"
					value="<?php echo $this->obj->tx_id; ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="deposit"><?php echo JText::_('COM_BOOKPRO_ORDER_DEPOSIT'); ?>
			</label>
			<div class="controls">
				<input class="text_area required" type="text" name="deposit"
					id="deposit" size="60" maxlength="255"
					value="<?php echo $this->obj->deposit; ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="notes"><?php echo JText::_('COM_BOOKPRO_ORDER_NOTES'); ?>
			</label>
			<div class="controls">
				<?php
				$editor =& JFactory::getEditor();
				echo $editor->display('notes', $this->obj->notes, '550', '400', '60', '20', false);
				?>
			</div>
		</div>

	</div>


	<div class="compulsory">
		<?php echo JText::_('Compulsory items'); ?>
	</div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>" /> <input
		type="hidden" name="controller"
		value="<?php echo CONTROLLER_ORDER; ?>" /> <input type="hidden"
		name="task" value="save" /> <input type="hidden" name="boxchecked"
		value="1" /> <input type="hidden" name="cid[]"
		value="<?php echo $this->obj->id; ?>" />
	<!-- Use for display customers reservations -->
	<?php echo JHTML::_('form.token'); ?>
</form>
