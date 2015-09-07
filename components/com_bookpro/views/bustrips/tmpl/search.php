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

$doc->addScript("http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js");
if($local !='en'){
	$doc->addScript(JURI::root().'components/com_bookpro/assets/js/validatei18n/messages_'.$local.'.js');
}
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/jquery.ui.slider.min.js');
$doc->addStyleSheet(JUri::root().'/components/com_bookpro/assets/css/view-bustrips.css');
$doc->addScript(JUri::root().'/components/com_bookpro/assets/js/view-bustrips-search.js');
$js=<<<javascript
    jQuery(document).ready(function(){
        function sethtmlfortag(respone_array)
        {
            respone_array = $.parseJSON(respone_array);
            $.each(respone_array, function(index, respone) {

                $(respone.key.toString()).html(respone.contents);
            });
        }
     });
javascript;
$doc->addScriptDeclaration($js);
?>

<form id="bustrip-search" name="car_search" action="index.php">
    <div class="widgetbookpro-loading"></div>

    <h1 class="car-search"><?php echo JText::_('Car search') ?></h1>
    <div class="row-fluid wapper-car-search">
        <div class="span8 col_left">
            <?php
            echo $this->loadTemplate('car_rental_offers');
            ?>
            <?php
            echo $this->loadTemplate('car_rentals');
            ?>
        </div>
        <div class="span4 col_right">
            <div class="row-fluid refine-your-result">
                <div class="header">
                    <?php echo JText::_('Refine your result') ?>
                </div>
                <div class="body">
                    <?php
                    echo $this->loadTemplate('price_range');
                    ?>
                    <?php
                    echo $this->loadTemplate('vehicles');
                    ?>
                    <?php
                    echo $this->loadTemplate('select_trip');
                    ?>
                </div>
                <div class="footer"></div>
            </div>
            <div class="row-fluid booking-summary">
                <?php
                echo $this->loadTemplate('booking_summary');
                ?>
            </div>
            <?php
            echo $this->loadTemplate('sign_in');
            ?>

        </div>
    </div>
    <input type="hidden" name="option" value="com_bookpro">
    <input type="hidden" name="controller" value="bus">
    <input type="hidden" name="task" value="booking">
</form>