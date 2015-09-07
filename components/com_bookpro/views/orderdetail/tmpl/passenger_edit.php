<?php
$app = JFactory::getApplication();
$input = $app->input;
$document = JFactory::getDocument();
AImporter::model("countries", 'passenger');
JHtml::_('jquery.ui');
JHtml::_('jquery.framework');
JHtml::_('behavior.calendar');
JHtmlBehavior::formvalidation();
$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/jquery-ui-timepicker-addon.js');
$passengerModel = new BookProModelPassenger();
$passengerModel->setId($input->get('passenger_id'));
$passenger = $passengerModel->getObject();
$lang = JFactory::getLanguage();
$local = substr($lang->getTag(), 0, 2);
$document->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js");
if ($local != 'en') {
    $document->addScript(JURI::root() . 'components/com_bookpro/assets/js/validatei18n/messages_' . $local . '.js');
}
$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/jquery.ui.datepicker.js');
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/jquery-ui.css');
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/view-order.css');

$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/jquery-ui-timepicker-addon.js');
?>
<form name="frontPassengerForm" id="frontPassengerForm" class="frontPassengerForm" action="index.php" method="post" >
<h2><?php echo JText::_('COM_BOOKPRO_PASSENGER_EDIT') ?></h2>
<div class="view-control row-fluid">
                <div class="span9"> </div>
                <div class="span3">
                    <input type="submit" class="btn" value="<?php echo JText::_('Save') ?>"/>
                    <input type="button" class="btn" value="<?php echo JText::_('Cancel') ?>"/>
                </div>
            </div>

    <div class="box-item row-fluid form-horizontal">




            <div   class="passenger_form">
                <h4 class="passenger_full_name"><?php echo JText::_('PASSENGER') ?>&nbsp;1:<?php echo $fullname ?></h4>
                <div class="span12 passenger_item">
                    <div class="span6">
                        <h5 class="passenger_detail"><?php echo JText::_('COM_BOOKPRO_PASSENGER_DETAIL') ?></h5>


                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="roomtype"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME'); ?> *
                            </label>
                            <div class="controls">
                                <?php echo $passenger->firstname ?>
                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="lastname"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME'); ?> *
                            </label>
                            <div class="controls">
                                <?php echo $passenger->lastname ?>
                            </div>
                        </div>
                        <div class="control-group form-inline">

                            <label class="control-label" for="gender"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_GENDER'); ?> *
                            </label>
                            <div class="form-inline">

                                <fieldset class="radio required" id="gender">
                                    <label class="">
                                        <input type="radio" <?php echo $passenger->gender=='male'?' checked="checked"  ':'' ?> name="gender" id="inlineCheckbox1" value="male">Male
                                    </label>
                                    <label class="">
                                        <input type="radio" <?php echo $passenger->gender=='female'?' checked="checked"  ':'' ?>  name="gender" id="inlineCheckbox2" value="female">Female
                                    </label>
                                </fieldset>


                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="birthday"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_DATE_OF_BIRTH'); ?> *
                            </label>
                            <div class="controls">
                                <input class="inputbox required birthday validate-birth" type="text"
                                       name="birthday"
                                       value="<?php echo JHtml::_('date', $passenger->birthday, "d-m-Y"); ?>" />

                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="email"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?> *
                            </label>
                            <div class="controls">
                                <input   class="input required" type="email"
                                         name="email"
                                         value="<?php echo $passenger->email ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>" />
                            </div>
                        </div>


                    </div>
                    <div class="span6">
                        <h5 class="passenger_detail"><?php echo JText::_('COM_BOOKPRO_PASSENGER_DETAIL') ?></h5>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="homephone"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_HOMEPHOME'); ?> *
                            </label>
                            <div class="controls">
                                <input style="width:24%;" class="input-small required homephone" type="text"
                                       name="homephone" id="homephone"
                                       value="<?php echo $passenger->homephone ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_HOMEPHOME'); ?>" />
                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="mobile"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE'); ?>
                            </label>
                            <div class="controls">
                                <input style="width:24%;" class="input-mini inputbox mobile" type="text"
                                       name="mobile" id="mobile"
                                       value="<?php echo $passenger->mobile ?>"  placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE'); ?>" />
                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="address"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_STREET_ADDRESS'); ?>
                            </label>
                            <div class="controls">
                                <input class="inputbox address" type="text"
                                       name="address" id="address"
                                       value="<?php echo $passenger->address ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_STREET_ADDRESS'); ?>" />
                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="suburb"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_SUBURB_TOWN'); ?>
                            </label>
                            <div class="controls">
                                <input class="inputbox suburb" type="text"
                                       name="suburb" id="suburb"
                                       value="<?php echo $passenger->suburb ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_SUBURB_TOWN'); ?>" />
                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="roomtype"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_STATE_PROVINCE'); ?>
                            </label>
                            <div class="controls">
                                <input class="inputbox privince" type="text"
                                       name="privince" id="privince"
                                       value="<?php echo $passenger->province ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_STATE_PROVINCE'); ?>" />
                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="roomtype"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_POST_CODE_ZIP'); ?>
                            </label>
                            <div class="controls">
                                <input class="inputbox code_zip" type="text"
                                       name="code_zip" id="code_zip"
                                       value="<?php echo $passenger->code_zip ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_POST_CODE_ZIP'); ?>" />
                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="country1"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_RES_COUNTRY'); ?> *
                            </label>
                            <?php echo BookProHelper::getCountryTourBookSelect($select, 'country_id', 'country1'); ?>
                        </div>

                    </div>

                </div>
                <div class="span12 passenger_item">

                    <div class="span6">
                        <h5 class="passenger_detail"><?php echo JText::_('COM_BOOKPRO_PASSPORT_DETAIL') ?></h5>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="country_id"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_NAITIONALITY'); ?> *
                            </label>
                            <?php echo BookProHelper::getCountryTourBookSelect($passenger->country_id, 'country_id', 'country_id'); ?>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="passport"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_NO'); ?> *
                            </label>
                            <div class="controls">
                                <input class="inputbox required passport" type="text"
                                       name="passport" id="passport"
                                       value="<?php echo $passenger->passport ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_NUMBER'); ?>" />
                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="passport_issue"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_ISSUE_DATE'); ?> *
                            </label>
                            <div class="controls">
                                <input  class="inputbox required passport_issue validate-birth" type="text"
                                        name="passport_issue"
                                        value="<?php echo JHtml::_('date', $passenger->passport_issue, "d-m-Y"); ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_ISSUE_DATE'); ?>" />


                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="passport_expiry"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_EXPIRY_DATE'); ?> *
                            </label>
                            <div class="controls">
                                <input  class="inputbox required passport_expiry validate-birth" type="text"
                                        name="passport_expiry"
                                        value="<?php echo JHtml::_('date', $passenger->passport_expiry, "d-m-Y"); ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_EXPIRY_DATE'); ?>" />
                            </div>
                        </div>

                    </div>
                    <div class="span6">
                        <h5 class="passenger_detail"><?php echo JText::_('COM_BOOKPRO_EMERGENCY_CONTACT') ?></h5>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="roomtype"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_CONTACT_NAME'); ?>
                            </label>
                            <div class="controls">
                                <input class="inputbox  emergency_name" type="text"
                                       name="emergency_name" id="emergency_name"
                                       value="<?php echo $passenger->emergency_name ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_CONTACT_NAME'); ?>" />
                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="roomtype"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE_PHONE'); ?>
                            </label>
                            <div class="controls">
                                <input class="inputbox emergency_mobile" type="text"
                                       name="emergency_mobile" id="emergency_mobile"
                                       value="<?php echo $passenger->emergency_mobile ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE_PHONE'); ?>" />
                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="roomtype"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_HOME_PHONE'); ?>
                            </label>
                            <div class="controls">
                                <input class="inputbox emergency_homephone" type="text"
                                       name="emergency_homephone" id="emergency_homephone"
                                       value="<?php echo $passenger->emergency_homephone ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_HOME_PHONE'); ?>" />
                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="roomtype"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAI_ADDRESS'); ?>
                            </label>
                            <div class="controls">
                                <input class="inputbox emergency_address" type="text"
                                       name="emergency_address" id="emergency_address"
                                       value="<?php echo $passenger->emergency_address ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAI_ADDRESS'); ?>" />
                            </div>
                        </div>


                    </div>

                </div>
                <?php if ($this->cart->stype == '') { ?>
                    <div class="row-fluid">
                        <h3 class="total_trip"><?php echo JText::_('COM_BOOKPRO_ADDITIONAL_REQUEST') ?></h3>
                        <div class="row-fluid">
                            <div class="span8">
                                <?php echo JText::_('COM_BOOKPRO_DO_YOU_HAVE_ANY') ?>
                            </div>

                        </div>

                        <textarea  name="textarea_aditional_request"  class="textarea" cols="10" rows="10"></textarea>
                        <div class="row-fluid">
                            <div class="row-fluid">
                                <div class="span8">
                                    <?php echo JText::_('COM_BOOKPRO_DO_YOU_HAVE_ANY_SPECIAL_MEAL_REQUEMENT') ?>
                                </div>

                            </div>
                        </div>
                        <div class="clr"></div>

                        <textarea  name="textarea_meal_requement" class="textarea" cols="10" rows="10"></textarea>

                        <div>

                            <?php echo JText::_('COM_BOOKPRO_SPECIAL_REQUEMENT') ?>

                        </div>
                        <div class="passenger_textarea">
                            <textarea class="textarea" cols="10" rows="10"  name="special_requement"></textarea>
                        </div>
                    </div>
                <?php } ?>
            </div>


    </div>
    <input type="hidden" name="option"	value="com_bookpro" />
    <input type="hidden" name="controller" value="order" />
    <input type="hidden" name="task" value="savepassenger" />
    <input type="hidden" name="passenger_id" value="<?php echo $input->get('passenger_id') ?>" />
    <input type="hidden" name="order_id" value="<?php echo $input->get('order_id') ?>" />
