
<div class="additionnaltrip_item additionnaltrip">
    <input type="hidden" name="additionnaltrip[<?php echo $this->a_addon->id; ?>][addon_id]" value="<?php echo $this->a_addon->id; ?>">
    <h3 class="title minusimage span12 slidetoggle_additionnaltrip"><?php echo $this->a_addon->title; ?></h3>
    <div class="content row-fluid">
        <div class="description row-fluid">
            <div class="div_description">
                <?php echo $this->a_addon->description ?>
            </div>
            <div class="price_booknow" >
                <div class="booknow booknow_additionnaltrip minusimage additionnaltrip"><?php echo JText::_('COM_BOOKPRO_BOOKNOW') ?></div>
            </div>
        </div>
        <div class="row-fluid form-content additionnaltrip  form-additionnaltrip">
            <div class="span3">
                <div class="colse btn_close"></div>
                <div class="inclusion">
                    <?php echo JText::_('COM_BOOKPRO_INCLISION') ?>

                </div>
                <div>The function is flexible,The function is flexible,The function is flexible</div>
            </div>
            <div class="checkin_checkout span3 form-horizontal">

                <ul class="passengers">
                    <?php
                    $person = $this->cart->person;
                    $a_key_persons = array(
                        'adult' => 'adult',
                        'teenner' => 'teenner',
                        'children' => 'children'
                    );
                    foreach ($person as $key_person => $a_person) {
                        if (!in_array($key_person, $a_key_persons, true)) {

                            continue;
                        }
                        for ($i = 0; $i < count($a_person); $i++) {
                            $passenger = $a_person[$i];
                            $fullname = $passenger->firstname . ' ' . $passenger->lastname;
                            ?>

                            <li class="passenger">
                                <?php $sec_person_id = $this->cart->additionnaltrip->{$this->a_addon->id}->sec_person_ids->{$key_person . ':' . $i}; ?>
                                <?php
                                $name='additionnaltrip['.$this->a_addon->id.'][sec_person_ids]['.$key_person . ':' . $i .']';
                                ?>
                                <label ><input  class="passenger" type="checkbox" <?php echo ($sec_person_id == ($key_person . ':' . $i)) ? 'checked=""' : '' ?>   name="<?php  echo $name ?>" data="<?php echo $key_person . ':' . $i ?>" value="<?php echo ($sec_person_id == ($key_person . ':' . $i)) ? $key_person . ':' . $i : '' ?>"/> <?php echo $fullname ?></label>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
            </div>
            <div class="span6 additionnaltrip_passenger">
                <?php echo JText::_('COM_BOOKPRO_THIS_JOINT_GROUP') ?>
                <div class="additionnaltrip_passenger_price"><?php echo JText::_('Detail') ?>:2 pers booked Total price:<?php echo $this->a_addon->price ?></div>
            </div>
        </div>
    </div>
</div>




