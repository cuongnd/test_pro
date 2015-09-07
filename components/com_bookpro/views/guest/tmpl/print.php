<?php 
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/
defined('_JEXEC') or die('Restricted access');

AImporter::model('bustrips','bustrip','passengers','orderinfos','order');
AImporter::helper('date','currency','bus');

$order_number = JRequest::getVar('order_number','');
$orderModel = new BookProModelOrder();
$this->order = $orderModel->getByOrderNumber($order_number);

$infomodel=new BookProModelOrderinfos();
$param=array('order_id'=>$this->order->id,'order'=>'id','order_Dir'=>'ASC');

$infomodel->init($param);
$this->orderinfo=$infomodel->getData();

$passModel=new BookProModelPassengers();
$lists=array('order_id'=>$this->order->id);
$passModel->init($lists);
$this->passengers=$passModel->getData();

if (count($this->orderinfo)>0){
	foreach ($this->orderinfo as $info)
	{
		$fmodel=new BookProModelBusTrip();
		$obj=$fmodel->getObjectByID($info->obj_id);
		$depart_date=DateHelper::formatDate($this->orderinfo->start);
		$route.=$obj->from_name.'-'.$obj->to_name.'('.$depart_date.')<br/>' ;
		$stop=BusHelper::getDepartBusStop($info->obj_id);
		$route.=JText::sprintf('COM_BOOKPRO_BUSTRIP_DEP',$stop->depart_time,$stop->title).'<br/>';
		$pax=$info->adult+$info->children;
	}
}

?>


<div id="ticket-print" style="padding:10px;">
		<?php 
		$ticket= JText::_('COM_BOOKPRO_BUS_TICKET');
		$ticket=str_replace('{order_number}', $this->order->order_number, $ticket);
		$ticket=str_replace('{customer}',$this->order->firstname.' '.$this->order->lastname, $ticket);
		$ticket=str_replace('{total}', CurrencyHelper::formatprice($this->order->total), $ticket);
		$ticket=str_replace('{phone}', $this->order->telephone, $ticket);
		$ticket=str_replace('{mobile}', $this->order->mobile, $ticket);
		$ticket=str_replace('{route}', $route, $ticket);
		$ticket=str_replace('{site}', JURI::root(), $ticket);
		$ticket=str_replace('{total_passenger}', $pax, $ticket);
		$ticket=str_replace('{pay_status}',JText::_('COM_BOOKPRO_PAYMENT_STATUS_'.$this->order->pay_status), $ticket);
		echo $ticket;
		?>
</div>