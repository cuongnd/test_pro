<?php 
	JHtml::_('jquery.framework');
	JHtml::_('jquery.ui');
	$doc=JFactory::getDocument();
	$doc->addScript(JURI::root().'components/com_bookpro/assets/js/ui111n/jquery.easing-1.3.js');
	$doc->addScript(JURI::root().'components/com_bookpro/assets/js/ui111n/jquery.mousewheel-3.1.12.js');
	$doc->addScript(JURI::root().'components/com_bookpro/assets/js/ui111n/jquery.jcarousellite.js');
	$doc->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/jcarousellite.css');
	AImporter::css('flight');
	$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
	$cart->load();
	
	AImporter::helper('flight');
	$airlines = FlightHelper::getAirlineBySearch();
	
	
	
?>
<div class="span12">
					<h3 class="title">FLIGHT SEARCH</h3>
					
					<div class="modspecial">
						<div class="special-title">
							<div class="row-fluid">
								<div class="span9">
									<h3 class="airline-title">
										<i class="icon-special"></i>
										<?php echo JText::_('COM_BOOKPRO_FLIGHT_AIRLINE_SPECIAL') ?>
									</h3>
									
								</div>
								<div class="span3">
									<div class="pull-right">
										<div class="change-currency">
										
											<?php echo JText::_('COM_BOOKPRO_FLIGHT_SPECIAL_CHANGE_CURRY') ?>
											
											<i class="icon-currency"></i>
										</div>
									</div>
									
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span3" >
								<div id="all_airline" class="air_block" align="center">
									
								</div>
							</div>
							
							<div class="span9">
								<div class="default-special">
									
									<div class="carousel">
							            <ul>
							            	<?php foreach ($airlines as $airline){ ?>
							            	<li style="width:<?php echo 100/3; ?>%">
							            		<div class="air_block" align="center">
							            			<div class="air_image">
							            				<img src="<?php echo $airline->image; ?>" style="width:100px;height: 50px;" />
							            			</div>
								            		
								            		<div class="air-title"><?php echo $airline->title; ?></div>
								            		<div class="special-price"><?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_SPECIAL_PRICE',CurrencyHelper::displayPrice($airline->min_price)) ?></div>
							            		</div>
							            	</li>
							            	<?php } ?>
							            	
							                
							            </ul>
							        </div>
									 
								</div>
							</div>
						</div>
						<div class="row-fluid">
							<div class="span12 special-footer" align="right">
								<a href="#" class="next"><?php echo JText::_('COM_BOOKPRO_FLIGHT_SPECIAL_NEXT') ?></a>
								
							</div>
						</div>
						<script type="text/javascript">
						jQuery(document).ready(function($) {
							$(".default-special .carousel").jCarouselLite({
				                btnNext: ".next"
				            });
						})
						</script>
						
						
					</div>
					</div>