<?php 
$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
$cart->load();
?>
<div class="air_image">
	<img src="<?php echo JUri::base() ?>/images/icon/all-airline.png" style="width:100px;height: 50px;" />
</div>
<div class="air-title"><?php echo JText::_('COM_BOOKPRO_FLIGHT_SPECIAL_ALL_AIRLINE'); ?></div>
<?php if((int) $cart->roundtrip == 0){ ?>
	<div class="special-price">
		<?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_SPECIAL_ALL_AIRLINE_COUNT',$displayData) ?>
	</div>
<?php } ?>