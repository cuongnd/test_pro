	<?php
	 
	AImporter::helper('image','currency','tour');
	AImporter::css('country');
	AImporter::model('country');
	AImporter::helper('flight');
	$app = JFactory::getApplication();
	$input = $app->input;
	$country_id = $input->get('country_id',0,'int');
	
	$country = TourHelper::getCountryObject($country_id);
	$document = JFactory::getDocument();
	$document->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/flight.css');
	
	?>

	<div class="row-fluid">
		<div class="span8 col_left">
			<img style="height:265px;" class="image-full" src="images/country-flight.png">
			<div class="package_tour">
				<div class="row-fluid">
					
					<?php 
					$layout = new JLayoutFile('country_article', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');
					$html = $layout->render($country_id);
					echo $html;
					?>
				</div>
			</div>
			
			<div class="top_destinations">
				<div style="padding-bottom:10px;">
					<span class="title_destinations text-left">Top Tour Destinations</span>
					<div style="border-bottom: 4px solid #cdcdcd;position:relative; top:-13px;"></div>
				</div>
				<div class="row-fluid">
					<div class="span6">
						<?php echo $this->loadTemplate('destinations') ?>
					</div>
					<div class="span6">
						<img src="images/map-1.jpg">
					</div>
				</div>
			</div>
			
			<div class="banner_center" style="padding-top:10px;">
				<img class="image-full" src="images/banner-center.jpg">
			</div>
			<div class="row-fluid">
				<div class="span5">
					<div class="flight_airline_box">
						<h3 class="flight_airline_title" align="right">
							<?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_FIND_BEST_AIRLINE',$this->country->country_name) ?>
						</h3>
						<?php 
						$layout = new JLayoutFile('best_airline', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');
						
						$html = $layout->render($this->country);
						
						echo $html;
						?>
					</div>
				</div>
				<div class="span7">
					<div class="country_carriers">
					<h3 class="flight_airline_title" align="left">
						<?php echo JText::sprintf('COM_BOOKPRO_FLIGHT_AIRLINE_CARRIES',$this->country->country_name) ?>
					</h3>
					<?php 
					$layout = new JLayoutFile('country_carriers', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');
					$html = $layout->render($this->country);
					echo $html;
					?>
					</div>
				</div>
			</div>
		</div>
		
		<div class="span4 col_right">
			<?php 
			
			
			$flights = FlightHelper::getFlightByCountry($country_id);
			
			$layout = new JLayoutFile('flight_option', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');
			$html = $layout->render($flights);
			echo $html;
					?>
			
			<?php 
					
				?>
		</div>
	</div>