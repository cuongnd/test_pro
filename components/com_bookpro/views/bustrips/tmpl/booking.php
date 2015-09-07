<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php  23-06-2012 23:33:14
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
AImporter::helper('form','html');
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
AImporter::css('bus','bookpro');
$lang=JFactory::getLanguage();
$local=substr($lang->getTag(),0,2);
$doc=JFactory::getDocument();
//$doc->addScript(JURI::root().'components/com_bookpro/assets/js/i18n/jquery.ui.datepicker-'.$local.'.js');

$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/FooTable-2/js/footable.js');
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/FooTable-2/js/footable.paginate.js');
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/FooTable-2/js/footable.sort.js');
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/__jquery.tablesorter/jquery.tablesorter.js');
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/noosliderlite/script_nooSliderLite.js');

$doc->addScript('components/com_bookpro/assets/js/view-bustrips-booking.js');
$doc->addScript('components/com_bookpro/assets/js/jquery-create-seat.js');
$doc->addStyleSheet('components/com_bookpro/assets/css/jquery-create-seat.css');
$doc->addScript('components/com_bookpro/assets/js/jquery.session.js');
$doc->addStyleSheet(JURI::root().'components/com_bookpro/assets/css/jquery-ui.css');
$doc->addScript('http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js');


$doc->addStyleSheet(JUri::root().'/components/com_bookpro/assets/js/noosliderlite/css/style.css');
$doc->addStyleSheet(JUri::root().'/components/com_bookpro/assets/js/FooTable-2/css/footable.core.css');
$doc->addStyleSheet(JUri::root().'/components/com_bookpro/assets/js/__jquery.tablesorter/themes/blue/style.css');
$doc->addStyleSheet(JUri::root().'/components/com_bookpro/assets/css/view-bustrips.css');
$lang = JFactory::getLanguage();
$local = substr($lang->getTag(), 0, 2);
$doc->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js");
if ($local != 'en') {
    $doc->addScript(JURI::root() . 'components/com_bookpro/assets/js/validatei18n/messages_' . $local . '.js');
}



?>
<form id="car-booking" name="car_booking" action="index.php">


    <div class="widgetbookpro-loading"></div>
    <div class="row-fluid wapper-car-booking">
        <div class="span8 col_left">
            <div id="traveller_details">
            <?php
            echo $this->loadTemplate('traveller_details');
            ?>
            </div>
            <?php
            echo $this->loadTemplate('select_pickup_drop_off');
            ?>
            <?php
            echo $this->loadTemplate('option_add_one');
            ?>
            <?php
            echo $this->loadTemplate('additional_request');
            ?>
            <?php
            echo $this->loadTemplate('select_payment');
            ?>
            <?php
                echo $this->loadTemplate('confirm');
            ?>
        </div>
        <div class="span4 col_right">
            <div class="top_discount_tour">
                <p class="title_top_discount">Book and Go</p>

                <div class="row-fluid">
                </div>
            </div>



            <div class="row-fluid booking-summary">
                <?php
                echo $this->loadTemplate('booking_summary');
                ?>
            </div>
            <?php
                echo $this->loadTemplate('joint_us');
            ?>
            <?php
                echo $this->loadTemplate('need_help');
            ?>


        </div>
    </div>

    <input type="hidden" name="option" value="com_bookpro">
    <input type="hidden" name="controller" value="bustrips">
    <input type="hidden" name="task" value="booking">
</form>