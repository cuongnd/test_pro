<?php
defined('_JEXEC') or die('Restricted access');
AImporter::js('master');
$config = AFactory::getConfig();
$user = JFactory::getUser();
$groups = $user->getAuthorisedGroups();
if (!$config->anonymous) {
    $this->customer = AFactory::getCustomer();
}

if (in_array($config->agentUsergroup, $groups)) {
    $this->customer = null;
}

$infoBooking=$this->cart->infoBooking;

$document = JFactory::getDocument();

$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/jquery.ui.datepicker.js');

$HotelRoomResponse = $this->cart->hotel['HotelRoomResponse'];

$HotelRoomResponse = $HotelRoomResponse[0] ? $HotelRoomResponse : array($HotelRoomResponse);
$HotelRoomResponse = JArrayHelper::pivot($HotelRoomResponse, 'roomTypeCode');
$room = $HotelRoomResponse[$this->cart->room_type];

$a_room = $room['RateInfos']['RateInfo']['RoomGroup']['Room'];
$a_room = $a_room[0] ? $a_room : array($a_room);
?>

<div class="row-fluid ">


<h2 class="headline-bar" style="margin-top: 0"><?php echo JText::_('COM_BOOKPRO_WHO_TRAVELING'); ?></h2>

<div class="row-fluid">
    Log in to your <a href="#">Expedia Account</a> Processing your request your loading or connect using <a href="">Facebook</a> to book faster!
</div>


<div class="row-fluid">
    <?php echo JText::_('COM_BOOKPRO_EXPEDIA_VALIDATE_YEAR_OLD') ?>
</div>
<?php for ($i = 0; $i < count($a_room); $i++) { ?>
    <?php $room_item = $a_room[$i] ?>
    <?php

    $BedType=$room['BedTypes']['BedType'];
    $BedType = $BedType[0] ? $BedType : array($BedType);
    $BedType=(object)$BedType;
    $BedType=AHtmlFrontEnd::getFilterSelect('infobooking[room'.($i+1).'BedTypeId]', JText::_("COM_BOOKPRO_SELECT_BED_TYPE"), $BedType, $select, false, 'class="input-medium required"', '@id', 'description');
    $smokingPreferences=$room['smokingPreferences'];
    $smokingPreferences=explode(',',$smokingPreferences);
    $smokingPreferencesText=array(
        'S'=>'Smooking',
        'NS'=>'none Smooking'
    );
    $listSmokingPreferences=array();
    foreach($smokingPreferences  AS $sItem)
    {
        $objectsmooking=new stdClass();
        $objectsmooking->id=$sItem;
        $objectsmooking->title=$smokingPreferencesText[$sItem];
        $listSmokingPreferences[]=$objectsmooking;
    }
    $cbsmooking=AHtmlFrontEnd::getFilterSelect('infobooking[room'.($i+1).'SmokingPreference]', JText::_("COM_BOOKPRO_SELECT_SMOOKING"), $listSmokingPreferences, $select, false, 'class="input-medium required"', 'id', 'title');
    ?>
    <div class="row-fluid room-item">
        <div class="row-fluid"><b>Room <?php echo $i + 1 ?> :</b><?php echo $room_item['numberOfAdults'] . ' adult' ?> <?php echo $room_item['numberOfChildren'] . ' children' ?></div>
        <div class="row-fluid">
            <div class="offset3 span2">
                <?php echo JText::_('Name contact') ?>
            </div>
            <div class="span7">
                <div class="control-group span6">
                    <label class="control-label" for="room<?php echo $i + 1 ?>FirstName"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME'); ?>
                    </label>

                    <div class="controls">
                        <?php
                        $inputName='room'.($i+1).'FirstName';
                        $firstName=$infoBooking[$inputName] ?>
                        <input class="inputbox required input-medium" type="text" id="room<?php echo $i + 1 ?>FirstName" name="infobooking[room<?php echo ($i+1) ?>FirstName]"  size="30" maxlength="50" value="<?php echo $firstName ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRSTNAME'); ?>"/>
                    </div>
                </div>
                <div class="control-group span6">
                    <label class="control-label" for="Room<?php echo $i + 1 ?>LastName"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME'); ?>
                    </label>

                    <div class="controls">
                        <?php
                        $inputname='room'.($i+1).'LastName';
                        $lastName=$infoBooking[$inputname] ?>

                        <input class="inputbox required input-medium" type="text" name="infobooking[room<?php echo ($i+1) ?>LastName]" id="room<?php echo $i + 1 ?>LastName" size="30" maxlength="50" value="<?php echo $lastName ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_LASTNAME'); ?>"/>
                    </div>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="offset3 span2">
                <?php echo JText::_('Phone contact') ?>
            </div>
            <div class="span7">
                <div class="row-fluid">
                    <div class="control-group span6">
                        <label class="control-label" for="countrycode"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_COUNTRYCODE'); ?>
                        </label>

                        <div class="controls">
                            <?php echo $this->getCountryCodeSelect(); ?>
                        </div>
                    </div>
                    <div class="control-group span6">
                        <label class="control-label" for="phonepriority"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_TELEPHONE_NUMBER_OF_PRIORITY'); ?>
                        </label>

                        <div class="controls">
                            <input class="inputbox input-medium" type="text" name="phonepriority" id="phonepriority" size="30" maxlength="50"
                                   value="<?php echo $this->customer->phonepriority ?>"
                                   placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_TELEPHONE_NUMBER_OF_PRIORITY'); ?>"/>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="row-fluid main-request-beds-smoking">
                        <div class="row-fluid">
                            <a data-toggle="collapse" data-target=".request-beds-smoking-<?php echo $i ?>" href="javascript:void(0)"><?php echo JText::_('Request Beds / Smoking permitted (optional)') ?></a>
                        </div>

                        <div class="row-fluid request-beds-smoking-<?php echo $i ?> collapse">
                            <div class="control-group span6">
                                <label class="control-label" for="countrycode"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_SELECT_BEDTYPE'); ?>
                                </label>

                                <div class="controls">
                                    <?echo $BedType ?>
                                </div>
                            </div>
                            <div class="control-group span6">
                                <label class="control-label" for="phonepriority"><?php echo JText::_('COM_BOOKPRO_SELECT_SMOOKING'); ?>
                                </label>

                                <div class="controls">
                                    <?php echo $cbsmooking ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row-fluid main-request-beds-smoking">
                        <div class="row-fluid">
                            <a data-toggle="collapse" data-target=".special-requirements-<?php echo $i ?>" href="javascript:void(0)"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_SPECIAL_REQUIREMENTS') ?></a>
                        </div>
                        <div class="row-fluid special-requirements-<?php echo $i ?> collapse">
                            <div class="control-group span12">
                                <label class="control-label" for="countrycode">No guarantee of special requirements (eg beds, late check) and may incur surcharges. You should confirm with the hotel to ensure your requirements are met.
                                </label>

                                <div class="controls">
                                    <textarea class="form-control" style="width: 90%" rows="3"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>




<?php } ?>
<h2 class="headline-bar"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_HOW_WOLD_YOU_LIKE_TO_PAY'); ?></h2>

