<style type="text/css">
   .block_right h4 {
        color:#9A0000;
        text-transform: uppercase;
    }
   .block_right ul,li {
        list-style: none;
        margin: 0;padding:0;
    }
   .block_right li{
        margin-left: 40px;
    }
   .block_right span.spanfee{
        color:#9A0000;
        font-weight: bold;
        text-align: right;
    }
</style>
<?php if (count($this->cart->additionnaltrip)) { ?>
    <div>
        <h3 style="text-transform: uppercase; color: #9A0000; text-align: left"><?php echo JText::_('COM_BOOKPRO_ADDITIONNALTRIP') ?></h3>
        <?php
        $j = 1;
        ?>
        <?php foreach ($this->cart->additionnaltrip AS $addone) { ?>
            <?php if (count($addone->sec_person_ids)) { ?>
                <ul><?php
                    echo JText::_("Trip ");
                    echo $j . ": ";
                    ?><?php echo $this->pivot_list_addone[$addone->addon_id]->title ?></ul>
                <?php $k = 1; ?>
                <?php foreach ($addone->sec_person_ids as $key_sec_person_id => $sec_person_id) { ?>
                        <?php if ($sec_person_id != '') { ?>
                        <li><?php
                            $person_sec_id = explode(':', $sec_person_id);
                            $person = $this->cart->person->{$person_sec_id[0]}[$person_sec_id[1]];
                            echo $k . ' . ' . $person->firstname . ' ' . $person->lastname;
                            ?></li>
                        <?php
                        $k++;
                    }
                }
                ?>
                <?php
                $j++;
            }
            ?>
    <?php } ?>
        <div style="text-transform: uppercase; color: #9A0000; text-align: right"><?php echo JText::_("COM_BOOKPRO_SERVICE_FEE"); ?>:<?php echo CurrencyHelper::formatprice($this->cart->additionnaltrip->total); ?></div>
    </div>

<?php } ?>