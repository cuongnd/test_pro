<?php
$config = AFactory::getConfig ();
AImporter::helper('currency','date');
?>
<div class="hotel_company">
	<div style="float: left; width: 50%">
		<h3><?php echo $this->hotel->title?></h3>
		<div><?php echo $this->hotel->address1?></div>
		<div><?php echo JText::_('COM_BOOKPRO_TEL')?> : <?php echo $this->hotel->t?>, <?php echo JText::_('COM_BOOKPRO_FAX')?> :</div>
		<div><?php echo JText::_('COM_BOOKPRO_EMAIL')?> : <?php echo $this->hotel->email?> <?php echo JText::_('COM_BOOKPRO_WEBSITE')?> :	<?php echo $this->hotel->website?></div>
	</div>
	<div>
		<h3><?php echo JText::_('COM_BOOKPRO_COMPANY_NAME')?></h3>
		<div><?php echo JText::_('COM_BOOKPRO_COMPANY_ADDRESS')?></div>
		<div><?php echo JText::_('COM_BOOKPRO_TEL')?> : <?php echo JText::_('COM_BOOKPRO_COMPANY_TELL')?>, <?php echo JText::_('COM_BOOKPRO_FAX')?> :<?php echo JText::_('COM_BOOKPRO_COMPANY_FAX')?></div>
		<div><?php echo JText::_('COM_BOOKPRO_EMAIL')?> : <?php echo JText::_('COM_BOOKPRO_COMPANY_EMAIL')?> <?php echo JText::_('COM_BOOKPRO_WEBSITE')?> : <?php echo JText::_('COM_BOOKPRO_COMPANY_WEBSITE')?></div>
	</div>
	<h4 style="text-align: center;"><?php echo JText::_('COM_BOOKPRO_HOTEL_BOOKING_COMFIRM')?></h4>


	<div
		style="width: 100%; float: left; padding: 10px; border: 1px solid;">
		<div><?php echo JText::_('COM_BOOKPRO_BOOKING_COMFIRM_ALERT')?></div>
		<div><h4><?php echo JText::_('COM_BOOKPRO_PARTY_NAME')?> : <b><?php echo $this->passenger->firstname.' '. $this->passenger->lastname; ?></b></h4></div>
		<div><?php echo JText::_('COM_BOOKPRO_COUNTRY')?>:<?php echo $this->passenger->country_name; ?></div>
		<div><?php echo JText::_('COM_BOOKPRO_ADDRESS')?>:<?php echo $this->passenger->address; ?></div>
		<div><?php echo JText::_('COM_BOOKPRO_EMAIL')?>:<?php echo $this->passenger->email; ?></div>
	</div>
	<div style="width: 100%; float: left; margin-top: 10px">
		<div style="width: 10%; float: left;"><?php echo JText::_('COM_BOOKPRO_HOTEL')?></div>
		<div style="width: 80%; float: left;">
			<?php echo $this->hotel->title?><br />
			<?php echo JText::_('COM_BOOKPRO_ADDRESS')?> : <?php echo $this->hotel->address1?><br />
			<?php echo JText::_('COM_BOOKPRO_TEL')?> : <?php echo $this->hotel->tel?><br />
			<?php echo JText::_('COM_BOOKPRO_FAX')?> : <?php echo $this->hotel->fax?> <br />
			<?php echo JText::_('COM_BOOKPRO_EMAIL')?> : <?php echo $this->hotel->email?>
		</div>
	</div>


	<table class="table_Include booking-room">
		<tr>
			<th><?php echo JText::_('COM_BOOKPRO_ORDER_FROM_DATE')?></th>
			<th><?php echo JText::_('COM_BOOKPRO_ORDER_TO_DATE')?></th>
			<th><?php echo JText::_('COM_BOOKPRO_NIGHT')?></th>
			<th><?php echo $this->bookinginfo->roomtype_title?></th>

		</tr>
		<tr>
			<td><?php echo DateHelper::formatDate($this->bookinginfo->checkin)?></td>
			<td><?php echo DateHelper::formatDate($this->bookinginfo->checkout)?></td>
			<td><?php echo JFactory::getDate($this->bookinginfo->checkin)->diff(JFactory::getDate($this->bookinginfo->checkout))->days ?></td>
			<td>1</td>
		</tr>
	</table>

	<div style="width: 100%; float: left">
		<span style="font-weight: bold; font-size: 13px"><?php echo JText::_('COM_BOOKPRO_TOTAL_PRICE')?> : </span><?php echo CurrencyHelper::formatprice($this->bookinginfo->price)?>

	</div>
	<div>
		<span style="font-weight: bold; font-size: 13px"><?php echo JText::_('COM_BOOKPRO_TOTAL_NIGHT')?> : </span><?php echo JFactory::getDate($this->bookinginfo->checkin)->diff(JFactory::getDate($this->bookinginfo->checkout))->days ?>

	</div>
	<div><?php echo JText::_('COM_BOOKPRO_GUEST_IN_ROOM')?></div>
	<div>
		<span style="font-weight: bold; font-size: 13px"><?php echo JText::_('COM_BOOKPRO_GUEST_NAME')?> : </span><?php echo $this->passenger->firstname.' '. $this->passenger->lastname; ?>
	</div>

</div>
<style>
.hotel_company {
	width: 60%;
	margin: 0 auto;
}

.table_Include {

}

.table_Include td,.table_Include th {
	padding: 5px;
	border: 1px solid #ccc;
}

.table_Include th {
	background: none;
	color: #000;
}

.booking-room {
	margin: 10px 2px;
}
</style>