<div class="form-horizontal pay-type">
    <div class="row-fluid coupon-code-wapper">
        <div class="row-fluid">
            <a href="javascript:void(0)" data-toggle="collapse" data-target=".coupon-code"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_ENTER_COUPON') ?></a>
        </div>
        <div class="row-fluil coupon-code  collapse">
            <div class="Offset5 control-group">
                <label class="control-label" for="coupon_code"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_ENTER_COUPON_CODE'); ?>
                </label>

                <div class="controls">
                    <div class="row-fluid">
                        <input class="inputbox input-medium" type="text" name="coupon_code" id="coupon_code" size="30" maxlength="50" value="<?php echo $this->customer->address ?>" placeholder="<?php echo JText::_('Coupon code'); ?>"/>
                    </div>
                    <div class="row-fluid btn-apply-coupon">
                        <input type="button" class="btn" value="Apply coupon"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="control-group">


        <div class="controls">
            <img src="<?php echo JUri::root() ?>components/com_bookpro/assets/images/Visa.gif">
            <img src="<?php echo JUri::root() ?>components/com_bookpro/assets/images/mastercard.gif">
            <img src="<?php echo JUri::root() ?>components/com_bookpro/assets/images/JCB_Emblem_Color_SM.gif">
            <img src="<?php echo JUri::root() ?>components/com_bookpro/assets/images/Discover.gif">
            <img src="<?php echo JUri::root() ?>components/com_bookpro/assets/images/DCI_AM.gif">
            <img src="<?php echo JUri::root() ?>components/com_bookpro/assets/images/AMEX.gif">

        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="creditCardNumber"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_ENTER_CARD_NUMBER'); ?>
        </label>

        <div class="controls">
            <input class="inputbox required" type="text" name="infobooking[creditCardNumber]" id="creditCardNumber" size="30" maxlength="50"
                   value="<?php echo $this->customer->address ?>"
                   placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_ADDRESS'); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="cardtype"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_ENTER_CARD_TYPE'); ?>
        </label>

        <div class="controls">
            <?php echo JHTML::_('select.genericlist', $this->payment_methor['PaymentType'], 'infobooking[creditCardType]', 'id="country" ', 'code', 'name', $default); ?>

        </div>
    </div>


    <div class="control-group">
        <label class="control-label" for="expiration_date"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_ENTER_CARD_EXPIRATION_DATE'); ?>
        </label>

        <div class="controls">
            <div class="row-fluil">
                <div class="span3">
                    <?php
                    $now = JFactory::getDate('2013-01-01');
                    $list = array();
                    for ($i = 0; $i <= 11; $i++) {
                        $list[] = $now->format('M');
                        $now = $now->modify('+1 month');
                    }
                    echo AHtmlFrontEnd::getFilterSelect('infobooking[creditCardExpirationMonth]', JText::_("COM_BOOKPRO_EXPEDIA_MONTH"), $list, $select, false, 'class="input-small required"');
                    ?>
                </div>
                <div class="span3">
                    <?php
                    $now = JFactory::getDate();
                    $list = array();
                    for ($i = 0; $i <= 20; $i++) {
                        $item=new stdClass();
                        $item->id=$now->format('Y');
                        $item->title=$now->format('Y');
                        $list[] =$item ;
                        $now = $now->modify('+1 year');
                    }
                    echo AHtmlFrontEnd::getFilterSelect('infobooking[creditCardExpirationYear]', JText::_("COM_BOOKPRO_EXPEDIA_YEAR"), $list, $select, false, 'class="input-small required"','id','title');
                    ?>
                </div>
            </div>

        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="creditCardIdentifier"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_CARD_IDENTIFICATION_NUMBER'); ?>
        </label>

        <div class="controls">
            <input class="inputbox required input-small" type="text" name="infobooking[creditCardIdentifier]" id="creditCardIdentifier" size="30" value="<?php echo $this->customer->card_identification_number ?>" placeholder="<?php echo JText::_('Card Identification Number'); ?>"/>
            <a href="#"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_WHAT_THIS') ?><i class="icon icon-new-windows"></i></a>
        </div>
    </div>


    <div class="control-group">
        <label class="control-label" for="biiling_zip_code"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_BILLING_ZIP_CODE'); ?>
        </label>

        <div class="controls">
            <input class="inputbox" type="text" name="biiling_zip_code"
                   id="address" size="30" maxlength="50"
                   value="<?php echo $this->customer->biiling_zip_code ?>"
                   placeholder="<?php echo JText::_('Billing ZIP Code'); ?>"/>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="cardholder_name"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_CARDHOLDER_NAME'); ?>
        </label>

        <div class="controls">
            <input class="inputbox" type="text" name="cardholder_name"
                   id="address" size="30" maxlength="50"
                   value="<?php echo $this->customer->cardholder_name ?>"
                   placeholder="<?php echo JText::_('COM_BOOKPRO_EXPEDIA_CARDHOLDER_NAME'); ?>"/>
        </div>
    </div>
