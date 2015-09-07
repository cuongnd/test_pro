<?php
defined('_JEXEC') or die('Restricted access');
$cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
$cart->load();
$config = AFactory::getConfig();
$document = JFactory::getDocument();
$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/jquery.ui.datepicker.js');
AImporter::helper('currency', 'room');
?>
<script>
    jQuery(document).ready(function ($) {

        $("#room_checkin_date").datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            minDate: new Date(),
            onSelect: function () {

                var selected = $('#room_checkin_date').datepicker('getDate');
                selected.setDate(selected.getDate() + 1);
                $('#room_checkout_date').datepicker('setDate', selected);
                $("#room_checkout_date").datepicker("option", {
                    minDate: selected
                });
            }
        });
        $("#room_checkout_date").datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            minDate: new Date()
        });
        $("#room_checkin_date").datepicker("setDate", "0");
        $("#room_checkout_date").datepicker("setDate", "1");
        $("#check_available").click(function () {
            loadAvailableRoom();
        });
        $(".ui-datepicker-trigger").addClass(' btn-small');

        //loadAvailableRoom();

        function loadAvailableRoom() {
            var hotel_id = <?php echo JRequest::getVar('hotel_id') ?>;
            var checkin = $("#room_checkin_date").val();
            var checkout = $("#room_checkout_date").val();
            $.ajax({
                type: "GET",
                url: 'index.php',
                cache: false,
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'expediahotel',
                        task: 'checkAvailable',
                        layout:'listroom',
                        hotel_id:hotel_id,
                        'tmpl':'component'

                    }
                    $data = $.param($data);
                    $data1 = $('.searchcheckincheckout :input').serialize();

                    $data = $data + '&' + $data1;
                    console.log($data);
                    return $data;
                })(),
                beforeSend: function() {
                    jQuery("#roomlist").html('<div align="center"><img src="components/com_bookpro/assets/images/loader.gif" /><div>');


                    // $('.loading').popup();
                },
                success: function(data) {
                    $('#roomlist').html(data);
                }
            });


        }


    });
