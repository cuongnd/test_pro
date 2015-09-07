<?php
AImporter::helper('hotel')
?>
<fieldset>
<legend><?php echo JText::_('COM_BOOKPRO_BOOKINGS') ?></legend>
	<form name="tourOrder" action="index.php">
	
			
	<?php 
	if(count($this->orders)>0) {
						
	foreach ($this->orders as $key=>$order) {
		$hotel = HotelHelper::getObjectHotelByOrder($order->id);
		$infos = $order->infos;
		$class = "";
		if (count($this->orders) > 1 && $key < count($this->orders) -1) {
			$class="row-border-bottom";
		}
	?>
		<div class="row-fluid">
			<div class="span8">
				<div class="row-fluid <?php echo $class; ?>">
					<div class="span12">
						<h3>
							<?php 
							echo JHtml::link(JURI::root().'index.php?option=com_bookpro&controller=order&task=detail&order_id='.$order->id, $hotel->title,'class="btn-link"');
							?>
						</h3>
						<p>
							<?php 
/*							$city=BookProHelper::getObjectAddress($hotel->city_id);
							echo $hotel->address1.', '. $city->title.', '.$city->country   */
                            echo $hotel->address1;
							?>
							<?php echo HotelHelper::displayHotelMap($hotel->id) ?>
						</p>
					</div>
				</div>
				<div class="row-fluid">
					<div class="span12">
						<div class="row-fluid">
							<div class="span3">
								<?php echo JText::_('Booking details') ?>
							</div>
							<div class="span9">
								<div class="row-fluid">
									<div class="span4">
										<label><?php echo JText::_('Booking number:') ?></label>
									</div>
									<div class="span8 pull-right">
										<?php echo $order->order_number; ?>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span4">
										<?php echo JText::_('Check-in:') ?>
									</div>
									<div class="span8 pull-right">
										<?php echo DateHelper::formatDate($infos[0]->start,'DATE_FORMAT_LC'); ?>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span4">
										<?php echo JText::_('Check-out:') ?>
									</div>
									<div class="span8 pull-right">
										<?php echo DateHelper::formatDate($infos[0]->end,'DATE_FORMAT_LC'); ?>
									</div>
								</div>
								<div class="row-fluid">
									<div class="span4"><?php echo JText::_('Number of nights:') ?></div>
									<div class="span8">
										<?php 
											$start = new JDate($infos[0]->start);
											$end = new JDate($infos[0]->end);
											$days = $start->diff($end);
											echo JText::sprintf('COM_BOOKPRO_NUMBER_NIGHTS',$days->days);
										?>
									</div>
								</div>
							</div>
						</div>
						<div class="fow-fluid">
							<div class="span3"><?php echo JText::_('Booking updates') ?></div>
							<div class="span9">
								<div class="row-fluid">
									<div class="span4">
										<?php echo DateHelper::formatDate($order->created,'d M Y'); ?>
									</div>
									<div class="span8">
										
									</div>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<div class="span4">
				<a href="#" class="btn btn-primary">
					<i class="icon-trash"></i>
					<?php echo JText::_('Remove from list') ?>
				</a>
			</div>
		</div>
				
				<?php } 
				}
				else {

					echo JText::_('COM_BOOKPRO_CUSTOMER_NO_BOOKINGS');

					?>
				
				<?php 
				}
				?>
			
		
	
	</form>
</fieldset>