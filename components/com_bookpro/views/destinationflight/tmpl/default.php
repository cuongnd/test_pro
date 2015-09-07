<?php
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/css/destination.css'); 
$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/css/flight.css');
$tours = $this->tours;

JHtml::_('behavior.framework');
JHtmlBehavior::modal('a.modal_tour');

?>
<div class="row-fluid">
	<div class="span8 col_left">
		<div class="content_oveview_tour">
			<div style="background: #ecf2f5;">
				<p class="div1_title_col_left text-left">
					Find Great Deals in
					<?php echo $this->dest->title ?>
				</p>
				<p class="div2_title_col_left text-right">
				<?php echo JText::sprintf('COM_BOOKPRO_DESTINATION_FLIGHT_COUNT',count($this->destos)); ?>
				</p>
			</div>
			<img src="images/flight/flight_city.png" class="image-full">
			<div class="overview_tour destination-info">
				<div class="row-fluid">
					<div class="span12">
						<div class="title text-right" align="right">
							<?php echo JText::sprintf('COM_BOOKPRO_DEST_TITLE_COUNTRY',$this->dest->title,$this->dest->country->country_name)?>
						</div>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span9 ">
						<div class="content_title_col_left">
							
							<div class="city-desc">
								<?php echo $this->dest->intro ?>
							</div>
						</div>
						<div class="Popular_tours">
							<div class="content_title_popular_tours text-left">
								<div class="row-fluid div1_popular_tour">
									<div class="pull-left city-title flight-city-title">
										<?php echo JText::sprintf('COM_BOOKPRO_DESTINATION_FLIGHT_FROM_CITY',$this->dest->title) ?>
									</div>
									<div class="pull-right city-map">
										 <a
					                        href="index.php?option=com_bookpro&task=displaymap&tmpl=component&dest_id=<?php echo $this->dest->id ?>"
					                        class='modal_tour'
					                        rel="{handler: 'iframe', size: {x: 570, y: 530}}"><sup>+</sup>&nbsp;<?php echo JText::_("COM_BOOKPRO_VIEW_CITY_MAP")?>
					                    </a>
									</div>
								</div>
								
								<?php
									 echo $this->loadTemplate('flights');
								?>
							</div>

						</div>
					</div>
					<div class="span3 content_flight_vacation_tips">
						<div class="vacation_tip">
						<?php echo $this->loadTemplate('vacation'); ?>
						</div>
					</div>
				</div>
			</div>

		</div>
		<div class="banner_center" style="padding-top:10px;padding-bottom:10px">
			<img class="image-full" src="images/banner-center.jpg">
		</div>
		<div class="row-fluid">
			<div class="span6">
				<?php 
				
				$layout = new JLayoutFile('top_flight_city', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');
				
				$html = $layout->render($this->dest);
				
				echo $html;
				?>
			</div>
			<div class="span6">
				<div class="flight_airline_city">
						<h3 class="flight_airline_title">
							<?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_DEST_AIRLINE_SERVING',$this->dest->title) ?>
						</h3>
						<p>
							<?php echo $this->dest->air_carries; ?>
						</p>
						<div class="airline_city_serving">
						<div class="title_asian_air">
							<i class="icon_flight_title"></i>
							<?php echo JText::sprintf('COM_BOOKPRO_DEST_ASIAN_AIR_CARRIES',$this->dest->title); ?>
							
						</div>
						<p>
							<?php echo $this->dest->asian_air; ?>
						</p>
						<div class="title_asian_air">
							<i class="icon_flight_title"></i>
							<?php echo JText::sprintf('COM_BOOKPRO_DEST_WORLDWIDE_AIR_CARRIES',$this->dest->title); ?>
						</div>
						<?php echo $this->dest->worldwide_air; ?>
						</div>
				</div>
			</div>
		</div>
	</div>
	<div class="span4 col_right">
		
			<?php 
					$flights = FlightHelper::getFlightByCountry($this->dest->country_id);
					$layout = new JLayoutFile('flight_option', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');
					$html = $layout->render($flights);
					echo $html;
					?>
			
			
			<?php //echo $this->loadTemplate('right'); ?>

	</div>
</div>