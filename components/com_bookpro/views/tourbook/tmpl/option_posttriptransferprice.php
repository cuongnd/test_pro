<?php if (count($this->cart->post_airport_transfer)) { 
    $k=0;
    $total=0;
    ?>
    <div>
        <h3 class="title minusimage" style="text-transform: uppercase; color: #9A0000; padding: 1px; text-align: left"><?php echo JText::_('COM_BOOKPRO_POST_TRIP_TRANSFER') ?></h3>
        <?php foreach ($this->cart->post_airport_transfer AS $airport_transfer_item) { ?>
            <?php if ($sec_person_id = $airport_transfer_item->sec_person_id) { ?>
                <div><?php
                    $person_sec_id = explode(':', $sec_person_id);
                    $person = $this->cart->person->{$person_sec_id[0]}[$person_sec_id[1]];
                      $total+=$person->post_airport_transfer->price;
                    echo ++$k.'.'.$person->firstname . ' ' . $person->lastname;
                    ?></div>
            <?php } ?>
        <?php } ?>
        <div style="text-transform: uppercase; color: #9A0000; text-align: right"><?php echo JText::_("COM_BOOKPRO_SERVICE_FEE"); ?>:<?php echo CurrencyHelper::formatprice($this->cart->post_airport_transfer->total); ?></div>
    </div>
<?php } ?>
