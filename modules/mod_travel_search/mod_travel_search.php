<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/
defined('_JEXEC') or die;
require_once( dirname(__FILE__).'/helper/helper.php' );
$document=JFactory::getDocument();
$document->addStyleSheet(JURI::root().'modules/mod_travel_search/assets/css/travel_search.css');
$document->addScript(JURI::root().'modules/mod_travel_search/assets/js/helper.js');
$tour = $params->get('tour');
$date_format=$params->get("date_format");
return;
require_once JPATH_ROOT.'/components/com_bookpro/models/tourcart.php';
$cart = JModelLegacy::getInstance('TourCart', 'bookpro');
$cart->load();


$itemid = $params->get ('tour_itemid');
$country_param=$params->get('country',0);
$duration_param=$params->get('duration',0);
$dest_param=$params->get('dest',null);
$keyword_param=$params->get('keyword',0);
$pdepart=$params->get('depart_date',0);
$category=$params->get('category',0);

$tour=$params->get('tour',0);
$hotel=$params->get('hotel',0);
$flight=$params->get('flight',0);
$car=$params->get('car',0);

$country = modTravelHelper::getCountrySelect($cart->filter['country_id']);

$duration=modTravelHelper::getDurationBox($cart->filter['duration']);

$cats=modTravelHelper::getTourCategorySelect($cart->filter['cat_id']);

$dests=modTravelHelper::getDestination($cart->filter['dest_id']);

$depart=modTravelHelper::getTourDepartsSelect('depart_date',$cart->filter['depart_date']);
//
$action="index.php?option=com_bookpro&view=tour";

if ($itemid) {
	$action .= '&Itemid=' . $itemid;
}
else
{
	$itemid = JRequest::getVar('Itemid');
	$action .= '&Itemid=' . $itemid;
}

require JModuleHelper::getLayoutPath('mod_travel_search',$params->get('layout','default'));