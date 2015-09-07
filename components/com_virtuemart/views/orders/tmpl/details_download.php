
<fieldset>
	<legend><?php echo JText::_('Link download product')?>:</legend>
	<a href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=orders&task=downloadproduct&order_number='.$this->orderdetails['details']['BT']->order_number) ?>"><?php echo $this->orderdetails['details']['BT']->order_number ?></a>
</fieldset>