</div>


<h2 class="headline-bar"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_WHAT_EMAIL_SEND_CONFIRM'); ?></h2>

<div class="row-fluil form-horizontal">
    <div class="row-fluil">
        <?php echo JText::_('COM_BOOKPRO_EXPEDIA_PLEASE_INPUT_EMAIL_SEND_CONFIRM') ?>
    </div>
    <div class="control-group">
        <label class="control-label" for="email"><?php echo JText::_('Email'); ?>
        </label>

        <div class="controls">


            <input class="inputbox required" type="email" name="infobooking[email]"  size="30" maxlength="50" value="<?php echo $infoBooking['email'] ?>" placeholder="<?php echo JText::_('Email'); ?>"/>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label"><input name="nhanthongbao" id="nhanthongbao" type="checkbox">
        </label>

        <div class="controls">
            <label class="control-label" for="nhanthongbao" style="text-align: justify; width: 100%">
                <?php echo JText::_('COM_BOOKPRO_EXPEDIA_PLEASE_CLICK_THIS_BOX') ?>
            </label>
        </div>
    </div>
    <?php if (!$this->customer->id) { ?>
        <div class="row-fluil well " style="padding: 19px 1px;">
            <div class="row-fluil"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_CREATE_AN_ACCOUNT_FOR_PAYMENT') ?></div>
            <div class="row-fluil"><a href="javascript:void(0)"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_LEARN_MORE') ?><i class="icon icon-new-windows"></i></a></div>
            <div class="row-fluil"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_ENTER_A_PASSWORD_TO_CREATE') ?></div>
            <div class="control-group">
                <label class="control-label" for="password"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_PASSWORD'); ?>
                </label>

                <div class="controls">
                    <input class="inputbox " type="password" name="password" id="password" size="30" maxlength="50" value="<?php echo $this->customer->password ?>" placeholder="<?php echo JText::_('password'); ?>"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="confirm_password"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_CONFIRM_PASSWORD'); ?>
                </label>

                <div class="controls">
                    <input class="inputbox" type="password" name="confirm_password" id="confirm_password" size="30" maxlength="50" value="<?php echo $this->customer->password ?>" placeholder="<?php echo JText::_('Confirm password'); ?>"/>
                </div>
            </div>

        </div>
    <?php } ?>


