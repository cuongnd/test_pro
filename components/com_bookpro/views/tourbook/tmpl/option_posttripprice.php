
<?php
$a_post_trip_acommodaton = $this->cart->post_trip_acommodaton;
$a_total = 0;
?>
<h3 class="right_level1" style="text-transform: uppercase; color: #9A0000; text-align: left"><?php echo JText::_('COM_BOOKPRO_POST_TRIP_HOTEL') ?></h3>
<?php foreach ($a_post_trip_acommodaton as $post_trip_acommodaton) { ?>
    <?php if (count($post_trip_acommodaton->trip_acommodaton)) { ?>
        <div style="border-bottom: 1px dotted #738498">
            <div class="right_level">
                <div>
                    <div class="right_level1"><?php echo Jtext::_('COM_BOOKPRO_CHECKIN') ?></div>
                    <div class="right_bold">
                        <?php echo DateHelper::formatDate($post_trip_acommodaton->checkin, 'd M Y'); ?> 
                    </div>
                </div>
                <div class="right_level1"><?php echo Jtext::_('COM_BOOKPRO_CHECKOUT') ?></div>
                <div class="right_bold">
                    <?php echo DateHelper::formatDate($post_trip_acommodaton->checkout, 'd M Y'); ?> 
                </div>
            </div>
            <ul>
                <?php
                $k = 0;
                foreach ($post_trip_acommodaton->trip_acommodaton as $trip_acommodaton) {
                    $roomtype = $trip_acommodaton->roomtype_id;
                    $roomtype = explode(':', $roomtype);
                    $roomtype_id = $roomtype[0];

                    foreach ($trip_acommodaton->setroom as $rooms) {
                        ?>
                        <li><b><?php echo++$k ?>.<?php echo $this->pivot_listroomtype[$roomtype_id]->title ?></b>
                            <ul>
                                <?php foreach ($rooms as $room) { ?>
                                    <?php $m = 0; ?>
                                    <?php foreach ($room as $passenger) { ?>
                                        <?php
                                        $passenger = explode(':', $passenger);
                                        $person = $this->cart->person->{$passenger[0]}[$passenger[1]];
                                        $fullname = $person->firstname . ' ' . $person->lastname;
                                        ?>
                                        <li><?php echo++$m; ?>.<?php echo $fullname ?></li>
                                    <?php } ?>
                                <?php } ?>
                            </ul></li>
                    <?php } ?>
                <?php } ?>
            </ul>
            <?php
            $a_total+=$post_trip_acommodaton->total;
            ?>
            <div style="text-transform: uppercase; color: #9A0000; text-align: right"><?php echo JText::_("COM_BOOKPRO_SERVICE_FEE"); ?>:<?php echo CurrencyHelper::formatprice($post_trip_acommodaton->total); ?></div>
        </div> 
    <?php } ?>
<?php } ?>
<div style="text-transform: uppercase; color: #9A0000; text-align: right;font-weight: bold"><?php echo JText::_("COM_BOOKPRO_SERVICE_FEE"); ?>:<?php echo CurrencyHelper::formatprice($a_total); ?></div>