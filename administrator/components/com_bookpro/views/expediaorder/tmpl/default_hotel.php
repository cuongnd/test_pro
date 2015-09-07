
<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/
defined('_JEXEC') or die('Restricted access');
AImporter::model('room','hotel','passengers','facility');
AImporter::helper('hotel');
$hmodel=new BookProModelHotel();
JToolbarhelper::title('Order detail');
$infos = HotelHelper::getRooms($this->order->id);
$link = JRoute::_(ARoute::edit(CONTROLLER_HOTEL, $hotel->id));
?>


<div class="row-fluid">
<div class="span6">
	<?php echo $this->loadTemplate('order')?>
	 <?php
    $layout = new JLayoutFile('customer', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
    $html = $layout->render($this->customer);
    echo $html;
   ?>	
</div>
<div class="span6">
	
	 <?php
    $layout = new JLayoutFile('hotelinformation', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
    $html = $layout->render($this->order);
    echo $html;
   ?>
	<?php 
	$layout = new JLayoutFile('rooms', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
	$html = $layout->render($infos);
	echo $html;
	?>
</div>
</div>
