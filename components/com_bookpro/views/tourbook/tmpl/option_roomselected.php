<?php

if (count($this->cart->setroom)) {
    $k = 0;
    ?>
    <div class="right_level">
        <h3 style="text-transform: uppercase; color: #9A0000; text-align: left"><?php echo JText::_('COM_BOOKPRO_ROOM_SELECTED') ?></h3>
        <?php
        foreach ($this->cart->setroom AS $room) {
            $next = 0;
            ?>
            <div class="right_level1">room&nbsp;&nbsp;<?php echo++$k ?>&nbsp; <?php
                $a_roomtype = json_decode($room->roomtype);
                echo $this->pivot_listroomtype[$a_roomtype->id]->title;
                ?></div>
            <?php
            foreach ($room->person_sec_id as $person_sec_id) {
                $person_sec_id = explode(':', $person_sec_id);
                $person = $this->cart->person->{$person_sec_id[0]}[$person_sec_id[1]];
                if ($person) {
                    ?>     
                    <div class="right_level11">
                        <?php
                        echo++$next . ' . ' . $person->firstname . ' ' . $person->lastname . '(' . $person_sec_id[0] . ')';
                        ?>
                    </div>
                    <?php
                }
                ?>    

                <?php
            }
            
            foreach ($this->cart->setchildrenacommodation as $childrenacommodation) {
                if ($childrenacommodation->oder_room == $k) {
                    $person_sec_id = $childrenacommodation->person_sec_id;
                    $person_sec_id = explode(':', $person_sec_id);
                    if (count($person_sec_id) == 2) {
                        $person = $this->cart->person->{$person_sec_id[0]}[$person_sec_id[1]];
                        $fullname_children = $person->firstname . ' ' . $person->lastname . '(' . $person_sec_id[0] . ')'.($person->setchildrenacommodation->needbed?'   ('.JText::_('COM_BOOKPRO_NEEDBED').')':'');
                        ?>
                        <div class="right_level11">
                            <?php echo++$next . ' . ' . $fullname_children ?>
                        </div>
                        <?php
                    }
                }
            }
        }
        ?>
    </div>
<?php } ?>
<div class="border-bottom1"></div>   