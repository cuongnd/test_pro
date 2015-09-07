
<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
AImporter::helper('currency', 'form');
$document = JFactory::getDocument();
JHtmlBehavior::framework();
JHtml::_('jquery.ui');
JHtml::_('jquery.framework');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');
AImporter::js('customer');
$config = AFactory::getConfig();

$lang = JFactory::getLanguage();
$local = substr($lang->getTag(), 0, 2);
$document = JFactory::getDocument();
$document->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js");
if ($local != 'en') {
    $document->addScript(JURI::root() . 'components/com_bookpro/assets/js/validatei18n/messages_' . $local . '.js');
}
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/jquery-ui.css');
$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/jquery-ui.js');
$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/view-tourbook.js');
$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/scroll-startstop.events.jquery.js');

$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/jquery-ui-timepicker-addon.js');
$document->addScript('http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/additional-methods.js');

$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/view-tourbook.css');
?>

<style type="text/css">
    .form-horizontal .control-label
    {
        width: auto;
        padding-right: 5px;


    }
    .form-horizontal .controls
    {
        margin-left: auto;
    }

    .form-horizontal .controls input.bridthday
    {
        width: 105px;
    }
    .form-horizontal.airpost_transfer input
    {
        width: 95px !important;
    }
    .form-horizontal.airpost_transfer input.transferitem
    {
        width: auto !important;
        margin: 3px;
    }
    .frontTourForm.children_acommodation select
    {
        width: auto;
    }
