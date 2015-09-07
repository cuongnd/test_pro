<?php 
$customer = AFactory::getCustomer();
AImporter::helper('hotel'); 
$orderid = HotelHelper::getOrderByHotelUser($customer->id); 

if( count($orderid) <=0 ){     
      $orderid[]= 0;
    }
    
    AImporter::model('orders');
    $model = new BookProModelOrders();
    $lists = array('orders-id'=>$orderid,'limit'=>5,'limitstart'=>0,'order'=>'created','order_Dir'=>'DESC');
    $model->init($lists);
    $orders = $model->getFullObject();
 
?>
<legend>
		<?php echo JText::_('COM_BOOKPRO_LATEST_ORDER')?>
	</legend>
	<form name="tourOrder" action="index.php">
		<table class="table">
			<thead>
				<tr>
					<th><?php echo JText::_('COM_BOOKPRO_BOOKING_HOTEL_DESTINATION'); ?></th>
					<th><?php echo JText::_("COM_BOOKPRO_BOOKING_AMOUNT_DISCOUNT"); ?></th>
					<th><?php echo JText::_("COM_BOOKPRO_BOOKING_DATE_IP"); ?></th>
					
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="4">
						<a class="btn btn-primary" href="index.php?option=com_bookpro&view=supplierpage&layout=order&Itemid=<?php echo JRequest::getVar('Itemid') ?>">
							<?php echo JText::_('COM_BOOKPRO_VIEWALL'); ?>
						</a>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php 
			if(count($orders)>0) {
						
			foreach ($orders as $order) {
			$hotel = HotelHelper::getObjectHotelByOrder($order->id);
			?>
				<tr>
					<td>
					
					<?php echo JHtml::link(JURI::root().'index.php?option=com_bookpro&controller=order&task=detail&order_id='.$order->id, $hotel->title,'class="cancelbt"');
					 ?>
					 <div>
					 	<?php echo $hotel->city_title; ?>
					 </div>
					</td>
					<td align="right">
					<div><?php echo JText::sprintf('COM_BOOKPRO_BOOKING_AMOUNT',CurrencyHelper::formatprice($order->total)) ?></div>
				<div><?php echo JText::sprintf('COM_BOOKPRO_BOOKING_DISCOUNT',CurrencyHelper::formatprice($order->discount)) ?></div>
					
					</td>
					<td>
					<div><?php echo JHtml::_('date',$order->created,'d-m-Y'); ?></div>
			<?php echo $order->ip_address; ?>
					
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
