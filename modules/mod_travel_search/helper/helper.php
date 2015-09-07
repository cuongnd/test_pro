<?php
/**
 * @package 	Bookpro
 * @author 		Ngo Van Quan
 * @link 		http://joombooking.com
 * @copyright 	Copyright (C) 2011 - 2012 Ngo Van Quan
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/
// no direct access
defined('_JEXEC') or die;
jimport( 'joomla.application.component.controller' );
if ( !JComponentHelper::isEnabled( 'com_bookpro', true) ) {
	echo 'Travel search module requires com_bookpro component!';
	die();
}
require_once JPATH_SITE.'/components/com_bookpro/models/bookpro.php';
class modTravelHelper
{

	
	public static function getDestination($select){
		$db = JFactory::getDBO();
		$query =$db->getQuery(true);
		$query->select('d.*');
		$query->from('#__bookpro_dest AS d')
		->innerJoin('#__bookpro_itinerary AS i ON i.dest_id=d.id');
		$query->where('d.state=1')->group('d.id');
		$query->order('d.id ASC');
		$sql = (string)$query;
		$db->setQuery($sql);
		$flight = $db->loadObjectList();
		$temp=new stdClass();
		$temp->id='';
		$temp->title=JText::_('MOD_TRAVEL_SEARCH_SELECT_DESTINATION');
		$flight[]=$temp;
		return JHtml::_('select.genericlist',$flight,'dest_id', 'class="form-control"', 'id', 'title',$select);

	}
	public static function  getCountrySelect($select){
		$db = JFactory::getDBO();
		$query =$db->getQuery(true);
		$query->select('c.*');
		$query->from('#__bookpro_country AS c')->innerJoin('#__bookpro_dest AS d ON d.country_id=c.id')
		->innerJoin('#__bookpro_itinerary AS i ON i.dest_id=d.id');
		$query->where('c.state=1')->group('c.id');
		$query->order('c.id ASC');
		$sql = (string)$query;
		$db->setQuery($sql);
		$flight = $db->loadObjectList();
			$temp=new stdClass();
			$temp->id='';
			$temp->country_name=JText::_('MOD_TRAVEL_SEARCH_SELECT_COUNTRY');
			$flight[]=$temp;
		return JHtml::_('select.genericlist',$flight,'country_id', 'class="form-control"', 'id', 'country_name',$select);

	}
	public static function getTourCategorySelect($select){
		$db = JFactory::getDBO();
		$query =$db->getQuery(true);
		$query->select('c.*');
		$query->from('#__bookpro_category AS c');
		$query->where(array('type=2','state=1'));
		$query->order('ordering ASC');
		$sql = (string)$query;
		$db->setQuery($sql);
		$flight = $db->loadObjectList();
		$temp=new stdClass();
		$temp->id='';
		$temp->title=JText::_('MOD_TRAVEL_SEARCH_SELECT_CATEGORY');

		array_unshift($flight, $temp);

		return JHtml::_('select.genericlist',$flight,'cat_id', 'class="form-control"', 'id', 'title',$select);

	}
	public static function getDurationBox($select){
		
		$option=array();
		
		$option[]=JHtmlSelect::option(0,JText::_('MOD_TRAVEL_SEARCH_EXCURSION'));
		
		for ($i = 2; $i < 20; $i++){
			$option[]=JHtmlSelect::option($i,$i.' '.JText::_('MOD_TRAVEL_SEARCH_DAYS'));
		}
		array_unshift($option, JHtmlSelect::option(null,JText::_('MOD_TRAVEL_SEARCH_DURATION_SELECT')));
		return JHtml::_('select.genericlist',$option,'duration', 'class="form-control"', 'value','text',$select);
	}


	static function getTourDepartsSelect($field,$selected){

		$db = JFactory::getDBO();
		$query =$db->getQuery(true);
		$query->select('c.*');
		$query->from('#__bookpro_dest AS c');
		$query->where('state=1');
		$query->order('ordering ASC');
		$sql = (string)$query;
		$db->setQuery($sql);
		$flight = $db->loadObjectList();
		$temp=new stdClass();
		$temp->id='';
		$temp->title=JText::_('MOD_TRAVEL_SEARCH_SELECT_DEPART_LOCATION');
		array_unshift($flight, $temp);
		return JHtml::_('select.genericlist',$flight,'departure_id', 'class="form-control"', 'id', 'title',$selected);

	}
	
	

}


