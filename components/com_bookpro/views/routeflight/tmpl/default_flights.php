<div class="dest-flight-list">
<?php
AImporter::helper('image','flight','currency');
$i = 1;
foreach ($this->destos as $desto){
	$link = JRoute::_('index.php?option=com_bookpro&controller=flight&view=flight&id=' . $desto -> id . '&Itemid=' . JRequest::getVar('Itemid'));
	
	
	
	if ($i == 1){
		echo '<div class="row-fluid">';
	}
?>
<div class="span4">
	<div class="dest-flight-item">
		<a href="<?php echo $link; ?>">
		<img src="<?php echo $desto->image; ?>" width="100%" height="149px" title="<?php echo $desto->title; ?>" />
		<div class="dest-flight-block">
			<div class="dest-flight-title">
				<?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_DEST_TO',$desto->title); ?>
			</div>
			<div class="dest-flight-price">
				<i class="flight-icon"></i>
				<?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_DEST_MIN_PRICE',CurrencyHelper::displayPrice(FlightHelper::getPriceCiTyFlight($desto->desfrom,$desto->desto))); ?>
			</div>
		</div>
		</a>
	</div>
</div>
<?php 
	if ($i % 3 ==0 && $i < count($this->destos)){
		echo '</div>';
		echo '<div class="row-fluid">';	
	}
	if ($i == count($this->destos)){
		echo '</div>';
	}
$i++;
} ?>
</div>