</script>
<div class="room">
    <h2>
        <span style="text-transform:capitalize;"><?php echo JText::_("COM_BOOKPRO_ROOM_SELECT_AND_BOOK") ?>
        </span>
    </h2>

    <div class="searchcheckincheckout well bookbar  form-inline">
        <div class="row-fluid">
            <div class="wapper1  row-fluid">
                <div class="span10">
                    <div class="row-fluid">
                        <div class="span9">

                            <div class="span4">
                                <label for="checkin_date"
                                       class="control-label"><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKIN'); ?></label>

                                <div class="">
                                    <div class="input-append">
                                        <input type="text" class="ininput-medium span7" name="checkin_date"
                                               id="room_checkin_date"
                                               value="<?php echo $cart->checkin_date; ?>" size="" maxlength="10"/>

                                        <div class="add-on"><i class="icon-calendar"></i></div>
                                    </div>
                                </div>
                            </div>

                            <div class="span4">
                                <label for="checkout_date"
                                       class="control-label"><?php echo JText::_('COM_BOOKPRO_HOTEL_CHECKOUT'); ?></label>

                                <div class="">
                                    <div class="input-append">
                                        <input type="text" class="ininput-medium span7" name="checkout_date"
                                               id="room_checkout_date"
                                               value="<?php echo $cart->checkout_date; ?>" size="" maxlength="10"/>

                                        <div class="add-on"><i class="icon-calendar"></i></div>
                                    </div>
                                </div>
                            </div>


                            <div class="span3">
                                <label class="control-label"
                                       style="width: 50px;"><?php echo JText::_('COM_BOOKPRO_TRAVEL_SEARCH_ROOM'); ?> </label>

                                <div class="">
                                    <?php echo JHtml::_('select.integerlist', 1, 5, 1, 'room', ' class="input-mini room"', $cart->room); ?>
                                </div>
                            </div>
                            <div class=" span1 title-room">
                                <span><span><?php echo JText::_('Room ') ?>&nbsp </span><span class="room-number">1</span></span>
                            </div>
                        </div>
                        <div class="span3">
                            <div class="span6 ">
                                <label class="control-label"
                                       style="width: 50px;"><?php echo JText::_('COM_BOOKPRO_TRAVEL_SEARCH_ADULT'); ?> </label>

                                <div class="controls">
                                    <?php echo JHtml::_('select.integerlist', 1, 5, 1, 'adult[]', ' class="input-mini"', $cart->room); ?>
                                </div>
                            </div>
                            <div class="span6">
                                <label class="control-label"
                                       style="width: 50px;"><?php echo JText::_('COM_BOOKPRO_TRAVEL_SEARCH_CHILDREN'); ?> </label>

                                <div class="controls">
                                    <?php echo JHtml::_('select.integerlist', 0, 5, 1, 'children[]', ' class="input-mini"', $cart->room); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="span2">
                    <button class="btn btn-primary " type="button"
                            id="check_available"><?php echo JText::_('COM_BOOKPRO_ROOM_AVAILABLE_CHECK') ?></button>
                </div>
            </div>
        </div>
        <div class="row-fluid adut-child">
            <div class="wapper3 row-fluid reflection" style="display: none">
                <div class="span10">
                    <div class="span9">
                        <div class="offset11 span1 title-room">
                            <span><span><?php echo JText::_('Room ') ?>&nbsp </span><span class="room-number">2</span></span>
                        </div>
                    </div>
                    <div class="span3">
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"
                                       style="width: 50px;"><?php echo JText::_('COM_BOOKPRO_TRAVEL_SEARCH_ADULT'); ?> </label>

                                <div class="controls">
                                    <?php echo JHtml::_('select.integerlist', 1, 5, 1, 'adult[]', ' class="input-mini"', $cart->room); ?>
                                </div>
                            </div>
                        </div>
                        <div class="span6">
                            <div class="control-group">
                                <label class="control-label"
                                       style="width: 50px;"><?php echo JText::_('COM_BOOKPRO_TRAVEL_SEARCH_CHILDREN'); ?> </label>

                                <div class="controls">
                                    <?php echo JHtml::_('select.integerlist', 0, 5, 1, 'children[]', ' class="input-mini"', $cart->room); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row-fluid wapper-children" >
            <div class="row-fluid wapper1-children reflection" style="display: none">
                <div class="span1 title-room">
                    <span><span><?php echo JText::_('Room ') ?>&nbsp </span><span class="room-number">1</span></span>
                </div>
                <div class="control-group age-children span2 reflection">
                    <label class="control-label"
                           style="width: 50px;"><span><span><?php echo JText::_('COM_BOOKPRO_TRAVEL_SEARCH_CHILDREN'); ?></span>&nbsp<span class="children-number">1</span></span> </label>

                    <div class="controls">
                        <?php echo JHtml::_('select.integerlist', 0, 17, 1, 'age_children[0][]', ' class="input-mini age_children"', $cart->room); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="row-fluid">

    </div>
    <!-- Display rooms -->
    <div id="roomlist" class="roomlist row-fluid">
        <?php //echo $this->loadTemplate("listroom")  ?>
    </div>
    <div id="facility_hotel">
        <div class="pull-right form-horizontal">
            <?php /* ?>
  <div class="control-group">
  <label class="control-label"><?php echo JText::_('COM_BOOKPRO_HOTEL_SELECT_FACILITY') ?>:</label>
  <div class="controls form-inline">
  <?php echo $this->facilitybox; ?>
  </div>

  </div>
  <?php */
            ?>


        </div>

    </div>

</div>

<style type="text/css">

