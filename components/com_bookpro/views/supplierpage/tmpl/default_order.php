<?php 
$user = JFactory::getUser();
$user_id = $user->get('id');
AImporter::helper('hotel');
$orders = HotelHelper::getOrderByHotelUser($user_id, 0, 5); 

?>
<legend>
		<?php echo JText::_('COM_BOOKPRO_LATEST_ORDER')?>
	</legend>
	<form name="tourOrder" action="index.php">
		<table class="table">
			<thead>
				<tr>
					<th><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER'); ?></th>
					<th><?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL'); ?></th>
					<th><?php echo JText::_('COM_BOOKPRO_ORDER_ORDER_TIME'); ?></th>
					
				</tr>
			</thead>
			<tbody>
				<?php 
			if(count($orders)>0) {
						
			foreach ($orders as $order) {?>
				<tr>
					<td>
					
					<?php echo JHtml::link(JURI::root().'index.php?option=com_bookpro&controller=order&task=detail&order_id='.$order->id, $order->order_number,'class="cancelbt"');
					 ?>
					</td>
					<td align="right"><?php echo CurrencyHelper::formatprice($order->total)?>
					</td>
					<td><?php echo JHtml::_('date',$order->created,'d-m-Y H:i:s'); ?>
					</td>
				
				</tr>
				<?php } 
				}
				else {
					?>
				<tr>
					<td colspan="6"><?php echo JText::_('COM_BOOKPRO_ORDER_UNAVAILABLE') ?>
					</td>
				</tr>
				<?php 
				}
				?>
			</tbody>
		</table>
	
	</form>
