
<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: tour.php 26 2012-07-08 16:07:54Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');
AImporter::model('packageprice','tourpackage','tour');
AImporter::helper("currency",'date');
$pmodel=new BookProModelPackagePrice();
$pmodel->setId($orderinfo[0]->obj_id);
$price=$pmodel->getObject();

$tourpackageModel=new BookProModelTourPackage();
$tourpackageModel->setId($price->package_id);
$package=$tourpackageModel->getObject();

$tourModel=new BookProModelTour();
$tourModel->setId($package->tour_id);
$tour=$tourModel->getObject();
$link = JRoute::_(ARoute::edit(CONTROLLER_TOUR, $tour->id));


?>

<form name="tourOrder" action="index.php">
	<table class="order-list">
		<thead>
		<tr>
			<th><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER'); ?> 
			</th>
			<th><?php echo JText::_('COM_BOOKPRO_ORDER_TOTAL'); ?> 
			</th>

			<th><?php echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_STATUS'); ?>
			</th>
			<th><?php echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_METHOD'); ?>
			
			</th>
			<th><?php echo JText::_('COM_BOOKPRO_ORDER_ORDER_TIME'); ?> 
			</th>
			<th><?php echo JText::_('COM_BOOKPRO_ORDER_ORDER_STATUS'); ?>
			
			</th>
			<th><?php echo JText::_('COM_BOOKPRO_ACTION'); ?> 
			</th>
		</tr>
		</thead>
		<tbody>
		<?php 
		if(count($this->orders)>0) {
			
	foreach ($this->orders as $order) {?>
		<tr>
			<td><?php echo $order->order_number; ?></td>
			<td><?php echo CurrencyHelper::formatprice($order->total)?></td>

			<td><?php echo $order->pay_status; ?></td>
			<td><?php echo $order->pay_method	?></td>
			<td><?php echo $order->created; ?></td>
			<td><?php echo $order->order_status; ?></td>
			<td><?php 
			if($order->order_status !='CANCELLED' && $order->order_status !='FINISHED'){

				echo JHtml::link(JURI::root().'index.php?option=com_bookpro&controller=order&task=cancel_order&order_id='.$order->id, JText::_('COM_BOOKPRO_ORDER_CANCEL'),'class="cancelbt"');
				echo " | ";

			}
			echo JHtml::link(JURI::root().'index.php?option=com_bookpro&controller=order&task=viewdetail&order_id='.$order->id, JText::_('COM_BOOKPRO_VIEW_DETAIL'),'class="cancelbt"');
			?>
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
	<script type="text/javascript">

	function cancel(){
		var form= document.orderForm;
		form.task='cancel_order';
		form.controller='customer';
		form.order_id='<?php ?>';
		form.submit();
	}

</script>
</form>

