<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 82 2012-08-16 15:07:10Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

$db=JFactory::getDbo();
$query= $db->getQuery(true);
$query->select('count(*)')->from('#__bookpro_tour');
$db->setQuery($query);
$total_hotel=$db->loadResult();

$query= $db->getQuery(true);
$query->select('count(*)')->from('#__bookpro_customer');
$db->setQuery($query);
$total_customer=$db->loadResult();

$query= $db->getQuery(true);
$query->select('count(*)')->from('#__bookpro_orders');
$db->setQuery($query);
$total_booking=$db->loadResult();

$query= $db->getQuery(true);
$query->select('count(*)')->from('#__bookpro_orders');
$db->setQuery($query);
$total_booking=$db->loadResult();

$query= $db->getQuery(true);
$query->select('sum(total)')->from('#__bookpro_orders');
$db->setQuery($query);
$total_revenue=$db->loadResult();
$rul_default = "index.php?option=com_bookpro&view=";
?>
<div class="row-fluid">
<div class="btn-group">
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_BOOKPRO_MANAGE_BOOKING'); ?> <span class="caret"></span></button>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo $rul_default.'bookings'?>"><?php echo JText::_('COM_BOOKPRO_BOOKING_LIST'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'tourreport'?>"><?php echo JText::_('COM_BOOKPRO_TOUR_REPORT'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'customers'?>"><?php echo JText::_('COM_BOOKPRO_CUSTOMERS'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'agents'?>"><?php echo JText::_('COM_BOOKPRO_AGENTS'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'messages'?>"><?php echo JText::_('COM_BOOKPRO_MESSAGES'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'reviews'?>"><?php echo JText::_('COM_BOOKPRO_REVIEWS'); ?></a></li>
                </ul>
     </div>
     
     <div class="btn-group">
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_BOOKPRO_TOUR_MANAGER'); ?> <span class="caret"></span></button>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo $rul_default.'tour'?>"><?php echo JText::_('COM_BOOKPRO_NEW_TOUR'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'tours'?>"><?php echo JText::_('COM_BOOKPRO_TOUR_CATALOG'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'itineraries'?>"><?php echo JText::_('COM_BOOKPRO_ITINERARIES'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'activities'?>"><?php echo JText::_('COM_BOOKPRO_ACTIVITIES'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'categories'?>"><?php echo JText::_('COM_BOOKPRO_CATEGORIES'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'packagetypes'?>"><?php echo JText::_('COM_BOOKPRO_PACKAGES_TYPE'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'roomtypes'?>"><?php echo JText::_('COM_BOOKPRO_ROOMS_TYPE'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'addons'?>"><?php echo JText::_('COM_BOOKPRO_ADDONS'); ?></a></li> 
                </ul>
     </div>
      <div class="btn-group">
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_BOOKPRO_HOTEL_MANAGER'); ?> <span class="caret"></span></button>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo $rul_default.'hotel'?>"><?php echo JText::_('COM_BOOKPRO_NEW_HOTEL'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'hotels'?>"><?php echo JText::_('COM_BOOKPRO_HOTEL_CATALOG'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'facilities'?>"><?php echo JText::_('COM_BOOKPRO_FACILITIES'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'categories'?>"><?php echo JText::_('COM_BOOKPRO_CATEGORIES'); ?></a></li>
                </ul>
     </div>
     
      <div class="btn-group">
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><?php echo JText::_('COM_BOOKPRO_CATALOG'); ?> <span class="caret"></span></button>
                <ul class="dropdown-menu">
                  <li><a href="<?php echo $rul_default.'airports'?>"><?php echo JText::_('COM_BOOKPRO_DESTINATIONS'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'countries'?>"><?php echo JText::_('COM_BOOKPRO_COUNTRIES'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'sendemails'?>"><?php echo JText::_('COM_BOOKPRO_EMAILS'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'coupons'?>"><?php echo JText::_('COM_BOOKPRO_COUPONS'); ?></a></li>
                  <li><a href="<?php echo $rul_default.'passengers'?>"><?php echo JText::_('COM_BOOKPRO_PASSENGER_GROUP'); ?></a></li>
                </ul>
     </div>

</div>
<!-- 
<div class="container-fluid" style="border:1px solid #ccc;">
	
	<div class="span6"> 
	<?php echo JText::sprintf('COM_BOOKPRO_TOTAL_PROPERTY',$total_hotel) ?> <br/>
	<?php echo JText::sprintf('COM_BOOKPRO_TOTAL_CUSTOMER',$total_customer) ?> <br/>
	<?php echo JText::sprintf('COM_BOOKPRO_TOTAL_BOOKING',$total_booking) ?> <br/>
	<?php echo JText::sprintf('COM_BOOKPRO_TOTAL_REVENUE',CurrencyHelper::formatprice($total_revenue)) ?> <br/>
	</div>
	<div class="span6"></div>
	<h1><?php echo JHtml::_('date','now')?></h1>
</div>

 -->