</style>
<style type="text/css">


    .facilities_room .facilities li {
        line-height: 30px;

        display: inline;
        padding-left: 10px;
        padding-right: 10px;
        padding-top: 3px;
        padding-bottom: 3px;
        color: #fff;
        border-radius: 3px;
        background: #ff9b3b;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
        background-color: #008ada;
        background-image: -moz-linear-gradient(top, #0097ee, #07b);
        background-image: -webkit-gradient(linear, 0 0, 0 100%, from(#0097ee), to(#07b));
        background-image: -webkit-linear-gradient(top, #0097ee, #07b);
        background-image: -o-linear-gradient(top, #0097ee, #07b);
        background-image: linear-gradient(to bottom, #0097ee, #07b);
        background-repeat: repeat-x;
    }

    .table-condensed .room_detail {
        padding-top: 10px;
        border-bottom: 1px #ddd solid;
    }

    .room_detail .adult .selectadult {

    }
    .searchcheckincheckout .wapper1
    {

    }
    .adut-child .wapper3
    {

    }
    .wapper-children .wapper1-children
    {

    }
    .adut-child .wapper3
    {

    }
    .searchcheckincheckout .title-room
    {



    }
    .searchcheckincheckout .title-room span
    {
        margin-left:0;
    }
    .searchcheckincheckout  .ui-datepicker-trigger
    {
        padding:4px 10px;
    }
    .control-group.age-children span
    {
        margin-left:0;
        float: none;
    }
</style>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.readmore').click(function () {
            id = $(this).attr('data');
            $('tr.readmore_' + id).toggle();
        });

        $('select[name="room"]').val(1);
        $('select[name="children[]"]').val(0);
        $('select[name="adult[]"]').val(1);
        $(document).on('change', 'select[name="room"]', function () {
            setavailbleroom($(this));
        });
        //setavailbleroom($('.reflection select[name="room"]'));

        function setavailbleroom($this) {
            $('select[name="children[]"]').val(0);
            $rooms = $this.val();

            $('.adut-child .wapper3.reflected').remove();
            if($rooms==1)
            {
                $('.adut-child .wapper3.reflection').css({
                    'display':'none'
                });
            }else
            {
                $('.adut-child .wapper3.reflection').css({
                    'display':'block'
                });
            }
            for ($i = 0; $i < $rooms - 2; $i++) {
                $('.adut-child .wapper3.reflection').css({'display':'block'}).after(function () {
                    $object = $(this).clone().toggleClass('reflection reflected');
                    $object.find('.title-room span.room-number').html($rooms-$i);
                    $object.find('select.age_children').attr('name','age_children['+($rooms-$i-1)+'][]');
                    return $object;
                });
            }
            $('.wapper-children .wapper1-children.reflected').remove();
            for ($i = 0; $i < $rooms - 1; $i++) {
                $('.wapper-children .wapper1-children.reflection').after(function () {
                    $object = $(this).clone().toggleClass('reflection reflected');
                    $object.find('.control-group.age-children.reflected').remove();
                    $object.find('.title-room span.room-number').html($rooms-$i);
                    $object.find('select.age_children').attr('name','age_children['+($rooms-$i-1)+'][]');
                    return $object;
                });
            }
            $('.wapper-children .wapper1-children').css({
                'display':'none'
            });

        }


        $(document).on('change', 'select[name="children[]"]', function () {
            showcontrolage_children($(this));
        });
        $('select[name="children[]"]').each(function () {
            //showcontrolage_children($(this));
        });


        function showcontrolage_children($this) {
            children = $this.val();
            $index_of=$('select[name="children[]"]').index($this);
            $age_child_index=$('.wapper-children .wapper1-children:eq('+$index_of+')');
            if(children>0)
            {
                $age_child_index.css({
                    'display':'block'
                });
            }
            else
            {
                $age_child_index.css({
                    'display':'none'
                });
            }


            $age_child_index.find('.age-children.reflected').remove();
            $age_children_reflection=$age_child_index.find('.age-children.reflection')

            for ($i = 0; $i < children - 1; $i++) {
                $age_children_reflection.css({'display': 'block'}).after(function () {
                    $object = $(this).clone().toggleClass('reflection reflected');
                    $object.find('span.children-number').html(children-$i);
                    $object.css({
                        'display':'block'
                    });
                    return $object;
                });
            }


        }


    });
</script>
