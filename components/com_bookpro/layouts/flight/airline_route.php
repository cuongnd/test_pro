<?php
$app = JFactory::getApplication();
$input = $app->input;
$dest_from = $input->get('desfrom');

$dest_to = $input->get('desto');

AImporter::helper('flight','currency');
$airlines = FlightHelper::getAirlineByRoute($dest_from, $dest_to);
AImporter::model('airport');
$model = new BookProModelAirport();
$from = $model->getObjectFull($dest_from);
$to = $model->getObjectFull($dest_to);
?>
<div class="airline_route">
<h3 class="route_airline_title">
	<?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_ROUTE_TITLE_AIRLINE',$airlines[0]->title) ?>
</h3>
<table class="table">
	<thead>
		<tr>
			<th><?php echo JText::_('COM_BOOKPRO_FLIGHT_ROUTE_AIRLINE') ?></th>
			<th><?php echo JText::_('COM_BOOKPRO_FLIGHT_ROUTE_DEPARTURE') ?></th>
			<th><?php echo JText::_('COM_BOOKPRO_FLIGHT_ROUTE_ARRIVAL') ?></th>
			<th><?php echo JText::_('COM_BOOKPRO_FLIGHT_ROUTE_TRIP') ?></th>
			<th><?php echo JText::_('COM_BOOKPRO_FLIGHT_ROUTE_AIRLINE_FARE') ?></th>
		</tr>
	</thead>
	<thead>
	<?php foreach ($airlines as $airline){
		
		?>
		<tr>
			<td>
			<?php 
			if($airline->airline_logo) {
			
				echo JHtml::image($airline->airline_logo, $airline->airline_name,'style="height:20px;width:40px;"');
			}
			?>
			<?php echo $airline->airline_name; ?></td>
			<td><?php echo $airline->fromName; ?></td>
			<td><?php echo $airline->toName; ?></td>
			<td>
				<?php echo JText::_('Oneway') ?>
			</td>
			<td><?php echo CurrencyHelper::displayPrice($airline->min_price); ?></td>
		</tr>
	<?php } ?>	
	</thead>
</table>
</div>