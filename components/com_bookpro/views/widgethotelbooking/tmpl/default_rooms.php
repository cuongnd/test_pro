<?php
    AImporter::helper('currency','room');    
?>
<script type="text/javascript">
    jQuery(function($) {

        function getinfobooking()
        {
            
            $.ajax({
                url:'<?php echo JUri::base() ?>index.php?option=com_bookpro&controller=widgethotelbooking&task=showroombookingdetail',
                type: "GET",
                data:$("#booking").serialize(),
                crossDomain: true,
                async: false,
                dataType: "jsonp",
                beforeSend: function() {
                    $('#widgetbookpro .widgetbookpro-loading').css({
                        display: "block",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },

                contentType: "application/json",

                success:function(data_json){
                    $('#widgetbookpro .widgetbookpro-loading').css({
                        display:"none"
                    });
                    $('.roombookingdetail').html(data_json);
                    $( "#tabs_booking" ).tabs("option", "active", 0);

                }
            });
        }
        $('#roomlist').delegate('.roomselect', 'change', function() {

            var attr_id_room_id=$(this).closest('tr').attr('id');
            var number_person_avaid=$('#roomlist .'+attr_id_room_id).length;
            var roomnumber=$(this).val();

            if(roomnumber>=number_person_avaid)
            { 
                if(roomnumber==1)
                {
                    room=$('#roomlist tr.perroom.'+attr_id_room_id).last();
                    room.css({
                        display:"table-row" 
                    });
                }      
                for(var i=0;i<roomnumber-number_person_avaid;i++)
                {

                    room=$('#roomlist tr.perroom.'+attr_id_room_id).last();
                    room.css({
                        display:"table-row" 
                    });
                    room.after(room.clone());
                }
            }
            else
            {
                for(var i=roomnumber;i<number_person_avaid;i++)
                {

                    if($('#roomlist tr.perroom.'+attr_id_room_id).length==1)
                    {
                        $('#roomlist tr.perroom.'+attr_id_room_id).last().css({
                            display:"none"
                        });    
                        break;
                    }
                    $('#roomlist tr.perroom.'+attr_id_room_id).last().remove();
                }
            }
            $('#roomlist tr.perroom.'+attr_id_room_id).each(function(index){
                $(this).find('td span.roomnumber').html('<?php echo JText::_('COM_BOOKPRO_ROOM') ?> '+(index+1).toString());
            });
            getinfobooking();


        });
        $('#roomlist').delegate('.adultselect', 'change', function() {
            getinfobooking();
        });
        $('#roomlist').delegate('.childselect', 'change', function() {
            getinfobooking();
        });
    });
</script>

<div id="roomlist" class="roomlist">
    <table class="table table-condensed">
        <thead>
            <tr>
                <th width="40%"><?php echo JText::_('COM_BOOKPRO_ROOM_TYPE') ?>
                </th>
                <th width="20%" align="right"><?php echo JText::_('COM_BOOKPRO_ROOM_MAX_PERSON') ?>
                </th>
                <th align="right"><?php echo JText::_('COM_BOOKPRO_ROOM_PRICE') ?>
                </th>
                <th class="text-right" align="right"><?php echo JText::_('COM_BOOKPRO_ROOM_SELECT')?>
                </th>
                <th class="text-right" align="right"><?php echo JText::_('COM_BOOKPRO_ADULT')?>
                </th>
                <th class="text-right" align="right"><?php echo JText::_('COM_BOOKPRO_CHILD')?>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($this->rooms))foreach ($this->rooms as $room){


                    $no_room =  0;
                    if ($cart->room) {
                        $no_room = $cart->room;
                    }else{
                        $no_room = $room->total;
                    }
                ?>
                <tr class="room" id="room_id_<?php echo $room->id ?>" >

                    <td><span><strong>  <?php echo $room->title ?></strong> </span><br/>
 						<?php echo  RoomHelper::getFacilities($room->id)?></td>
                    <td align="right">

                        <?php 
                            if ($room->adult) {
                                echo JText::sprintf('COM_BOOKPRO_MAX_ADULT',$room->adult);
                            }
                            if ($room->child) {
                                echo JText::sprintf('COM_BOOKPRO_MAX_CHILD',$room->child);
                            }
                        ?>
                    </td>
                    <td align="right">
                        <?php echo CurrencyHelper::formatprice($room->price)?>
                    </td>
                    <td align="right" class="text-right"> 
                        <?php 
                            if ($room->total){
                            ?>

                            <?php
                                //$no_room
                                echo JHtmlSelect::integerlist(0,$no_room, 1, 'no_room['.$room->id.']','class="roomselect"') ?>
                            <?php }else{
                                echo JText::_('COM_BOOKPRO_HOTEL_NOT_ROM');
                        } ?>
                    </td>
                    <td class="text-right" align="right">&nbsp;
                    </td>
                    <td class="text-right" align="right">&nbsp;
                    </td>
                </tr>
                <tr class="perroom room_id_<?php echo $room->id ?>" style="display: none;"  >
                    <td colspan="3">&nbsp;</td>
                    <td ><span class="roomnumber">room 1</span></td> 
                    <td class="text-right" align="right"><?php echo RoomHelper::getAdultSelectBox($room, 'adult['.$room->id.'][]','class="adultselect input-mini pull-right"')?>
                    </td>
                    <td class="text-right" align="right"><?php echo RoomHelper::getChildSelectBox($room, 'child['.$room->id.'][]','class="childselect input-mini pull-right"')?>
                    </td>
                </tr>
                <?php }
                if(!count($this->rooms)) { ?>
                <tr>
                    <td colspan="6"><?php echo JText::_('COM_BOOKPRO_ROOM_UNAVAILABLE')?> </td>
                </tr>
                <?php } ?>
        </tbody>
    </table>
</div>
