<?php
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/css/destination.css'); 
$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/css/flight.css');



JHtml::_('behavior.framework');
JHtmlBehavior::modal('a.modal_tour');
AImporter::model('flightroutes');
?>
<div class="row-fluid">
	<div class="span8 col_left">
		<div class="span12">
			<div class="content_oveview_tour">				
					<?php 
						$layout = new JLayoutFile('airline_route', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');						
						$html = $layout->render(null);					
						echo $html;
					?>
			</div>
		</div>
	
		<div class="span12">
			 <div class="row-fluid">
				<div class="title">
					<b>Did You Know ?</b>
				</div>	 
				<div class="span8">					
					<?php 
						$desfrom=JFactory::getApplication()->input->get('desfrom', 0);
						$desto=JFactory::getApplication()->input->get('desto', 0);					
						$model= new BookproModelFlightroutes();
						$data=$model->getflightroute($desfrom,$desto);						
					?>
					<?php foreach ($data as $item):?>
						<?php echo $item->desc;?>
					<?php endforeach;?>
				</div>
				
				<div class="span4">
					<h3>VACATION TIPS</h3>
				</div>
			</div>
		</div>
		
		<div class="banner_center" style="padding-top:10px;padding-bottom:10px">
			<img class="image-full" src="images/banner-center.jpg">
		</div>
		<div class="row-fluid">
			<div class="span12">
				<div class="flight_route_list">
					<?php 
					
					AImporter::helper('flight');
					$input = JFactory::getApplication()->input;
					$desfrom = $input->get('desfrom',0);
					$destto = $input->get('desto',0);
					$date = JFactory::getDate('now')->format('d-m-Y');
					
					$lists = array('desfrom'=>$desfrom,'desto'=>$destto,'depart_date'=>$date);
					
					AImporter::model('flights');
					$model = new BookProModelFlights();
					$model->init($lists);
					
					$routeflights = $model->getData();
					
					$data = new stdClass();
					$data->total = $model->getTotal();
					$data->routeflights = $routeflights;
					$data->desfrom = $desfrom;
					$data->destto = $desto;
					
					//$routeflights = FlightHelper::getFlightSearch($list);
				//var_dump(count($routeflights));
					
					
					$layout = new JLayoutFile('flight_route_list', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');
					
					$html = $layout->render($data);
					
					echo $html;
					?>
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
