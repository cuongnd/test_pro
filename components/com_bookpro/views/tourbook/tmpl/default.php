
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
JHtml::_('bootstrap.framework');
AImporter::js('customer');
$config = AFactory::getConfig();

$lang = JFactory::getLanguage();
$local = substr($lang->getTag(), 0, 2);
$document = JFactory::getDocument();

$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/jquery-ui.css');
$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/jquery-ui.js');
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/view-tourbook.css');
$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/datetimebookingpicker/jquery.datetimebookingpick.js');
$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/js/datetimebookingpicker/jquery.datetimebookingpick.css');

/* validate using jquery validate plugin */
$lang = JFactory::getLanguage();
$local = substr($lang->getTag(), 0, 2);
$document = JFactory::getDocument();
$document->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
if ($local != 'en') {
    $document->addScript(JURI::root() . 'components/com_bookpro/assets/js/validatei18n/messages_' . $local . '.js');
}
/* end valdiate */
$this->minmaxperson;
?>



<style type="text/css">
    .form-horizontal .control-label
    {
        width: auto;
        padding-right:4px;


    }
    .datetimebookingpick-month td .price
    {
        font-size: 9px;
    }
    .form-horizontal .controls 
    {
        margin-left: auto;
    }
    .form-horizontal .controls input
    {
        width: auto;
        padding:0px 0px;
        border: 1px solid #000;
    }

    .form-horizontal.passenger_select select
    {
        height: 22px;
        margin: 0;
        padding: 0;
        width: 46px;
    }
    .form_passenger .control-group
    {
        margin-bottom:5px;
    }
    .form-horizontal.passenger_select .control-label
    {
        padding-top: 1px;
    }
    .mainfarm h3
    {
        text-transform: uppercase;
    }
    .form_passenger
    {
        background: #FBFBFB;
    }
    .form_passenger h3
    {
        color:#9A0000; 
    }
    .form_passenger .passenger_control .btn
    {

        background: none repeat scroll 0 0 #A8A8A6;

        border: 0;
        border: none;
        color: #FFFFFF;
        font-size: 12px;
        font-weight: bold;
        line-height: 13px;
        height: 20px;
        text-shadow: none;
        width: 36x;
    }
    input:focus{
        border-color: #E9322D !important;
    }  
    input:focus{
        border-color: #E9322D !important;
        box-shadow: 0 0 6px #F8B9B7 !important;
    }

    div.form_listtour {
        font-size: 15px;
        float:left;
        border: 1px solid #000000 !important;
        width: 100%;
        padding: 7px 0;
    }
    .tour-form-left {
        color: #003399;
        margin-left: 20px;

    }
    .tour-form-right{
        margin-right: 20px; 
    }

    h3.title-tour{
        font-size: 15px;
        margin-top: 40px;
    }



