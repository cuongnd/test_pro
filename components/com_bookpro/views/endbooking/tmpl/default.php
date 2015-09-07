<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php  23-06-2012 23:33:14
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
AImporter::helper('orderstatus');
?>
<div class="order_number"><?php echo JText::_('COM_BOOKPRO_ORDER_NUMBER')?>: <?php echo $this->order->order_number ?></div>
<div>
<?php 
switch ($this->order->order_status) {
	case OrderStatus::$FINISHED:
		echo JText::_('COM_BOOKPRO_ORDER_SUCCESS_MSG');
		break;
	case  OrderStatus::$CONFIRMED:
		echo JText::_('COM_BOOKPRO_ORDER_CONFIRM_MSG');
		break;
}
?>
</div>

<div class="payment_status">
	
	<?php 
	switch ($this->order->pay_status) {
		case 'SUCCESS':
			echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_SUCCESS_MSG');
			break;
		case 'FAILED':
			$pay_again=JHtml::link(JURI::base().'index.php?option=com_bookpro&controller=payment&view=formpayment&order_id='.$this->order->id, JText::_('COM_BOOKPRO_HERE'));
			echo JText::sprintf('COM_BOOKPRO_ORDER_PAYMENT_FAILED_MSG',$pay_again);
			break;
		case 'PENDING':
			echo JText::_('COM_BOOKPRO_ORDER_PAYMENT_PENDING_MSG');
			break;
		default:
			echo JText::_('UNKNOWN_STATUS');
			break;
	}
?>
</div>
