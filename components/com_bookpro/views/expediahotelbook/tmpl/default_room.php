<?php
AImporter::helper('hotel', 'date');
$numberday = DateHelper::getCountDay($this->cart->checkin_date, $this->cart->checkout_date);
$rooms = JArrayHelper::pivot($this->cart->hotel['HotelRoomResponse'], 'roomTypeCode');
$room = $rooms[$this->cart->room_type];
$start = new JDate($this->cart->checkin_date);

?>
<?php
$HotelRoomResponse=$this->cart->hotel['HotelRoomResponse'];
$HotelRoomResponse=$HotelRoomResponse[0]?$HotelRoomResponse:array($HotelRoomResponse);
$HotelRoomResponse=JArrayHelper::pivot($HotelRoomResponse,'roomTypeCode');
$room=$HotelRoomResponse[$this->cart->room_type];

$a_room = $room['RateInfos']['RateInfo']['RoomGroup']['Room'];
$a_room=$a_room[0]?$a_room:array($a_room);
$NightlyRate=$room['RateInfos']['RateInfo']['ChargeableRateInfo']['NightlyRatesPerRoom']['NightlyRate'];
$NightlyRate=$NightlyRate[0]?$NightlyRate:array($NightlyRate);
$total_room = count($a_room);
$count_day = DateHelper::getCountDay($this->cart->checkin_date, $this->cart->checkout_date);
?>
<h4><?php echo JText::_('COM_BOOKPRO_ROOM_BASIC') ?></h4>
<hr/>
<div class="row-fluid">
    <b><?php echo $total_room . ' ' . JText::_('Room') ?>: </b><?php echo $room['rateDescription'] ?>
</div>
<div class="row-fluid">
    <b><?php echo $count_day . ' ' . JText::_('Nighht') ?>
        : </b> <?php echo JFactory::getDate($this->cart->checkin_date)->format('d/M/Y'); ?>
    -<?php echo JFactory::getDate($this->cart->checkout_date)->format('d/M/Y'); ?>
</div>
<?php  ?>
<?php for ($i = 0; $i < count($a_room); $i++) { ?>
    <?php $room_item = $a_room[$i]; ?>
    <div class="row-fluid room-item">
        <div class="row-fluid"><div class="span6"><?php echo "room ".($i+1) ?>: </div><div class="span6"> <?php echo $room_item['numberOfAdults'].' adult' ?> <?php echo $room_item['numberOfChildren'].' children' ?></div></div>
        <div class="row-fluid"><div class="span6"><a data-toggle="collapse" data-target=".perDayPrices<?php echo $i ?>" class="h5 show-list-night toggle-link trigger expand" href="javascript:void(null);"> <?php echo $count_day." night" ?></a></div><div class="span6"><?php echo JText::_('averageRate/night') ?></br> <?php echo  CurrencyHelper::formatprice($room['RateInfos']['RateInfo']['ChargeableRateInfo']['@averageRate']) ?></div></div>
        <div  class=" perDayPrices<?php echo $i ?> collapse">
            <?php for($j=0;$j<count($NightlyRate);$j++){ ?>
            <div class="">
                <div class="span6"><?php echo JFactory::getDate($this->cart->checkin_date)->modify('+'.$j.' day')->format('d/M/Y') ?></div>
                <div class="span6"><?php echo CurrencyHelper::formatprice($NightlyRate[$j]['@rate']) ?></div>
            </div>
            <?php } ?>
        </div>
        <div>
            <div class="span6"><a href="#" rel="tooltip" data-placement="top" data-original-title="<?php echo JText::_('Rent & service charge anything? Taxes are taxes collected Expedia redundancy payments to service providers (such as hotels); To find out details, please see Terms of Use. We retain the service fee for booking service / booking your') ?>"><?php echo JText::_('Rental & Fees per night') ?><i class="icon icon-new-windows"></i></a></div>
            <div class="span6"><?php echo CurrencyHelper::formatprice($room['RateInfos']['RateInfo']['ChargeableRateInfo']['@surchargeTotal']) ?></div>
        </div>
    </div>
<?php } ?>
<div><div class="span6"><?php echo JText::_('Total price') ?></div><div class="span6"><h2><?php echo CurrencyHelper::formatprice($room['RateInfos']['RateInfo']['ChargeableRateInfo']['@total']) ?></h2></div></div>


<script>
    jQuery(document).ready(function($) {
//        $('a.show-list-night').each(function(){
//            roomitem=$(this).closest('.room-item');
//            console.log(roomitem.find('.perDayPrices').attr('class'));
//            $(this).collapse({
//                parent:'.perDayPrices'
//            });
//        });

    });

</script>