</style>
<div class="widgetbookpro-loading"></div>
<?php $this->currentstep = 2 ?>
<?php echo $this->loadTemplate("currentstep") ?>
<form name="frontTourForm" method="post" action='index.php' id="frontTourForm">
    <div class="mainfarm row">
        <div class="col-md-8">
            <?php
            $layout = new JLayoutFile('header_tour', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');
            $html = $layout->render($this->tour);
            echo $html;
            ?>
            <div class="clr"></div>
            <div class="frontTourForm setroomselected">
                <h3 class="title minusimage slidetoggle"><?php echo JText::_('COM_BOOKPRO_TRIP_ROOM_REFERENCER') ?></h3>

                <div class="content">
                    <div class="div_description"><?php echo JText::_('COM_BOOKPRO_TRIP_ROOM_REFERENCER_DESCRIPTION') ?></div>
                    <div class="form-horizontal">
                        <div class="setroom_for_person row-fluid">
                            <div class="col-md-8">
                                <div class="needasignchildren">
                                    <label><input type="checkbox"  <?php echo $this->cart->needasignchildrenforspecialroom == 1 ? 'checked=""' : '' ?> name="needasignchildren"> <?php echo JText::_('COM_BOOKPRO_I_NEED_ASIGN_CHILDREN_FOR_ROOM'); ?></label>

                                </div>
                            </div>
                            <div class="control-group col-md-4">
                                <label class="control-label" for="rooms"><?php echo JText::_('COM_BOOKPRO_SELECT_ROOM_FOR_GROUP'); ?>
                                </label>
                                <div class="controls">
                                    <?php echo JHtmlSelect::integerlist(1, count($this->a_listadultandteennerandchildren), 1, 'rooms', 'class="input-small rooms"', ($totalroom = count($this->cart->setroom)) ? $totalroom : 1) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php echo $this->loadTemplate("item") ?>
                </div>


            </div>
            <div class="frontTourForm children_acommodation">
                <h3 class="title minusimage slidetoggle"><?php echo JText::_('COM_BOOKPRO_CHILD_ACOMMODATION') ?></h3>
                <div class="content">
                    <?php echo $this->loadTemplate("childrenacommodation") ?>
                </div>
            </div>

            <div class="frontTourForm trip_acommodaton pre_trip_acommodaton" data="pre_trip_acommodaton">
                <?php $this->class_trip_acommodaton = "pre_trip_acommodaton" ?>
                <?php $this->trip_acommodaton = JText::_('COM_BOOKPRO_PRE_TRIP_ACOMMODATON'); ?>
                <?php $this->trip_acommodaton_description = JText::_('COM_BOOKPRO_PRE_TRIP_ACOMMODATON_DESCRIPTION'); ?>
                <?php echo $this->loadTemplate("posttrip") ?>
            </div>
            <div class="frontTourForm trip_acommodaton post_trip_acommodaton" data="post_trip_acommodaton">
                <?php $this->class_trip_acommodaton = "post_trip_acommodaton" ?>
                <?php $this->trip_acommodaton = JText::_('COM_BOOKPRO_POST_TRIP_ACOMMODATON'); ?>
                <?php $this->trip_acommodaton_description = JText::_('COM_BOOKPRO_POST_TRIP_ACOMMODATON_DESCRIPTION'); ?>
                <?php echo $this->loadTemplate("posttrip") ?>
            </div>
            <div class="frontTourForm a_triptransfer pre_airport_transfer" data="pre_airport_transfer">
                <?php $this->class_airport_transfer = "pre_airport_transfer" ?>
                <?php $this->airport_transfer = JText::_('COM_BOOKPRO_PRE_AIRPORT_TRANSFER'); ?>
                <?php $this->airport_transfer_description = JText::_('COM_BOOKPRO_PRE_AIRPORT_TRANSFER_DESCRIPTION'); ?>
                <?php echo $this->loadTemplate("airporttransfer") ?>
            </div>
            <div class="frontTourForm a_triptransfer post_airport_transfer" data="post_airport_transfer">
                <?php $this->class_airport_transfer = "post_airport_transfer" ?>
                <?php $this->airport_transfer = JText::_('COM_BOOKPRO_POST_AIRPORT_TRANSFER'); ?>
                <?php $this->airport_transfer_description = JText::_('COM_BOOKPRO_POST_AIRPORT_TRANSFER_DESCRIPTION'); ?>
                <?php echo $this->loadTemplate("airporttransfer") ?>
            </div>
            <div class="frontTourForm  additionnaltrip">
                <?php echo $this->loadTemplate("additionnaltrip") ?>
            </div>
            <?php echo FormHelper::bookproHiddenField(array('controller' => 'tourbook', 'task' => 'show_form_input_detail_passenger', 'Itemid' => JRequest::getInt('Itemid'))) ?>
            <div style="text-align: right;padding-right: 10px">
                <a href="index.php?option=com_bookpro&view=tourbook" class="btn back"><?php echo JText::_('Prev') ?></a>
                <input class="btn next" name="next" type="submit" value="Next"/>
            </div>
        </div>
        <div class="col-md-4 block_right"  style="border: 1px solid #CCCCCC; padding: 5px;">
            <div class="checkinandcheckout">
                <?php echo $this->loadTemplate("checkinandcheckout") ?>
            </div>
            <div class="listpassenger">
                <?php echo $this->loadTemplate("listpassenger") ?>
            </div>
            <div class="roomselected">
                <?php echo $this->loadTemplate("roomselected") ?>
            </div>
            <div class="extrabedprice">
                <?php echo $this->loadTemplate("extrabedprice") ?>
            </div>

            <div class="tripprice pre_trip_acommodaton">
                <?php echo $this->loadTemplate("pretripprice") ?>
            </div>
            <div class="tripprice post_trip_acommodaton">
                <?php echo $this->loadTemplate("posttripprice") ?>
            </div>
            <div class="triptransfer pre_airport_transfer">
                <?php echo $this->loadTemplate("pretriptransferprice") ?>
            </div>
            <div class="triptransfer post_airport_transfer">
                <?php echo $this->loadTemplate("posttriptransferprice") ?>
            </div>
            <div class="additionnaltripprice">
                <?php echo $this->loadTemplate("additionnaltripprice") ?>
            </div>
            <div class="totaltripprice">
                <?php echo $this->loadTemplate("totaltripprice") ?>
            </div>

            <?php
            $this->setlayout('default');
            echo $this->loadTemplate("needsomehelp")
            ?>
        </div>

    </div>
    <div style="display:none;" class="nav_up" id="nav_up"></div>
    <div style="display:none;" class="nav_down" id="nav_down"></div>
</form>
<style type="text/css">
    .form-horizontal .control-label
    {
        width: auto;


    }
    .form-horizontal .controls
    {
        margin-left: auto;
    }

    .form-horizontal .controls input.bridthday
    {
        width: 105px;
    }
</style>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        var $elem = $('.mainfarm');

        $('#nav_up').fadeIn('slow');
        $('#nav_down').fadeIn('slow');

        $(window).bind('scrollstart', function() {
            $('#nav_up,#nav_down').stop().animate({'opacity': '0.2'});
        });
        $(window).bind('scrollstop', function() {
            $('#nav_up,#nav_down').stop().animate({'opacity': '1'});
        });

        $('#nav_down').click(
                function(e) {
                    $('html, body').animate({scrollTop: $elem.height()}, 1800);
                }
        );
        $('#nav_up').click(
                function(e) {
                    $('html, body').animate({scrollTop: '0px'}, 2800);
                }
        );
        function sethtmlfortag($respone_array)
        {
            $respone_array = $.parseJSON($respone_array);
            $.each($respone_array, function($index, $respone) {

                $($respone.key.toString()).html($respone.contents);
            });
        }
        function getajax_form_totaltripprice()
        {
            $.ajax({
                type: "GET",
                url: 'index.php',
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'tourbook',
                        task: 'getajax_form_totaltripprice',
                    }
//                    $data = $.param($data);
//                    $data1 = $('.frontTourForm.children_acommodation *').serialize();
//
//                    $data = $data + '&' + $data1;
//                    console.log($data);
                    return $data;
                })(),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "none",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });
                    $('.totaltripprice').html($result);
                }
            });
        }
        $(".img-toogle").click(function() {
            //$('.passenger').slideToggle(0);
            passenger = $(this).closest('.box.item');
            if (passenger.find('.control-group').is(":visible")) {
                $(this).addClass('plusimage');
                $(this).removeClass('minusimage');

            } else {
                $(this).removeClass('plusimage');
                $(this).addClass('minusimage');
            }
            passenger.find('.control-group').slideToggle(0);

        });
        function getajax_airport_transfer($airport_transfer)
        {
            $array_airport_transfer = {
                'post_airport_transfer': 'post_airport_transfer',
                'pre_airport_transfer': 'pre_airport_transfer'
            };


            $.ajax({
                type: "GET",
                url: 'index.php?option=com_bookpro&controller=tourbook&task=ajax_showfrom_airpost_transfer&airport_transfer=' + $array_airport_transfer[$airport_transfer],
                data: (function() {
                    $find_airport_transfer = [/post_airport_transfer/gi, /pre_airport_transfer/gi, /flight_number/gi, /flight_arrival_date/gi, /flight_arrival_time/gi];
                    $replace_airport_transfer = ['post', 'pre', 'number', 'date', 'time'];
                    $a_param = $.param($('.frontTourForm.a_triptransfer.' + $airport_transfer).find(':input'), false);
                    for ($i = 0; $i < $find_airport_transfer.length; $i++)
                    {
                        $a_param = $a_param.replace($find_airport_transfer[$i], $replace_airport_transfer[$i]);
                    }

                    return $a_param;
                })(),
                //data: $.param($('.frontTourForm.a_triptransfer.' + $airport_transfer).find(':input'), false),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "none",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });
                    sethtmlfortag($result);
                    //$('.triptransfer.' + $airport_transfer).html(result);
                    //sgetajax_form_totaltripprice();
                }
            });
        }
        $(document).on('blur', '.pre_airport_transfer .airpost_transfer .flight_number', function() {
            getajax_airport_transfer('pre_airport_transfer');
        });
        $(document).on('click', '.pre_airport_transfer .airpost_transfer .transferitem', function() {
            $airpost_transfer = $(this).closest('.airpost_transfer');
            if ($(this).is(':checked'))
            {
                $(this).val($(this).attr('data'));
                $airpost_transfer.find('input.flight_number').removeAttr('disabled');
                $airpost_transfer.find('input.flight_arrival_date').removeAttr('disabled');
                $airpost_transfer.find('input.flight_arrival_time').removeAttr('disabled');

            }
            else
            {
                $(this).val('');

                $airpost_transfer.find('input.flight_number').attr('disabled', 'disabled');
                $airpost_transfer.find('input.flight_arrival_date').attr('disabled', 'disabled');
                $airpost_transfer.find('input.flight_arrival_time').attr('disabled', 'disabled');
            }
            getajax_airport_transfer('pre_airport_transfer');
        });
        $(document).on('click', '.post_airport_transfer .airpost_transfer .transferitem', function() {
            $airpost_transfer = $(this).closest('.airpost_transfer');
            if ($(this).is(':checked'))
            {
                $(this).val($(this).attr('data'));
                $airpost_transfer.find('input.flight_number').removeAttr('disabled');
                $airpost_transfer.find('input.flight_arrival_date').removeAttr('disabled');
                $airpost_transfer.find('input.flight_arrival_time').removeAttr('disabled');
            }
            else
            {
                $(this).val('');
                $airpost_transfer.find('input.flight_number').attr('disabled', 'disabled');
                $airpost_transfer.find('input.flight_arrival_date').attr('disabled', 'disabled');
                $airpost_transfer.find('input.flight_arrival_time').attr('disabled', 'disabled');
            }

            getajax_airport_transfer('post_airport_transfer');
        });
        $(document).on('click', '.frontTourForm.additionnaltrip  input.passenger', function() {
            if ($(this).is(':checked'))
            {
                $(this).val($(this).attr('data'));
            }
            else
            {
                $(this).val('');
            }
            $.ajax({
                type: "GET",
                url: 'index.php?option=com_bookpro&controller=tourbook&task=ajax_showfrom_additionnaltrip',
                data: $.param($('.frontTourForm.additionnaltrip').find(':input:checked'), false),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "none",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });
                    sethtmlfortag($result);

                }
            });
        });

        function ajax_getformsetroom()
        {
//            console.log(
//                    $.param($('.frontTourForm.setroomselected').find(':input'))
//                    );
            $.ajax({
                type: "GET",
                url: 'index.php?option=com_bookpro&controller=tourbook&task=ajax_showfrom_roomselected' + ($is_change_passenger != undefined ? $is_change_passenger : ''),
                data: $.param($('.frontTourForm.setroomselected').find(':input')),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "none",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });
                    sethtmlfortag($result);
                }
            });
        }
        //khach book ve dua don tu khach san den san bay va tu san bay ve khach san
