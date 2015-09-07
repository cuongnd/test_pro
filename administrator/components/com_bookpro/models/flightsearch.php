<?php

defined('_JEXEC') or die;

AImporter::helper('request', 'model');
class BookProModelFlightSearch extends AModel
{
	var $_table;

	function __construct()
	{
		parent::__construct();
		
	}
	
	
	function buildQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		
		if ($this->_lists["depart_date"]) {
			$depart_date=JFactory::getDate($this->_lists["depart_date"])->format('Y-m-d');
			
		}
		$query->select('flight.*,airline.title AS airline_name,dest1.title AS fromName,dest2.title AS toName');
		$query->select('CONCAT(`dest1`.`title`,'.$db->quote('-').',`dest2`.`title`) AS title');
		
		$query->select('(select adult from #__bookpro_roomrate AS r WHERE r.room_id=flight.id AND DATE_FORMAT(`r`.`date`,"%Y-%m-%d")='.$this->_db->quote($depart_date).') AS rprice ');
		$query->from('#__bookpro_bustrip AS bustrip');
		$query->join("LEFT", '#__bookpro_dest AS `dest1` ON `flight`.`desfrom` = `dest1`.`id`');
		$query->join("LEFT", '#__bookpro_dest AS `dest2` ON `flight`.`desto` = `dest2`.`id`');
		$query->join('LEFT', '#__bookpro_airline AS `airline` ON `airline`.`id` = `flight`.`airline_id`');
		
		if ($this->_lists['from']){
			
			$query->where('flight.desfrom='.$this->_lists['from']);
		}
		if ($this->_lists['to']){
			
			$query->where('flight.desto='.$this->_lists['to']);
		}
		if ($this->_lists['airline_id']){
			
			$query->where('flight.airline_id='.$this->_lists['airline_id']);
		}
		
		$query->having('rprice > 0');
		$query->order('flight.start ASC');
		
		return $query;
	}
	
	
}