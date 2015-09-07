<?php
$app = JFactory::getApplication();
$input = $app->input;
$document = JFactory::getDocument();
$jlang = JFactory::getLanguage();
$jlang->load('com_bookpro', JPATH_SITE, 'en-GB', true);
JToolBarHelper::save('savepassenger');
JToolBarHelper::cancel();
$jlang->load('com_bookpro', JPATH_SITE, $jlang->getDefault(), true);
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
JToolBarHelper::title(JText::_('COM_BOOKPRO_PASSENGER_EDIT')); 
$local = substr($lang->getTag(), 0, 2);
$document->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js");
if ($local != 'en') {
    $document->addScript(JURI::root() . 'components/com_bookpro/assets/js/validatei18n/messages_' . $local . '.js');
}
$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/jquery-ui.js');
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/jquery-ui.css');
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/view-order.css');

$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/jquery-ui-timepicker-addon.js');
?>
<form name="adminForm" id="adminForm" class="frontPassengerForm" action="index.php" method="post" >
    <div class="box-item span12 form-horizontal">
        <fieldset>
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
                                        <input type="radio"  name="gender" id="inlineCheckbox1" value="Male">Male  
                                    </label>
                                    <label class="">
                                        <input type="radio"  name="gender" id="inlineCheckbox2" value="Female">Female   
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
                                <input   class="inputbox required" type="email" 
                                         name="email" 
                                         value="<?php echo $passenger->email ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>" />
                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="confirm_email"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_CONFIRM_EMAIL'); ?> *
                            </label>
                            <div class="controls">
                                <input  class="inputbox required confirm_email" type="email" 
                                        name="pconfirm_email" id="confirm_email" 
                                        value="<?php echo $passenger->email ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_CONFIRM_EMAIL'); ?>" />
                            </div>
                        </div>


                    </div>
                    <div class="span6">
                        <h5 class="passenger_detail"><?php echo JText::_('COM_BOOKPRO_PASSENGER_DETAIL') ?></h5>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="phone1"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE1'); ?> *
                            </label>
                            <div class="controls">
                                <select class="validate-select required" style="width:25%;height:24px;">
                                    <option>Mobile</option>
                                    <option>Home Phone</option>

                                </select>
                                <input style="width:24%;" class="input-small required phone1" type="text" 
                                       name="phone1" id="phone1" 
                                       value="<?php echo $passenger->phone1 ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE1'); ?>" />
                            </div>
                        </div>
                        <div class="control-group" style="padding-bottom:5px;">
                            <label class="control-label" for="roomtype"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE2'); ?>
                            </label>
                            <div class="controls">
                                <select style="width:25%;height:24px;">
                                    <option>Mobile</option>
                                    <option>Home Phone</option>

                                </select>
                                <input style="width:24%;" class="input-mini inputbox phone2" type="text" 
                                       name="phone2" id="phone2" 
                                       value="<?php echo $passenger->phone2 ?>"  placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE2'); ?>" />
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
                    <div class="span12">
                        <h3 class="total_trip"><?php echo JText::_('COM_BOOKPRO_ADDITIONAL_REQUEST') ?></h3>
                        <div class="row-fluid">
                            <div class="span10">
                                <?php echo JText::_('COM_BOOKPRO_DO_YOU_HAVE_ANY') ?>
                            </div>
                            <div class="form-inline span2 offset2">
                                <label class="checkbox inline" style="padding:0px!important">
                                    <input name="aditional_request" type="checkbox" id="inlineCheckbox1" value="1">YES
                                </label>
                                <label class="checkbox inline">
                                    <input name="aditional_request" type="checkbox" id="inlineCheckbox2" value="0">NO
                                </label>
                            </div>
                        </div>
                        <div class="click_box click_box_aditional_request">
                            <?php echo JText::_('COM_BOOKPRO_IF_YES_CLICK_THIS_BOX') ?>
                        </div>
                        <textarea style="display: none" name="textarea_aditional_request"  class="textarea" cols="10" rows="10"></textarea>
                        <div class="span12">
                            <div class="row-fluid">
                                <div class="span10">
                                    <?php echo JText::_('COM_BOOKPRO_DO_YOU_HAVE_ANY_SPECIAL_MEAL_REQUEMENT') ?>
                                </div>
                                <div class="form-inline span2 offset2">
                                    <label class="checkbox inline" style="padding:0px!important">
                                        <input name="meal_requement" type="checkbox" id="inlineCheckbox1" value="1">YES
                                    </label>
                                    <label class="checkbox inline">
                                        <input name="meal_requement" type="checkbox" id="inlineCheckbox2" value="0">NO
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="clr"></div>
                        <div class="click_box click_box-meal_requement">
                            <?php echo JText::_('COM_BOOKPRO_IF_YES_CLICK_THIS_BOX') ?>
                        </div>
                        <textarea style="display: none"  name="textarea_meal_requement" class="textarea" cols="10" rows="10"></textarea>

                        <div>

                            <?php echo JText::_('COM_BOOKPRO_SPECIAL_REQUEMENT') ?>

                        </div>
                        <div class="passenger_textarea">
                            <textarea style="display: none" name="special_requement"></textarea>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </fieldset>

    </div>
   <?php echo JHTML::_('form.token'); ?>
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
        width: auto;
        padding: 4px 2px;
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
        groupcheckbox($('input[name="meal_requement"]'));
        groupcheckbox($('input[name="aditional_request"]'));
    });
</script>