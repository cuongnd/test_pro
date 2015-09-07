<p>
<?php

echo $displayData->air_carries; ?>
<?php //echo JText::sprintf('COM_BOOKPRO_COUNTRY_AIR_CARRIES_CONTENT',$displayData->country_name); ?>
</p>
<div class="airline_list">
	<?php 
		$airlines = $displayData->airlines;
		
	?>
	<?php foreach ($airlines as $airline){ 
		
		?>
	<div class="row-fluid">
		<div class="span12">
			<div class="airline_title">
				<img class="carriers_image" src="<?php echo $airline->image; ?>" />
				<a href="index.php?option=com_bookpro&view=airline&id=<?php echo $airline->id; ?>">
				<?php echo $airline->title; ?>
				</a>
			</div>
			
			<p><?php echo $airline->short_description; ?>
				<a href="#" class="readmore"><?php echo JText::_('COM_BOOKPRO_READMORE') ?></a>
			</p>
		</div>
	</div>
	<?php } ?>
</div>
