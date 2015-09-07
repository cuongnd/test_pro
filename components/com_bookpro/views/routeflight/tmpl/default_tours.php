<?php 
$now = JHtml::date('now');
$date = new JDate($now);
$from_date = JFactory::getDate($date)->format('d-m-Y',true);

$date->add(new DateInterval('P30D'));
$to_date = JFactory::getDate($date)->format('d-m-Y',true);
AImporter::helper('image','currency','tour');
$this->products_per_row=3;
$this->count=6;
$i = 1;

foreach ($this->tours as $key=>$tour){
	
	$link = JRoute::_('index.php?option=com_bookpro&controller=tour&view=tour&id=' . $tour -> id . '&Itemid=' . JRequest::getVar('Itemid'));
	$ipath = BookProHelper::getIPath($tour->image);
	$thumb = AImage::thumb($ipath, 190, 124);
	if( $i == 1 )
	  echo '<div class="row-fluid dest-tours-list">';
	?>

<div class="span4">
<div class="list-tour-center">
	<div>
		<a href="<?php echo $link; ?>" class="thumbnail">
			<img src="<?php echo $thumb; ?>">
		</a>
	</div>
	<div>
	
		<a class="dest-tours-title " href="<?php echo $link; ?>">
			
		<?php 
		echo $tour->title;
		//echo JText::sprintf('COM_BOOKPRO_DESTINATION_TOUR_TITLE',$tour->title,$tour->days,$tour->citytitle);
		?>
		
		
		</a>	
	<div class="tour-days-city">
		<?php echo JText::sprintf('COM_BOOKPRO_DESTINATION_TOUR_TITLE',$tour->days,$tour->citytitle); ?>
	</div>
	</div>
	<div class="row-fluid">
		<div class=" span6 pull-left detail_popular">
			<div class="row-fluid" style="line-height:16px;">
				<div class="pull-left dest-tour-price">
					<?php echo CurrencyHelper::formatprice(TourHelper::getMinPriceTour($tour->id, $from_date, $to_date)) ?>
				</div>
				<div class="pull-left dest-tour-pers">
					 &nbsp;/pers.
				</div>
			</div>
			
			
			<button type="submit" class="btn read_more">Read more</button>
		</div>
		<div class=" span6 pull-right">
			<div class="row-fluid">
				<div class="span4 dest-tour-percentage">
					90%
				</div>
				<div class="span8 dest-tour-customer">
					Customer Satisfaction
				</div>
			</div>
			
			
			
			
			
			
			
		</div>
	</div>
	<div>
		<div class="pull-right dest-base-review">
			<div class="pull-left dest-tour-baseon">Based on</div>
			<div class="pull-left dest-tour-reviews">120 reviews</div>
		</div>
		
	</div>
</div>	
</div>

<?php 
	if ($i < count($this->tours) && $i > 1 && $i%3 == 0) {
		echo "</div>";
		echo '<div class="row-fluid dest-tours-list">';
	}
	if ($i == count($this->tours)) {
		echo "</div>";
	}
	$i++;
?>
<?php } ?>