//        $('.airpost_transfer .flight_arrival_time').timepicker({
//        });
        $('.airpost_transfer .flight_arrival_date').datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            minDate: new Date(),
            buttonImageOnly: true,
            buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/callender.png',
            onSelect: function(selected) {

//                $checkin_checkout = $(this).closest('.checkin_checkout');
//                var selected = $checkin_checkout.find('input.checkin').datepicker('getDate');
//                selected.setDate(selected.getDate() + 1);
//                $checkin_checkout.find('input.checkout').datepicker('setDate', selected);
//                $checkin_checkout.find(' input.checkout').datepicker("option", {
//                    minDate: selected
//                });
            }
        });
        setlimitrooms();
        function setlimitrooms()
        {
            $total_adultandteenner =<?php echo count($this->a_listadultandteenner) ?>;
            $total_adultandteennerandchiren =<?php echo count($this->a_listadultandteennerandchildren) ?>;
            $needasignchildren = $('.setroom_for_person input[name="needasignchildren"]');
            $value_roomselect = $('select[name="rooms"]').val();
            $html = new Array();
            if ($needasignchildren.is(':checked'))
            {
                for ($i = 1; $i <= $total_adultandteennerandchiren; $i++)
                {
                    $selected = '';
                    if ($i == $value_roomselect)
                    {
                        $selected = 'selected="selected"';
                    }
                    $html.push('<option ' + $selected + ' value="' + $i + '">' + $i + '</option>');
                }
            }
            else
            {
                for ($i = 1; $i <= $total_adultandteenner; $i++)
                {
                    $selected = '';
                    if ($i == $value_roomselect)
                    {
                        $selected = 'selected="selected"';
                    }
                    else
                    {
                        if ($i == $total_adultandteenner)
                            $selected = 'selected="selected"';
                    }
                    $html.push('<option ' + $selected + ' value="' + $i + '">' + $i + '</option>');
                }
            }
            $html = $html.join(' ');
            $('select[name="rooms"]').html($html);
            $('select[name="rooms"]').change();

        }
        $(document).on('click', '.setroom_for_person input[name="needasignchildren"]', function() {
            $checked = 0;
            if ($(this).is(':checked'))
            {

                validateperson('passenger_setroom');
                $checked = 1;
            }
            else
            {
                $checked = 0;
                validateperson('passenger_setroom');
            }
            setlimitrooms();
            ajax_need_asign_children_for_special_room($checked);

        });
        function ajax_need_asign_children_for_special_room($need_asign_children_for_special_room)
        {
            $.ajax({
                type: "GET",
                url: 'index.php',
                cache: false,
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'tourbook',
                        task: 'ajax_need_asign_children_for_special_room',
                        'needasignchildrenforspecialroom': $need_asign_children_for_special_room
                    }
                    return $data;
                })(),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "none",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });

                    $a_result = $.parseJSON($result);
                    sethtmlfortag($result);
                    //$('.tripprice.' + $trip_acommodaton).html(result);
                    //getajax_form_totaltripprice();
                }
            });
        }
        $('.airpost_transfer .flight_arrival_date.pre_airport_transfer').datepicker("option", {
            maxDate: new Date('<?php echo JFactory::getDate($this->cart->checkin_date)->format('Y-m-t', true) ?>'),
            onSelect: function(selected) {
                getajax_airport_transfer('pre_airport_transfer');
            }
        });
        $('.airpost_transfer .flight_arrival_date.post_airport_transfer').datepicker("option", {
            minDate: new Date('<?php echo JFactory::getDate($this->cart->checkin_date)->format('Y-m-t', true) ?>'),
            onSelect: function(selected) {
                getajax_airport_transfer('post_airport_transfer');
            }
        });


        $('.airpost_transfer .pre_airport_transfer.flight_arrival_time').timepicker("option", {
            onSelect: function(selected) {
                getajax_airport_transfer('pre_airport_transfer');
            }
        });
        $('.airpost_transfer .post_airport_transfer.flight_arrival_time').timepicker("option", {
            onSelect: function(selected) {
                getajax_airport_transfer('post_airport_transfer');
            }
        });
        function stylewidthcontrol($object)
        {
            $maxwidth = 0;
            $object.find('.control-group .control-label').each(function($index) {
                if ($maxwidth < $(this).width())
                    $maxwidth = $(this).width();
            });
            $object.find('.control-group .control-label').css({
                width: $maxwidth + 10
            });
        }
        stylewidthcontrol($('.checkin_checkout'));
        stylewidthcontrol($('.select_passenger'));
        function auto_submit_when_ready()
        {
            //ajax_getformsetroom();
        }
        auto_submit_when_ready();
        function renderacommodation($object)
        {

            $data_select_name = $object.attr('data');
            $data_trip_acommodaton = $object.attr('data_trip_acommodaton');
            $form_acommodation = $object.closest('.form-acommodation');

            $current_total_room_avaible = $form_acommodation.find('.room_and_passenger .select_passenger div.a_room_select.' + $data_select_name + ' div.control-person').length;
            $a_allow_room = $object.val();
            if ($a_allow_room == 0)
                $a_allow_room = '0:0:0';
            $a_allow_room = $a_allow_room.split(':');
            $allow_room = $a_allow_room[2];
            $id_roomtype_select = $a_allow_room[0];
            if ($allow_room >= 1 && $form_acommodation.find('.select_passenger div.a_room_select.' + $data_select_name + ' div.control-person').length == 1)
            {
                $form_acommodation.find('.select_passenger div.a_room_select.' + $data_select_name + ' div.control-person').css({
                    display: 'block'
                });
            }
            if ($current_total_room_avaible < $allow_room)
            {

                for ($i = 0; $i < $allow_room - $current_total_room_avaible; $i++)
                {
                    $last_passenger = $form_acommodation.find('.select_passenger div.a_room_select.' + $data_select_name + ' div.control-person:last');
                    $passenger = $last_passenger.clone();
                    $passenger.find('select.passenger').val(0);
                    $passenger.find('select.passenger').attr('name', 'roomtype_id_' + $id_roomtype_select);
                    $last_passenger.after($passenger);
                }
            }
            else
            {
                for ($i = 0; $i < $current_total_room_avaible - $allow_room; $i++)
                {
                    if ($allow_room == 0 && $form_acommodation.find('.select_passenger div.a_room_select.' + $data_select_name + ' div.control-person').length == 1)
                    {
                        break;
                    }
                    $last_passenger = $form_acommodation.find('.select_passenger div.a_room_select.' + $data_select_name + ' div.control-person:last');
                    $last_passenger.remove();
                }
            }
            if ($allow_room == 0 && $form_acommodation.find('.select_passenger div.a_room_select.' + $data_select_name + ' div.control-person').length == 1)
            {
                $form_acommodation.find('.select_passenger div.a_room_select.' + $data_select_name + ' div.control-person').css({
                    display: 'none'
                });
                $form_acommodation.find('.select_passenger div.a_room_select.' + $data_select_name + ' div.control-person select.passenger').val(0);
            }
            $form_acommodation.find('.select_passenger div.a_room_select.' + $data_select_name + ' div.control-person').each(function($index) {
                $(this).find('span.' + $data_select_name).html($index + 1);
            });
            validateperson($data_trip_acommodaton);
            validateperson('passenger_setroom');
            setnamebropboxtrip_acommodaton($data_trip_acommodaton);


        }
        function getajax_trip_acommodaton($trip_acommodaton)
        {
            console.log($('.frontTourForm.trip_acommodaton.' + $trip_acommodaton).find(':input').length);
            $array_trip_acommodaton = {
                'post_trip_acommodaton': 'post_trip_acommodaton',
                'pre_trip_acommodaton': 'pre_trip_acommodaton'
            };
            $.ajax({
                type: "GET",
                url: 'index.php?option=com_bookpro&controller=tourbook&task=ajax_showfromtrip_acommodaton&trip_acommodaton=' + $array_trip_acommodaton[$trip_acommodaton],
                data: (function() {
                    $find_trip_acommodaton = [/post_trip_acommodaton/gi, /pre_trip_acommodaton/gi,/roomtype_id/gi,/trip_acommodaton/gi,/person_sec_ids/gi];
                    $replace_trip_acommodaton = ['post', 'pre','rtid','trip_ac','ids'];
                    $a_param = $.param($('.frontTourForm.trip_acommodaton.' + $trip_acommodaton).find(':input:visible'));
                    for ($i = 0; $i < $find_trip_acommodaton.length; $i++)
                    {
                        $a_param = $a_param.replace($find_trip_acommodaton[$i], $replace_trip_acommodaton[$i]);
                    }

                    return $a_param;
                })(),
                //data: $.param($('.frontTourForm.trip_acommodaton.' + $trip_acommodaton).find(':input')),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "none",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });
                    sethtmlfortag($result);
                    //$('.tripprice.' + $trip_acommodaton).html(result);
                    //getajax_form_totaltripprice();
                }
            });
        }
        $('.form-acommodation  select.room_select').each(function($index) {
            renderacommodation($(this));
        });
        $(document).on('change', '.form-acommodation select.room_select', function() {
            //clone bropbox passenger
            $data_trip_acommodaton = $(this).attr('data_trip_acommodaton');
            renderacommodation($(this));
            getajax_trip_acommodaton($data_trip_acommodaton);


        });
        setcalendar_checkin_checkout($('.checkin_checkout input.checkin'));
        function setcalendar_checkin_checkout($jobect)
        {
            //set lich cho khach lua chon khi khach den truoc doan va den sau doan
            $jobect.datepicker({
                dateFormat: "dd-mm-yy",
                changeMonth: true,
                changeYear: true,
                showButtonPanel: false,
                minDate: new Date(),
                buttonImageOnly: true,
                buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/callender.png',
                onSelect: function(selected) {
                    $checkin_checkout = $(this).closest('.checkin_checkout');
                    var selected = $checkin_checkout.find('input.checkin').datepicker('getDate');
                    selected.setDate(selected.getDate() + 1);
                    $checkout = $checkin_checkout.find('input.checkout');
                    if ($checkout.val().trim() == '')
                        $checkout.datepicker('setDate', selected);
                    $checkin_checkout.find(' input.checkout').datepicker("option", {
                        minDate: selected
                    });
                    $trip_acommodaton_attr = $(this).closest('.frontTourForm.trip_acommodaton').attr('data');
                    getajax_trip_acommodaton($trip_acommodaton_attr);
                }
            });
        }

        function ajaxgettotal()
        {

        }
        $('.checkin_checkout input.checkout').datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            minDate: new Date(),
            buttonImageOnly: true,
            buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/callender.png',
            onSelect: function(selected) {
                getajax_trip_acommodaton('post_trip_acommodaton');
                getajax_trip_acommodaton('pre_trip_acommodaton');
//                        var selected = $('.hotel_search #checkin').datepicker('getDate');
//                        selected.setDate(selected.getDate() + 1);
//                        $('.hotel_search #checkout').datepicker('setDate', selected);
//                        $(".hotel_search #checkout").datepicker("option", {
//                            minDate: selected
//                        });
            }
        });
        $('.checkin_checkout input.checkin.pre_trip_acommodaton').datepicker("option", {
            maxDate: new Date('<?php echo JFactory::getDate($this->cart->checkin_date)->format('Y-m-d', true) ?>')
        });
        $('.checkin_checkout input.checkout.pre_trip_acommodaton').datepicker("option", {
            maxDate: new Date('<?php echo JFactory::getDate($this->cart->checkin_date)->format('Y-m-d', true) ?>')
        });
        $('.checkin_checkout input.checkin.post_trip_acommodaton').datepicker("option", {
            minDate: new Date('<?php echo JFactory::getDate($this->cart->checkout_date)->format('Y-m-d', true) ?>')
        });
        $('.checkin_checkout input.checkout.post_trip_acommodaton').datepicker("option", {
            minDate: new Date('<?php echo JFactory::getDate($this->cart->checkout_date)->format('Y-m-d', true) ?>')
        });

        function h3_title_slidetoggle($object)
        {
            $frontTourForm = $object.closest('.frontTourForm');
            $content = $frontTourForm.find('div.content');

            if ($content.is(":visible")) {
                $object.addClass('plusimage');
                $object.removeClass('minusimage');
            } else {
                $object.removeClass('plusimage');
                $object.addClass('minusimage');
            }
            $frontTourForm.find('div.content').slideToggle(0);
        }
        $(document).on('click', '.mainfarm h3.title.slidetoggle', function() {
            h3_title_slidetoggle($(this));
        });
        function slidetoggle_additionnaltrip($object)
        {
            $additionnaltrip_item = $object.closest('.additionnaltrip_item');
            $content = $additionnaltrip_item.find('div.content');

            if ($content.is(":visible")) {
                $object.addClass('plusimage');
                $object.removeClass('minusimage');
            } else {
                $object.removeClass('plusimage');
                $object.addClass('minusimage');
            }
            $additionnaltrip_item.find('div.content').slideToggle(0);
        }

        $(document).on('click', '.mainfarm h3.title.slidetoggle_additionnaltrip', function() {
            slidetoggle_additionnaltrip($(this));
        });
        function slidetoggle_form_acommodation($object)
        {
            $frontTourForm = $object.closest('.frontTourForm');
            $form_content = $object.closest('.form-content.form-acommodation');
            if ($frontTourForm.find('.form-content.form-acommodation').length > 1)
            {
                $form_content.remove();
            }
            else
            {
                $div_booknow = $frontTourForm.find('.description div.booknow');

                if ($form_content.is(":visible")) {
                    $div_booknow.addClass('plusimage');
                    $div_booknow.removeClass('minusimage');
                } else {
                    $div_booknow.removeClass('plusimage');
                    $div_booknow.addClass('minusimage');
                }
                $form_content.find('input.checkin').val('');
                $form_content.find('input.checkout').val('');
                $form_content.find('.listroom select.room_select').val(0);
                $form_content.find('.a_room_select').each(function() {
                    $coutgroup = $(this).find('.control-group').length;
                    for ($i = 0; $i < $coutgroup - 1; $i++)
                    {
                        $(this).find('.control-group:last').remove();
                    }
                    $(this).find('.control-group:last').css({
                        display: "none"
                    });
                });

                $frontTourForm.find('input[name="make_other_booking"]').slideToggle(0);
                $form_content.slideToggle(0);
            }

            getajax_trip_acommodaton($frontTourForm.attr('data'));
        }
        $(document).on('click', '.mainfarm .form-content .colse.a_btn_close', function() {
            slidetoggle_form_acommodation($(this));

        });
        $(document).on('click', '.mainfarm .additionnaltrip_item .btn_close', function() {
            return false;
            $additionnaltrip_item = $(this).closest('.additionnaltrip_item');
            $form_content = $additionnaltrip_item.find('.form-content');
            $div_booknow = $additionnaltrip_item.find('.description div.booknow');

            if ($form_content.is(":visible")) {
                $div_booknow.addClass('plusimage');
                $div_booknow.removeClass('minusimage');
            } else {
                $div_booknow.removeClass('plusimage');
                $div_booknow.addClass('minusimage');
            }
            $form_content.slideToggle(0);
        });
        $(document).on('click', '.mainfarm .description div.booknow_item', function() {
            $frontTourForm = $(this).closest('.frontTourForm');
            form_content = $frontTourForm.find('.form-content');
            $div_booknow = $frontTourForm.find('.description div.booknow');

            if (form_content.is(":visible")) {
                $div_booknow.addClass('plusimage');
                $div_booknow.removeClass('minusimage');
            } else {
                $div_booknow.removeClass('plusimage');
                $div_booknow.addClass('minusimage');
            }
            form_content.slideToggle(0);
            $frontTourForm.find('input[name="make_other_booking"]').slideToggle(0);
        });
        $(document).on('click', '.mainfarm .description div.booknow_additionnaltrip', function() {
            $additionnaltrip_item = $(this).closest('.additionnaltrip_item');
            form_content = $additionnaltrip_item.find('.form-content');
            $div_booknow = $additionnaltrip_item.find('.description div.booknow');

            if (form_content.is(":visible")) {
                $div_booknow.addClass('plusimage');
                $div_booknow.removeClass('minusimage');
            } else {
                $div_booknow.removeClass('plusimage');
                $div_booknow.addClass('minusimage');
            }
            form_content.slideToggle(0);
        });
        $(document).on('click', '.frontTourForm .room.room_group input[name="delete"]', function() {

            $current_total_person = $('.frontTourForm .room.room_group').length;
            if ($current_total_person > 1)
            {
                $room = $(this).closest('.room');
                $room.remove();
            }

            validateperson('passenger_setroom');
            $('select.rooms[name="rooms"]').val($('.frontTourForm .room.room_group').length);
            $('.frontTourForm .room_title').each(function($index) {
                $(this).html(++$index);
            });

            $('.children select.share_room').each(function() {
                $html_share_room = '';
                for ($i = 0; $i < $('.frontTourForm .room.room_group').length; $i++)
                {
                    $k = $i + 1;
                    $html_share_room += '<option ' + ($k == $(this).val() ? 'selected="selected"' : '') + ' value="' + $k + '">' + $k + '</option>';
                }
                $(this).html($html_share_room);
                setnamebropbox();
            });
            ajax_getformsetroom();

            //showfrom_childrenacommodation


        });
        $(document).on('change', 'select.roomtype', function() {

            $room = $(this).closest('.room.room_group');
            $allow_total_person = $(this).val();
            if ($allow_total_person == 0)
            {
                $allow_total_person = '{"id":0,"max_person":1}';
            }
            $allow_total_person = $.parseJSON($allow_total_person);
            $allow_total_person = $allow_total_person.max_person;
            $current_person = $room.find('.control-person').length;
            if ($allow_total_person < $current_person)
            {
                for ($i = 0; $i < $current_person - $allow_total_person; $i++)
                {
                    $room.find('.control-person:last').remove();
                }
            }
            else
            {
                for ($i = 0; $i < $allow_total_person - $current_person; $i++)
                {
                    $lastperson = $room.find('.control-person:last');
                    $person = $lastperson.clone();
                    $person.find('select.passenger').val(0);
                    $lastperson.after($person);
                }
            }
            validateperson('passenger_setroom');
            setnamebropbox();
            ajax_getformsetroom();
        });
        setnamebropbox();
        function setnamebropbox()
        {
            $('.room.room_group').each(function($index) {
                $control_roomtype = $(this).find('select.roomtype');
                $control_roomtype.attr('name', 'setroom[' + $index + '][roomtype]');
                $(this).find('.passenger.passenger_setroom').each(function($index1) {
                    $(this).attr('name', 'setroom[' + $index + '][person_sec_id][' + $index1 + ']');
                });
            });
        }
        function validateperson($classname)
        {

            $listperson_stander = $.parseJSON('<?php echo json_encode($this->a_listadultandteennerandchildren) ?>');
            $needasignchildren = $('.setroom_for_person input[name="needasignchildren"]');
            if ($needasignchildren.is(':checked'))
            {
                $needasignchildrenforspecialroom = 1;
            }
            else
            {
                $needasignchildrenforspecialroom = 0;
            }

            if ($needasignchildrenforspecialroom == 0 && $classname == 'passenger_setroom')
            {
                $listperson_stander = $.parseJSON('<?php echo json_encode($this->a_listadultandteenner) ?>');
            }


            $list_select = new Array();
            $('.frontTourForm  select.passenger.' + $classname).each(function($index) {
                $value_selected = $(this).val();
                if ($value_selected != 0 && $list_select.indexOf($value_selected) == -1)
                {
                    $list_select.push($value_selected);
                }
            });

            $('.frontTourForm  select.passenger.' + $classname).each(function($index) {
                $listperson = jQuery.extend({}, $listperson_stander);
                $value_seleted = $(this).val();
                $html = new Array();
                $html.push('<option value="0"><?php echo Jtext::_('COM_BOOKPRO_SELECT_PASSENGER') ?></option>');

                $.each($listperson, function($index, $person) {
                    if ($person.value == $value_seleted && typeof $person.text !== "undefined")
                    {
                        $html.push('<option selected="selected" value="' + $person.value + '">' + $person.text + '</option>');

                    }
                    else if ($list_select.indexOf($person.value) == -1 && typeof $person.text !== "undefined")
                    {
                        $html.push('<option  value="' + $person.value + '">' + $person.text + '</option>');

                    }

                });
                $html = $html.join(' ');
                $(this).html($html);

            });






        }
        $(document).on('change', '.frontTourForm select.passenger.pre_trip_acommodaton', function() {
            $(this).addClass('focus');
            validateperson('pre_trip_acommodaton');

            $(this).removeClass('focus');
            getajax_trip_acommodaton('pre_trip_acommodaton');
            //setnamebropboxtrip_acommodaton('pre_trip_acommodaton');
        });

        $(document).on('change', '.frontTourForm select.passenger.post_trip_acommodaton', function() {
            $(this).addClass('focus');
            validateperson('post_trip_acommodaton');

            $(this).removeClass('focus');
            //setnamebropboxtrip_acommodaton('post_trip_acommodaton');
            getajax_trip_acommodaton('post_trip_acommodaton');
        });
        var $is_change_passenger = '';
        $(document).on('change', '.frontTourForm select.passenger.passenger_setroom', function() {
            $is_change_passenger = '&is_change_passenger=1';
            $(this).addClass('focus');
            validateperson('passenger_setroom');

            $(this).removeClass('focus');
            ajax_getformsetroom();
            $is_change_passenger = '';

        });

        setnamebropboxtrip_acommodaton('pre_trip_acommodaton');
        setnamebropboxtrip_acommodaton('post_trip_acommodaton');

        function setnamebropboxtrip_acommodaton($trip_acommodaton)
        {
            $('.listroom.' + $trip_acommodaton).each(function($index_trip_acommodaton) {
                $(this).find('.room_select').each(function($index) {
                    $control_roomtype = $(this);
                    $roomtype_select = $control_roomtype.val();
                    $control_roomtype.attr('name', $trip_acommodaton + '[' + $index_trip_acommodaton + '][trip_acommodaton][' + $index + '][roomtype_id]');
                    $select_passenger = $(this).closest('.form-content.form-acommodation.' + $trip_acommodaton);
                    $select_passenger.find('.a_room_select.' + $trip_acommodaton + '.' + $control_roomtype.attr('data') + ' .control-person').each(function($index1) {
                        $(this).find('.controls .passenger.' + $trip_acommodaton).each(function($index2) {
                            $(this).attr('name', $trip_acommodaton + '[' + $index_trip_acommodaton + '][trip_acommodaton][' + $index + '][setroom][' + $index1 + '][person_sec_ids][' + $index2 + ']');
                        });

                    });
                });

            });
            $('.form-content.form-acommodation.' + $trip_acommodaton).each(function($index_trip_acommodaton) {
                $(this).find('input.checkin').attr('name', $trip_acommodaton + '[' + $index_trip_acommodaton + '][checkin]');
                $(this).find('input.checkout').attr('name', $trip_acommodaton + '[' + $index_trip_acommodaton + '][checkout]');

            });
        }
        $(document).on('change', 'select.rooms[name="rooms"]', function() {

            $allow_person = $(this).val();
            $current_total_person = $('.frontTourForm .room.room_group').length;
            if ($current_total_person < $allow_person)
            {
                for ($i = 0; $i < $allow_person - $current_total_person; $i++)
                {
                    $lastroom = $('.frontTourForm .room.room_group:last');
                    $room = $lastroom.clone();
                    $total_control_person = $room.find('.control-person').length;
                    $room.find('select.roomtype:last option').each(function() {
                        $option_value = $(this).val();
                        $option_value = $option_value == 0 ? '{"id":0,"max_person":0}' : $option_value;
                        $option_value = $.parseJSON($option_value);
                        if ($option_value.max_person == $total_control_person)
                        {
                            $(this).attr('selected', 'selected');
                        }
                        else
                        {
                            $(this).removeAttr('selected', 'selected');
                        }
                    });
                    $room.find('.control-person select.passenger').val(0);
                    $lastroom.after($room);


                }
            }
            else
            {
                for ($i = 0; $i < $current_total_person - $allow_person; $i++)
                {
                    $room = $('.frontTourForm .room.room_group:last');
                    $room.remove();
                }
            }
            validateperson('passenger_setroom');
            $('.frontTourForm .room_title').each(function($index) {
                $(this).html(++$index);
            });

            $('.children select.share_room').each(function() {
                $html_share_room = '';
                for ($i = 0; $i < $('.frontTourForm .room.room_group').length; $i++)
                {
                    $k = $i + 1;
                    $html_share_room += '<option ' + ($k == $(this).val() ? 'selected="selected"' : '') + ' value="' + $k + '">' + $k + '</option>';
                }
                $(this).html($html_share_room);
            });
            setnamebropbox();
            ajax_getformsetroom();
        });
