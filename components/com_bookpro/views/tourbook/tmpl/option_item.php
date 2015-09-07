<?php

$this->cart->setroom=  array_values((array)$this->cart->setroom);
$count_setroom = count($this->cart->setroom);
$count_setroom = $count_setroom ? $count_setroom : 1;
?>
<?php for ($i = 0; $i < $count_setroom; $i++) { ?>
    <?php
    $room_person = $this->cart->setroom[$i];
    ?>


    <div class="room room_group">
        <h3><?php echo JText::_('COM_BOOKPRO_ROOM'); ?>&nbsp;<span class="room_title"><?php echo $i+1 ?></span><input type="button" name="delete" value=""></h3>
        <div class="row-fluid setroom_select">
            <div class="control-group span5">
                <label class="control-label label_roomtype" for="roomtype"><?php echo JText::_('COM_BOOKPRO_SELECT_YOU_ROOM_TYPE'); ?>
                </label>
                <div class="controls">
                    <?php echo AHtmlFrontEnd::getFilterSelect('setroom['.$i.'][roomtype]', JText::_('COM_BOOKPRO_SELECT_ROOMTYPE'), $this->listroomtype, ($roomtype_id=$this->cart->setroom[$i]->roomtype)?$roomtype_id:$this->listroomtype[0]->id_max_person, false, 'class="input-small roomtype required"', 'id_max_person', 'title') ?>
                    <?php //echo JHtmlSelect::integerlist(1, 10, 1, 'roomtype', 'class="input-small roomtype"'); ?>
                </div>
            </div>
            <div class="span7 form-horizontal">
                <?php
                $count_person_sec_id = count($room_person->person_sec_id);
                $count_person_sec_id = $count_person_sec_id ? $count_person_sec_id : 1;
                for ($j = 0; $j < $count_person_sec_id; $j++) {
                    $person_sec_id = $room_person->person_sec_id[$j];
                    ?>

                    <div class="control-group control-person">
                        <label class="control-label" for="passenger"><?php echo JText::_('COM_BOOKPRO_PASSENGER'); ?>
                        </label>
                        <div class="controls">  
                            <?php echo AHtmlFrontEnd::getFilterSelect('setroom['+$i+'][person_sec_id]['.$j.']', JText::_('COM_BOOKPRO_SELECT_PASSENGER'), $this->cart->needasignchildrenforspecialroom==1?$this->a_listadultandteennerandchildren:$this->a_listadultandteenner, $person_sec_id, false, 'class="passenger passenger_setroom" data="twin_room"') ?>

                        </div>
                    </div>
                <?php } ?>
            </div>


        </div>
    </div>
<?php } ?>