</form>
<style type="text/css">
    .view-control
    {
        padding: 5px;
    }
    .box-item
    {
        background-color: #FFFFFF;
        border: 1px solid #CCCCCC;
        border-radius: 3px 3px 3px 3px;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
        line-height: 18px;
        transition: border 0.2s linear 0s, box-shadow 0.2s linear 0s;
        margin-bottom: 10px;
        margin-top: 10px;
    }
    .box-item .passenger_form
    {
    	padding: 5px;
    }
    .click_box {
        border: 1px solid #000000;
        margin-bottom: 10px;
        padding: 10px;
        color: #980000;
        font-weight: bold;
    }
    .textarea {
        width: 98%;
        height: 100px;
    }
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

    }

</style>
<script type="text/javascript">

    jQuery(document).ready(function($) {
        $('.passport_issue, .passport_expiry, .birthday').datepicker({
            dateFormat: "dd-mm-yy",
            changeMonth: true,
            changeYear: true,
            showButtonPanel: false,
            //maxDate: new Date(),
            buttonImageOnly: true,
            buttonImage: '<?php echo JUri::base() ?>components/com_bookpro/assets/images/callender.png',
            onSelect: function(selected) {
            }
        });
        $(document).on('submit', '#frontPassengerForm', function(event) {


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
        stylewidthcontrol($('.passenger_form'));

        $('#frontPassengerForm').validate({// initialize the plugin

            ignore: ".ignore"
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
        $(document).on('click', 'input[name="aditional_request"]', function() {
            if ($(this).is(":checked") && $(this).val() == 1) {
                $('textarea[name="textarea_aditional_request"]').show();
                $('.click_box_aditional_request').hide();

            } else
            {
                $('textarea[name="textarea_aditional_request"]').hide();
                $('.click_box_aditional_request').show();
            }
        });
        $(document).on('click', 'input[name="meal_requement"]', function() {
            if ($(this).is(":checked") && $(this).val() == 1) {
                $('textarea[name="textarea_meal_requement"]').show();
                $('.click_box-meal_requement').hide();

            } else
            {
                $('textarea[name="textarea_meal_requement"]').hide();
                $('.click_box-meal_requement').show();
            }
        });
        //groupcheckbox($('input[name="meal_requement"]'));
        //groupcheckbox($('input[name="aditional_request"]'));
    });
</script>