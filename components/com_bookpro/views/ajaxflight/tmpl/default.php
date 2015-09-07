<?php 
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
$input = JFactory::getApplication()->input;

$config = JComponentHelper::getParams('com_bookpro');
defined('_JEXEC') or die('Restricted access');
AImporter::helper('currency','image');
$params=JcomponentHelper::getParams('com_bookpro');
$config = JComponentHelper::getParams('com_bookpro');
$input = JFactory::getApplication()->input;
$params=JcomponentHelper::getParams('com_bookpro');
$airport_from=$this->from_to[0]->title." (".$this->from_to[0]->code.")";
$airport_to=$this->from_to[1]->title." (".$this->from_to[1]->code.")";
$start = $input->get('start',null);

$app = JFactory::getApplication();
$tzoffset = $app->getCfg('offset');
$sstart=new JDate($start,$tzoffset);
$prevday = $sstart->sub(new DateInterval('P1D'));
$sstart=new JDate($start,$tzoffset);
$nextday = $sstart->add(new DateInterval('P1D'));

$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
$cart->load();

$prev_price = FlightHelper::getMinPriceBySearch($cart->from, $cart->to, $prevday->format('Y-m-d',true));
$next_price = FlightHelper::getMinPriceBySearch($cart->from, $cart->to, $nextday->format('Y-m-d',true));
$prev_url=JURI::base().'index.php?option=com_bookpro&controller=flight&task=ajaxsearch&tmpl=component&format=raw&start='.$prevday->format('Y-m-d',true).'&roundtrip='.$cart->roundtrip;
$next_url=JURI::base().'index.php?option=com_bookpro&controller=flight&task=ajaxsearch&tmpl=component&format=raw&start='.$nextday->format('Y-m-d',true).'&roundtrip='.$cart->roundtrip;
?>



<div class="search_header">
	<div class="row-fluid">
		<div class="span8">
			<div class="row-fluid">
				<div class="span4">
					<div class="depart_title">
						<?php echo JText::_('COM_BOOKPRO_FLIGHT_SEARCH_DEPART_TITLE') ?>
					</div>
				</div>
				
			</div>
			<div class="row-fluid">
				<div class="span8 offset4">
					<div class="flight_title">
						<span class="pull-left from_title">
						<?php echo $airport_from; ?>
						
						</span>
						<i class="flight_title_icon pull-left"></i>
						<div class="pull-left to_title">
							<?php 
							echo $airport_to;
						?>
						</div>
					</div>
				</div>
				
			</div>
			<div class="row-fluid">
				<div class="span8 offset4 date_flight">
					<?php echo JFactory::getDate($start)->format('l , d F, Y') ?>
				</div>
				
			</div>
		</div>
		
		<div class="span4">
			<div class="pull-left box-prev">
				<div class="prev_day">
					<a onclick="getDepartDay('<?php echo $prev_url; ?>')">
					<i class="icon_prev_day"></i>
					<?php echo JText::_('COM_BOOKPRO_FLIGHT_PREV_DAY') ?>
					</a>
				</div>
				<div class="min_price">
					<?php echo CurrencyHelper::displayPrice($prev_price); ?>
				</div>
			</div>
			<div class="pull-left box-next">
				<div class="next_day">
					<a onclick="getDepartDay('<?php echo $next_url; ?>')">
						<?php echo JText::_('COM_BOOKPRO_FLIGHT_NEXT_DAY') ?>
						<i class="icon_next_day"></i>
					</a>
						
				</div>
				<div class="min_price">
					<?php echo CurrencyHelper::displayPrice($next_price); ?>
				</div>
			</div>
			<div class="pull-left sort">
				<div class="icon-sorting">
					
				</div>
			</div>
		</div>
	</div>
