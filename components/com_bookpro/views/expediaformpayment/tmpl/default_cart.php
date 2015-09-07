<?php 
defined('_JEXEC') or die('Restricted access');
AImporter::helper('currency');
AImporter::css('bookpro');


?>
<div class="bpcart">
	<h2 class='block_head'>
		<span><?php echo JText::_("COM_BOOKPRO_CART_SUMMARY")?> </span>
	</h2>

	<dl id="summary">
		
		<?php if ($this->order->discount){?>
		<dt>
			<?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL_ORIGINAL')?>
		</dt>
		<dd>
			<?php echo CurrencyHelper::formatprice($this->order->discount+$this->order->total) ?>
		</dd>
		<dt>
			<?php echo JText::_('COM_BOOKPRO_ORDER_DISCOUNT')?>
		</dt>
		<dd>
			<?php echo CurrencyHelper::formatprice($this->order->discount) ?>
		</dd>
		<?php } ?>
		<dt>
			<?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL')?>
		</dt>
		<dd>
			<?php echo CurrencyHelper::formatprice($this->order->total) ?>
		</dd>
		<dt>
			<?php echo JText::_('COM_BOOKPRO_COUPON_CODE')?>
		</dt>
		<dd><div class="form-inline">
			<input type="text" value="" class="input-small" name="coupon"> <input type="submit" class="btn"
				value="<?php echo JText::_('COM_BOOKPRO_SUBMIT') ?>" id="couponbt">
				</div>
		</dd>

	</dl>
</div>
