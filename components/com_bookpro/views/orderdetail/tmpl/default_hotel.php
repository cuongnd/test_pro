<?php 
defined('_JEXEC') or die('Restricted access');
AImporter::model('room','hotel','facility');
AImporter::helper('hotel');
$hmodel=new BookProModelHotel();

$infos = HotelHelper::getRooms($this->order->id);

$link = JRoute::_(ARoute::edit(CONTROLLER_HOTEL, $hotel->id));

?>
<a href="index.php?option=com_bookpro&controller=order&task=detail&layout=invoice&tmpl=component&order_id=<?php echo Jrequest::getVar('order_id'); ?>">Invoice</a>
<div class="row-fluid">
<div class="span6">
	<?php echo $this->loadTemplate('order')?>	
</div>


<div class="span6">
 <h2><?php echo JText::_('COM_BOOKPRO_BOOKING_DETAIL')?></h2>
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