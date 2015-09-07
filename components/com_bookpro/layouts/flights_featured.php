<?php
AImporter::model('flights');
AImporter::helper('date','flight');
$featuredModel = new BookProModelFlights();
$lists = array('state'=>1,'country_id'=>$country_id,'featured'=>1);
$featuredModel->init($lists);
$featureds = $featuredModel->getData();
$date = new JDate('now');
$date->modify('last Monday');
$start_week = $date->format('d-m-Y');
$date = new JDate('now');
$date->modify('next Sunday');
$end_week = $date->format('d-m-Y');

?>
<div class="top_discount_tour">
<p class="title_top_discount"><?php echo JText::_('COM_BOOKPRO_FLIGHT_FEATURED') ?></p>
<?php foreach ($featureds as $featured){ 
	$rate = FlightHelper::getMinPriceFlight($featured->id, $start_week, $end_week);
	
	?>
<div class="row-fluid">
	<div class="span12">
		<div class="row-fluid content-feature">
			<div class="span8 content-text">
				<h3 class="ltour-title">
				<a href="#">
					<?php echo $featured->title; ?>
				</a>
				</h3>
				
				
			</div>
			<div class="span4 content-img">
				
				<div class="imglist">
					<div class="discount-search">
						
					</div>
					<div class="content-price">
					<div class="fro">Fro. </div>
					<div class="cprice">
						<?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_FEATURED_PRICE',CurrencyHelper::displayPrice($rate->adult)); ?>
						
					</div>
					
					</div>
					<?php 
					if($deal->image){
						$ipath = BookProHelper::getIPath($deal->image);
					}else {
						$ipath = BookProHelper::getIPath('components/com_bookpro/assets/images/no_image.jpg');
					}
					$thumb = AImage::thumb($ipath, 107, 79);
					?>
					<img src="<?php echo $thumb; ?>" alt="" />
				</div>
				
				
			</div>
			</div>
	</div>
</div>
<?php } ?>

</div>