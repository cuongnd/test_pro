<style>
    .title_table{
        background:none!important;
        color:#000;
        text-transform:uppercase;
        border:1px solid #cccccc;
    }
    .table_bottom{
        background:#eeeeee;
        margin-top:2px;
        text-align: right;
        padding-right: 20px;
        margin-bottom: 5px;
    }
    .enter_promo{
        color:#990000;
        text-transform:uppercase;
        padding-left:8px;
    }
    .input_go{
        width:10%;
        border:1px solid #9c9c9c;
        height:15px!important;
        margin:5px!important;
    }
    .passenger_title{
        color:#990000;
        border-bottom:1px solid #cccccc;
        margin-top:10px;
    }
</style>
<?php
$person = $this->cart->person;
$listadditionnaltrip = JArrayHelper::pivot($this->list_addone, 'id');
$k = 0;
$a_key_persons = array(
    'adult' => 'adult',
    'teenner' => 'teenner',
    'children' => 'children'
);
$reset_tour_title = reset($this->list_destination_of_tour)->title;
$end_tour_title = end($this->list_destination_of_tour)->title;
if (count($person))
    $this->sum = 0;
foreach ($person as $key_person => $a_person) {
    if (!in_array($key_person, $a_key_persons, true)) {

        continue;
    }
    for ($i = 0; $i < count($a_person); $i++) {
        $passenger = $a_person[$i];
        $fullname = $passenger->firstname . ' ' . $passenger->lastname
        ?>
        <div  class="passenger_form row-fluid">
            <input type="hidden" name="key_sec_person" value="<?php echo $key_person . ':' . $i ?>">
            <h4 class="passenger_title"><?php echo JText::_('PASSENGER') ?>&nbsp;<?php echo++$this->inteval_i ?>:<?php echo $fullname ?></h4>
            <div class="row-fluid" style="margin:0px;">

                <table style="width: 100%; ">
                    <tr>
                        <th style="border:none" class="title_table">
                            <?php echo JText::_('COM_BOOKPRO_SERVICE_DETAIL') ?>
                        </th>
                        <td style="border:none">
                        </td>
                        <td style="border:none"><?php echo JText::_('COM_BOOKPRO_SERVICE_PRICE') ?></td>
                    </tr>
                    <tr>
                        <th class="title_table">
                            <?php echo JText::_('COM_BOOKPRO_PACKAGE_TOUR') ?>
                        </th>
                        <td><?php
                            $roomtype_id = $passenger->roombooking->roomtype_id;
                            echo $this->tour->title . ' trip from  ' . DateHelper::formatDate($this->cart->checkin_date) . ' to ' . DateHelper::formatDate($this->cart->checkout_date) . ' include ' . $this->pivot_listroomtype[$roomtype_id]->title;
                            ?>
                        </td>
                        <td><?php echo CurrencyHelper::formatprice($passenger->priceroomselect); ?></td>
                    </tr>
                    <tr>
                        <th class="title_table">
                            <?php echo JText::_('COM_BOOKPRO_EXTRA_SERVICE') ?>
                        </th>
                        <td><?php
                            $list_extra = array();
                            $pre_roomtype = $passenger->pre_trip_acommodaton->roomtype;
                            $pre_roomtype = explode(':', $pre_roomtype);
                            $post_roomtype = $passenger->post_trip_acommodaton->roomtype;
                            $post_roomtype = explode(':', $post_roomtype);
                            //echo "<pre>";
                            // print_r($passenger->pre_trip_acommodaton);

                            if ($passenger->pre_trip_acommodaton) {
                                $checkin = DateHelper::formatDate(JFactory::getDate($passenger->pre_trip_acommodaton->checkin));
                                $checkout = DateHelper::formatDate(JFactory::getDate($passenger->pre_trip_acommodaton->checkout));
                                $night = $passenger->pre_trip_acommodaton->interval->days;
                                $room_title = $listroomtype[$pre_roomtype[0]]->title;
                                $list_extra[] = "pre tour hotel in $reset_tour_title: $room_title room for $night night  from $checkin to $checkout";
                            }
                            if ($passenger->post_trip_acommodaton) {
                                $checkin = DateHelper::formatDate(JFactory::getDate($passenger->post_trip_acommodaton->checkin));
                                $checkout = DateHelper::formatDate(JFactory::getDate($passenger->post_trip_acommodaton->checkout));
                                $night = $passenger->post_trip_acommodaton->interval->days;
                                $room_title = $listroomtype[$pre_roomtype[0]]->title;
                                $list_extra[] = "post tour hotel in $end_tour_title: $room_title room for $night night  from $checkin to $checkout";
                            }
                            if ($passenger->post_airport_transfer) {
                                $post_airport_transfer = $this->cart->post_airport_transfer->{$key_person . ':' . $i};
                                $post_airport_transfer_datetime = DateHelper::formatDate(JFactory::getDate($post_airport_transfer->flight_arrival_date_time), 'l, j F Y h:i A');
                                $list_extra[] = 'post transfer airport-hote in ' . $post_airport_transfer_datetime;
                            }

                            if ($passenger->pre_airport_transfer) {
                                $pre_airport_transfer = $this->cart->pre_airport_transfer->{$key_person . ':' . $i};
                                $pre_airport_transfer_datetime = DateHelper::formatDate(JFactory::getDate($pre_airport_transfer->flight_arrival_date_time), 'l, j F Y h:i A');
                                $list_extra[] = 'pre transfer hote-airport in ' . $pre_airport_transfer_datetime;
                            }
                            $list_additionnaltrip_title = array();

                            if (count($passenger->additionnaltrip_ids))
                                foreach ($passenger->additionnaltrip_ids as $additionnaltrip_id) {

                                    $list_additionnaltrip_title[] = $listadditionnaltrip[$additionnaltrip_id->addon_id]->title;
                                }

                            $list_additionnaltrip_title = implode(',', $list_additionnaltrip_title);
                            if ($list_additionnaltrip_title)
                                $list_extra[] = $list_additionnaltrip_title ? 'Additional activities:' . $list_additionnaltrip_title : '';

                            $list_extra = '<span class="list_extra"><span>' . implode('</span>+<span>', $list_extra) . '</span></span>';
                            echo $list_extra;
                            ?>
                        </td>
                        <td><?php
                            $total = 0;
                            $total+=$passenger->post_trip_acommodaton->price;
                            $total+=$passenger->pre_trip_acommodaton->price;
                            $total+=$passenger->post_airport_transfer->price;
                            $total+=$passenger->pre_airport_transfer->price;
                            if (count($passenger->additionnaltrip_ids)) {
                                foreach ($passenger->additionnaltrip_ids as $additionnaltrip) {
                                    $total+=$additionnaltrip->price;
                                }
                            }
                            ?>
                            <?php echo CurrencyHelper::formatprice($total); ?></td>
                    </tr>
                </table>
                <div class="table_bottom">
                    <div style="float: left;padding: 6px;">
                        <span class="enter_promo"><?php echo JText::_('COM_BOOKPRO_ENTER_PROMO_OR_BONUS_CODE') ?></span>
                    </div>
                    <span><input class="input_go" type="text" name="discount"/></span>
                    <span class="enter_promo">
                        <input class="btn btn_gone" type="button"  name="go" value="GO">
                        <?php
                        $this->sum+=$passenger->priceroomselect + $total;
                        ?>
                        <?php echo JText::_('COM_BOOKPRO_SUBTOTAL') ?>
                        <span class="total_discount"><?php echo $passenger->total_discount != 0 ? CurrencyHelper::formatprice($passenger->total - $passenger->total_discount) : '' ?></span>
                        <span class="total_price_person <?php echo $passenger->total_discount != 0 ? ' discount ' : '' ?>"> <?php echo CurrencyHelper::formatprice($passenger->total); ?></span>
                    </span>
                </div>





            </div>

        </div>

        <?php
        $k++;
    }
}
?>



