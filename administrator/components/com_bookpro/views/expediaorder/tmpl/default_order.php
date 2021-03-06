<?php 
	defined( '_JEXEC' ) or die( 'Restricted access' );
?>
		<h2>
		<?php echo JText::_('COM_BOOKPRO_ORDER_SUMARY'); ?>
		</h2>

			<div class="control-group">
				<label class="control-label" for="order_number"><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER'); ?>
				</label>
				<div class="controls">
					<?php echo $this->order->order_number; ?>
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="total"><?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL'); ?>
				</label>
				<div class="controls">
					<?php echo CurrencyHelper::formatprice($this->order->total); ?>
				</div>
			</div>
			<!-- 
			<div class="control-group">
				<label class="control-label" for="order_status"><?php echo JText::_('COM_BOOKPRO_ORDER_ORDER_STATUS'); ?>
				</label>
				<div class="controls">
					<?php echo $this->getOrderStatusSelect($this->order->order_status); ?>
				</div>
			</div>
			 -->
			<?php if ($this->order->pay_method) {?>
			<div class="control-group">
				<label class="control-label" for="pay_method"><?php echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_METHOD'); ?>
				</label>
				<div class="controls">
					<?php echo $this->order->pay_method; ?>
				</div>
			</div>
			<?php } ?>
			
			<div class="control-group">
				<label class="control-label" for="pay_status"><?php echo JText::_('COM_BOOKPRO_ORDER_PAY_STATUS'); ?>
				</label>
				<div class="controls">
					<?php echo PayStatus::format($this->order->pay_status);?> 
				</div>
			</div>
			<?php if($this->order->notes){?>
			<div class="control-group">
				<label class="control-label" for="notes"><?php echo JText::_('COM_BOOKPRO_ORDER_NOTE'); ?>
				</label>
				<div class="controls">
					<?php echo $this->order->notes; ?>
				</div>
			</div>
			<?php } ?>
			
			<div class="control-group">
				<label class="control-label" for="created"><?php echo JText::_('COM_BOOKPRO_ORDER_ORDER_TIME'); ?>
				</label>
				<div class="controls">
					<?php echo JHtml::_('date',$this->order->created); ?>
				</div>
			</div>
