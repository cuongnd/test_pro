<?php 

    AImporter::helper('currency','image','room');
    $cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
    $cart->load();
    $numbernight = DateHelper::getCountDay($cart->checkin_date, $cart->checkout_date);
?>
<script type="text/javascript">
    jQuery(document).ready(function($) {


        $(".lightbox").colorbox({rel:'lightbox'});


    });
</script>
<table class="table table-condensed">
    <thead style="display: none;" >
        <tr>
            <th width="20%">

            </th>
            <th width="20%">&nbsp;

            </th>

            <th width="30%">
                &nbsp;
            </th>




            <th align="center" class="text-right">
            </th>

            <th class="text-right" align="right">
            </th>
            <th class="text-right" align="right">
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($this->rooms)>0) {?>
        <?php foreach ($this->rooms as $room){
                $no_room =  0;
                if ($cart->room) {
                    $no_room = $cart->room;
                }else{
                    $no_room = $room->total;
                }

                if($room->total_price){
                ?>
                <tr class="room_detail">
                    <td  style="vertical-align: top;">

                        <?php $thumb = null;

                            $ipath = BookProHelper::getIPath($room->image);
                            $thumb = AImage::thumb($ipath, $this->config->subjectThumbWidth, $this->config->subjectThumbHeight);

                            $slide = AImage::thumb($ipath, $this->config->galleryPreviewWidth, $this->config->galleryPreviewHeight);
                            if ($thumb) {
                            ?>
                            <a href="<?php echo $slide; ?>" title="" class="lightbox" rel="lightbox"
                                style="position: relative;"> <img src="<?php echo $thumb; ?>"
                                    alt="" style="height:165px;width:300px" />
                            </a>
                            <?php
                        } ?>


                    </td>
                    <td  style="vertical-align: top;">

                        <div class="facilities_room">
                            <?php
                                AImporter::helper('facility');
                                $layout = new JLayoutFile('facilitytext', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
                                $html = $layout->render(RoomHelper::getFacilitiesByRoomID($room->id));
                                echo $html;
                            ?>
                        </div> 


                    </td>
                    <td style="vertical-align: top; line-height:20px;">
                        <h3 style="margin:0px;padding:0px; line-height:20px;">  <?php echo $room->title ?></h3>

                        <b><?php echo Jtext::_('COM_BOOKPRO_ROOM_MAX_PERSON') ?>:</b>
                        <?php 
                            if ($room->adult) {
                                echo JText::sprintf('COM_BOOKPRO_ADULT_TXT',$room->adult);
                            }
                            if ($room->child) {
                                echo JText::sprintf('COM_BOOKPRO_CHILD_TXT',$room->child);
                            }
                        ?>
                        <br/>
                        <b><?php echo JText::sprintf('COM_BOOKPRO_ROOM_AVAILABEL_PRICE',CurrencyHelper::formatprice($room->total_price),$numbernight) ?>
                        <?php echo $room->desc ?>
                    </td>

                    <td align="center" class="text-right">

                        <input type="hidden" name="room_type[]"
                            value="<?php echo $room->id ?>"> 
                        <?php 

                            if ($no_room && $room->total_price){

                            ?>
                            <div style="text-align: center;"><b><?php echo JText::_('COM_BOOKPRO_ROOM_SELECT')?></b></div>
                            <?php
                                //$no_room
                                echo JHtmlSelect::integerlist(0,$no_room, 1, 'no_room['.$room->id.']','class="roomselect input-small"') ?>
                            <?php }else{
                                echo JText::_('COM_BOOKPRO_HOTEL_NOT_ROM');
                        } ?>
                    </td>

                    <td align="right" class="adult" width="100px;">
                        <div style="text-align: center;"><b><?php echo JText::_('COM_BOOKPRO_ADULT')?></b></div>

                    <?php echo RoomHelper::getAdultSelectBox($room, 'adult['.$room->id.'][]','class="selectadult input-small pull-right"')?></td>
                    <td align="right" class="child" width="100px;">
                        <div style="text-align: center;"><b><?php echo JText::_('COM_BOOKPRO_CHILD')?></b></div>
                    <?php echo RoomHelper::getChildSelectBox($room, 'child['.$room->id.'][]','class="selectchild input-small pull-right"')?></td>
                </tr>
                <?php } ?>
                <?php }
            }else { ?>
            <tr>
                <td colspan="6"><?php echo JText::_('COM_BOOKPRO_ROOM_UNAVAILABLE')?>
                </td>
            </tr>
            <?php } ?>
    </tbody>

</table>
<?php if (count($this->rooms)>0) {?>
    <div class="row-fluid">
        <div class="span12">
            <div class="text-right">
             <span style="padding-right: 5px; color: #D11118; font-size: 13px"><?php echo JText::_('COM_BOOKPRO_SELECT_BOOKING'); ?></span>
                <button class="btn btn-primary" type="submit">
                    <?php echo JText::_('COM_BOOKPRO_BOOK') ?>
                </button>
            </div>
        </div>
    </div>
    <?php } ?>


<style type="text/css">
    #roomlist .table-condensed tbody tr:first-child td
    {
        border-top: none;
    }
    .facilities_room .facilities li
    {
        line-height: 30px;

        display: inline;
        padding-left: 10px;
        padding-right: 10px;
        padding-top: 3px;
        padding-bottom: 3px;
        color: #fff;
        border-radius: 3px;
        background: #ff9b3b;
        text-shadow: 0 -1px 0 rgba(0,0,0,0.25);
        background-color: #008ada;
        background-image: -moz-linear-gradient(top,#0097ee,#07b);
        background-image: -webkit-gradient(linear,0 0,0 100%,from(#0097ee),to(#07b));
        background-image: -webkit-linear-gradient(top,#0097ee,#07b);
        background-image: -o-linear-gradient(top,#0097ee,#07b);
        background-image: linear-gradient(to bottom,#0097ee,#07b);
        background-repeat: repeat-x;
    }




    .table-condensed .room_detail{
        padding-top:10px;
        border-bottom:1px #ddd solid ;
    }


    .room_detail .adult .selectadult
    {


    }
</style>
<script type="text/javascript">
    jQuery(document).ready(function($){
        $('.readmore').click(function(){
            id=$(this).attr('data');
            $('tr.readmore_'+id).toggle();
        });



        $('body').delegate('.roomselect', 'change', function() {
            room=$(this).closest('tr.room_detail');
            totalroomselect=$(this).val();

            totalroomavaible=room.find('.adult .selectadult').length;

            if(totalroomselect >totalroomavaible) for(var i=totalroomavaible;i<totalroomselect;i++)
            {
                lastselectbox=room.find('td.adult .selectadult').last();
                lastselectbox.after(lastselectbox.clone());

                lastselectbox=room.find('td.child .selectchild').last();
                lastselectbox.after(lastselectbox.clone());


            }
            id_first=room.find('.adult .selectadult:first').attr('id');
            room.find('.adult .selectadult').each(function(index){
                if(index>0)
                {
                    var id=id_first.concat(index.toString());
                    $(this).attr('id',id);
                }
            });
            if(totalroomselect <totalroomavaible)for(i=totalroomselect;i<totalroomavaible;i++)
            {
                if(room.find('td.adult .selectadult').length==1)
                    break;
                room.find('td.adult .selectadult').last().remove();
                room.find('td.child .selectchild').last().remove(); 
            }
            id_first=room.find('.child .selectchild:first').attr('id');
            room.find('.child .selectchild').each(function(index){
                if(index>0)
                {
                    var id=id_first.concat(index.toString());
                    $(this).attr('id',id);
                }
            });


        });
    });
</script>
    



