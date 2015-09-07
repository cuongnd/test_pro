<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: flights.php 81 2012-08-11 01:16:36Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelFlights extends AModel
{

	var $_table;

	function __construct()
	{
		parent::__construct();
		$this->_table = $this->getTable('flight');

	}

	/**
	 * Get MySQL loading query for customers list
	 *
	 * @return string complet MySQL query
	 */
	function buildQuery()
	{
		$query=null;

		if(IS_ADMIN) {
			$airportTable = &$this->getTable('airport');
			$flightTable=&$this->getTable("flight");
			$airlineTable=&$this->getTable("airline");
			if (is_null($query)) {
				$query = 'SELECT `flight`.*,`dest1`.`code` as `from_code`,`dest2`.`code` as `to_code`, `dest1`.`title` as `fromName`, `dest2`.`title` AS `toName`';
				$query.=',`airline`.`title` AS `airline_name`, `airline`.`image` AS `airline_logo`,CONCAT(`dest1`.`title`,'.$this->_db->quote('-').',`dest2`.`title`) AS title ';
				$query .= 'FROM `' . $this->_table->getTableName() . '` AS `flight` ';
				$query .= 'LEFT JOIN `' . $airportTable->getTableName() . '` AS `dest1` ON `flight`.`desfrom` = `dest1`.`id` ';
				$query .= 'LEFT JOIN `' . $airportTable->getTableName() . '` AS `dest2` ON `flight`.`desto` = `dest2`.`id` ';
				$query .= 'LEFT JOIN `' . $airlineTable->getTableName() . '` AS `airline` ON `airline`.`id` = `flight`.`airline_id` ';
				$query .= $this->buildContentWhere();
				$query .= $this->buildContentOrderBy();
			}
			
			
			return $query;
			
		} else {
			return $this->searchQuery();
		}
	}
	 

	function  getFlightName($flight_id){
		 
		$flightTable=&$this->getTable("flight");
		$destTable=&$this->getTable("dest");
		$query="";
		$query = 'SELECT `flight`.*, `dest1`.`value` as `destfrom`, `dest2`.`value` as `destto` ';
		$query .= 'FROM `' . $this->_table->getTableName() . '` AS `flight` ';
		$query .= 'LEFT JOIN `' . $destTable->getTableName() . '` AS `dest1` ON `flight`.`desfrom` = `dest1`.`id` ';
		$query .= 'LEFT JOIN `' . $destTable->getTableName() . '` AS `dest2` ON `flight`.`desto` = `dest2`.`id` ';
		$query .= 'WHERE `flight`.`id` = ' . $flight_id;
		if($this->_getListCount($query)==1){
			$row=$this->_getList($query);
			$from=$row[0]->destfrom;
			$to=$row[0]->destto;
			return $from." to ".$to;


		}
	}
	 
	function buildContentWhere()
	{
		$where = array();
		$this->addIntProperty($where, 'desfrom');
		$this->addIntProperty($where, 'desto','state','featured');
		$this->addIntProperty($where, 'airline_id');
		$this->addIntProperty($where, 'frequency');
		$this->addTimeRange($where,'start','min_time', 'max_time');
		return $this->getWhere($where);
	}
	function searchQuery(){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('flight.*,airline.title AS airline_name,`airline`.`image` AS `airline_logo`,dest1.code AS fromcode,dest2.code AS tocode,dest1.title AS fromName,dest2.title AS toName');
		$query->select('CONCAT(`airline`.`code`,'.$db->quote(' ').',`flight`.`flightnumber`) AS flight_number');
		$query->select('CONCAT(`dest1`.`title`,'.$db->quote('-').',`dest2`.`title`) AS title');
		$query->select('count(rate.id) as count_rate');
		$query->from('#__bookpro_flight AS flight');
		$query->join("LEFT", '#__bookpro_dest AS `dest1` ON `flight`.`desfrom` = `dest1`.`id`');
		$query->join("LEFT", '#__bookpro_dest AS `dest2` ON `flight`.`desto` = `dest2`.`id`');
		$query->join('LEFT', '#__bookpro_airline AS `airline` ON `airline`.`id` = `flight`.`airline_id`');
		$query->join('LEFT', '#__bookpro_flightrate AS rate ON rate.flight_id = flight.id');
		
		if ($this->_lists['desfrom']){
				
			$query->where('flight.desfrom='.$this->_lists['desfrom']);
		}
		if ($this->_lists['desto']){
				
			$query->where('flight.desto='.$this->_lists['desto']);
		}
		if ($this->_lists['airline_id']){
				
			$query->where('flight.airline_id='.$this->_lists['airline_id']);
		}
		if ($this->_lists['featured']) {
			$query->where('flight.featured='.$this->_lists['featured']);
		}
		if (!empty($this->_lists['airline'])) {
			$airline = implode(",", $this->_lists['airline']);
			$query->where('flight.airline_id IN ('.$airline.')');
		}
		if ($this->_lists['depart_date']) {
			$depart_date = JFactory::getDate($this->_lists['depart_date'])->toSql();
			$query->where('rate.date = '.$db->quote($depart_date));
		}
		if ($this->_lists['min_price'] > 0 && $this->_lists['max_price'] > 0) {
			$query->where('rate.adult BETWEEN '.$this->_lists['min_price'].' AND '.$this->_lists['max_price']);
		}
		
		if ($this->_lists['min_time'] && $this->_lists['max_time']) {
			$time_min = $this->_lists['min_time'].":00";
			$time_max = $this->_lists['max_time'].":00:00";
			
			//$time_max = JFactory::getDate($time_max)->format('H:i:s');
			
			$time_min = JFactory::getDate($time_min)->format('H:i:s');
			
			$query->where('flight.start BETWEEN '.$db->quote($time_min).' AND '.$db->quote($time_max));
		}
		$query->group('flight.id');
		$query->order('flight.start ASC');
		$query->having('count_rate > 0');
		//var_dump((string) $query);
		return  $query;
	}


	 
}

?>