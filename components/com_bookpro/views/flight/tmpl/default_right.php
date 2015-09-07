<?php 
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
$cart->load();
AImporter::helper('flight','html');
$airlines = FlightHelper::getAirlineByFlightSearch($cart->from, $cart->to);

?>

	<div class="filter_flight">
		<div class="row-fluid">
			<div class="span12">
					<p>
						<label for="amount_price">Price range:</label>
						<input type="text" id="amount_price" readonly style="border:0; color:#f6931f; font-weight:bold;">
					</p>
					<div id="slider-price-range"></div>
						
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
					<p>
						<label for="amount_time">Time range:</label>
						<input type="text" id="amount_time" readonly style="border:0; color:#f6931f; font-weight:bold;">
					</p>
					<div id="slider-time-range"></div>
						
			</div>
		</div>
		<div class="row-fluid">
			<div class="span12">
				<div class="flight-filter-airline">
				<?php
				echo AHtmlFrontEnd::bootrapCheckBoxList($airlines, 'airline[]', '',$cart->airline,'id','title');
				?>
				</div>
			</div>
		</div>
	</div>
	<div class="flight_summary">
		<div id="cart_summary">
			
		</div>
				
		<div id="loading_summary" style="display: none;"><?php echo JText::_('COM_BOOKPRO_LOADING') ?></div>
	</div>
	
	<button class="btn btn-primary flight-continue" type="submit"><?php echo JText::_('COM_BOOKPRO_CONTINUE') ?>
		<i class="icon-flight-continue"></i>
	</button>