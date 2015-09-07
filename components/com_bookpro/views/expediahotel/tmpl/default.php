<link href='http://fonts.googleapis.com/css?family=Akronim' rel='stylesheet' type='text/css'>
<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 30 2012-07-09 15:23:13Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');



JHtml::_('behavior.framework');
JHtmlBehavior::modal('a.modal_hotel');
AImporter::helper('image', 'bookpro', 'currency', 'form');
AImporter::css('bookpro');
jimport('joomla.html.html.tabs');
JHtml::_('jquery.framework');

$document = JFactory::getDocument();

JHtml::_('jquery.framework');
JHtml::_('jquery.ui');
$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/colorbox/jquery.colorbox.js');
$document->addScript(JURI::root() . 'components/com_bookpro/assets/js/colorbox/bootstrap-popover.js');
$document->addStyleSheet(JUri::root() . 'components/com_bookpro/assets/css/expedia.css');

$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/js/colorbox/colorbox.css');
$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/css/view-hotel.css');
$document->addStyleSheet(JURI::root() . 'components/com_bookpro/assets/css/jquery-ui.css');
$js = "
    jQuery(document).ready(function($) {


    $(\".lightbox-atomium\").colorbox({rel:'lightbox-atomiums'});

    $('.masterTooltip').hover(function(){
    // Hover over code
    var title = $(this).attr('title');
    $(this).data('tipText', title).removeAttr('title');
    $('<p class=\"tooltip\"></p>')
    .text(title)
    .appendTo('body')
    .fadeIn('slow');
    }, function() {
    // Hover out code
    $(this).attr('title', $(this).data('tipText'));
    $('.tooltip').remove();
    }).mousemove(function(e) {
    var mousex = e.pageX + 20; //Get X coordinates
    var mousey = e.pageY + 10; //Get Y coordinates
    $('.tooltip')
    .css({ top: mousey, left: mousex })
    });


    });
    ";
$document->addScriptDeclaration($js);

$action = JURI::base() . 'index.php';
$cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
$cart->load();
$lang = JFactory::getLanguage();
$local = substr($lang->getTag(), 0, 2);

$config = AFactory::getConfig();
$config = JFactory::getApplication()->getCfg;

    $this->array_star=array(
        "star1"=>"1"
    ,"star1.5"=>"star1-5"
    ,"star2"=>"star2"
    ,"star2.5"=>"star2-5"
    ,"star3"=>"star3"
    ,"star3.5"=>"star3-5"
    ,"star4"=>"star4"
    ,"star4.5"=>"star4-5"
    ,"star5"=>"star5"
    );



?>
<form name="hotelBook" id="hotelBook" method="post"	action="index.php?option=com_bookpro&controller=expediahotel&task=guestform&Itemid=<?php echo JRequest::getInt('Itemid'); ?>"
      >
    <div class="row-fluid ">
        <div class="span3 offset6">
            <label class="control-label"><?php echo JText::_('COM_BOOKPRO_SELECT_CURRENCY'); ?>
            </label>
            <?php echo $this->list_currency ?>
        </div>
        <div class="span3">
            <label class="control-label" ><?php echo JText::_('COM_BOOKPRO_SELECT_LANGUAGE'); ?>
            </label>
            <?php echo $this->list_language ?>
        </div>
    </div>
<?php echo $this->loadTemplate("slide") ?>
<?php echo $this->loadTemplate("room") ?>
    <?php echo $this->loadTemplate("hotel") ?>



<?php
echo FormHelper::bookproHiddenField(array('controller' => 'expediahotel',
    'task' => 'displayhotel',
    'Itemid' => JRequest::getInt('Itemid'),
    'hotel_id' => $this->hotel['HotelSummary']['hotelId']));
echo JHtml::_('form.token');
?>

</form>


