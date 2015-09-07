<?php 
return;
defined( '_JEXEC' ) or die( 'Restricted access' );
AImporter::model('transports','tour','orderinfos','airport','transport');
AImporter::css('tour');

$infomodel=new BookProModelOrderinfos();
$param=array('order_id'=>$this->order->id,'ordering'=>'id','order_Dir'=>'ASC');
$infomodel->init($param);
$orderinfos=$infomodel->getData();
$config=AFactory::getConfig();

for ($i = 0; $i < count($orderinfos); $i++) {
	$tmodel=new BookProModelTransport();
	$tmodel->setId($orderinfos[$i]->obj_id);
	$transport=$tmodel->getObject();

	$model=new BookProModelAirport();
	$model->setId($transport->from);
	$dest=$model->getObject();

	$orderinfos[$i]->from_type=$dest->air;
	$orderinfos[$i]->tfrom=$transport->tfrom;

	$model=new BookProModelAirport();
	$model->setId($transport->to);
	$dest=$model->getObject();

	$orderinfos[$i]->to_type=$dest->air;
	$orderinfos[$i]->tto=$transport->tto;
}

?>
<h2>
	<span><?php echo JText::_("COM_BOOKPRO_BOOKING_INFORMATION")?> </span>
</h2>
	<div id="booking_detail">
	 <table>

			<thead>
				<tr>
					<th><?php echo JText::_('COM_BOOKPRO_TRANSPORT_PICKUP_LOCATION')?></th>
					<th><?php echo JText::_('COM_BOOKPRO_TRANSPORT_DROP_LOCATION')?></th>
					<th><?php echo JText::_('COM_BOOKPRO_BUSTRIP_PRICE')?></th>
					<th><?php echo JText::_('COM_BOOKPRO_TRAVELER')?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				if(count($orderinfos)>0){

				foreach ($orderinfos as $trip) { ?>
				<tr>
					<td nowrap="nowrap" valign="top"><?php echo $trip->tfrom?><br />
					 <?php if($trip->from_type){
						echo JText::_('COM_BOOKPRO_TRANSPORT_FLIGHT_NUMBER').': '.$trip->purpose.'<br/>';
						echo JText::_('COM_BOOKPRO_TRANSPORT_FLIGH_TIME').':'.JFactory::getDate($trip->start)->format('d-m-Y H:i');
					} else {
				    	echo $trip->location;
				    } ?>
					</td>
					<td nowrap="nowrap" valign="top"><?php echo $trip->tto ?><br />
					 <?php if($trip->to_type && !$trip->from_type){
						echo JText::_('COM_BOOKPRO_TRANSPORT_FLIGHT_NUMBER').': '.$trip->purpose.'<br/>';
						echo JText::_('COM_BOOKPRO_TRANSPORT_FLIGH_TIME').':'.JFactory::getDate($trip->start)->format('d-m-Y H:i');
					} else{
				    	echo $trip->location;
				    } ?>
					</td>
					<td><?php echo CurrencyHelper::formatprice($trip->price) ?></td>
					<td><?php echo $trip->adult ?></td>
				</tr>


				<?php } 
}
else{
			?>

				<tr>
					<td colspan="5"><?php echo JText::_('COM_BOOKPRO_NO_RECORD')?></td>
				</tr>
				<?php 
		}
		?>

			</tbody>
		</table>
	</div>
	
