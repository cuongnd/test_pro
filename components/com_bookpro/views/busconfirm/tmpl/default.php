<?php
/**
 * @package     Bookpro
 * @author         Nguyen Dinh Cuong
 * @link         http://ibookingonline.com
 * @copyright     Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version     $Id: default.php  23-06-2012 23:33:14
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
AImporter::helper('date', 'form', 'currency', 'bookpro');
AImporter::css('customer', 'bus');
JHtmlBehavior::formvalidation();
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');

$lang = JFactory::getLanguage();
$local = substr($lang->getTag(), 0, 2);
$config = AFactory::getConfig();

$document = JFactory::getDocument();

$document->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
if ($local != 'en') {
    $document->addScript(JURI::root() . 'components/com_bookpro/assets/js/validatei18n/messages_' . $local . '.js');
}
$cart = & JModelLegacy::getInstance('BusCart', 'bookpro');
$cart->load();
?>

<script>
	jQuery(document).ready(function ($) {
        $("input.birthday").datepicker({yearRange: "1930:2013", maxDate: -1, changeMonth: true, changeYear: true, showOn: "button", buttonImage: "<?php echo JUri::base()?>components/com_bookpro/assets/images/calendar.png", buttonImageOnly: true });
        $("input.birthday").datepicker("option", $.datepicker.regional['vi']);
        $("input.birthday").datepicker("option", "dateFormat", "dd-mm-yy");
    });
</script>
<script type = "text/javascript">

window.addEvent('domready', function () {

    document.formvalidator.setHandler('fname', function (value) {

        regex = /^[a-zA-Z ]+$/;

        return regex.test(value);

    });

    window.addEvent('domready', function () {

        document.formvalidator.setHandler('select', function (value) {
            return (value != 0);

        });

    });


});

</script>
<script type = "text/javascript">

Joomla.submitbutton = function (task) {

    var form = document.frontBusForm;


    if (document.formvalidator.validate(form.country_id) === false) {
        alert("<?php echo JText::_( 'COM_BOOKPRO_VALIDATE_COUNTRY', true ); ?>");
        form.duration.focus();
        return false;
    }

    if (document.formvalidator.isValid(form)) {

        form.task.value = task;

        form.submit();

    }

    else {

        alert('<?php echo JText::_('Fields highlighted in red are compulsory!'); ?>')

        return false;

    }

}

	</script>
<div>
    <div class = "span8">

<div class = "row-fluid ">
<form class="form-validate">

<h2 style = "margin-top: 0" class = "headline-bar"><?php echo JText::_('COM_BOOKPRO_WHO_IS_CAR_BOOKING') ?></h2>
<div class = "row-fluid"><?php echo JText::_('COM_BOOKPRO_NOTE_MUST_BE_18_YEARS_OR_OLDER') ?></div>
<div class = "row-fluid form-horizontal">
    <div class = "control-group">
        <label for = "derivername" class = "control-label"><?php echo JText::_('COM_BOOKPRO_DRIVER_NAME') ?></label>
        <div class = "controls">
            <input type = "text" class="required" name = "drivername">
        </div>
    </div>
    <div class = "control-group">
        <label for = "deriverphone" class = "control-label"><?php echo JText::_('COM_BOOKPRO_DRIVER_PHONE') ?></label>
        <div class = "controls">
            <input type = "text" class="required" name = "driverphone">
        </div>
    </div>
</div>




<h2 class = "headline-bar"><?php echo JText::_('COM_BOOKPRO_HOW_WOULD_YOU_LIKE_TO_PAY') ?></h2>
<div class = "form-horizontal pay-type">
    <div class = "row-fluid coupon-code-wapper">
        <div class = "row-fluid">
            <a data-target = ".coupon-code" data-toggle = "collapse" href = "javascript:void(0)"><?php echo JText::_('COM_BOOKPRO_ENTER_COUPON') ?></a>
        </div>
        <div class = "row-fluil coupon-code  collapse">
            <div class = "Offset5 control-group">
                <label for = "coupon_code" class = "control-label"><?php echo JText::_('COM_BOOKPRO_COUPON_CODE') ?></label>
                <div class = "controls">
                    <div class = "row-fluid">
                        <input type = "text" class="required" placeholder = "<?php echo JText::_('COM_BOOKPRO_COUPON_CODE') ?>" value = "ha noi" maxlength = "50" size = "30" id = "coupon_code" name = "coupon_code" class = "inputbox input-medium">
                    </div>
                    <div class = "row-fluid btn-apply-coupon">
                        <input type = "button" value = "Apply coupon" class = "btn">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class = "control-group">
        <div class = "controls">
            <img src = "components/com_bookpro/assets/images/Visa.gif">
            <img src = "components/com_bookpro/assets/images/mastercard.gif">
            <img src = "components/com_bookpro/assets/images/JCB_Emblem_Color_SM.gif">
            <img src = "components/com_bookpro/assets/images/Discover.gif">
            <img src = "components/com_bookpro/assets/images/DCI_AM.gif">
            <img src = "components/com_bookpro/assets/images/AMEX.gif">
        </div>
    </div>
    <div class = "control-group">
        <label for = "creditCardNumber" class = "control-label"><?php echo JText::_('COM_BOOKPRO_DEBIT_CART_NUMBER') ?></label>
        <div class = "controls">
            <input type = "text" class="required" placeholder = "Address" value = "" maxlength = "50" size = "30" id = "creditCardNumber" name = "infobooking[creditCardNumber]" class = "inputbox required" aria-required = "true" required = "required">
        </div>
    </div>
    <div class = "control-group">
        <label for = "cardtype" class = "control-label"><?php echo JText::_('COM_BOOKPRO_CARD_TYPE') ?></label>
        <div class = "controls">
            <select name = "infobooking[creditCardType]" id = "infobookingcreditCardType">
                <option value = "AX">American Express</option>
                <option value = "DC">DINERS CLUB INTERNATIONAL</option>
                <option value = "CA">Master Card</option>
                <option value = "VI">Visa</option>
            </select>
        </div>
    </div>


    <div class = "control-group">
        <label for = "expiration_date" class = "control-label"><?php echo JText::_('COM_BOOKPRO_EXPIRATION_DATE') ?></label>

        <div class = "controls">
            <div class = "row-fluil">
                <div class = "span3">
                    <select class = "input-small required" class="required" name = "infobooking[creditCardExpirationMonth]" id = "infobookingcreditCardExpirationMonth" aria-required = "true" required = "required">
	                    <option value = "0">- Month</option>
                        <option value = "1">Jan</option>
                        <option value = "2">Feb</option>
                        <option value = "3">Mar</option>
                        <option value = "4">Apr</option>
                        <option value = "5">May</option>
                        <option value = "6">Jun</option>
                        <option value = "7">Jul</option>
                        <option value = "8">Aug</option>
                        <option value = "9">Sep</option>
                        <option value = "10">Oct</option>
                        <option value = "11">Nov</option>
                        <option value = "12">Dec</option>
                    </select>
                </div>
                <div class = "span3">
                    <select class = "input-small required" class="required" name = "infobooking[creditCardExpirationYear]" id = "infobookingcreditCardExpirationYear" aria-required = "true" required = "required">
                        <option value = "0">- Year</option>
                        <option value = "2014">2014</option>
                        <option value = "2015">2015</option>
                        <option value = "2016">2016</option>
                        <option value = "2017">2017</option>
                        <option value = "2018">2018</option>
                        <option value = "2019">2019</option>
                        <option value = "2020">2020</option>
                        <option value = "2021">2021</option>
                        <option value = "2022">2022</option>
                        <option value = "2023">2023</option>
                        <option value = "2024">2024</option>
                        <option value = "2025">2025</option>
                        <option value = "2026">2026</option>
                        <option value = "2027">2027</option>
                        <option value = "2028">2028</option>
                        <option value = "2029">2029</option>
                        <option value = "2030">2030</option>
                        <option value = "2031">2031</option>
                        <option value = "2032">2032</option>
                        <option value = "2033">2033</option>
                        <option value = "2034">2034</option>
                    </select>
                </div>
            </div>

        </div>
    </div>
    <div class = "control-group">
        <label for = "creditCardIdentifier" class = "control-label">Card Identification Number        </label>

        <div class = "controls">
            <input type = "text" placeholder = "Card Identification Number" value = "" size = "30" id = "creditCardIdentifier" name = "infobooking[creditCardIdentifier]" class = "inputbox required input-small" aria-required = "true" required = "required">
            <a href = "#">What this ?<i class = "icon icon-new-windows"></i></a>
        </div>
    </div>


    <div class = "control-group">
        <label for = "biiling_zip_code" class = "control-label">Billing ZIP Code        </label>

        <div class = "controls">
            <input type = "text" placeholder = "Billing ZIP Code" value = "" maxlength = "50" size = "30" id = "address" name = "biiling_zip_code" class = "inputbox required">
        </div>
    </div>

    <div class = "control-group">
        <label for = "cardholder_name" class = "control-label">Cardholder Name        </label>

        <div class = "controls">
            <input type = "text" placeholder = "Cardholder Name" value = "" maxlength = "50" size = "30" id = "address" name = "cardholder_name" class = "inputbox required">
        </div>
    </div>
</div>


<h2 class = "headline-bar">We should send your confirmation to where ?</h2>

<div class = "row-fluil form-horizontal">
    <div class = "row-fluil">
        Please enter the email address you would like to receive their certification .    </div>
    <div class = "control-group">
        <label for = "email" class = "control-label">Email        </label>

        <div class = "controls">


            <input type = "email" placeholder = "Email" value = "" maxlength = "50" size = "30" name = "infobooking[email]" class = "inputbox required" aria-required = "true" required = "required">
        </div>
    </div>

    <div class = "control-group">
        <label class = "control-label"><input type = "checkbox" id = "nhanthongbao" name = "nhanthongbao">
        </label>

        <div class = "controls">
            <label style = "text-align: justify; width: 100%" for = "nhanthongbao" class = "control-label">
                Please click this box if you do not want to receive emails about travel deals , special offers or other information from Expedia             </label>
        </div>
    </div>


</div>
<h2 class = "headline-bar">Fill out the booking done</h2>
<div class = "row-fluil form-horizontal">
    <div class = "control-group">
        <label for = "firstName" class = "control-label">First name        </label>

        <div class = "controls">
            <input type = "text" placeholder = "First name" value = "" maxlength = "50" size = "30" id = "firstName" name = "infobooking[firstName]" class = "inputbox required" aria-required = "true" required = "required">
        </div>
    </div>
    <div class = "control-group">
        <label for = "lastName" class = "control-label">Last name        </label>

        <div class = "controls">
                        <input type = "text" placeholder = "Last name" value = "" maxlength = "50" size = "30" id = "lastName" name = "infobooking[lastName]" class = "inputbox required" aria-required = "true" required = "required">
        </div>
    </div>
    <div class = "control-group">
        <label for = "homePhone" class = "control-label">Home phone        </label>

        <div class = "controls">
                        <input type = "text" placeholder = "Home phone" value = "" maxlength = "50" size = "30" id = "homePhone" name = "infobooking[homePhone]" class = "inputbox required" aria-required = "true" required = "required">
        </div>
    </div>
    <div class = "control-group">
        <label for = "address1" class = "control-label">Address        </label>
        <div class = "controls">
                        <textarea class = "textarea required" id = "address1" name = "infobooking[address1]" aria-required = "true" required = "required"></textarea>
        </div>
    </div>
    <div class = "control-group">
        <label for = "city" class = "control-label">Country        </label>

        <div class = "controls">
                        <select noi = "" ha = "" name = "infobooking[city]" id = "infobookingcity" class="required">
	
	<option value = "238">Zambia</option>
	<option value = "239">Zimbabwe</option>
</select>
        </div>
    </div>


    <div class = "control-group">
        <label for = "postalCode" class = "control-label">Postal code        </label>

        <div class = "controls">
                        <input type = "text" placeholder = "Postal code" value = "" maxlength = "50" size = "30" id = "postalCode" name = "infobooking[postalCode]" class = "inputbox required">
        </div>
    </div>

    <div class = "control-group">
        <label for = "mobile" class = "control-label">Mobile        </label>

        <div class = "controls">
            <input type = "text" placeholder = "Mobile" value = "" maxlength = "50" size = "30" id = "mobile" name = "mobile" class = "inputbox required">
        </div>
    </div>
    <div class = "control-group">
        <label for = "telephone" class = "control-label">Phone        </label>

        <div class = "controls">
            <input type = "text" placeholder = "Phone" value = "" maxlength = "50" size = "30" id = "telephone" name = "telephone" class = "inputbox required">
        </div>
    </div>
    <div class = "control-group">
        <label for = "email" class = "control-label">Email        </label>

        <div class = "controls">
            <input type = "email" placeholder = "Email" value = "" maxlength = "50" size = "30" id = "email" name = "email" class = "inputbox required" aria-required = "true" required = "required">
        </div>
    </div>


</div>


</div>


<style type = "text/css">

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

    .icon-new-windows {
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
        margin: 0px;
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
        <div class = "panel panel-primary">
                <div class = "panel-heading">
                    <h3 class = "panel-title">Review and book your trip</h3>
                </div>
                <div class = "panel-body">
                    <div class = "row-fluid">
                        <b>Important Information request your book :</b>
                        <ul>
                            <li>Request this book first is not refundable and can not be changed or canceled .</li>
                        </ul>
                    </div>
                </div>
                <div class = "panel-footer">
                    <div class = "row-fluid">
                        <div class = "row-fluid checkbox"><label><input type = "checkbox" name = "accept">I have read and accept the <a data-toggle = "modal" href = "#rules_limits">rules &amp; limits <i class = "icon icon-new-windows"></i></a> , <a href = "#">terms &amp; conditions<i class = "icon icon-new-windows"></i></a> and <a href = "#">privacy policy<i class = "icon icon-new-windows"></i></a> .<label></label></label></div>
                        <!-- Modal -->
                        <div aria-hidden = "true" aria-labelledby = "myModalLabel" role = "dialog" tabindex = "-1" class = "modal hide fade" id = "rules_limits">
                            <div class = "modal-header">
                                <button aria-hidden = "true" data-dismiss = "modal" class = "close" type = "button">×</button>
                                <h3 id = "myModalLabel">rules &amp; limits header</h3>
                            </div>
                            <div class = "modal-body">
                                <p>rules &amp; limits body…</p>
                            </div>
                            <div class = "modal-footer">
                                <button aria-hidden = "true" data-dismiss = "modal" class = "btn">Close</button>
                            </div>
                        </div>

                        <div class = "row-fluid">
                            <input type = "submit" class = "btn btn-large btn-primary" value = "Continue" name = "btnSubmit">
                        </div>
                    </div>
                </div>
            </div>


        </div>

    <div class = "span4 booking-info">
            <h2 class = "headline-bar headline-bar-alt">Trip Summary</h2>

            <div class = "booking-info-wapper">

<div class = "row-fluid">
    <div><h3><?php echo JText::_('COM_BOOKPRO_CAR_BOOKING_OVERVIEW') ?></h3></div>
    <div class = "images"><img src = "components/com_bookpro/assets/images/car1-medium.png"></div>
    <div class="row-fuid">
        <div class="span3"><?php echo JText::_('COM_BOOKPRO_PICKUP') ?></div>
        <div class="span9"><?php echo $cart->pickup ?></div>
    </div>
    <div class="row-fuid">
        <div class="span3"><?php echo JText::_('COM_BOOKPRO_DROPOFF') ?></div>
        <div class="span9"><?php echo $cart->dropoff ?></div>
    </div>
    
</div>
</form>

<div class = "row-fluid"></div>
<div><div class = "span6">Total price</div><div class = "span6"><h2>US$11.3</h2></div></div>


<script>
    jQuery(document).ready(function ($) {
//        $('a.show-list-night').each(function(){
//            roomitem=$(this).closest('.room-item');
//            console.log(roomitem.find('.perDayPrices').attr('class'));
//            $(this).collapse({
//                parent:'.perDayPrices'
//            });
//        });

    });

</script>            </div>
        </div>
</div>

