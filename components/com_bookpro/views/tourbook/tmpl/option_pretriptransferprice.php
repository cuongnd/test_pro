<?php if (count($this->cart->pre_airport_transfer)) {
    $k=0;
    $total=0;
    ?>
    <div>
        <h3 style="text-transform: uppercase; color: #9A0000; text-align: left"><?php echo JText::_('COM_BOOKPRO_PRE_TRIP_TRANSFER') ?></h3>
        <?php foreach ($this->cart->pre_airport_transfer AS $airport_transfer_item) { ?>
            <?php if ($sec_person_id = $airport_transfer_item->sec_person_id) { ?>
                <div><?php
                    $person_sec_id = explode(':', $sec_person_id);
                   
                    $person = $this->cart->person->{$person_sec_id[0]}[$person_sec_id[1]];
                     $total+=$person->pre_airport_transfer->price;
                    echo ++$k.'.'.$person->firstname . ' ' . $person->lastname;
                    ?></div>
            <?php } ?>
        <?php } ?>
        <div style="text-transform: uppercase; color: #9A0000; text-align: right"><?php echo JText::_("COM_BOOKPRO_SERVICE_FEE"); ?>:<?php echo CurrencyHelper::formatprice($total); ?></div>
    </div>
<?php } ?>