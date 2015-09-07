<?php

//echo "<pre>";     print_r($this->array_rooms);

?>
<?php if(count($this->array_rooms)){ ?>
    <div class="row-fluid">
        <div class="span6 form-inline">
            <label><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKIN') ?>:</label>
            <label><b><?php echo DateHelper::formatDate($this->cart->checkin_date); ?></b></label>
        </div>
        <div class="span6 form-inline">
            <label>
                <?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKOUT') ?>:
            </label>
            <label><b><?php echo DateHelper::formatDate($this->cart->checkout_date); ?></b></label>
        </div>
    </div>
    <div class="row-fluid">
        <div class="span3 form-inline">
            <label>
                <b><?php echo JText::_('COM_BOOKPRO_NIGHT_NUMBER') ?>:</b>
            </label>
            <label><?php echo $this->numberofnight ?></label>
        </div>
        <div class="span3 form-inline">
            <label>
                <b><?php echo JText::_('COM_BOOKPRO_ROOMS') ?>:</b>
            </label>
            <label><?php echo $this->no_room ?></label>
        </div>
        <div class="span3 form-inline">
            <label>
                <b><?php echo JText::_('COM_BOOKPRO_TOTAL_ADULT') ?>:</b>
            </label>
            <label><?php echo $this->totaladult ?></label>
        </div>
        <div class="span3 form-inline">
            <label>
                <b><?php echo JText::_('COM_BOOKPRO_TOTAL_CHILD') ?>:</b>
            </label>
            <label><?php echo $this->totalchild ?></label>
        </div>
    </div>
    <table class="table a_room_detail">
        <thead>
            <tr>
                <th style="width:20%" ><?php echo JText::_('COM_BOOKPRO_FLIGHT_DATE') ?></th>
                <th style="width:60%" ><?php echo JText::_('COM_BOOKPRO_ROOM_DETAIL') ?></th>

                <th style="width:20%" ><?php echo JText::_('COM_BOOKPRO_TOTAL_PRICE_PER_NIGHT') ?></th>
            </tr>
        </thead>
        <tbody>

            <?php

                foreach($this->array_rooms as $a_room)
                {

                ?>
                <tr class="b_room_detail">
                    <td><?php echo DateHelper::formatDate($a_room->date); ?></td>
                    <td class="room_detail">

                        <table style="width: 100%;"  class="room_detail">
                            <thead>
                                <tr>
                                    <th style="width: 30%;"><?php echo JText::_('COM_BOOKPRO_ROOM_TYPE') ?></th>
                                    <th><?php echo JText::_('COM_BOOKPRO_TOTAL_ROOM') ?></th>
                                    <th><?php echo JText::_('COM_BOOKPRO_PRICE') ?></th>
                                    <th><?php echo JText::_('COM_BOOKPRO_ADULT') ?></th>
                                    <th><?php echo JText::_('COM_BOOKPRO_CHILD') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($a_room->rooms))foreach($a_room->rooms as $room){ ?><tr class="perroom-detail room_id_<?php echo $room->id ?>"  class-name="room_id_<?php echo $this->rooms[0]->id ?>">
                                        <td class="room-title"><?php echo $room->title ?></td>
                                        <td style="text-align: right;" class="total-room"><?php echo $room->totalroom ?></td>
                                        <td style="text-align: center;" class="price"><?php echo $room->price ?></td>
                                        <td style="text-align: center;"  class="number-adult"><?php echo $room->totaladult ?></td>
                                        <td style="text-align: center;" class="number-child"><?php echo $room->totalchild ?></td>
                                    </tr>
                                    <?php

                                    }
                            ?></tbody>
                            
                        </table>
                        
                </td>              
                <td style="text-align: center;" class="total-price-per-day"><?php echo $a_room->totalperday ?></td>
            </tr> 
             <?php } ?>

        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" style="text-align: right;color: #000;  font-weight: bold;"><?php echo JText::_('COM_BOOKPRO_TOTAL') ?></td>
                <td style="color: #000; font-weight: bold; text-align: center;" class="totalallday"><?php echo $this->totalallday ?></td>
            </tr>
        </tfoot>
    </table>
    <?php } ?>