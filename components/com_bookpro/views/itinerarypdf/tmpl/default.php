<?php
defined('_JEXEC') or die('Restricted access');

//echo $this->itineraries['iti_detail'];

?>
<div style="margin: 30px 30px 50px 50px;">



<h3 style="border-bottom: 1px solid #ccc;"><?php echo JText::_('Tour name') ?>:<?php echo $this->tour->title ?></h3>
<h2><?php echo JText::_('Itinerary Detail')?></h2>
<?php 

foreach ($this->items as $item) {
	?>
	<p style="font-weight: bold;"><?php echo JText::_('Activity') ?>:<?php echo $item->title; ?></p>
 	<p><?php echo JText::_('Location') ?>:<?php echo $item->dest_name; ?></p>
 	<p><?php echo $item->desc; ?></p>
	
<?php 
}

?>
</div>




