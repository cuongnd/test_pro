<div class="row-fluid">
		<div class="span12">
		
		
	<?php if (count($this->tours)>0) { 
		$total=count($this->tours);
		?>
	
	<?php
	$i = 0;
	foreach ($this->tours as $tour){
	$rankstar=JURI::root()."components/com_bookpro/assets/images/". $tour->rank.'star.png';
	$link=JRoute::_('index.php?option=com_bookpro&view=tour&id='.$tour->id.'&controller=tour&Itemid='.JRequest::getVar('Itemid'));
	
	if($tour->image){
		$ipath = BookProHelper::getIPath($tour->image);
	}else {
		$ipath = BookProHelper::getIPath('components/com_bookpro/assets/images/no_image.jpg');
	}
	$thumb = AImage::thumb($ipath, 160, 100);
	
	?>
	<?php if( $i == 0 )
					
					echo '<div class="row-fluid">';?>
					
			<div class="span<?php echo (12/$this->products_per_row) ?>">
	            
				
				<div class="row-fluid tour-list">
				
				<div class="span2">
						<a href="<?php echo $link ?>" class="thumbnail"><img
							src="<?php echo $thumb ?>" alt="<?php echo $tour->title ?>"> </a>
				</div>

				<div class="span10">
					<div class="row-fluid">
						<div class="pull-left">
							<h3 class="tour-title">
								<a href="<?php echo $link ?>"><?php echo $tour->title ?>,<?php echo TourHelper::getDuration($tour->days)?> </a>
								
							    
							</h3>
							<p class="tour-leed"><?php echo JText::sprintf('COM_BOOKPRO_TOUR_STYLE',TourHelper::buildCategoryTour($tour->id))?>,
							<?php echo JText::sprintf('COM_BOOKPRO_TOUR_ACTIVITY',TourHelper::buildActivityTour($tour->id))?>
							</p>
						</div>
						<div class="pull-right">
							<div class="price">
								<?php echo JText::sprintf('COM_BOOKPRO_TOUR_FROM_PRICE',CurrencyHelper::formatprice(TourHelper::getMinPriceTour($tour->id, $from_date, $to_date)) ) ?>
							</div>
						</div>
					</div>
					<div class="row-fluid tour-line">
						<div class="pull-left highlights">
							<?php echo JText::_('HIGHLIGHTS:') ?>
						</div>
						<div class="pull-right">
							Tour map | Reviews ( 135)
						</div>
					</div>
					<div class="row-fluid">
						<div class="tour-destination">
						<?php echo TourHelper::getTourDestination($tour->id) ?>
						</div>
					</div>
						
						
						
						
						
					
					<?php /*if (!$this->showIntro) {?>
					<p>
						<?php	echo JHtmlString::truncate(strip_tags($tour->description),100);?>
					</p>
					<?php } */?>

				</div>
				</div>
				</div>
					
	<?php 	if (($i+1) % $this->products_per_row == 0) {
					echo '</div>';
					echo '<div class="row-fluid">';
				}
				if(($i+1) == $this->count) {
					echo "</div>";
				}
				if($total < $this->count)
				{
					if(($i+1) == $total) {
						echo "</div>";
						}
				}
				if($total< $this->products_per_row){
					//echo "</div>";
					}
				$i++;
			 }
			} else { ?>
	
	<div><?php echo JText::sprintf('COM_BOOKPRO_NO_RECORD',JText::_('COM_BOOKPRO_TOUR'))?></div>
	
	<?php } ?>
	
		</div>
	</div>