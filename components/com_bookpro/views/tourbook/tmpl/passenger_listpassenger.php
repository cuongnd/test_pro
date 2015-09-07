<ul class="passengers">
    <?php
    $person = $this->cart->person;
    $k = 1;
    $a_key_persons = array(
        'adult' => 'adult',
        'teenner' => 'teenner',
        'children' => 'children'
    );
    foreach ($person as $key_person => $a_person) {
        if (!in_array($key_person, $a_key_persons))
            continue;
        for ($i = 0; $i < count($a_person); $i++) {
            $passenger = $a_person[$i];
            $fullname = $passenger->firstname . ' ' . $passenger->lastname;
            ?>

            <li  class="passenger">
                <span class="text_passenger"><?php echo JText::_("Passenger "); ?><?php echo $k . ":"; ?><span class="text_name_passenger"> <?php echo $fullname ?></span></span>
                <div class="row-fluid">
                    <div class="span6">
                        <div class="img_passenger">
                            <img src="<?php echo JURI::base() . "/components/com_bookpro/assets/images/limited.jpg"; ?>">
                        </div>
                    </div>
                    <div class="span3 offset2">

                        <div class="leader_edit">
                            <a style="text-align: center" class="passenger_edit <?php echo $key_person == 'children'?' span12 ':' span2 ' ?>" href="javascript:void(0)"> <?php echo JText::_('Edit') ?></a><?php echo $key_person == 'children'?' ':' <span style="text-align: right; padding-right: 2px" class="span2">|</span> ' ?>
                            <?php if ($key_person == 'adult' || $key_person == 'teenner') { ?>
                                <label class="control-group span8 passenger_leader " href="javascript:void(0)"><span class="control-label"> <?php echo JText::_('Leader') ?></span><input class="controls leader" type="radio" style="margin-top: 0px;margin-left: 2px;" name="person[<?php echo $key_person ?>][<?php echo $i ?>][leader]" value="1"></label>
                            <?php } ?>
                        </div>

                    </div>
                </div>
            </li>
            <?php
            $k++;
        }
    }
    ?>
</ul>

<style>
    .passengers li{
        list-style:none;
    }

    .text_passenger{
        font-weight:bold;
    }
    .passengers .passenger{
        background:#f6f6f6;
        border:1px solid #000;
        margin-bottom:10px;
    }
    .text_passenger{
        color:#990000;
        padding-left:10px;
    }
    .text_name_passenger{
        color:#000;
    }
    .leader_edit{
        font-weight:bold;
        padding-top:30px;

    }
    .img_passenger{
        padding-left:30px;
        padding-bottom:10px;
    }
</style>

