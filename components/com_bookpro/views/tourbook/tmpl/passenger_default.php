
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
    .form-horizontal .controls input
    {
        width: auto;
        padding: 4px 2px;
    }
    .form-horizontal .controls input.bridthday
    {
        width: 105px;
    }
    .form-horizontal.airpost_transfer input
    {
        width: 70px !important;
    }
    label.error {
        /* remove the next line when you have trouble in IE6 with labels in list */
        color: red;
        font-style: italic
    }
    div.error { display: none; }
    input {	border: 1px solid black; }
    input.checkbox { border: none }
    input:focus { border: 1px dotted black; }
    input.error { border: 1px dotted red; }
    form.cmxform .gray * { color: gray; }
</style>
<?php $this->currentstep = 3 ?>
<?php echo $this->loadTemplate("currentstep") ?>

<form name="frontTourForm" method="post"  action='index.php' id="frontTourForm">
    <div class="mainfarm">
        <div class="span8">
            <div class="row-fluid">
                <div class="form-horizontal">
                    <h3 style="text-transform: uppercase; background-color: #EEEEEE; padding:5px; text-align: left"><?php echo JText::_('COM_BOOKPRO_PASSENGER_INFOMATION') ?></h3>
                    <div><?php echo JText::_('COM_BOOKPRO_PASSENGER_INFOMATION_DESCRIPTION') ?></div>
                    <?php echo $this->loadTemplate("item") ?>
                </div>
            </div>





            <?php echo FormHelper::bookproHiddenField(array('controller' => 'tourbook', 'task' => 'show_form_payment', 'Itemid' => JRequest::getInt('Itemid'))) ?>
            <div class="row-fluid" style="text-align: right;padding: 20px 10px">
                <a href="index.php?option=com_bookpro&view=tourbook&layout=option&tpl=default" class="btn back"><?php echo JText::_('Prev') ?></a>
                <input class="btn next" type="submit" name="next"  value="Next"/>
            </div>
        </div>
        <div class="span4 block_right">
            <?php
            $this->setLayout('option');
            ?>
            <div class="checkinandcheckout">
                <?php echo $this->loadTemplate("checkinandcheckout") ?>
            </div>
            <?php echo $this->loadTemplate("listpassenger") ?>
            <?php echo $this->loadTemplate("totaltripprice") ?>
            <?php
            $this->setLayout('passenger');
            echo $this->loadTemplate("listpassenger")
            ?>

        </div>

    </div>

</form>
<style type="text/css">
    .form-horizontal .control-label
    {
        width: auto;
        text-align: left;


    }
    .form-horizontal .controls
    {
        margin-left: auto;
    }
    .form-horizontal .controls input
    {
        width: auto;
        padding: 4px 2px;
    }
    .form-horizontal .controls input.bridthday
    {
        width: 105px;
    }
    .mainfarm .controls input.redrequiment
    {
        border: 1px solid red;
    }
</style>

<script type="text/javascript">
    jQuery(document).ready(function($) {



        $(document).on('click', 'ul.passengers a.passenger_edit', function() {
            $li_passenger = $(this).closest('li.passenger');
            $indexoflipassenger = $li_passenger.index();
            $('div.passenger_form').each(function($index) {

                if ($indexoflipassenger == $index)
                {
                    $(this).css({
                        display: "block"
                    });
                }
                else
                {
                    $(this).css({
                        display: "none"
                    });
                }
            });

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

        $(document).on('click', '.leader_edit input.leader', function() {

            if ($(this).is(":checked")) {
                var group = "input.leader";
                $(group).prop("checked", false);
                $(this).prop("checked", true);
            } else {
                $(this).prop("checked", false);
            }
        });

        $(document).on('click', '.aditional_request', function() {

            if ($(this).is(":checked")) {
                var group = "input:checkbox[name='" + $(this).attr("name") + "']";
                $(group).prop("checked", false);
                $(this).prop("checked", true);
            } else {
                $(this).prop("checked", false);
            }
            $passenger_form = $(this).closest('.passenger_form');
            if ($(this).is(":checked") && $(this).val() == 1) {
                $passenger_form.find('.textarea.textarea_aditional_request').show();
                $passenger_form.find('.click_box_aditional_request').hide();

            } else
            {
                $passenger_form.find('.textarea.textarea_aditional_request').hide();
                $passenger_form.find('.click_box_aditional_request').show();
            }
            if ($(this).is(":checked") && $(this).val() == 1) {
                $passenger_form.find('.textarea_aditional_request').rules('add', {
                    required: true,
                    messages: {
                        required: "Enter something else"
                    }
                });
            }
            else
            {
                $passenger_form.find('.textarea_aditional_request').rules('remove');
            }


        });
        $(document).on('click', '.expand', function() {
        	expand($(this));
        });
        function expand(object)
        {
        	object.next('.passenger_item').slideToggle(500);
        }
        $(document).on('click', '.meal_requement', function() {

            if ($(this).is(":checked")) {
                var group = "input:checkbox[name='" + $(this).attr("name") + "']";
                $(group).prop("checked", false);
                $(this).prop("checked", true);
            } else {
                $(this).prop("checked", false);
            }
            $passenger_form = $(this).closest('.passenger_form');
            if ($(this).is(":checked") && $(this).val() == 1) {
                $passenger_form.find('.textarea.textarea_meal_requement').show();
                $passenger_form.find('.click_box-meal_requement').hide();

            } else
            {
                $passenger_form.find('.textarea.textarea_meal_requement').hide();
                $passenger_form.find('.click_box-meal_requement').show();
            }
            if ($(this).is(":checked") && $(this).val() == 1) {
                $passenger_form.find('.textarea_meal_requement').rules('add', {
                    required: true,
                    messages: {
                        required: "Enter something else"
                    }
                });
            }
            else
            {
                $passenger_form.find('.textarea_meal_requement').rules('remove');
            }

        });

        function groupcheckbox($object)
        {
            $object.click(function() {
                if ($object.is(":checked")) {
                    var group = "input:checkbox[name='" + $object.attr("name") + "']";
                    $(group).prop("checked", false);
                    $(this).prop("checked", true);
                } else {
                    $(this).prop("checked", false);
                }
            });
        }
        stylewidthcontrol($('.passenger_form'));
        $('.passport_issue').datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            maxDate: new Date(),
            buttonImageOnly: true,
            buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/callender.png',
            onSelect: function(selected) {
            }
        });
        $('.passport_expiry').datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            minDate: new Date(),
            buttonImageOnly: true,
            buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/callender.png',
            onSelect: function(selected) {
            }
        });
        $('.birthday').datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            maxDate: new Date(),
            buttonImageOnly: true,
            buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/callender.png',
            onSelect: function(selected) {
            }
        });
        $.validator.setDefaults({
            ignore: []
        });

        //$('#frontTourForm').validate({// initialize the plugin


        $('#frontTourForm').validate({// initialize the plugin

            ignore: ".ignore",

            invalidHandler: function(e, validator) {
                if (validator.errorList.length)
                {
                    $('.passenger_form').css({
                        display: "none"
                    });
                    $passenger_form = $(validator.errorList[0].element).closest('.passenger_form');
                    $passenger_form.css({
                        display: "block"
                    });
                }
            },
            rules: {

            }

        });
        $.validator.addMethod('selectcheck', function (value) {
            return (value != '0');
            }, "This field is required");
        $('select.validate-select').each(function(){
        	$(this).rules('add', {
        		selectcheck: true
            });



        });
        //$.removeData($('#frontTourForm').get(0), 'validator');






    });

</script>




