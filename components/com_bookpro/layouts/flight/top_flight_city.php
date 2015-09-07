<?php
$dest = $displayData;
AImporter::helper('flight');
$flights = FlightHelper::getTopFlightCity($dest->id);

?>
<div class="top_flight_route">
<table class="table table_top_flight_route">
	<tr>
		<th><?php echo JText::_('COM_BOOKPRO_TOP_FLIGHT_ROUTE_DEPART') ?></th>
		<th><?php echo JText::_('COM_BOOKPRO_TOP_FLIGHT_ROUTE_ARRIVAL') ?></th>
		<th><?php echo JText::_('COM_BOOKPRO_TOP_FLIGHT_ROUTE_FLIGHTS') ?></th>
	</tr>
	<?php foreach ($flights as $flight){ 
		$link = JRoute::_('index.php?option=com_bookpro&view=routeflight&desfrom='.$flight->desfrom.'&desto='.$flight->desto.'&Itemid=' . JRequest::getVar('Itemid'));
		?>
	<tr>
		<td class="depart">
			<a href="<?php echo $link; ?>">
			<?php echo $flight->fromtitle; ?>
			</a>
		</td>
		<td class="arrival">
			<a href="<?php echo $link ?>">
			<?php echo $flight->totitle; ?>
			</a>
		</td>
		<td class="flights" align="center">
			<a href="<?php echo $link; ?>">
			<?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_ROUTE_FLIGHTS',$flight->number_flights); ?>
			</a>
		</td>
	</tr>
	<?php } ?>
</table>
</div>
