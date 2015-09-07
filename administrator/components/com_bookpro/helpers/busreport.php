<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 80 2012-08-10 09:25:35Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

class BusReportHelper
{
	static function getAgentBusStop($bus_id){
		$db = JFactory::getDbo();
		$query = "
		SELECT depart_time FROM #__bookpro_busstation WHERE bustrip_id = $bus_id ORDER BY ordering ASC LIMIT 0,1
		";
		 
		$db->setQuery($query);
		$row = $db->loadResult();
		 
		return $row;
	}
	static function getAgentDriver($obj_id,$date){
		$db = JFactory::getDbo();
		$query = "
    			SELECT COUNT(oi.order_id) AS ticket,SUM(oi.adult) + SUM(oi.child) AS seat
    			FROM #__bookpro_orderinfo AS oi
    			";
		if ($obj_id) {
			$where[] = "oi.obj_id=".$obj_id;
		}
		if ($date) {
			$where[] = "DATE_FORMAT(oi.start,'%Y-%m-%d') = ".$db->quote(JFactory::getDate($date)->format('Y-m-d'));
		}
		if ($where != NULL) {
			$query .= " WHERE ".implode(" AND ", $where);
		}
	
		 
		$db->setQuery($query);
		return $db->loadObject();
	}
	static function getAgentDriverReport($obj_id,$date){
		$db = JFactory::getDbo();
		$query = "
    			SELECT COUNT(oi.order_id) AS ticket,SUM(oi.price) AS subprice,SUM(adult)+SUM(child) AS qty
    			FROM #__bookpro_orderinfo AS oi
    			";
		if ($obj_id) {
			$where[] = "oi.obj_id=".$obj_id;
		}
		if ($date) {
			$where[] = "DATE_FORMAT(oi.start,'%Y-%m-%d') = ".$db->quote(JFactory::getDate($date)->format('Y-m-d'));
		}
		if ($where != NULL) {
			$query .= " WHERE ".implode(" AND ", $where);
		}
	
		 
		$db->setQuery($query);
		return $db->loadObject();
	}
}