<?php 
defined('_JEXEC') or die('Restricted access');
BookProHelper::importLightBox();
?>
<div class="row-fluid">
<div class="pull-left">
	<p class="lead"><?php echo $this->tour->title ?></p>
</div>
<?php if($this->event->afterDisplayTitle){?>
<div class="pull-right">
<?php echo $this->event->afterDisplayTitle ?>
</div>
<?php } ?>
</div>


<div class="row-fluid">
	<div class="span6">

		<?php
		$thumb = null;
	
		
		$ipath = BookProHelper::getIPath($this->tour->image);
		$thumb = AImage::thumb($ipath, $this->config->subjectThumbWidth, $this->config->subjectThumbHeight);
		$slide = AImage::thumb($ipath, $this->config->galleryPreviewWidth, $this->config->galleryPreviewHeight);
		if ($thumb) {
				?>
		<a href="<?php echo $slide; ?>" title="" data-lightbox="<?php echo $slide ?>"
			class="thumbnail"> <img src="<?php echo $thumb; ?>"
			alt="<?php echo $this->tour->title  ?>" />
		</a>
		<?php
			}
			?>
	</div>
	<div class="span6">

		<table class="table table-condensed">
			<thead>
			
			</thead>
			<tr>
				<td><?php echo JText::_('COM_BOOKPRO_TOUR_CODE')?>
				</td>
				<td><?php echo $this->tour->code ?>
				</td>
			</tr>
			
			<tr>
				<td><?php echo JText::_('COM_BOOKPRO_TOUR_DESTINATION')?>
				</td>
				<td><?php echo TourHelper::getTourDestination($this->tour->id) ?>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_BOOKPRO_TOUR_CATEGORY')?>
				</td>
				<td><?php echo TourHelper::buildThemesTour($this->tour->id) ?>
				</td>
			</tr>
			<tr>
				<td><?php echo JText::_('COM_BOOKPRO_TOUR_DURATION')?>
				</td>
				<td><?php echo TourHelper::buildDuration($this->tour->duration) ?>
				</td>
			</tr>

			<?php 	
			if($this->tour->departure_id)
			{
				JTable::addIncludePath(JPATH_COMPONENT_FRONT_END.DS.'tables');
				$item = JTable::getInstance('airport', 'table');
				$item->load($this->tour->departure_id);
				?>
			<tr>
				<td><?php echo JText::_('COM_BOOKPRO_TOUR_DEPART_LOCATION')?>
				</td>
				<td><?php echo $item->title ?>
				</td>
			</tr>
			<?php 
			}
			?>

			<?php if($this->tour->files){ ?>

			<tr>
				<td><?php echo JText::_('COM_BOOKPRO_TOUR_FILES')?>
				</td>
				<td>

					<div id="download_files">
						<?php echo TourHelper::getDownloadFiles($this->tour->files) ?>
					</div>
				</td>
			</tr>
			<?php } ?>
		</table>

	</div>

</div>
<br />
<div class="row-fluid">
	<div class="span12 hidden-phone">

		<?php
		if ($this->config->displayGallery) {
			$images = BookProHelper::getSubjectImages($this->tour);
			$pcount = count($images);
			if (!empty($images)) {
				?>

		<div class="photogallery">
			<div id="cbi-images" class="images">
				<?php
				foreach  ($images as $image) {
					$ipath = BookProHelper::getIPath($image); // image full path
					$thumb = AImage::thumb($ipath, $this->config->galleryThumbWidth, $this->config->galleryThumbHeight); // thumnail
					$slide = AImage::thumb($ipath, $this->config->galleryPreviewWidth, $this->config->galleryPreviewHeight); // preview
					if ($thumb && $slide) { // both are required
						?>
				<a href="<?php echo $slide; ?>" title="" rel="lightbox-atomium"> <img
					src="<?php echo $thumb; ?>" alt="" />
				</a>
				<?php
					}
				}
				?>
			</div>

		</div>
		<?php } 
} ?>

	</div>

</div>

