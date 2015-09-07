<?php defined('_JEXEC') or die('Restricted access'); ?>

<p class="head">
	<span><?php echo JText::_('COM_BOOKPRO_TOUR_SUMMARY')?> </span>
</p>
<div class="row-fluid">
	<div class="span4">
		<?php
		$ipath = BookProHelper::getIPath($this->tour->image);
		$thumb = AImage::thumb($ipath, 160, 100);
		?>
		<img src="<?php echo $thumb ?>" alt="<?php echo $this->tour->title ?>">
	</div>
	<div class="span8">
		<div class="title">
			<span><?php echo $this->tour->title ?> <?php echo CurrencyHelper::formatprice($this->package->price) ?>
			</span>
		</div>

		<div class="category">
			<label><?php echo JText::_('COM_BOOKPRO_TOUR_CATEGORY')?>:</label>&nbsp;
			<?php echo TourHelper::buildThemesTour($this->tour->id) ?>
		</div>
		<div class="duration">

			<label><?php echo JText::_('COM_BOOKPRO_TOUR_DURATION')?>:</label>&nbsp;
			<span><?php echo TourHelper::buildDuration($this->tour->duration)?> </span>
		</div>
		<div class="duration">

			<label><?php echo JText::_('COM_BOOKPRO_TOUR_PACKAGE') ?>:</label>&nbsp;
			<span><?php echo $this->package->title ?> </span>
		</div>
		<?php if ($this->tour->private) {?>
		<div>
			<label><?php echo JText::_('COM_BOOKPRO_TOUR_START_TIME') ?>:</label>&nbsp;
			<span><?php echo $this->tour->start_time ?> </span>
		</div>
		<?php }?>

		<p>
			<label><?php echo JText::_('ITINERARY') ?>:</label>
			<?php echo $this->itineraries['iti_sum'] ?>
		</p>

	</div>

</div>
