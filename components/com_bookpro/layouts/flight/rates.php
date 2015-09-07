<?php
AImporter::helper('currency','image');
$params=JcomponentHelper::getParams('com_bookpro');
$config = JComponentHelper::getParams('com_bookpro');
$rates=$displayData->rates;
$row=$displayData->flight;
$config=JComponentHelper::getParams('com_bookpro');
$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
$cart->load();
$pax_count=$cart->adult+$cart->children+$cart->infant;
?>

<div class="row-fluid">
	<div class="span4">
		<?php 
		$avail_seat= $row->base_seat - $rates['BASE']->booked;
		if($params->get('show_seat')){ ?>
			<?php
					
			 if ($avail_seat < $pax_count) {
				
				echo JText::_('COM_BOOKPRO_FLIGHT_SEAT_UNAVAILABLE');
			}else{ ?>
			<label class="flight_label"
				for="ecoFlight<?php echo $i?>">
				 <?php echo CurrencyHelper::formatprice($rates['BASE']->discount ? $rates['BASE']->discount:$rates['BASE']->adult); ?>
				 
			</label>
			<span>(<?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_ECO_SEAT',$avail_seat); ?>)</span>
			<div>
				<?php 
					$base_checked = $rates['BASE']->id == $displayData->rate_id ? 'checked="checked"':'';
				?>
				<input type="radio"
				 name="<?php echo $displayData->field ?>" <?php echo $base_checked; ?>
				value="<?php echo $rates['BASE']->id ?>" />
			
			</div>
			<?php } ?> 	
		<?php }else{ ?>
		
			<label class="flight_label">
			<?php echo CurrencyHelper::formatprice($rates['BASE']->discount ? $rates['BASE']->discount:$rates['BASE']->adult); ?>
			
			
			</label>
			<div>
				<?php 
					$base_checked = $rates['BASE']->id == $displayData->rate_id ? 'checked="checked"':'';
				?>
				<input type="radio"
				 name="<?php echo $displayData->field ?>" <?php echo $base_checked ?>
				value="<?php echo $rates['BASE']->id ?>" />
			</div>
		<?php } ?>
	</div>
	<div class="span4">
		<?php if ($config->get('economy')){ 
			$avail_seat= $row->eco_seat - $rates['ECO']->booked;
			?>
				<?php 

				if($params->get('show_seat')){ ?>
					<?php
							
					 if ($avail_seat < $pax_count) {
						
						echo JText::_('COM_BOOKPRO_FLIGHT_SEAT_UNAVAILABLE');
					}else{ ?>
					<label class="flight_label"
						for="ecoFlight<?php echo $i?>">
						 <?php echo CurrencyHelper::formatprice($rates['ECO']->discount ? $rates['ECO']->discount:$rates['ECO']->adult); ?>
						 
					</label>
					<span>(<?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_ECO_SEAT',$avail_seat); ?>)</span>
					<div>
						<?php 
							$eco_checked = $rates['ECO']->id == $displayData->rate_id ? 'checked="checked"':'';
						?>
						<input type="radio" name="<?php echo $displayData->field ?>" <?php echo $eco_checked; ?> value="<?php echo $rates['ECO']->id ?>" />
					</div>
					<?php } ?> 	
				<?php }else{ ?>
					<label class="flight_label"
						for="ecoFlight<?php echo $i?>">
					<?php echo CurrencyHelper::formatprice($rates['ECO']->discount ? $rates['ECO']->discount:$rates['ECO']->adult); ?>
				
				</label>
				<div>
					<?php 
							$eco_checked = $rates['ECO']->id == $displayData->rate_id ? 'checked="checked"':'';
						?>
					<input type="radio" name="<?php echo $displayData->field ?>" <?php echo $eco_checked; ?> value="<?php echo $rates['ECO']->id ?>" />
				</div>
				<?php } ?>	
		
		<?php } ?>
	</div>
	<div class="span4">
		<?php if ($config->get('business')){ 
			$avail_seat= $row->eco_seat - $rates['BUS']->booked;
			?>
				<?php 

				if($params->get('show_seat')){ ?>
					<?php
							
					 if ($row->bus_avail < $this->cart->adult) {
						
						echo JText::_('COM_BOOKPRO_FLIGHT_SEAT_UNAVAILABLE');
					}else{ ?>
					<label class="flight_label"
						for="ecoFlight<?php echo $i?>">
						 <?php echo CurrencyHelper::formatprice($rates['BUS']->discount ? $rates['BUS']->discount:$rates['BUS']->adult); ?>
						 
					</label>
					<span>(<?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_ECO_SEAT',$avail_seat); ?>)</span>
					<div>
						<?php 
							$bus_checked = $rates['BUS']->id == $displayData->rate_id ? 'checked="checked"':'';
						?>
						<input type="radio" name="<?php echo $displayData->field ?>" <?php echo $bus_checked; ?> value="<?php echo $rates['BUS']->id ?>" />
					</div>
					<?php } ?> 	
				<?php }else{ ?>
					<label class="flight_label"
						for="ecoFlight<?php echo $i?>">
					<?php echo CurrencyHelper::formatprice($rates['BUS']->discount ? $rates['BUS']->discount:$rates['BUS']->adult); ?>
					
				
				</label>
				<div>
					<?php 
							$bus_checked = $rates['BUS']->id == $displayData->rate_id ? 'checked="checked"':'';
						?>
					<input type="radio" name="<?php echo $displayData->field ?>" <?php echo $bus_checked; ?> value="<?php echo $rates['BUS']->id ?>" />
				</div>
				<?php } ?>
		<?php } ?>
	</div>
	</div>