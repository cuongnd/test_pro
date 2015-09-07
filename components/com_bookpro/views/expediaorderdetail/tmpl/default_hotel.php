<?php
defined('_JEXEC') or die('Restricted access');
AImporter::model('room','hotel','facility');
AImporter::helper('hotel');
AImporter::css('hotel');
$hmodel=new BookProModelHotel();

$infos =  $RateInfos=$this->itin['Itinerary']['HotelConfirmation'];

$link = JRoute::_(ARoute::edit(CONTROLLER_HOTEL, $hotel->id));


?>


<div class="pull-right">
<a href="index.php?option=com_bookpro&controller=order&task=detail&layout=invoice&tmpl=component&order_id=<?php echo JRequest::getVar('order_id'); ?>" class="btn btn-primary">Invoice</a>
<button class="btn btn-primary">Cancel booking</button>
</div>

<div class="row-fluid">
<div class="span6">
	<?php echo $this->loadTemplate('order')?>
</div>


<div class="span6">

  <?php
    $layout = new JLayoutFile('expediahotelinformation', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
    $html = $layout->render($infos);
    echo $html;
   ?>

	<?php
	$layout = new JLayoutFile('rooms', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts');
	$html = $layout->render($infos);
	echo $html;
	?>
</div>
</div>