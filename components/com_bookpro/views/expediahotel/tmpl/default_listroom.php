<?php
AImporter::helper('image', 'expediaroom');
//echo "<pre>";
//print_r($this->hotel);
//die;
$rooms = $this->hotel['HotelRoomResponse'];
if (!$rooms[0])
    $rooms = array($rooms);

$cart = & JModelLegacy::getInstance('ExpediaHotelCart', 'bookpro');
$cart->load();

$numbernight = DateHelper::getCountDay($cart->checkin_date, $cart->checkout_date);
?>


<table class = "table table table-hover table-striped  table-condensed">

    <tbody>
    <?php if (count($rooms) > 0) { ?>
        <?php
        foreach ($rooms as $room) {

            $no_room = 0;
            if ($cart->room) {
                $no_room = $cart->room;
            } else {
                $no_room = $room->total;
            }
            $no_room = 3;
            $RateInfo = $room['RateInfos']['RateInfo'];
            ?>

            <tr class = "room_detail">
                <td style = "vertical-align: top; width: 20%">
                    <?php
                    $image = $room['RoomImages']['RoomImage']['url'];

                    if (getimagesize($image)) {
                        ?>
                        <a href = "#" title = "" class = "lightbox" rel = "lightbox" style = "position: relative;">

                            <img src = "<?php echo $image ?>" alt = ""/>

                        </a>
                    <?php } ?>


                </td>

                <td style = "vertical-align: top; line-height:20px;">
                    <h3 style = "margin:0px;padding:0px; line-height:20px;">  <?php echo $room['roomTypeDescription'] ?></h3>

                    <b><?php echo Jtext::_('COM_BOOKPRO_ROOM_MAX_PERSON') ?>:</b>
                    <?php
                    $a_rooms = $RateInfo['RoomGroup']['Room'];
                    if (!$a_rooms[0]) {
                        $a_rooms = array($a_rooms);
                    }
                    $numberofadults = $a_rooms[0]['numberOfAdults'];
                    if ($a_rooms[0]['numberOfAdults']) {
                        echo JText::sprintf('COM_BOOKPRO_ADULT_TXT', $a_rooms[0]['numberOfAdults']);
                    }
                    $numberofchildren = $a_rooms[0]['numberOfChildren'];
                    if ($a_rooms[0]['numberOfChildren']) {
                        echo JText::sprintf('COM_BOOKPRO_CHILD_TXT', $a_rooms[0]['numberOfChildren']);
                    }
                    ?>

                    <div class = "facilities_room">
                        <?php
                        $layout = new JLayoutFile('expediafacilitytext', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');
                        $html = $layout->render($room['ValueAdds']['ValueAdd']);
                        echo $html;
                        ?>
                    </div>


                    <div>

                        <?php echo $room['descriptionLong'] ?>
                    </div>

                </td>

                <td>
                    <?php

                    if ($room['RateInfos']['RateInfo']['nonRefundable'] != 1) {

                        $CancelPolicyInfo1 = $room['RateInfos']['RateInfo']['CancelPolicyInfoList']['CancelPolicyInfo'][1];

                        $cancelcheckin = JFactory::getDate($this->cart->checkin_date)->modify('-' . $CancelPolicyInfo1['startWindowHours'] . ' hour');
                        ?>
                        <?php //echo "<pre>"; print_r($room['RateInfos']); echo "</pre>" ?>
                        <a data-content="And here's some amazing content. It's very engaging. right?" title="" data-toggle="popover" class="btn btn-large btn-danger" href="#" data-original-title="A Title">Click to toggle popover</a>
                        <a href = "#" class = "link cancel-checkin" data-container = "body" data-toggle = "popover" data-placement = "center" data-content = "<?php echo $room['RateInfos']['RateInfo']['cancellationPolicy'] ?>">
                            <?php echo JText::sprintf('COM_BOOKPRO_EXPEDIA_FREE_CANCELLATION_BEFORE',$cancelcheckin->format('l m/d/Y')); ?>
                        </a>
                    <?php } ?>
                    <?php if ($room['RateInfos']['RateInfo']['nonRefundable'] == 1) {
                        ?>
                        <div><?php echo JText::_('COM_BOOKPRO_EXPEDIA_NON_REFUND_ABLE') ?></div>
                    <?php } ?>
                </td>
                <td class = "price" style = "text-align: right">
                    <b><?php echo JText::sprintf('COM_BOOKPRO_ROOM_AVAILABEL_PRICE', CurrencyHelper::formatprice($RateInfo['ChargeableRateInfo']['@total'],null,$this->cart->currency_code), $numbernight) ?></b>
                    <br/>
                    <a href = "index.php?option=com_bookpro&controller=expediahotel&task=guestform&roomtypecode=<?php echo $room['roomTypeCode'] ?>&hotel_id=<?php echo $this->hotel['hotelId'] ?>" class = "btn btn-primary"><?php echo JText::_('Book') ?></a>
                </td>


            </tr>

        <?php
        }
    } else {
        ?>
        <tr>
            <td colspan = "3"><?php echo JText::_('COM_BOOKPRO_ROOM_UNAVAILABLE') ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>

</table>
