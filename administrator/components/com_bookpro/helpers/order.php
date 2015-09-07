<?php
class OrderHelper {
	
	/**
	 * Get total revenue of orders
	 * @param $start: booking date 
	 * @param $end: booking date
	 * @param String $type: application type 
	 */
	static function getTotal($start,$end,$type=null){
		$db = JFactory::getDbo();
		
		$where=array();
		if($start){
			$where[]="created >= '".$start."'";
		}
		if($end) {
			$where[]="created <= '".$end."'";
		}
		if($type) {
			$where[]="LOWER(type) LIKE ".$db->quote('%' . JString::strtolower($type) . '%');
		}
		
		$query = "SELECT sum(total) FROM #__bookpro_orders";
		
		$query.=" WHERE ".implode(" AND ", $where);
		$db->setQuery($query);
		return $db->loadResult();
	
	}
	
	
}