<?php
JHtml::_('behavior.modal');
$flights = $displayData;
AImporter::helper('currency');
$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
$cart->load();

?>
<h3 class="summary-title"><?php echo JText::_('COM_BOOKPRO_FLIGHT_BOOKING_AND_GO') ?></h3>
<h3 class="summary_title"><?php echo JText::_('COM_BOOKPRO_FLIGHT_SUMMARY') ?></h3>
<div class="box_flight_summary">
	<?php foreach ($flights as $flight){ ?>
	<div class="cart_summary_row">
		<div class="row-fluid">
			<div class="span12">
				<div class="type_flight">
					<?php echo $flight->roundtrip == 1 ? JText::_('COM_BOOKPRO_FLIGHT_CART_RETURN'):JText::_('COM_BOOKPRO_FLIGHT_CART_DEPART'); ?>
				</div>
				
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<label>
				<img class="pull-left airline_logo" alt="" src="<?php echo $flight->airline_logo ?>" width="40px" height="30px" />
				<?php echo $flight->flight_number; ?>
				</label>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span6 cart_row">
				<div class="cart_depart_from">
					<div class="cart_dest_title">
						<?php echo $flight->fromName; ?>
					</div>
				</div>
			</div>
			<div class="pull-right span6 cart_row">	
				<div class="cart_depart_to">
					<div class="cart_dest_title">
						<?php echo $flight->toName; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span6 cart_row">
				<div class="cart_depart_from">
					<div class="cart_dest_code">
						<?php echo JText::sprintf('COM_BOOKPRO_CART_FROM_CODE',$flight->fromIATA,JFactory::getDate($flight->start)->format('H:i')); ?>
					</div>
				</div>
			</div>
			<div class="pull-right span6 cart_row">	
				<div class="cart_depart_to">
					<div class="cart_dest_code">
						<?php echo JText::sprintf('COM_BOOKPRO_CART_FROM_CODE',$flight->toIATA,JFactory::getDate($flight->end)->format('H:i')); ?>
					</div>
				</div>
			</div>
		</div>	
		<div class="row-fluid">
			<div class="span6 cart_row">
				<div class="cart_depart_from">
					<div class="cart_date">
						<?php echo JFactory::getDate($flight->depart_date)->format('l, d M'); ?>
					</div>
				</div>
			</div>
			<div class="pull-right span6 cart_row">	
				<div class="cart_depart_to">
					<div class="cart_date">
						<?php echo JFactory::getDate($flight->depart_date)->format('l, d M'); ?>
					</div>
				</div>
			</div>
		</div>	
		<div class="row-fluid">
			<div class="offset2 span8">
				<div class="icon_flight_center">
					<div class="icon_flight_left">
						<div class="icon_flight_right" align="center">
						
							<div class="icon_flight"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="offset2 span8">
				<div class="pull-left cart-duration" style="width:50%">
					<div class="cart-duration-right pull-right">
						<i class="icon-cart-duration"></i>
						<?php echo $flight->duration; ?>
					</div>
				</div>
				<div class="pull-left car-non-stop">
					<?php echo JText::_('COM_BOOKPRO_CART_FLIGHT_NON_STOP') ?>
				</div>
			</div>
		</div>
		<div class="baggage">
			<div class="row-fluid">
				<div class="span12">
					<?php 
						$link_paggage = JUri::root().'index.php?option=com_bookpro&view=baggage&airline_id='.$flight->airline_id.'&tmpl=component';
					?>
					<a class="cart-baggage" onclick = "SqueezeBox.fromElement(this, {handler:'iframe', size: {x: 900, y: 600}, url:'<?php echo $link_paggage; ?>'})">
					<i class="icon-cart-baggage"></i>
					<?php echo JText::_('COM_BOOKPRO_CART_BAGGAGE') ?>
				</a>
				</div>
			</div>
				
		</div>
	</div>
	<?php } ?>
	<div class="cart-adult">
		<div class="row-fluid">
			<div class="span12">
				<div class="pull-right type_flight">
					<?php echo JText::_('COM_BOOKPRO_CART_FLIGHT_FARE') ?>
				</div>
			</div>
		</div>
		
		<div class="adult-summary">
			<?php if ($flight->adult_number) {
			
			 ?>
				<label class="adult-label">
					<?php echo JText::sprintf('COM_BOOKPRO_CART_FLIGHT_ADULT',$flight->adult_number); ?>
					
				</label> 
				<?php foreach ($flights as $flight){ ?>
					
					<div class="adult-row">
						<div class="row-fluid cart-adult-row">
							<div class="span9 cart_row">
								<div class="cart-adult-label">
									<div class="row-fluid">
										<div class="span8 cart-adult-text cart_row"><?php echo $flight->roundtrip == 0 ? JText::_('COM_BOOKPRO_CART_FLIGHT'):JText::_('COM_BOOKPRO_CART_FLIGHT_RETURN') ?></div>
										<div class="span4 cart_row">
											<?php echo JText::sprintf('COM_BOOKPRO_CART_FLIGHT_ADULT_NUMBER',$flight->adult_number); ?>
										</div>
									</div>
									
								</div>
							</div>
							<div class="span3 cart_row">
								<div class="cart-adult-price"><?php echo CurrencyHelper::displayPrice($flight->adult_price); ?></div>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="tax-row">
						<div class="row-fluid cart-taxt-row">
							<div class="span9">
								<div class="cart-tax-label"><?php echo JText::_('COM_BOOKPRO_CART_FLIGHT_TAXES'); ?></div>
							</div>
							<div class="span3">
								<?php echo CurrencyHelper::displayPrice($cart->adult_tax) ?>
							</div>
						</div>
					</div>
			<?php } ?>
			<?php if ($flight->child_number) {
			
				 ?>
					 <label class="adult-label">
						<?php echo JText::sprintf('COM_BOOKPRO_CART_FLIGHT_CHILD',$flight->child_number); ?>
						
					</label> 
					<?php foreach ($flights as $flight){ ?>
						<div class="adult-row">
							<div class="row-fluid cart-adult-row">
								<div class="span9 cart_row">
									<div class="cart-adult-label">
										<div class="row-fluid">
											<div class="span8 cart-adult-text cart_row"><?php echo $flight->roundtrip == 0 ? JText::_('COM_BOOKPRO_CART_FLIGHT'):JText::_('COM_BOOKPRO_CART_FLIGHT_RETURN') ?></div>
											<div class="span4 cart_row">
												<?php echo JText::sprintf('COM_BOOKPRO_CART_FLIGHT_ADULT_NUMBER',$flight->child_number); ?>
											</div>
										</div>
										
									</div>
								</div>
								<div class="span3 cart_row">
									<div class="cart-adult-price"><?php echo CurrencyHelper::displayPrice($flight->child_price); ?></div>
								</div>
							</div>
						</div>	
					<?php } ?>
					<div class="tax-row">
						<div class="row-fluid cart-taxt-row">
							<div class="span9">
								<div class="cart-tax-label"><?php echo JText::_('COM_BOOKPRO_CART_FLIGHT_TAXES'); ?></div>
							</div>
							<div class="span3">
								<?php echo CurrencyHelper::displayPrice($cart->child_tax) ?>
							</div>
						</div>
					</div>
				<?php } ?>
				<?php if ($flight->infant_number) {
					
				 ?>
					 <label class="adult-label">
						<?php echo JText::sprintf('COM_BOOKPRO_CART_FLIGHT_INFANT',$flight->infant_number); ?>
						
					</label> 
					<?php foreach ($flights as $flight){ ?>
						<div class="adult-row">
							<div class="row-fluid cart-adult-row">
								<div class="span9 cart_row">
									<div class="cart-adult-label">
										<div class="span8 cart_row cart-adult-text"><?php echo $flight->roundtrip == 0 ? JText::_('COM_BOOKPRO_CART_FLIGHT'):JText::_('COM_BOOKPRO_CART_FLIGHT_RETURN') ?></div>
										<div class="span4 cart_row">
											<?php echo JText::sprintf('COM_BOOKPRO_CART_FLIGHT_ADULT_NUMBER',$flight->infant_number); ?>
										</div>
									</div>
								</div>
								<div class="span3 cart_row">
									<div class="cart-adult-price"><?php echo CurrencyHelper::displayPrice($flight->infant_price); ?></div>
								</div>
							</div>
						</div>
						
					<?php } ?>
					<div class="tax-row">
						<div class="row-fluid cart-taxt-row">
							<div class="span9">
								<div class="cart-tax-label"><?php echo JText::_('COM_BOOKPRO_CART_FLIGHT_TAXES'); ?></div>
							</div>
							<div class="span3">
								<?php echo CurrencyHelper::displayPrice($cart->infant_tax) ?>
							</div>
						</div>
					</div>
			<?php } ?>
		</div>
		
		
		
	</div>
	
	
</div>
<?php if($cart->total){ ?>
<div class="cart-box-total">
	<div class="cart-total">
		<div class="row-fluid">
			<div class="span12">
				<div class="cart-total-center">
					<div class="pull-left you-pay">
						<?php echo JText::_('COM_BOOKPRO_CART_FLIGHT_YOU_PAY') ?>
					</div>
					<div class="pull-left total-price">
						<?php echo CurrencyHelper::displayPrice($cart->total); ?>
					</div>
				</div>
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<div class="cart-fare-rule pull-right">
					<a href="#">
					<i class="icon-cart-fare-rule"></i>
					<?php echo JText::_('COM_BOOKPRO_CART_FLIGHT_FARE_RULE') ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>