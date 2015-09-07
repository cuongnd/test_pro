<?php

defined('_JEXEC') or die;

AImporter::helper('request', 'model');
class BookProModelBustripSearch extends AModelFrontEnd
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

		$query->select('bustrip.*,`agent`.`brandname` AS `brandname`,agent.company, `bus`.`id` AS `bus_id_`,  `bus`.`image` AS `bus_image`, `dest1`.`title` as `fromName`, `dest2`.`title` as `toName`');
		$query->select('`bus`.`seat` AS `bus_seat`, `bus`.`title` as `bus_name`, `bus`.`desc` as `bus_sum`,CONCAT(`dest1`.`title`,'.$db->quote('-').',`dest2`.`title`) AS title,`bustrip`.`id` AS b_id');
		$query->from('#__bookpro_bustrip AS bustrip');
		$query->join("LEFT", '#__bookpro_dest AS `dest1` ON `bustrip`.`from` = `dest1`.`id`');
		$query->join("LEFT", '#__bookpro_dest AS `dest2` ON `bustrip`.`to` = `dest2`.`id`');
		$query->join('LEFT', '#__bookpro_bus AS `bus` ON `bus`.`id` = `bustrip`.`bus_id`');
		$query->join('LEFT', '#__bookpro_agent AS agent ON `agent`.`id` = `bus`.`agent_id`');

		if ($this->_lists['from']){
			
			$query->where('bustrip.from='.$this->_lists['from']);
		}
		if ($this->_lists['to']){
			
			$query->where('bustrip.to='.$this->_lists['to']);
		}
		if ($this->_lists['bus_id']){
			
			$query->where('bustrip.bus_id='.$this->_lists['bus_id']);
		}
		
		if ($this->_lists['agent_id']){
			
			$query->where('bus.agent_id='.$this->_lists['agent_id']);
		}

		$query->where('bustrip.state = 1');
		//$query->having('price > 0');
		$query->order('bustrip.start_time ASC');

		return $query;
	}
	
	
}