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
AImporter::model('addons');
$modeltouraddone = new BookProModelAddons();
$listadditionnaltrip = $modeltouraddone->getItems();
$listadditionnaltrip = JArrayHelper::pivot($listadditionnaltrip, 'id');
$reset_destination_of_tour_title = reset($this->list_destination_of_tour)->title;
$end_destination_of_tour_title = end($this->list_destination_of_tour)->title;
$k = 0;

foreach ($this->pivot_passengers as $passenger) {

    $fullname = $passenger->firstname . ' ' . $passenger->lastname
    ?>
    <div  class="passenger_form row-fluid">
        <h4 class="passenger_title"><?php echo JText::_('PASSENGER') ?>&nbsp;<?php echo++$this->inteval_i ?>:<?php echo $fullname ?></h4>
        <div class="row-fluid" style="margin:0px;">

            <h5><?php echo JText::_('COM_BOOKPRO_PASSENGER_DETAIL') ?></h5>
            <table style="width: 100%; border:1px solid #ccc;">
                <tr>
                    <th class="title_table">
                        <?php echo JText::_('COM_BOOKPRO_PACKAGE_TOUR') ?>
                    </th>
                    <td><?php
                        $tour_title = $this->tour->title;
                        $checkin = DateHelper::formatDate($this->info->start);
                        $checkout = DateHelper::formatDate($this->info->end);
                       
                         $roomtype_id = $this->pivot_bookroom[$passenger->id]->roomtype_id;
                        
                        $roomtype_title = $this->pivot_listroomtype[$roomtype_id]->title;

                        echo "$tour_title trip from  $checkin to $checkout include $roomtype_title";
                        ?>
                    </td>
                    <td><?php echo CurrencyHelper::formatprice($this->listtourpassenger[$passenger->id]->price); ?></td>
                </tr>
                <tr>
                    <th class="title_table">
                        <?php echo JText::_('COM_BOOKPRO_EXTRA_SERVICE') ?>
                    </th>
                    <td><?php
                        $pre_roomtype = $passenger->pre_trip_acommodaton->roomtype_id;
                        $post_roomtype = $passenger->post_trip_acommodaton->roomtype_id;
                        $list_extra = array();
                        if ($pre_roomtype) {
                            $roomtype = $listroomtype[$pre_roomtype]->title;
                            $night = JFactory::getDate($passenger->pre_trip_acommodaton->checkin)->diff(JFactory::getDate($passenger->pre_trip_acommodaton->checkout))->days;
                            $checkin = DateHelper::formatDate($passenger->pre_trip_acommodaton->checkin);
                            $checkout = DateHelper::formatDate($passenger->pre_trip_acommodaton->checkout);
                            $list_extra[] = "pre trip hotel in $reset_destination_of_tour_title $roomtype  room for $night  night from  $checkin  to  $checkout";
                        }
                        if ($post_roomtype) {
                            $roomtype = $listroomtype[$post_roomtype]->title;
                            $night = 3;
                            $checkin = DateHelper::formatDate($passenger->post_trip_acommodaton->checkin);
                            $checkout = DateHelper::formatDate($passenger->post_trip_acommodaton->checkout);
                            $list_extra[] = "post trip hotel in $reset_destination_of_tour_title $roomtype  room for $night  night from  $checkin  to  $checkout";
                        }

                        $post_airport_transfer = $passenger->post_airport_transfer;
                        if ($post_airport_transfer) {
                            $post_airport_transfer_datetime = $post_airport_transfer->flight_arrival_date . ' ' . $post_airport_transfer->flight_arrival_time;
                            $post_airport_transfer_datetime = DateHelper::formatDate($post_airport_transfer_datetime, 'l, j F Y h:i A');
                            $list_extra[] = "private transfer airport-hote in $post_airport_transfer_datetime";
                        }

                        $pre_airport_transfer = $passenger->pre_airport_transfer;
                        if ($pre_airport_transfer) {
                            $pre_airport_transfer_datetime = $pre_airport_transfer->flight_arrival_date . ' ' . $pre_airport_transfer->flight_arrival_time;
                            $pre_airport_transfer_datetime = DateHelper::formatDate($pre_airport_transfer_datetime, 'l, j F Y h:i A');
                            $list_extra[] = "private transfer hote-airport in $pre_airport_transfer_datetime";
                        }
                        $list_additionnaltrip_title = array();

                        if (count($passenger->additionnaltrip_ids)) {
                            foreach ($passenger->additionnaltrip_ids as $additionnaltrip) {

                                $list_additionnaltrip_title[] = $listadditionnaltrip[$additionnaltrip->addone_id]->title;
                            }

                            $list_additionnaltrip_title = implode(',', $list_additionnaltrip_title);
                            $list_extra[] = $list_additionnaltrip_title ? 'Additional activities:' . $list_additionnaltrip_title : '';
                        }
                        if ($list_extra) {
                            $list_extra = implode('+', $list_extra);
                            echo $list_extra;
                        }
                        ?>
                    </td>
                    <td><?php
                        $total = 0;
                        $total+=$passenger->post_trip_acommodaton->price;
                        $total+=$passenger->pre_trip_acommodaton->price;

                        if (count($passenger->additionnaltrip_ids)) {
                            foreach ($passenger->additionnaltrip_ids as $additionnaltrip) {
                                $total+=$additionnaltrip->price;
                            }
                        }
                        ?>
                        <?php echo CurrencyHelper::formatprice($total); ?></td>
                </tr>
            </table>






        </div>

    </div>

    <?php
    $k++;
}
?>



