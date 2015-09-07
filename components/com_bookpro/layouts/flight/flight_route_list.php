<?php 
$input = JFactory::getApplication()->input;
$data = $displayData;

$flights = $data->routeflights;

$config = JComponentHelper::getParams('com_bookpro');
defined('_JEXEC') or die('Restricted access');
AImporter::helper('currency','image');
$params=JcomponentHelper::getParams('com_bookpro');
$config = JComponentHelper::getParams('com_bookpro');
$input = JFactory::getApplication()->input;

AImporter::model('airport');
$model = new BookProModelAirport();
$from = $model->getItem($data->desfrom);
$model = new BookProModelAirport();
$to = $model->getItem($data->destto);



$params=JcomponentHelper::getParams('com_bookpro');
$airport_from=$from->title." (".$from->code.")";
$airport_to=$to->title." (".$to->code.")";

$start = $input->get('start',null);

$app = JFactory::getApplication();


JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
$doc=JFactory::getDocument();

$doc->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/rcarousel.css');
$doc->addScript(JURI::root().'components/com_bookpro/assets/js/jquery.ui.rcarousel.js');
?>


<h2 class="route_flight_title">
	<span> <?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_ROUTE_TOTAL',$data->total,$airport_from,$airport_to) ?>
	</span>
</h2>

<div class="row-fluid flight_head_table">
	<div class="span2" align="center">
		<label>
		<?php echo JText::_('COM_BOOKPRO_FLIGHT'); ?>
		</label>
	</div>
	<div class="span2">
		<label>
		<?php echo JText::_('COM_BOOKPRO_FLIGHT_DEPARTURE'); ?>
		</label>
	</div>
	<div class="span2">
		<label>
		<?php echo JText::_('COM_BOOKPRO_FLIGHT_DURATION'); ?>
		</label>
	</div>
	<div class="span2">
		<label>
		<?php echo JText::_('COM_BOOKPRO_FLIGHT_ARRIVAL'); ?>
		</label>
	</div>
	<div class="span2">
		<label><?php echo JText::_('DATE') ?></label>
	</div>
	<div class="span2">
		
	</div>
</div>
<div class="flight_onway">
<div id="carousel_depart">
	<?php $i=1; foreach($flights as $row):
		$rates = $row->rates;	
		
		
		
		?>
		<div id="slide<?php echo $i; ?>" class="slide">
		<div class="flight_row">
			<div class="flight_content_row">
				<div class="row-fluid flight_search_row">
					<div class="span2">
						<div class="row-fluid">
							<div class="span12" align="center">
								<div class="flight_number">
									<?php 
										echo $row->flightnumber;
									?>
								</div>	
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12" align="center">
								<div class="airline">
									<div class="airline_logo">
									<?php 
									
									if($row->airline_logo) {
										
										echo JHtml::image($row->airline_logo, $row->airline_name);
									}
									?>
									</div>
									<div class="airline_name">
									<?php echo $row->airline_name; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="row-fluid">
							<div class="span12">
								<label class="flight_label depart_icon">
									<span>
										<?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_ROW_DEPARTURE',$row->start,$row->fromcode); ?>	
									</span>
								</label>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12">
								<label class="flight_label">
									<?php echo $row->fromName; ?>
								</label>
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="row-fluid">
							<div class="span12">
								<div class="duratoin_icon">
									<label class="flight_label">
										<span>	
									<?php 
										echo JFactory::getDate($row->duration)->format('h:i');
									 ?>
									 	</span>
									 </label>
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12">
								<label class="flight_label">
									<?php echo JText::_('Non Stop') ?>
								</label>
								
							</div>
						</div>
					</div>
					<div class="span2">
						<div class="row-fluid">
							<div class="span12">
								<label class="flight_label"><?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_ROW_ARRIVAL',$row->end,$row->tocode); ?>
							
								</label>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12">
								<label class="flight_label"><?php echo $row->toName; ?></label>
								
							</div>
						</div>
					</div>
					
					<div class="span2 top-flight-date">
						<?php echo FlightHelper::getFrequencyFlight($row->frequency); ?>
					</div>
					<div class="span2">
						<div class="flight-route-check-fare">
							<div class="row-fluid">
								<div class="span12">
									<div class="check-fare-title"><?php echo JText::_('COM_BOOKPRO_CHECK_FARE') ?></div>
									<label class="check-fare">
										<input class="pull-left" type="checkbox" value="" name="route-check-fare" />
										<i class="icon-checkfare"></i>
									</label>		
								</div>
							</div>
							
						</div>
					</div>
					
				</div>
				
				
			</div>
			
		</div>
		</div>
	<?php $i++; endforeach;?>	
</div>	
</div>
<div class="row-fluid flight-navigation">
	<div class="span12">	
		<div class="pull-right">
			<label class="pull-left more-flight">
				
				<?php echo JText::_('COM_BOOKPRO_SEARCH_MORE_FLIGHT') ?>
			</label>
			<div class="pull-left">
				<a href="#" id="ui-carousel-next"><span class="ui-carousel-next"></span></a>
				<a href="#" id="ui-carousel-prev"><span class="ui-carousel-prev"></span></a>
			</div>	
		</div>
	</div>
	
</div>
<div id="pages"></div>
<script type="text/javascript">
jQuery(document).ready(function($){
	

	
	
	$("#carousel_depart").rcarousel(
		{
			visible: 5,
			step: 5,
			speed: 700,
			orientation: "vertical",
			auto: {
				enabled: false
			},
			width: 780,
			height: 112
			
		}
	);
	
	
})
			
</script>

