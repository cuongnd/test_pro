
<?php
AImporter::helper('flight');

$airports = FlightHelper::getAirportByDest($this->dest->id);

//$actis = TourHelper::getActivityByDest($this->dest->id);
//$type = TourHelper::getTypeListToursByDest($this->dest->id);
//$departures = TourHelper::getDepartureDate($this->dest->id);
?>
<p style="text-transform: uppercase; font-weight: bold; text-align:center; font-size:14px;padding-top:4px;">VACATION
							TIPS</p>
						<div class="vacation_tips text-left">
							<div class="content_ul">
								<?php foreach ($airports as $airport){ ?>
								<p
									style="color: #006699; font-weight: bold; padding-left: 10px;margin:0px;"><?php echo $airport->title ?></p>
								<ul>
									
									<li><a href="#"><?php echo JText::sprintf('COM_BOOKPRO_AIRPORT_CODE',$airport->code); ?></a></li>
									<li><a href="#"><?php echo JText::sprintf('COM_BOOKPRO_AIRPORT_LOCATION',$airport->location_airport); ?></a></li>
									<li><a href="#"><?php echo JText::sprintf('COM_BOOKPRO_AIRPORT_CAPACITY',$airport->capacity_airport); ?></a></li>
									<li><a href="#"><?php echo JText::sprintf('COM_BOOKPRO_AIRPORT_LODGING',$airport->airport_lodging); ?></a></li>
								</ul>
								<?php } ?>
								<p
									style="color: #006699; font-weight: bold; padding-left: 10px;margin:0px;">Tour activities</p>
								<ul>
									<?php foreach ($actis as $acti){ ?>
										<li><a href="#"><?php echo JText::sprintf('COM_BOOKPRO_VACATION_ACTIVITY',$acti->title,$acti->totaltour); ?></a></li>
									<?php } ?>
									
									
								</ul>
							</div>
							<div class="content_ul">
								<p
									style="color: #006699; font-weight: bold; padding-left: 10px;margin:0px;">Tour Type</p>
								<ul>
									<?php foreach ($type as $tp){ ?>
									<li><a href="#"><?php echo JText::sprintf('COM_BOOKPRO_VACATION_TYPE',$tp->stitle,$tp->total); ?></a></li>
									<?php } ?>
									
								</ul>
							</div>
							<div class="content_ul">
								<p
									style="color: #006699; font-weight: bold; padding-left: 10px; margin:0px;">Set Departure Date</p>
								<ul>
								<?php foreach ($departures as $key=>$departure){ ?>
									<li><a href="#"><?php echo $key ?>(<?php echo $departure->total; ?> tours)</a></li>
								<?php } ?>
																	</ul>
							</div>
						</div>
						<div class="pull-right"
							style="padding-bottom: 10px; padding-right: 10px; padding-top: 10px;">
							<img src="images/img_array.png">
						</div>