<?php
$displayData;


?>
<div class="title_asian_air">
	<i class="icon_flight_title"></i>
	<?php echo JText::sprintf('COM_BOOKPRO_ASIAN_AIR_CARRIES',$displayData->country_name); ?>
	
</div>
<p>
	<?php echo $displayData->asian_air; ?>
</p>
<div class="title_asian_air">
	<i class="icon_flight_title"></i>
	<?php echo JText::sprintf('COM_BOOKPRO_WORLDWIDE_AIR_CARRIES',$displayData->country_name); ?>
</div>
<?php echo $displayData->worldwide_air; ?>