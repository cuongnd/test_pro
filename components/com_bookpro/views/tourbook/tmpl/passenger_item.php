<?php
//echo "<pre>";print_r($this->cart);
$document = JFactory::getDocument();
JHtmlBehavior::framework();
JHtml::_('jquery.ui');
JHtml::_('jquery.framework');
JHtml::_('behavior.calendar');
JHtmlBehavior::formvalidation();
$document->addScript(JUri::root() . 'components/com_bookpro/assets/js/jquery-ui-timepicker-addon.js');

$persons = $this->cart->person;

$k = 0;
$a_key_persons = array(
    'adult' => 'adult',
    'teenner' => 'teenner',
    'children' => 'children'
);
foreach ($persons as $key_persons => $listperson) {
    if (!in_array($key_persons, $a_key_persons, true)) {
        continue;
    }
    for ($i = 0; $i < count($listperson); $i++) {
        $passenger = $listperson[$i];

        $fullname = $passenger->firstname . ' ' . $passenger->lastname;
        ?>
        <div <?php echo $k != 0 ? 'style="display: none"' : '' ?>  class="passenger_form">
            <h4 class="passenger_full_name"><?php echo JText::_('PASSENGER') ?>&nbsp;1:<?php echo $fullname ?></h4>
            <div class="span12 passenger_item">
                <div class="span6">
                    <h5 class="passenger_detail"><?php echo JText::_('COM_BOOKPRO_PASSENGER_DETAIL') ?></h5>

                    <div class="control-group" style="padding-bottom:5px;">
                        <!--  <label class="control-label" for="roomtype">
                        <?php echo JText::_('COM_BOOKPRO_CUSTOMER_TITLE'); ?>
                        </label>
                        <?php echo BookProHelper::formatGender('title'); ?>

                        <select name="title" style="width:73%;height:24px;">
                            <option><?php echo JText::_('COM_BOOKPRO_MALE') ?></option>
                            <option>2</option>
                            <option>3</option>
                            <option>4</option>
                            <option>5</option>
                        </select>
                        -->
                    </div>
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
                                    <input type="radio" class="required" <?php echo $passenger->gender=='male'?' checked="checked"':'' ?>  name="person[<?php echo $key_persons ?>][<?php echo $i ?>][gender]" id="inlineCheckbox1" value="male">Male
                                </label>
                                <label class="">
                                    <input type="radio" class="required" <?php echo $passenger->gender=='female'?' checked="checked"':'' ?>  name="person[<?php echo $key_persons ?>][<?php echo $i ?>][gender]" id="inlineCheckbox2" value="female">Female
                                </label>
                            </fieldset>


                        </div>
                    </div>
                    <div class="control-group" style="padding-bottom:5px;">
                        <label class="control-label" for="birthday"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_DATE_OF_BIRTH'); ?> *
                        </label>
                        <div class="controls">
                            <input class="inputbox required birthday validate-birth" type="text"
                                   name="person[<?php echo $key_persons ?>][<?php echo $i ?>][birthday]"
                                   value="<?php echo $passenger->birthday ?>" />

                        </div>
                    </div>
                    <div class="control-group" style="padding-bottom:5px;">
                        <label class="control-label" for="email"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?> *
                        </label>
                        <div class="controls">
                            <input   class="inputbox required email" type="email"
                                     name="person[<?php echo $key_persons ?>][<?php echo $i ?>][email]"
                                     value="<?php echo $passenger->email ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAIL'); ?>" />
                        </div>
                    </div>
                    <div class="control-group" style="padding-bottom:5px;">
                        <label class="control-label" for="confirm_email"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_CONFIRM_EMAIL'); ?> *
                        </label>
                        <div class="controls">
                            <input  class="inputbox required confirm_email" type="email" equalTo="input[name='person[<?php echo $key_persons ?>][<?php echo $i ?>][email]']"
                                    name="person[<?php echo $key_persons ?>][<?php echo $i ?>][confirm_email]" id="confirm_email"
                                    value="<?php echo $passenger->email ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_CONFIRM_EMAIL'); ?>" />
                        </div>
                    </div>


                </div>
                <div class="span6">
                    <h5 class="passenger_detail"><?php echo JText::_('COM_BOOKPRO_PASSENGER_DETAIL') ?></h5>
                    <div class="control-group" style="padding-bottom:5px;">
                        <label class="control-label" for="homephone"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_HOMEPHOME'); ?> *
                        </label>
                        <div class="controls">

                            <input  style="width:24%;" class="input-small required homephone" type="number"
                                   name="person[<?php echo $key_persons ?>][<?php echo $i ?>][homephone]" id="homephone"
                                   value="<?php echo $passenger->homephone ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_HOMEPHOME'); ?>" />
                        </div>
                    </div>

                    <div class="control-group validate-dropbox"  style="padding-bottom:5px;">
                        <label class="control-label" for="country1"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_RES_COUNTRY'); ?> *
                        </label>
                        <?php echo BookProHelper::getCountryTourBookSelect( $passenger->country1, 'country_id',"person[$key_persons][$i][country1]"); ?>
                    </div>
<div class="control-group validate-dropbox" style="padding-bottom:5px;">
                        <label class="control-label" for="country_id"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_NAITIONALITY'); ?> *
                        </label>
                        <?php echo BookProHelper::getCountryTourBookSelect( $passenger->country_id, 'country_id', "person[$key_persons][$i][country_id]"); ?>
                    </div>
                    <div class="control-group" style="padding-bottom:5px;">
                        <label class="control-label" for="passport"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_NO'); ?> *
                        </label>
                        <div class="controls">
                            <input class="inputbox required passport" type="text"
                                   name="person[<?php echo $key_persons ?>][<?php echo $i ?>][passport]" id="passport"
                                   value="<?php echo $passenger->passport ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_NUMBER'); ?>" />
                        </div>
                    </div>
                    <div class="control-group" style="padding-bottom:5px;">
                        <label class="control-label" for="passport_issue"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_ISSUE_DATE'); ?> *
                        </label>
                        <div class="controls">
                            <input  class="inputbox required passport_issue validate-birth" type="text"
                                    name="person[<?php echo $key_persons ?>][<?php echo $i ?>][passport_issue]"
                                    value="<?php echo $passenger->passport_issue ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_ISSUE_DATE'); ?>" />


                        </div>
                    </div>
                    <div class="control-group" style="padding-bottom:5px;">
                        <label class="control-label" for="passport_expiry"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_EXPIRY_DATE'); ?> *
                        </label>
                        <div class="controls">
                            <input  class="inputbox required passport_expiry validate-birth" type="text"
                                    name="person[<?php echo $key_persons ?>][<?php echo $i ?>][passport_expiry]"
                                    value="<?php echo $passenger->passport_expiry ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_PASSPORT_EXPIRY_DATE'); ?>" />
                        </div>
                    </div>
                </div>

            </div>
            <div class="expand span12" style="background: #EEEEEE;padding: 5px;cursor: pointer;text-transform: uppercase; font-weight: bold;">
					<?php echo JText::_('COM_BOOKPRO_EXPAND')?>
			</div>
            <div style="display: none" class="span12 passenger_item">

                <div class="span6">
                    <h5 class="passenger_detail"><?php echo JText::_('COM_BOOKPRO_OTHER_DETAIL') ?></h5>
                    <div class="control-group" style="padding-bottom:5px;">
                        <label class="control-label" for="mobile"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE'); ?>
                        </label>
                        <div class="controls">

                            <input style="width:24%;" class="input-mini inputbox mobile" type="number"
                                   name="person[<?php echo $key_persons ?>][<?php echo $i ?>][mobile]" id="mobile"
                                   value="<?php echo $passenger->mobile ?>"  placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE'); ?>" />
                        </div>
                    </div>
                    <div class="control-group" style="padding-bottom:5px;">
                        <label class="control-label" for="address"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_STREET_ADDRESS'); ?>
                        </label>
                        <div class="controls">
                            <input class="inputbox address" type="text"
                                   name="person[<?php echo $key_persons ?>][<?php echo $i ?>][address]" id="address"
                                   value="<?php echo $passenger->address ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_STREET_ADDRESS'); ?>" />
                        </div>
                    </div>
                    <div class="control-group" style="padding-bottom:5px;">
                        <label class="control-label" for="suburb"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_SUBURB_TOWN'); ?>
                        </label>
                        <div class="controls">
                            <input class="inputbox suburb" type="text"
                                   name="person[<?php echo $key_persons ?>][<?php echo $i ?>][suburb]" id="suburb"
                                   value="<?php echo $passenger->suburb ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_SUBURB_TOWN'); ?>" />
                        </div>
                    </div>
                    <div class="control-group" style="padding-bottom:5px;">
                        <label class="control-label" for="roomtype"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_STATE_PROVINCE'); ?>
                        </label>
                        <div class="controls">
                            <input class="inputbox privince" type="text"
                                   name="person[<?php echo $key_persons ?>][<?php echo $i ?>][privince]" id="privince"
                                   value="<?php echo $passenger->province ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_STATE_PROVINCE'); ?>" />
                        </div>
                    </div>
                    <div class="control-group" style="padding-bottom:5px;">
                        <label class="control-label" for="roomtype"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_POST_CODE_ZIP'); ?>
                        </label>
                        <div class="controls">
                            <input class="inputbox code_zip" type="text"
                                   name="person[<?php echo $key_persons ?>][<?php echo $i ?>][code_zip]" id="code_zip"
                                   value="<?php echo $passenger->code_zip ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_POST_CODE_ZIP'); ?>" />
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
                                   name="person[<?php echo $key_persons ?>][<?php echo $i ?>][emergency_name]" id="emergency_name"
                                   value="<?php echo $passenger->emergency_name ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_CONTACT_NAME'); ?>" />
                        </div>
                    </div>
                    <div class="control-group" style="padding-bottom:5px;">
                        <label class="control-label" for="roomtype"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE_PHONE'); ?>
                        </label>
                        <div class="controls">
                            <input class="inputbox emergency_mobile" type="text"
                                   name="person[<?php echo $key_persons ?>][<?php echo $i ?>][emergency_mobile]" id="emergency_mobile"
                                   value="<?php echo $passenger->emergency_mobile ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE_PHONE'); ?>" />
                        </div>
                    </div>
                    <div class="control-group" style="padding-bottom:5px;">
                        <label class="control-label" for="roomtype"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_HOME_PHONE'); ?>
                        </label>
                        <div class="controls">
                            <input class="inputbox emergency_homephone" type="text"
                                   name="person[<?php echo $key_persons ?>][<?php echo $i ?>][emergency_homephone]" id="emergency_homephone"
                                   value="<?php echo $passenger->emergency_homephone ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_HOME_PHONE'); ?>" />
                        </div>
                    </div>
                    <div class="control-group" style="padding-bottom:5px;">
                        <label class="control-label" for="roomtype"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAI_ADDRESS'); ?>
                        </label>
                        <div class="controls">
                            <input class="inputbox emergency_address" type="text"
                                   name="person[<?php echo $key_persons ?>][<?php echo $i ?>][emergency_address]" id="emergency_address"
                                   value="<?php echo $passenger->emergency_address ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_EMAI_ADDRESS'); ?>" />
                        </div>
                    </div>


                </div>

            </div>
            <?php
            $nonedaytrip = array(
                "nonedaytripprivate",
                "nonedaytripshared"
            );
            ?>
            <?php if (in_array($this->cart->stype, $nonedaytrip)) { ?>
                <div class="span12">
                    <h3 class="total_trip"><?php echo JText::_('COM_BOOKPRO_ADDITIONAL_REQUEST') ?></h3>
                    <div class="row-fluid">
                        <div class="span8">
                            <?php echo JText::_('COM_BOOKPRO_DO_YOU_HAVE_ANY') ?>
                        </div>
                        <div class="form-inline span2 offset2">
                            <label class="checkbox inline" style="padding:0px!important">
                                <input class="aditional_request" style="margin-top: 0;margin-right: 2px;" name="person[<?php echo $key_persons ?>][<?php echo $i ?>][aditional_request]" type="radio" id="inlineCheckbox1" value="1">YES
                            </label>
                            <label class="checkbox inline">
                                <input class="aditional_request" style="margin-top: 0;margin-right: 2px;" name="person[<?php echo $key_persons ?>][<?php echo $i ?>][aditional_request]" type="radio" id="inlineCheckbox2" value="0">NO
                            </label>
                        </div>
                    </div>
                    <div class="click_box click_box_aditional_request">
                        <?php echo JText::_('COM_BOOKPRO_IF_YES_CLICK_THIS_BOX') ?>
                    </div>
                    <textarea style="display: none" name="person[<?php echo $key_persons ?>][<?php echo $i ?>][textarea_aditional_request]"  class="textarea_aditional_request textarea" cols="10" rows="10"></textarea>
                    <div class="span12">
                        <div class="row-fluid">
                            <div class="span8">
                                <?php echo JText::_('COM_BOOKPRO_DO_YOU_HAVE_ANY_SPECIAL_MEAL_REQUEMENT') ?>
                            </div>
                            <div class="form-inline span2 offset2">
                                <label class="checkbox inline" style="padding:0px!important">
                                    <input class="meal_requement" style="margin-top: 0;margin-right: 2px;" name="person[<?php echo $key_persons ?>][<?php echo $i ?>][meal_requement]" type="radio" id="inlineCheckbox1" value="1">YES
                                </label>
                                <label class="checkbox inline">
                                    <input class="meal_requement" style="margin-top: 0;margin-right: 2px;" name="person[<?php echo $key_persons ?>][<?php echo $i ?>][meal_requement]" type="radio" id="inlineCheckbox2" value="0">NO
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="clr"></div>
                    <div class="click_box click_box-meal_requement">
                        <?php echo JText::_('COM_BOOKPRO_IF_YES_CLICK_THIS_BOX') ?>
                    </div>
                    <textarea  style="display: none" name="person[<?php echo $key_persons ?>][<?php echo $i ?>][textarea_meal_requement]" class="textarea_meal_requement textarea" cols="10" rows="10"></textarea>

                    <div>

                        <?php echo JText::_('COM_BOOKPRO_SPECIAL_REQUEMENT') ?>

                    </div>
                    <div class="passenger_textarea">
                        <textarea name="special_requement"></textarea>
                    </div>
                </div>
            <?php } ?>
        </div>
        <?php
        $k++;
    }
}
?>