//        $('#frontTourForm').validate({// initialize the plugin
//
//            invalidHandler: function(e, validator) {
//                if (validator.errorList.length)
//                {
////                    $('.passenger_form').css({
////                        display: "none"
////                    });
////                    $passenger_form = $(validator.errorList[0].element).closest('.passenger_form');
////                    $passenger_form.css({
////                        display: "block"
////                    });
//                }
//            }
//        });
        $(document).on('change', '.frontTourForm.children_acommodation select.needbed', function() {
            $setroom_select = $(this).closest('.setroom_select');
            $setroom_select.find('select.share_room').change();

//            $indexofthis = $setroom_select.index();
//            $focus_value_selected = $setroom_select.find('select.share_room').val();
//            $('.frontTourForm.children_acommodation select.share_room').each(function() {
//
//                if ($(this).closest('.setroom_select').index() != $indexofthis && $(this).val() == $focus_value_selected)
//                {
//                    $(this).change();
//                }
//            });
            //getajax_showfrom_childrenacommodation();

        });
        $(document).on('click', '.frontTourForm.trip_acommodaton  input[name="make_other_booking"]', function() {
            clone_make_orther_booking($(this));
        });
        function clone_make_orther_booking($object)
        {
            $data_attr = $object.attr('data');
            $form_acommodation = null;
            $form_acommodation = $('.form-content.form-acommodation.' + $data_attr + ':last');
            $clone_form_acommodation = $form_acommodation.clone();
            $clone_form_acommodation.find('.a_room_select').each(function() {
                $coutgroup = $(this).find('.control-group').length;
                for ($i = 0; $i < $coutgroup - 1; $i++)
                {
                    $(this).find('.control-group:last').remove();
                }
                $(this).find('.control-group:last').css({
                    display: "none"
                });
            });

            $clone_form_acommodation.find('select.room_select').val(0);
            $clone_form_acommodation.find('select.passenger.' + $data_attr).val(0);
            $clone_form_acommodation.find('input.checkin').val('');
            $clone_form_acommodation.find('input.checkout').val('');
            $clone_form_acommodation.find('.checkin_checkout input.hasDatepicker').removeClass('hasDatepicker').removeAttr('id');
            setcalendar_checkin_checkout($clone_form_acommodation.find('.checkin_checkout input.' + $data_attr));
            $form_acommodation.after($clone_form_acommodation);
            validateperson($data_attr);
            setnamebropboxtrip_acommodaton($data_attr);

            getajax_trip_acommodaton($data_attr);
        }
        $(document).on('change', '.frontTourForm.children_acommodation select.share_room', function() {
            $setroom_select = $(this).closest('.setroom_select');

            $focus_value_selected = $(this).val();
            $('.frontTourForm.children_acommodation').find('.hidden_focus').val('');
            $setroom_select.find('.hidden_focus').val(1);
            $value_roomtype = $('.frontTourForm.setroomselected .room.room_group select.roomtype:eq(' + ($focus_value_selected - 1) + ')').val();
            $value_roomtype = $.parseJSON($value_roomtype);

            $.ajax({
                type: "GET",
                url: 'index.php',
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'tourbook',
                        task: 'ajax_show_children_acommodation',
                        roomtype_id: $value_roomtype.id,
                    }
                    $data = $.param($data);
                    $data1 = $('.frontTourForm.children_acommodation  *').serialize();

                    $data = $data + '&' + $data1;
                    return $data;
                })(),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
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
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });
                    sethtmlfortag($result);
                }
            });
