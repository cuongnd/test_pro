<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );
include_once(JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'html'.DS.'select.php');
AImporter::model('transports','transport','orderinfos','airport');
AImporter::css('transport');
$infomodel=new BookProModelOrderinfos();
$param=array('order_id'=>$this->order->id);
$infomodel->init($param);
$orderinfos=$infomodel->getData();


$tmodel=new BookProModelTransport();
for ($i = 0; $i < count($orderinfos); $i++) {
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

<?php  echo $this->loadTemplate('order'); ?>

<div class="bpblock">
<h2>
	<span><?php echo JText::_("COM_BOOKPRO_BOOKING_INFORMATION")?> </span>
</h2>
<form id="tourBookForm" name="tourBookForm" action="index.php"
	method="post" onSubmit="return validateForm()">
	<div id="booking_detail">

		<table class="transport_trip">

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
<!-- 
	<div class="center-button">
		<input type="submit" name="update" <?php echo $disable?>
			value="<?php echo JText::_('COM_BOOKPRO_SAVE')?>" class="button" /> <a
			href="index.php?option=com_bookpro&view=mypage"><input type="button"
			name="Close" value="<?php echo JText::_('Back')?>" class="button" />
		</a>
	</div>
 -->

	<input type="hidden" name="option" value="com_bookpro" /> <input
		type="hidden" name="controller" value="order" /> <input type="hidden"
		name="task" value="updateorder" /> <input type="hidden"
		name="order_id" value="<?php echo $this->order->id;?>" />


</form>
</div>

<script type="text/javascript">


function validateForm(){

	var form= document.tourBookForm;
	var countday=parseInt('<?php echo $count_day ?>');
	var adult=form.adult;
	var children=form.children;
	var pax=adult.options[adult.selectedIndex].value+children.options[children.selectedIndex].value;
	if(pax < form.pax_min.value){
		alert('<?php echo JText::_('COM_BOOKPRO_PACKAGE_PAX_WARN')?>');
		return false;
	}

	if(form.depart.value==""){
		alert('<?php echo JText::_('COM_BOOKPRO_DEPART_DATE_WARN')?>');
		form.depart.focus();
		return false;

	}
	if(countday<=2){
		alert('<?php echo JText::_('COM_BOOKPRO_DEPART_DATE_OVER')?>');
		return false;
	}
	
return true;
}
</script>
