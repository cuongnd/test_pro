
<?php defined('_JEXEC') or die('Restricted access');?>
<div class="span12">
	<div class="span6">
		<div class="head">
			<?php
			$thumb = null;

			$ipath = BookProHelper::getIPath($this->hotel->image);
			$thumb = AImage::thumb($ipath, $this->config->subjectThumbWidth, $this->config->subjectThumbHeight);
			$slide = AImage::thumb($ipath, $this->config->galleryPreviewWidth, $this->config->galleryPreviewHeight);
			if ($thumb) {
				?>
			<a href="<?php echo $slide; ?>" title="" rel="lightbox-atomium" class="thumbnail"> <img
				src="<?php echo $thumb; ?>" alt="" class="subjectImage" />
			</a>
			<div class="clearLeft"></div>
			<?php
			}

			?>
		</div>

		<?php
		if ($this->config->displayGallery) {


			$images = BookProHelper::getSubjectImages($this->hotel);
			$pcount = count($images);

			if ($pcount) {
				?>

		<script type="text/javascript">
    //<![CDATA[
      var pg_count = <?php echo $pcount; ?>;
      var pg_position = 0;
    
      function moveLeft() {
        if (pg_position > 0)
          pg_position--;
        moveGallery();
      }
    
      function moveRight() {
        if (pg_position < (pg_count - 5))
          pg_position++;
        moveGallery();
      }
      
      function moveGallery() {
        var pg = document.getElementById('cbi-images');
        pg.style.left = pg_position * -53 + 'px';
      }
    //]]>
    </script>



		<div class="photogallery">
			<div class="leftButton" onclick="moveLeft();"></div>
			<div class="display">
				<div id="cbi-images" class="images" style="width: <?php echo $pcount * 53; ?>px">

					<?php
			}
			for ($i = 0; $i < $pcount; $i++) {
				$image = $images[$i];
				$ipath = BookProHelper::getIPath($image);
				$thumb = AImage::thumb($ipath, $this->config->galleryThumbWidth, $this->config->galleryThumbHeight);
				$slide = AImage::thumb($ipath, $this->config->galleryPreviewWidth, $this->config->galleryPreviewHeight);
				if ($thumb) {
					?>
					<a href="<?php echo $slide; ?>" title="" rel="lightbox-atomium"> <img
						src="<?php echo $thumb; ?>" alt="" />
					</a>
					<?php
				}
			}
			if ($pcount) {
				?>
				</div>
			</div>
			<div class="rightButton" onclick="moveRight();"></div>
		</div>

		<?php } ?>

	</div>

	<div class='span6'>
		<div class="desc">
			<?php $rankstar=JURI::base()."/components/com_bookpro/assets/images/". $this->hotel->rank.'star.png'; ?>
			<h2 class="hoteltitle">
				<?php echo $this->hotel->title ?>
				<span><img src="<?php echo $rankstar; ?>"> </span>
			</h2>
			<p class="hoteladd">
				<?php echo $this->hotel->address1.', '. $this->city->title.', '.$this->city->country ?>
				<a
					href="index.php?option=com_bookpro&task=displaymap&tmpl=component&hotel_id=<?php echo $this->hotel->id ?>"
					class='modal' rel="{handler: 'iframe', size: {x: 501, y: 460}}"><?php echo JText::_("COM_BOOKPRO_VIEW_MAP")?>
				</a>
			</p>
			<p>
			<?php echo JHtmlString::truncate(strip_tags($this->hotel->desc),300)?>
			</p>

		</div>
	</div>
	<div class="clear"></div>
</div>
<?php } ?>