</div>
<div class="flight-search-box">
<div class="row-fluid flight_head_table">
	<div class="span2" align="center">
		<label>
		<?php echo JText::_('COM_BOOKPRO_FLIGHT'); ?>
		</label>
	</div>
	<div class="span10">
		<div class="row-fluid">
			<div class="span7">
				<div class="row-fluid">
					<div class="span4">
						<label>
						<?php echo JText::_('COM_BOOKPRO_FLIGHT_DEPARTURE'); ?>
						</label>
					</div>
					<div class="span4">
						<label>
						<?php echo JText::_('COM_BOOKPRO_FLIGHT_DURATION'); ?>
						</label>
					</div>
					<div class="span4">
						<label>
						<?php echo JText::_('COM_BOOKPRO_FLIGHT_ARRIVAL'); ?>
						</label>
					</div>
				</div>
			</div>
			<div class="span5">
				<div class="row-fluid">
					<div class="span4">
						<label class="head2">
						<?php echo JText::_('COM_BOOKPRO_FLIGHT_DELUXE')?>
						</label>
					</div>
					<?php if ($config->get('economy')){ ?>
					<div class="span4">
						<label class="head2">
						<?php echo JText::_('COM_BOOKPRO_FLIGHT_AIR_FARE')?>
						</label>
					</div>
					<?php } ?>
					<?php if ($config->get('business')){ ?>
					<div class="span4">
						<label class="head2">
						<?php echo JText::_('COM_BOOKPRO_FLIGHT_SAVING')?>
						</label>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
	
	
	
</div>
<div class="flight_onway">
	<div id="carousel_depart">
	<?php $i=1; foreach($this->flights as $row):
		$rates = $row->rates;	
		
		
		
		?>
		<div id="slide<?php echo $i; ?>" class="slide">
			<div class="flight_row">
				<div class="flight_content_row">
					<div class="row-fluid flight_search_row">
						<div class="span2" align="center">
							<div class="flight_number">
								<?php 
									echo $row->flight_number;
								?>
							</div>	
							<div class="airline">
								<div class="airline_logo">
								<?php 
								
								if($row->airline_logo) {
									
									echo JHtml::image($row->airline_logo, $row->airline_name);
								}
								?>
								</div>
								
							</div>
						</div>
						<div class="span10">
							<div class="row-fluid">
								<div class="span7">
									<div class="row-fluid">
										<div class="span4">
											<div class="row-fluid">
												<div class="span12">
													<label class="flight_label depart_icon">
														<span>
															<?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_ROW_DEPARTURE',JFactory::getDate($row->start)->format('H:i'),$row->fromcode); ?>	
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
										<div class="span4">
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
														<?php 
															if ($row->stops == 0) {
																echo JText::_('Non Stop');
																
															}else{
																echo $row->stops;
															}
														?>
														<?php  ?>
													</label>
													
												</div>
											</div>
										</div>
										<div class="span4">
											<div class="row-fluid">
												<div class="span12">
													<label class="flight_label"><?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_ROW_ARRIVAL',JFactory::getDate($row->end)->format('H:i'),$row->tocode); ?>
												
													</label>
												</div>
											</div>
											<div class="row-fluid">
												<div class="span12">
													<label class="flight_label"><?php echo $row->toName; ?></label>
													
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="span5">
									
										<?php 
										$data->rates=$row->rates;
										
										$data->flight=$row;
										$data->field="rate_id";
										$data->rate_id = $cart->rate_id;
										$layout = new JLayoutFile('rates', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');
										$html = $layout->render($data);
										echo $html;
							
										?>	
									
								</div>
							</div>
						</div>
						
						
						
						
					</div>
					<div class="row-fluid row-flight-footer">
						<div class="span12">
							<div class="span8">
								<div class="airline_name">
								<?php echo $row->airline_name; ?>
								</div>
							</div>
							<div class="span4">
								<div class="row-fluid">
									<div class="span6 flight_detail">
										
											<a href="#">FLIGHT DETAILS<i class="icon-flight-detail"></i></a>
										
									</div>
									
									<div class="span6 fare_rule">
										
											<a href="#">BUGGAGE TIPS<i class="icon-flight-detail"></i></a>
										
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

