<?php
$displayData;
AImporter::model('country');
$model = new BookProModelCountry();
$country = $model->getObjectById($displayData);

?>
<div class="row-fluid">
	<div class="span4 text_title text-left">
		<p class="title">
		<?php echo JText::_('COM_BOOKPRO_COUNTRY_GUARANTEE'); ?>
		</p>
		<p>
		<?php echo $country->guarantee; ?>
		
	</div>
	<div class="span4 text_title text-left">
		<p class="title">
			<?php echo JText::_('COM_BOOKPRO_COUNTRY_CLUB') ?>
		</p>
		<p>
			<?php echo $country->club; ?>
		</p>
	</div>
	<div class="span4 text_title text-left">
		<p class="title"><?php echo JText::_('COM_BOOKPRO_REVIEWS') ?></p>
		<p>
			<?php echo $country->reviews; ?>
		</p>
	</div>
</div>