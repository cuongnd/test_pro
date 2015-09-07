<?php
$config = AFactory::getConfig ();
AImporter::helper('currency','date');
?>
<div class="hotel_company">

	<div>
		<h3><?php echo JText::_('COM_BOOKPRO_COMPANY_NAME')?></h3>
		<div><?php echo JText::_('COM_BOOKPRO_COMPANY_ADDRESS')?></div>
		<div><?php echo JText::_('COM_BOOKPRO_TEL')?> : <?php echo JText::_('COM_BOOKPRO_COMPANY_TELL')?>, <?php echo JText::_('COM_BOOKPRO_FAX')?> :<?php echo JText::_('COM_BOOKPRO_COMPANY_FAX')?></div>
		<div><?php echo JText::_('COM_BOOKPRO_EMAIL')?> : <?php echo JText::_('COM_BOOKPRO_COMPANY_EMAIL')?> <?php echo JText::_('COM_BOOKPRO_WEBSITE')?> : <?php echo JText::_('COM_BOOKPRO_COMPANY_WEBSITE')?></div>
	</div>
	<h4 style="text-align: center;"><?php echo JText::_('COM_BOOKPRO_BOOKING_ADDONE_COMFIRM')?></h4>


	<div style="width: 100%; float: left; padding: 10px; border: 1px solid;">
		<div><?php echo JText::_('COM_BOOKPRO_BOOKING_ADDONE_COMFIRM_ALERT')?></div>
		<div><h4><?php echo JText::_('COM_BOOKPRO_PARTY_NAME')?> : <b><?php echo $this->passenger->firstname.' '. $this->passenger->lastname; ?></b></h4></div>
		<div><?php echo JText::_('COM_BOOKPRO_EMAIL')?>:<?php echo $this->passenger->email; ?></div>
		<div><?php echo JText::_('COM_BOOKPRO_ORDER_ADDON_SERVICE')?>:<?php echo $this->addition->title?></div>
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