</div>
<h2 class="headline-bar"><?php echo JText::_('COM_BOOKPRO_EXPEDIA_FILL_OUT_THE_BOOKING_DONE'); ?></h2>
<div class="row-fluil form-horizontal">
    <div class="control-group">
        <label class="control-label" for="firstName"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRST_NAME'); ?>
        </label>
        <?php
        $firstName=($firstName=$this->customer->firstname)?$firstName:$infoBooking['firstName']

        ?>

        <div class="controls">
            <input class="inputbox required" type="text" name="infobooking[firstName]"
                   id="firstName" size="30" maxlength="50"
                   value="<?php echo $firstName ?>"
                   placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_FIRST_NAME'); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="lastName"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_LAST_NAME'); ?>
        </label>

        <div class="controls">
            <?php
            $lastName=($lastName=$this->customer->lastName)?$lastName:$infoBooking['lastName'];

            ?>
            <input class="inputbox required" type="text" name="infobooking[lastName]"
                   id="lastName" size="30" maxlength="50"
                   value="<?php echo $lastName ?>"
                   placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_LAST_NAME'); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="homePhone"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_HOME_PHONE'); ?>
        </label>

        <div class="controls">
            <?php $homePhone=($homePhone=$this->customer->homePhone)?$homePhone:$infoBooking['homePhone']; ?>
            <input class="inputbox required" type="text" name="infobooking[homePhone]" id="homePhone" size="30" maxlength="50" value="<?php echo $homePhone ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_HOME_PHONE'); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="address1"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_ADDRESS'); ?>
        </label>
        <div class="controls">
            <?php $address1=($address1=$this->customer->address1)?$address1:$infoBooking['address1']; ?>
            <textarea name="infobooking[address1]" id="address1" class="textarea required"><?php echo $address1 ?></textarea>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="city"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_COUNTRY'); ?>
        </label>

        <div class="controls">
            <?php $city=($city=$this->customer->city)?$city:$infoBooking['city']; ?>
            <?php echo BookProHelper::getCountryList('infobooking[city]', 'placeholder="' . JText::_('COM_BOOKPRO_CUSTOMER_COUNTRY') . '"', $city, '') ?>
        </div>
    </div>


    <div class="control-group">
        <label class="control-label" for="postalCode"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_POSTAL_CODE'); ?>
        </label>

        <div class="controls">
            <?php $postalCode=($postalCode=$this->customer->postalCode)?$postalCode:$infoBooking['postalCode']; ?>
            <input class="inputbox" type="text" name="infobooking[postalCode]" id="postalCode" size="30" maxlength="50" value="<?php echo $postalCode ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_POSTAL_CODE'); ?>"/>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="mobile"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE'); ?>
        </label>

        <div class="controls">
            <input class="inputbox" type="text" name="mobile"
                   id="mobile" size="30" maxlength="50"
                   value="<?php echo $this->customer->mobile ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_MOBILE'); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="telephone"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE'); ?>
        </label>

        <div class="controls">
            <input class="inputbox" type="text" name="telephone"
                   id="telephone" size="30" maxlength="50"
                   value="<?php echo $this->customer->telephone ?>" placeholder="<?php echo JText::_('COM_BOOKPRO_CUSTOMER_PHONE'); ?>"/>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="email"><?php echo JText::_('Email'); ?>
        </label>

        <div class="controls">
            <input class="inputbox required" type="email" name="email" id="email" size="30" maxlength="50" value="<?php echo $this->customer->email ?>" placeholder="<?php echo JText::_('Email'); ?>"/>
        </div>
    </div>