//            $indexofthis = $setroom_select.index();
//            $('.frontTourForm.children_acommodation select.needbed').each(function() {
//                if ($(this).closest('.setroom_select').index() != $indexofthis)
//                {
//                    $(this).change();
//                }
//            });
        });
        function ajax_show_form_listpassenger()
        {
            $.ajax({
                type: "GET",
                url: 'index.php',
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'tourbook',
                        task: 'ajax_show_form_listpassenger'
                    }

                    return $data;
                })(),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "none",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });
                    $('.listpassenger').html($result);
                }
            });
        }
        function getajax_showfrom_childrenacommodation()
        {
            $.ajax({
                type: "GET",
                url: 'index.php',
                cache: false,
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'tourbook',
                        task: 'ajax_showfrom_childrenacommodation'
                    }
                    $data = $.param($data);
                    $data1 = $('.frontTourForm.children_acommodation *').serialize();

                    $data = $data + '&' + $data1;
                    console.log($data);
                    return $data;
                })(),
                beforeSend: function() {
                    $('.widgetbookpro-loading').css({
                        display: "none",
                        position: "fixed",
                        "z-index": 1000,
                        top: 0,
                        left: 0,
                        height: "100%",
                        width: "100%"
                    });
                    // $('.loading').popup();
                },
                success: function($result) {
                    $('.widgetbookpro-loading').css({
                        display: "none"
                    });
                    sethtmlfortag($result);
                }
            });
        }
        $(document).on('submit', '#frontTourForm', function(event) {

            $listperson_stander = $.parseJSON('<?php echo json_encode($this->a_listadultandteenner) ?>');

            $needasignchildren = $('.setroom_for_person input[name="needasignchildren"]');
            if ($needasignchildren.is(':checked'))
            {
                $needasignchildrenforspecialroom = 1;
            }
            else
            {
                $needasignchildrenforspecialroom = 0;
            }

            if ($needasignchildrenforspecialroom == 1)
            {
                $listperson_stander = $.parseJSON('<?php echo json_encode($this->a_listadultandteennerandchildren) ?>');
            }



            $listperson = jQuery.extend({}, $listperson_stander);


            $list_select = new Array();
            var $roomempty = 0;
            $('.frontTourForm  select.passenger.passenger_setroom').each(function($index) {
                $value_selected = $(this).val();

                $roomempty = $value_selected == 0 ? 1 : $roomempty;

                if ($value_selected != 0 && $list_select.indexOf($value_selected) == -1)
                {
                    $list_select.push($value_selected);
                }
            });

            $setroomenenoughedperson = 1;

            $setroomenenoughedperson = $listperson_stander.length == $list_select.length ? 1 : 0;

            if ($setroomenenoughedperson == 1 && $roomempty == 1)
            {
                var btns = {};
                btns['yes'] = function() {
                    $(this).dialog("close");
                    $('.setroomselected .content').addClass('noice');
                    $("body, html").animate({scrollTop: $("div.setroomselected").offset().top}, 750);

                    if (!$('.setroomselected .content').is(":visible"))
                    {
                        h3_title_slidetoggle($('.setroomselected h3.title'));
                    }

                };
                $("<div><?php echo Jtext::_('COM_BOOKPRO_PLEASE_ADD_MORE_PASSENGER_OR_SELECT_ROOM_AGAIN') ?></div>").dialog({
                    autoOpen: true,
                    title: '<?php echo Jtext::_('COM_BOOKPRO_WARNING') ?>',
                    modal: true,
                    buttons: btns,
                    closeOnEscape: false
                });
                return false;

            }
            if ($setroomenenoughedperson == 0 && $roomempty == 1)
            {
                var btns = {};
                btns['yes'] = function() {
                    $(this).dialog("close");
                    $('.setroomselected .content').addClass('noice');
                    $("body, html").animate({scrollTop: $("div.setroomselected").offset().top}, 750);
                    if (!$('.setroomselected .content').is(":visible"))
                    {
                        h3_title_slidetoggle($('.setroomselected h3.title'));
                    }



                };
                $("<div><?php echo Jtext::_('COM_BOOKPRO_PLEASE_ADD_PASSENGER_FOR_ROOM') ?></div>").dialog({
                    autoOpen: true,
                    title: '<?php echo Jtext::_('COM_BOOKPRO_WARNING') ?>',
                    modal: true,
                    buttons: btns,
                    closeOnEscape: false
                });
                return false;

            }
            if ($setroomenenoughedperson == 0)
            {

                var btns = {};
                btns['yes'] = function() {
                    $(this).dialog("close");
                    $('.setroomselected .content').addClass('noice');
                    $("body, html").animate({scrollTop: $("div.setroomselected").offset().top}, 750);
                    if (!$('.setroomselected .content').is(":visible"))
                    {
                        h3_title_slidetoggle($('.setroomselected h3.title'));
                    }


                };
                $("<div><?php echo Jtext::_('COM_BOOKPRO_PLEASE_ADD_ROOM_FOR_PASSENGER_OR_CHANGE_ROOM') ?></div>").dialog({
                    autoOpen: true,
                    title: '<?php echo Jtext::_('COM_BOOKPRO_WARNING') ?>',
                    modal: true,
                    buttons: btns,
                    closeOnEscape: false
                });
                return false;

            }
            for ($i = 0; $i < $('.frontTourForm.children_acommodation select.share_room').length; $i++)
            {
                $children_acommodation = $('.frontTourForm.children_acommodation .setroom_select:eq(' + $i + ')');
                $share_room = $('.frontTourForm.children_acommodation select.share_room:eq(' + $i + ')');
                if ($share_room.val() == 0)
                {

                    var btns = {};
                    btns['yes'] = function() {
                        $(this).dialog("close");

                        if (!$('.children_acommodation .content').is(":visible"))
                        {
                            h3_title_slidetoggle($('.children_acommodation h3.title'));
                        }
                        $children_acommodation.addClass('noice');
                        $("body, html").animate({scrollTop: $children_acommodation.offset().top}, 750);

                        return false;


                    };
                    $("<div><?php echo Jtext::_('COM_BOOKPRO_PLEASE_ASIGN_ACOMMODATION_CHILDREN') ?></div>").dialog({
                        autoOpen: true,
                        title: '<?php echo Jtext::_('COM_BOOKPRO_WARNING') ?>',
                        modal: true,
                        buttons: btns,
                        closeOnEscape: false
                    });
                    return false;
                }
                else
                {
                    $children_acommodation.find('.setroom_select.noice').removeClass('noice');
                }

            }
            for ($i = 0; $i < $('.frontTourForm.trip_acommodaton').length; $i++)
            {
                $trip_acommodaton = $('.frontTourForm.trip_acommodaton:eq(' + $i + ')');
                $select_room_select = false;
                $trip_acommodaton.find('select.room_select').each(function() {
                    $select_room_select = $(this).val() != 0 ? true : $select_room_select;
                });
                $trip_acommodaton.find('.content').removeClass('noice');

                if ($select_room_select == true)
                {
                    for ($checkin_checkout_interval = 0; $checkin_checkout_interval < $trip_acommodaton.find('div.checkin_checkout input').length; $checkin_checkout_interval++)
                    {
                        $control_checkin_checkout = $trip_acommodaton.find('div.checkin_checkout input:eq(' + $checkin_checkout_interval + ')');
                        if ($control_checkin_checkout.val().trim() == '')
                        {
                            var btns = {};
                            btns['yes'] = function() {
                                $(this).dialog("close");
                                $trip_acommodaton.find('.content').addClass('noice');
                                $("body, html").animate({scrollTop: $trip_acommodaton.offset().top}, 750);
                                if (!$trip_acommodaton.find('.content').is(":visible"))
                                {
                                    h3_title_slidetoggle($trip_acommodaton.find('h3.title'));
                                }
                                if (!$trip_acommodaton.find('.form-content.form-acommodation').is(":visible"))
                                {
                                    $trip_acommodaton.find('.booknow.booknow_item').click();
                                }
                                return false;


                            };
                            $("<div><?php echo Jtext::_('COM_BOOKPRO_PLEASE_INPUT_CHECKIN_OR_CHECKOUT') ?></div>").dialog({
                                autoOpen: true,
                                title: '<?php echo Jtext::_('COM_BOOKPRO_WARNING') ?>',
                                modal: true,
                                buttons: btns,
                                closeOnEscape: false
                            });
                            return false;

                        }
                    }




                }

                for ($form_content_form_acommodation_interval = 0; $form_content_form_acommodation_interval < $trip_acommodaton.find('.form-content.form-acommodation').length; $form_content_form_acommodation_interval++)
                {
                    $form_content_form_acommodation = $trip_acommodaton.find('.form-content.form-acommodation:eq(' + $form_content_form_acommodation_interval + ')');
                    if ($form_content_form_acommodation.find('input.checkin').val() == '' && $form_content_form_acommodation.find('input.checkout').val() == '')
                    {
                        continue;
                    }
                    $setroomtyped = false;
                    for ($control_roomtype_interval = 0; $control_roomtype_interval < $form_content_form_acommodation.find('.listroom select.room_select').length; $control_roomtype_interval++)
                    {

                        $control_roomtype = $form_content_form_acommodation.find('.listroom select.room_select:eq(' + $control_roomtype_interval + ')');
                        if ($control_roomtype.val() != 0)
                        {
                            $setroomtyped = true;
                            break;
                        }
                    }
                    if ($setroomtyped == false)
                    {
                        var btns = {};
                        btns['yes'] = function() {
                            $(this).dialog("close");
                            $trip_acommodaton.find('.content').addClass('noice');
                            $("body, html").animate({scrollTop: $trip_acommodaton.offset().top}, 750);
                            if (!$trip_acommodaton.find('.content').is(":visible"))
                            {
                                h3_title_slidetoggle($trip_acommodaton.find('h3.title'));
                            }
                            if (!$trip_acommodaton.find('.form-content.form-acommodation').is(":visible"))
                            {
                                $trip_acommodaton.find('.booknow.booknow_item').click();
                            }
                            return false;


                        };
                        $("<div><?php echo Jtext::_('COM_BOOKPRO_PLEASE_SELECT_ROOM_TYPE') ?></div>").dialog({
                            autoOpen: true,
                            title: '<?php echo Jtext::_('COM_BOOKPRO_WARNING') ?>',
                            modal: true,
                            buttons: btns,
                            closeOnEscape: false
                        });
                        return false;

                    }
                }
                if ($select_room_select == true)
                {
                    $listpassengerselected = new Array();
                    $hasemptyselectbox = false;

                    $trip_acommodaton.find('.control-group.control-person select.passenger').each(function() {
                        if ($(this).closest('.control-group.control-person').css('display') != 'none')
                        {
                            $value_selected = $(this).val();
                            $hasemptyselectbox = $(this).val() == 0 ? true : $hasemptyselectbox;

                            if ($value_selected != 0 && $listpassengerselected.indexOf($value_selected) == -1)
                            {
                                $listpassengerselected.push($value_selected);
                            }
                        }
                    });

                    if ($listpassengerselected.length == $listperson_stander.length && $listpassengerselected.length < $trip_acommodaton.find('.control-group.control-person:visible select.passenger').length)
                    {
                        var btns = {};
                        btns['yes'] = function() {
                            $(this).dialog("close");
                            $trip_acommodaton.find('.content').addClass('noice');
                            $("body, html").animate({scrollTop: $trip_acommodaton.offset().top}, 750);
                            console.log($trip_acommodaton.find('select.passenger[value="0"]:first'));
                            if (!$trip_acommodaton.find('.content').is(":visible"))
                            {
                                h3_title_slidetoggle($trip_acommodaton.find('h3.title'));
                            }
                            if (!$trip_acommodaton.find('.form-content.form-acommodation').is(":visible"))
                            {
                                $trip_acommodaton.find('.booknow.booknow_item').click();
                            }
                            return false;


                        };
                        $("<div><?php echo Jtext::_('COM_BOOKPRO_YOU_CHOOSE_TOO_MANY_ROOM') ?></div>").dialog({
                            autoOpen: true,
                            title: '<?php echo Jtext::_('COM_BOOKPRO_WARNING') ?>',
                            modal: true,
                            buttons: btns,
                            closeOnEscape: false
                        });
                        return false;
                    }
                    if ($hasemptyselectbox == true)
                    {

                        var btns = {};
                        btns['yes'] = function() {
                            $(this).dialog("close");
                            $trip_acommodaton.find('.content').addClass('noice');
                            $("body, html").animate({scrollTop: $trip_acommodaton.offset().top}, 750);
                            console.log($trip_acommodaton.find('select.passenger[value="0"]:first'));
                            if (!$trip_acommodaton.find('.content').is(":visible"))
                            {
                                h3_title_slidetoggle($trip_acommodaton.find('h3.title'));
                            }
                            if (!$trip_acommodaton.find('.form-content.form-acommodation').is(":visible"))
                            {
                                $trip_acommodaton.find('.booknow.booknow_item').click();
                            }



                        };
                        $("<div><?php echo Jtext::_('COM_BOOKPRO_PLEASE_ADD_PASSENGER_FOR_ROOM') ?></div>").dialog({
                            autoOpen: true,
                            title: '<?php echo Jtext::_('COM_BOOKPRO_WARNING') ?>',
                            modal: true,
                            buttons: btns,
                            closeOnEscape: false
                        });
                        return false;

                    }

                }


            }
            ;
            $('.setroomselected .content').removeClass('noice');
            //event.preventDefault();








            //event.preventDefault();
        });
    });

</script>



