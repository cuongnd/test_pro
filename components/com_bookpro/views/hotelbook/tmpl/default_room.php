<?php
AImporter::helper('hotel','date');
$numberday =  DateHelper::getCountDay($this->cart->checkin_date,$this->cart->checkout_date);

$start = new JDate($this->cart->checkin_date);
?>

<h4><?php echo JText::_('COM_BOOKPRO_ROOM_BASIC') ?></h4>
<hr/>	
   <div class="row-fluid">
			<div class="span6 form-inline">
				<label><b><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKIN') ?></b></label>
				<label>
					<?php echo DateHelper::formatDate($this->cart->checkin_date);
							?>
				</label>
			</div>
			<div class="span6 form-inline">
				<label>
					<b><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKOUT') ?></b>
				</label>
				<label>
				<?php 	echo DateHelper::formatDate($this->cart->checkout_date); ?>
				</label>
			</div>
		</div>	
		
		<div class="row-fluid">
			<div class="span3 form-inline">
				<label>
					<b><?php echo JText::_('COM_BOOKPRO_NIGHT_NUMBER') ?>:</b>
				</label>
				<label><?php echo DateHelper::getCountDay($this->cart->checkin_date,$this->cart->checkout_date) ?></label>
			</div>
			<div class="span3 form-inline">
				<label>
					<b><?php echo JText::_('COM_BOOKPRO_ROOM_TOTAL') ?>:</b>
				</label>
				<label><?php echo $this->cart->total_room; ?></label>
			</div>
		</div>
		

		
<h4><?php echo JText::_('COM_BOOKPRO_ROOM_DETAIL') ?></h4>	
<hr/>	
<table class="table table-hover">
	<thead>
		<tr>
			<th><?php echo JText::_('COM_BOOKPRO_DATE') ?></th>
			<th><?php echo JText::_('COM_BOOKPRO_ROOM_DETAIL') ?></th>
			
			<th><?php echo JText::_('Adult') ?></th>
			<th><?php echo JText::_('Child') ?></th>
			<th><?php echo JText::_('COM_BOOKPRO_ROOM_PRICE'); ?></th>
			<th class="text-right"><?php echo JText::_('COM_BOOKPRO_ROOM_TOTAL_PRICE'); ?></th>
		</tr>
		
	</thead>
	<tbody>
    
		<?php if(!empty($this->rooms)){ ?><tr>
			<?php foreach ($this->rooms as $room){
				
				?>
			<?php for($i = 0;$i < $numberday;$i++){
				
				$dStart = clone $start;
				$date = $dStart->add(new DateInterval('P'.$i.'D'));
				$date = JFactory::getDate($date)->format('d-m-Y',true);
				$dateprice =(int) HotelHelper::getRoomRatePriceDate($room->id, $date);
				
				$totalprice = $dateprice*$room->no_room + $room->total_adult_price+ $room->total_child_price;
				
				?>
				<tr>
					<td><?php echo DateHelper::formatDate($date); ?></td>
					<td><?php echo $room->title; ?></td>
					<td class="text-center"><?php echo $room->total_adult; ?></td>
					<td class="text-center"><?php echo $room->total_child; ?></td>
					<td><?php echo CurrencyHelper::formatprice($dateprice); ?></td>
					<td class="text-right"><?php echo CurrencyHelper::formatprice($totalprice); ?></td>
				</tr>
			<?php } ?>
			<?php } ?>
		<?php } ?>
		<?php if(!empty($this->facilities)){ ?>
		<tr>
			<td colspan="4" class="text-right">
				<b>
				<?php echo JText::_('COM_BOOKPRO_FACILITY'); ?>:
				</b>
			</td>
			<td colspan="2" align="right" class="text-right">
				
				
				<?php foreach ($this->facilities as $fac){ ?>
				<?php 
					echo $fac->title.'&nbsp;'.CurrencyHelper::formatprice($fac->price).'<br>';
				?>
				
				<?php } ?>
				
			</td>
		</tr>
		<?php } ?>	
				
		
		<tr>
			<td colspan="5" class="text-right">
				<b>
					<?php echo JText::_('TOTAL AMOUNT + TAX') ?>:
				</b>
			</td>
			<td class="text-right">
				<?php echo CurrencyHelper::formatprice($this->cart->total); ?>
			</td>
		</tr>
	</tbody>
</table>