</div>


</div>


<style type="text/css">
    .headline-bar {
        margin-top: 18px;
    }

    .panel {
        padding: 15px;
        margin-bottom: 20px;
        background-color: #ffffff;
        border: 1px solid #dddddd;
        border-radius: 4px;
        -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
    }

    .panel-heading {
        padding: 10px 15px;
        margin: -15px -15px 15px;
        font-size: 17.5px;
        font-weight: 500;
        background-color: #f5f5f5;
        border-bottom: 1px solid #dddddd;
        border-top-right-radius: 3px;
        border-top-left-radius: 3px;
    }

    .panel-footer {
        padding: 10px 15px;
        margin: 15px -15px -15px;
        background-color: #f5f5f5;
        border-top: 1px solid #dddddd;
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;
    }

    .panel-primary {
        border-color: #003366;
    }

    .panel-primary .panel-heading {
        color: #ffffff;
        background-color: #003366;
        border-color: #003366;
    }
    .icon-new-windows
    {
        background: url("data:image/gif;base64,R0lGODlhDgAOAJEDAP/+/////wBmmf///yH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS4wLWMwNjAgNjEuMTM0Nzc3LCAyMDEwLzAyLzEyLTE3OjMyOjAwICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6MENGNzQ5MzE1NjQzMTFFMEJCNkE5MjUxMTQzMDQ2QUEiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MENGNzQ5MzI1NjQzMTFFMEJCNkE5MjUxMTQzMDQ2QUEiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDowQ0Y3NDkyRjU2NDMxMUUwQkI2QTkyNTExNDMwNDZBQSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDowQ0Y3NDkzMDU2NDMxMUUwQkI2QTkyNTExNDMwNDZBQSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgH//v38+/r5+Pf29fTz8vHw7+7t7Ovq6ejn5uXk4+Lh4N/e3dzb2tnY19bV1NPS0dDPzs3My8rJyMfGxcTDwsHAv769vLu6ubi3trW0s7KxsK+urayrqqmop6alpKOioaCfnp2cm5qZmJeWlZSTkpGQj46NjIuKiYiHhoWEg4KBgH9+fXx7enl4d3Z1dHNycXBvbm1sa2ppaGdmZWRjYmFgX15dXFtaWVhXVlVUU1JRUE9OTUxLSklIR0ZFRENCQUA/Pj08Ozo5ODc2NTQzMjEwLy4tLCsqKSgnJiUkIyIhIB8eHRwbGhkYFxYVFBMSERAPDg0MCwoJCAcGBQQDAgEAACH5BAEAAAMALAAAAAAOAA4AAAIpnI+pyy0PnxKhWjFD1GfXxxmU9pGYWI7UOajW1X0RFL8hMlbA3c1SUgAAOw==");
    }
    .panel-success {
        border-color: #d6e9c6;
    }

    .panel-success .panel-heading {
        color: #468847;
        background-color: #dff0d8;
        border-color: #d6e9c6;
    }

    .panel-warning {
        border-color: #fbeed5;
    }

    .panel-warning .panel-heading {
        color: #c09853;
        background-color: #fcf8e3;
        border-color: #fbeed5;
    }

    .panel-danger {
        border-color: #eed3d7;
    }

    .panel-danger .panel-heading {
        color: #b94a48;
        background-color: #f2dede;
        border-color: #eed3d7;
    }

    .panel-info {
        border-color: #bce8f1;
    }

    .panel-info .panel-heading {
        color: #3a87ad;
        background-color: #d9edf7;
        border-color: #bce8f1;
    }

    .room-item {
        padding: 5px 0;
    }

    .control-group {
        padding: 10px 0;
    }

    .headline-bar {
        background-color: #003366;
        border-radius: 4px 4px 0 0;
        color: #FFFFFF;
        font-size: 16px;
        padding: 6px 18px;
        line-height: 30px;
    }

    .form-horizontal .control-group {
        margin-bottom: 0px;
    }

    .pay-type .control-group {
        padding: 10px 0;
    }

    .btn-apply-coupon {
        padding-top: 5px;
    }

    .coupon-code-wapper {
        border-bottom: 1px solid #CECECE;
        padding: 10px 0px;
    }

    .main-request-beds-smoking {
        border-bottom: 1px dotted #CECECE;
        padding: 3px 0px;

    }
</style>