</style>
<div class="widgetbookpro-loading"></div>
<?php $this->currentstep = 1 ?>
<?php echo $this->loadTemplate("currentstep") ?>
<form name="frontTourForm" class="row" method="post" action='index.php' id="frontTourForm">
    <div class="mainfarm row">
        <div class="col-md-8">
            <div class="row-fluid">
                <?php
                $layout = new JLayoutFile('header_tour', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');

                $html = $layout->render($this->tour);
                echo $html;
                ?>

                <?php echo JText::_('COM_BOOKPRO_SELECT_TRAVELLER_DESCRITION') ?>
                <h3><?php echo JText::_('COM_BOOKPRO_SELECT_TRAVELLER') ?></h3>
                <div class="form-horizontal passenger_select">
                    <div class="row">
                        <div class="col-md-4"></div>
                        <div class="control-group col-md-2">
                            <label class="control-label" for="adult"><?php echo JText::_('COM_BOOKPRO_ADULT'); ?>
                            </label>
                            <div class="controls">
                                <?php echo JHtmlSelect::integerlist(0, 50, 1, 'adult', 'class="input-small person"', ($total = count($this->cart->person->adult)) ? $total : 1); ?>
                            </div>
                        </div>
                        <div class="control-group col-md-3">
                            <label class="control-label" for="teenner"><?php echo JText::_('COM_BOOKPRO_TEENNER'); ?>
                            </label>
                            <div class="controls">
                                <?php $total = count($this->cart->person->teenner) ?>
                                <?php echo JHtmlSelect::integerlist(0, 50, 1, 'teenner', 'class="input-small person"', !is_null($this->cart->person->teenner) ? $total : 0); ?>
                            </div>
                        </div>
                        <div class="control-group col-md-3">
                            <label class="control-label" for="children"><?php echo JText::_('COM_BOOKPRO_CHILDREN'); ?>
                            </label>

                            <div class="controls">
                                <?php $total = count($this->cart->person->children) ?>
                                <?php echo JHtmlSelect::integerlist(0, 100, 1, 'children', 'class="input-small person"', !is_null($this->cart->person->teenner) ? $total : 0); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form_passenger row-fluid">
                <div class="frontTourForm row_item adult" data="adult">
                    <h3><?php echo JText::_('COM_BOOKPRO_ADULT'); ?></h3>
                    <?php $this->person = "adult" ?>
                    <?php $this->passengers = $this->cart->person->adult ?>
                    <?php echo $this->loadTemplate("item") ?>
                </div>
                <div class="frontTourForm row_item teenner" data="teenner">
                    <h3><?php echo JText::_('COM_BOOKPRO_TEENNER'); ?></h3>
                    <?php $this->person = "teenner" ?>
                    <?php $this->passengers = $this->cart->person->teenner; ?>
                    <?php echo $this->loadTemplate("item") ?>
                </div>

                <?php $this->person = "children" ?>
                <?php $this->passengers = $this->cart->person->children ?>
                <div class="frontTourForm row_item children" data="children">
                    <h3><?php echo JText::_('COM_BOOKPRO_CHILDREN'); ?></h3>
                    <?php echo $this->loadTemplate("item") ?>
                </div>
                <div style="text-align: right;padding-right: 10px"></div>
            </div>
            <div class="form-calendar-tour">
                <h3 class="title-tour"><?php echo JText::_('COM_BOOKPRO_SELECT_TOUR_CLASS') ?></h3>
                <div class="form_listtour form-inline">

                    <span style="text-transform: uppercase" class="tour-form-left tourpackagetype_header pull-left">
                        <?php echo $this->packagetypes[$this->cart->packagetype_id]->title ?>-5 GUESTS ROM 12 YEARS UP
                    </span>

                    <span class="tour-form-right pull-right">
                        <?php if ($this->tour->stype == 'private') { ?>
                            CHANGE CLASS
                            <?php echo JHTML::_('select.genericlist', $this->packagetypes, 'packagetype_id', 'class="input-small packagetype"', 'id', 'title', ($this->cart->packagetype_id ? $this->cart->packagetype_id : $this->packagetypes[0]->id)); ?>
                        <?php } elseif ($this->tour->stype == 'shared') { ?>
                            CHANGE CLASS
                            <?php echo JHTML::_('select.genericlist', $this->listpackagerate, 'packagerate_id', 'class="input-small packagerate"', 'id', 'title', ($this->cart->packagerate_id ? $this->cart->packagerate_id : $this->packagetypes[0]->id)); ?>
                        <?php } ?>
                    </span> 

                </div>
                <div class="clr"></div>

                <div  id="listtour"></div>
                <div class="listtour_discription row-fluid">
                    <span><span class="icon request"></span><?php echo JText::_('COM_BOOKPRO_REQUEST_PLACE') ?></span>
                    <span><span class="icon close"></span><?php echo JText::_('COM_BOOKPRO_REQUEST_CLOSE') ?></span>
                    <span><span class="icon selected"></span><?php echo JText::_('COM_BOOKPRO_REQUEST_SELECTED_TOUR_DATE') ?></span>
                    <span><span class="icon promotion"></span><?php echo JText::_('COM_BOOKPRO_REQUEST_PROMOTION_DEPARTURES') ?></span>
                </div>
                <?php //echo "<pre>"; print_r($this->cart); die; ?>
                <input type="hidden" name="checkin" value="<?php echo JFactory::getDate($this->cart->date_checkin)->format('Y-m-d') ?>" />
                <input type="hidden" name="packagerate_id" value="<?php echo $this->cart->packagerate_id ?>" />
            </div>
            <?php echo FormHelper::bookproHiddenField(array('controller' => 'tourbook', 'task' => 'showformoption', 'Itemid' => JRequest::getInt('Itemid'))) ?>
            <div style="text-align: right;padding: 10px 0px">
                <input class="btn next" name="next" type="submit" value="Next"/>
            </div>

        </div>
        <div class="col-md-4 block_right">
            <?php
            $this->setLayout('option');
            ?>
            <div class="checkinandcheckout">
                <?php echo $this->loadTemplate("checkinandcheckout") ?>
            </div>

            <?php echo $this->loadTemplate("passenger") ?>
            <?php echo $this->loadTemplate("needsomehelp") ?>

        </div>
    </div>

</form>


<?php
$browser = JBrowser::getInstance();
$date_format = 'm-d-Y';
switch ($browser->getBrowser()) {
    case 'mozilla':
        $date_format = 'Y,m,d';

        break;
    case 'msie':
        $date_format = 'm-d-Y';
        break;
    default:
        break;
}
?>
<script type="text/javascript">
    jQuery(document).ready(function($) {

        $yearrange = {
            adult: ((new Date().getFullYear()) - 99) + ':' + (new Date().getFullYear() - 18),
            teenner: ((new Date().getFullYear()) - 17) + ':' + (new Date().getFullYear() - 12),
            children: (new Date().getFullYear() - 11) + ':' + new Date().getFullYear()
        }
        $yeardefault = {
            adult: (new Date().getFullYear() - 18),
            teenner: (new Date().getFullYear() - 12),
            children: (new Date().getFullYear() - 11)
        }
        function sethtmlfortag($respone_array)
        {
            $respone_array = $.parseJSON($respone_array);
            $.each($respone_array, function($index, $respone) {

                $($respone.key.toString()).html($respone.contents);
            });
        }
        var d = new Date();

        function changepackagetype($object)
        {
            $.ajax({
                type: "GET",
                url: 'index.php',
                data: (function() {
                    $data = {
                        option: 'com_bookpro',
                        controller: 'tourbook',
                        task: 'ajax_show_datetimebookingpick',
                        packagetype_id: $object.val(),
                        adult: $('select[name="adult"]').val(),
                        teenner: $('select[name="teenner"]').val()
                    }

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
                    $('#listtour').datetimebookingpick("destroy");
                    $result = $.parseJSON($result);
                    show_datetimebookingpick($result);
                }
            });

        }
        function rendercalendar()
        {
            $('.frontTourForm .passenger_item').each(function($index) {
                $persondata = $(this).closest('.frontTourForm').attr('data');
                if ($(this).find('input.birthday').hasClass('hasDatepicker'))
                {
                    $(this).find('input.birthday.hasDatepicker').removeClass('hasDatepicker');
                    $(this).find('img.ui-datepicker-trigger').remove();
                    $(this).find('input.birthday').removeAttr('id');
                }
                $(this).find('input.birthday').datepicker({
                    dateFormat: "dd-mm-yy",
                    changeMonth: true,
                    changeYear: true,
                    showButtonPanel: false,
                    maxDate: new Date(),
                    yearRange: $yearrange[$persondata],
                    buttonImageOnly: true,
                    defaultDate: new Date($yeardefault[$persondata], d.getMonth(), d.getDay()),
                    buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/calendar.jpg',
                    onSelect: function(selected) {
                        genderlistpassenger();

                        //                        var selected = $('.hotel_search #checkin').datepicker('getDate');
                        //                        selected.setDate(selected.getDate() + 1);
                        //                        $('.hotel_search #checkout').datepicker('setDate', selected);
                        //                        $(".hotel_search #checkout").datepicker("option", {
                        //                            minDate: selected
                        //                        });
                    }
                });
                //$(this).find('input.birthday').datepicker("setDate","<?php echo $jsdate ?>" );
            });
        }

        $strs = {
            adult: '<?php echo JText::_('COM_BOOKPRO_ADULT'); ?>',
            teenner: '<?php echo JText::_('COM_BOOKPRO_TEENNER'); ?>',
            children1: '<?php echo JText::_('COM_BOOKPRO_CHILDREN1'); ?>',
            children2: '<?php echo JText::_('COM_BOOKPRO_CHILDREN2'); ?>',
            children3: '<?php echo JText::_('COM_BOOKPRO_CHILDREN3'); ?>'
        };

        $('.passenger_select .person').each(function() {
            renderperson($(this));
        });
        rendercalendar();
        function calculate_age($birth_month, $birth_day, $birth_year)
        {
            var $depart = new Date('<?php echo JFactory::getDate($this->cart->checkin_date)->format($date_format) ?>');
            $depart_year = $depart.getFullYear();
            $depart_month = $depart.getMonth();
            $depart_day = $depart.getDate();
            $age = $depart_year - $birth_year;

            if ($depart_month < ($birth_month - 1))
            {
                $age--;
            }
            if ((($birth_month - 1) == $depart_month) && ($depart_day < $birth_day))
            {
                $age--;
            }
            return $age;
        }

        function genderlistpassenger()
        {


            $str_fullname = '';
            $('.frontTourForm .passenger_item').each(function($index) {
                $fronttourform = $(this).closest('.frontTourForm');
                if ($fronttourform.css("display") == "block") {

                    $persondata = $(this).closest('.frontTourForm').attr('data');
                    $fullname = $(this).find('input.firstname').val().concat(' ', $(this).find('input.lastname').val());
                    $birthday = $(this).find('input.birthday').datepicker('getDate');
                    $birthday = $birthday ? $birthday : new Date();

                    $age = calculate_age($birthday.getMonth(), $birthday.getDate(), $birthday.getFullYear());
                    if ($age < 2)
                    {
                        $persondata = 'children1';
                    } else if ($age >= 2 && $age <= 5)
                    {
                        $persondata = 'children2';
                    }
                    else if ($age >= 6 && $age <= 11)
                    {
                        $persondata = 'children3';
                    }
                    $birthday_text = $(this).find('input.birthday').val() != '' ? '(' + $strs[$persondata] + ')' : '';
                    $str_fullname += '<li class="' + $(this).parent('.frontTourForm').attr('data') + '">' + $fullname + $birthday_text + '</li>';
                }
            });
            $('ul.passenger').html($str_fullname);
        }
        genderlistpassenger();
        function renderperson($person)
        {
            $name = $person.attr('name');
            $('.frontTourForm.' + $name).css({
                display: 'block'
            });

            $select_total_person = $person.val();
            $select_total_person = $select_total_person == 0 ? 1 : $select_total_person;
            $current_person = $('.frontTourForm.' + $name).find('.passenger_item').length;
            if ($select_total_person < $current_person)
            {
                for ($i = 0; $i < $current_person - $select_total_person; $i++)
                {
                    $('.frontTourForm.' + $name).find('.passenger_item:last').remove();
                }
            }
            else
            {
                for ($i = 0; $i < $select_total_person - $current_person; $i++)
                {
                    $last_passenger_item = $('.frontTourForm.' + $name).find('.passenger_item:last');
                    $passenger_item_clone = $last_passenger_item.clone();
                    $passenger_item_clone.find('input.firstname').val('');
                    $passenger_item_clone.find('input.lastname').val('');
                    $passenger_item_clone.find('input.birthday').val('');

                    $last_passenger_item.after($passenger_item_clone);
                }
            }
            if ($person.val() == 0)
            {
                $('.frontTourForm.' + $name).css({
                    display: 'none'
                });
            }
            reder_name_passenger();

        }
        function reder_name_passenger()
        {
            $('.row_item').each(function() {
                $attr_data = $(this).attr('data');
                $(this).find('.passenger_item').each(function($index) {
                    $(this).find('.firstname').attr('name', 'person[' + $attr_data + '][' + $index.toString() + '][firstname]');
                    $(this).find('.lastname').attr('name', 'person[' + $attr_data + '][' + $index.toString() + '][lastname]');
                    $(this).find('.birthday').attr('name', 'person[' + $attr_data + '][' + $index.toString() + '][birthday]');
                });

            });

        }
        function writeulli($object)
        {
            $passenger_item = $object.closest('.passenger_item');

            $dataperson = $passenger_item.parent('.frontTourForm').attr('data');
            $birthday_text = $passenger_item.find('input.birthday').val() != '' ? '(' + $strs[$persondata] + ')' : '';

            $fullname = $passenger_item.find('input.firstname').val().concat(' ', $passenger_item.find('input.lastname').val(), $birthday_text);

            $('ul.passenger li.' + $dataperson + ':eq(' + ($passenger_item.index() - 1) + ')').html($fullname);
        }
        $(document).on('keyup', '.passenger_item input.firstname', function($event) {
            writeulli($(this));
        });
        $(document).on('blur', '.passenger_item input.lastname', function() {
            writeulli($(this));
        });
        $(document).on('blur', '.passenger_item input.firstname', function($event) {
            writeulli($(this));
        });
        $(document).on('blur', '.passenger_item input.birthday', function($event) {
            genderlistpassenger();
        });
        $(document).on('change', 'select[name="packagetype_id"]', function() {
            changepackagetype($(this));
        });
        $(document).on('click', '.savepassenger', function() {
            changepackagetype($('select[name="packagetype_id"]'));
        });

        $(document).on('keyup', '.passenger_item input.lastname', function() {
            writeulli($(this));
        });
        $(document).on('change', '.passenger_select .person', function() {

            renderperson($(this));
            rendercalendar();
            setnumberchildren();
            changepackagetype($('select[name="packagetype_id"]'));
            genderlistpassenger();

        });
        function setnumberchildren()
        {
            return false;
            $totalpersonadultandteenner = $('select[name="adult"]').val().toInt() + $('select[name="teenner"]').val().toInt();
            $value_selectedchildren = $('select[name="children"]').val();

            $('select[name="children"] option').each(function() {
                $option_value = $(this).val();
                if ($option_value > $totalpersonadultandteenner)
                {
                    $(this).attr('disabled', 'disabled');
                }
                else
                {
                    $(this).removeAttr('disabled');
                }
            });
            if ($totalpersonadultandteenner < $value_selectedchildren)
            {
                $('select[name="children"]').val($totalpersonadultandteenner);
                renderperson($('select[name="children"]'));
                rendercalendar();

            }


        }
        setnumberchildren();
        $(document).on('click', '.passenger_control input[name="add"]', function() {

            $frontTourForm = $(this).closest('.frontTourForm');
            $total_item = $frontTourForm.find('.passenger_item').length;
            $person = $frontTourForm.attr('data');

            $totalpersonallow = $('.passenger_select select[name="' + $person + '"] option:last').val();
            $total_adult_and_teenner = $('select[name="adult"]').val().toInt() + $('select[name="teenner"]').val().toInt();


            $passenger_item = $(this).closest('.passenger_item');
            if ($total_item < $totalpersonallow)
            {
                $passenger_item_clone = $passenger_item.clone();
                $passenger_item_clone.find('input.firstname').val('');
                $passenger_item_clone.find('input.lastname').val('');
                $passenger_item_clone.find('input.birthday').val('');
                $passenger_item.after($passenger_item_clone);
            }
            $total_item = $frontTourForm.find('.passenger_item').length;
            $('.passenger_select select[name="' + $person + '"]').val($total_item);
            genderlistpassenger();
            rendercalendar();
            reder_name_passenger();
            changepackagetype($('select[name="packagetype_id"]'));
            setnumberchildren();

        });
        $(document).on('click', '.passenger_control input[name="remove"]', function() {
            $frontTourForm = $(this).closest('.frontTourForm');
            $person = $frontTourForm.attr('data');
            $passenger_item = $(this).closest('.passenger_item');
            $total_item = 0;
            if ($frontTourForm.find('.passenger_item').length > 1)
            {
                $passenger_item.remove();
                $total_item = $frontTourForm.find('.passenger_item').length;
            }
            else
            if ($frontTourForm.find('.passenger_item').length == 1)
            {
                if ($person.trim() != 'adult') {
                    $frontTourForm.css({
                        display: 'none'
                    });
                    $total_item = 0;
                } else {
                    $total_item = 1;
                }
            }

            $('.passenger_select select[name="' + $person + '"]').val($total_item);

            genderlistpassenger();
            rendercalendar();
            reder_name_passenger();
            changepackagetype($('select[name="packagetype_id"]'));
            setnumberchildren();

        });
        $htmls = <?php echo json_encode($this->date_tours) ?>;
        show_datetimebookingpick($htmls);

        //changepackagetype($('select[name="packagetype_id"]'));

        function show_datetimebookingpick($htmls)
        {


            $('#listtour').datetimebookingpick({
                htmls: $htmls,
                dateFormat: 'yy-mm-dd',
                monthsToShow: 2,
                minDate: "today",
                changeMonth: false,
                defaultDate: new Date(),
                onSelect: function(dateText, inst) {
                    $packagerate_id = $(this).find('.datetimebookingpick-selected').attr('packagerate_id');
                    $('input[name="packagerate_id"]').val($packagerate_id);
                    var d = new Date(dateText);
                    var fmt2 = $.datetimebookingpick.formatDate("dd-mm-yyyy", d);
                    if (fmt2 != 'NaN-NaN-NaN')
                        $('input[name="checkin"]').val(fmt2);
                    $.ajax({
                        type: "GET",
                        url: 'index.php?option=com_bookpro&controller=tourbook&task=ajax_showfrom_checkinandcheckout',
                        data: $.param($('.form-calendar-tour').find(':input'), false),
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
                            //$('.checkinandcheckout').html(result);
                        }
                    });
                },
                onChangeMonthYear: function() {
                    //reload another calendar, past or future?


                }
            });
            //m-d-Y

            $('#listtour').datetimebookingpick("setDate", new Date('<?php echo JFactory::getDate($this->cart->checkin_date)->format($date_format) ?>'));
        }
        //$("#frontTourForm").validate();
        $(document).on('submit', '#frontTourForm', function(event) {
            if ($('select[name="adult"]').val() == 0 && $('select[name="teenner"]').val() == 0)
            {
                var btns = {};
                btns['yes'] = function() {
                    $(this).dialog("close");
                    $('select[name="adult"]').focus();

                };
                $("<div><?php echo Jtext::_('COM_BOOKPRO_PLEASE_ADD_PASSENGER') ?></div>").dialog({
                    autoOpen: true,
                    title: '<?php echo Jtext::_('COM_BOOKPRO_WARNING') ?>',
                    modal: true,
                    buttons: btns,
                    closeOnEscape: false
                });
                return false;
            }
            $stop = false;
            $input_focus = null;
            for ($i = 0; $i < $('.form_passenger .row_item').length; $i++)
            {
                $row_item = $('.row_item:eq(' + $i + ')');

                if ($row_item.css("display") == "block")
                {
                    for ($j = 0; $j < $row_item.find('input').length; $j++)
                    {
                        $input = $row_item.find('input:eq(' + $j + ')');
                        if ($input.val().trim() == '')
                        {
                            $input_focus = $input_focus == null ? $input : $input_focus;
                            $input.css({
                                "border-color": "#E9322D",
                                "box-shadow": "0 0 6px #F8B9B7"
                            });
                            $stop = true;
                        }
                    }
                }
            }
            if ($stop)
            {
                $input_focus.focus();
                return false;
            }
            if ($('#listtour .datetimebookingpick-selected').length == 0)
            {
                var btns = {};
                btns['yes'] = function() {
                    $(this).dialog("close");
                    $('select[name="adult"]').focus();

                };
                $("<div><?php echo Jtext::_('COM_BOOKPRO_PLEASE_DATE') ?></div>").dialog({
                    autoOpen: true,
                    title: '<?php echo Jtext::_('COM_BOOKPRO_WARNING') ?>',
                    modal: true,
                    buttons: btns,
                    closeOnEscape: false
                });
                return false;
            }

            //event.preventDefault();

        });

    });

</script>


