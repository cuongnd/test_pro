<?php
$now = JHtml::date('now');
$date = new JDate($now);
$from_date = JFactory::getDate($date)->format('d-m-Y',true);

$date->add(new DateInterval('P30D'));
$to_date = JFactory::getDate($date)->format('d-m-Y',true);
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/css/bestdeals.css');

AImporter::helper('image','currency','flight');
foreach ($displayData as $deal){
	$link = JRoute::_('index.php?option=com_bookpro&controller=flight&view=flight&id=' . $deal -> id . '&Itemid=' . JRequest::getVar('Itemid'));
	if($deal->image){
		$ipath = BookProHelper::getIPath($deal->image);
	}else {
		$ipath = BookProHelper::getIPath('components/com_bookpro/assets/images/no_image.jpg');
	}
	$thumb = AImage::thumb($ipath, 100, 93);
	$price = FlightHelper::getMinPriceFlight($deal->id, $from_date, $to_date);
	
?>

<div class="row-fluid bestdeal-row">
	<div class="span5">
		<div class="deal-thumbnail">
			<div class="deal-price">
				<span class="dealprice"><?php echo CurrencyHelper::displayPrice($price); ?></span>
				<span class="deal-pers"> /pers</span>
			</div>
			<a href="<?php echo $link; ?>">
			<img alt="" src="<?php echo $thumb; ?>">
			</a>
		</div>
	</div>
	<div class="span7 content-bestdeals" >
		<div class="contair-bestdeals">
			<h3 class="bestdeal-title">
				<a href="<?php echo $link; ?>"><?php echo $deal->title; ?></a>
				
			</h3>
			<div class="bestdeal-desc">
				<?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_DEAL_AIRLINE',$deal->airline_name); ?>
				
			</div>
		</div>
		<div>
			<a href="#" class="view-details text-right">View Details</a>
		</div>
		
	</div>
</div>

<?php } ?>

<div class="pull-right" style="padding-bottom:10px; padding-right:10px;">
    <div class="slide-up-1 pull-left action_item <?php echo $classpage; ?>next" key="<?php echo $classpage; ?>next"></div>
    <div class="slide-down-1 pull-left action_item <?php echo $classpage; ?>previous" key="<?php echo $classpage; ?>previous" style="opacity: 0.2;"></div>    
</div>
<div class="content_span_detail">
	<p class="limited_offer text-right">LIMITED OFFER</p>
</div>
