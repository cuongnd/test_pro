<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: agents.php 56 2012-07-21 07:53:28Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelAgents extends AModel
{
    /**
     * Main table
     * 
     * @var TableAgent
     */
    var $_table;

    function    __construct()
    {
        parent::__construct();
        $this->_table = $this->getTable('agent');
    }

    /**
     * Get MySQL loading query for agents list
     * 
     * @return string complet MySQL query
     */
    function buildQuery()
    {
        static $query;
        if (is_null($query)) {
            $query = 'SELECT `agent`.* ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `agent` ';
            $query .= $this->buildContentWhere();
        }
        return $query;
    }

    /**
     * Get MySQL filter criteria for agents list
     * 
     * @return string filter criteria in MySQL format
     */
    function buildContentWhere()
    {
        $where = array();
        $this->addIntProperty($where, 'state');
        $this->addStringProperty($where, 'firstname','name','lastname','desc');
        return $this->getWhere($where);
    }
    function buildQueryDriver(){
    	$db = JFactory::getDbo();
    	$query = "
    				SELECT bustrip.*,CONCAT(`dest1`.`title`,".$this->_db->quote('-').",`dest2`.`title`) AS title 
    			FROM #__bookpro_bustrip AS bustrip 
    			LEFT JOIN #__bookpro_bus AS bus ON bustrip.bus_id = bus.id 
    			LEFT JOIN #__bookpro_dest AS dest1 ON bustrip.from = dest1.id 
    			LEFT JOIN #__bookpro_dest AS dest2 ON bustrip.to = dest2.id 
    			";
    	if($this->_lists['agent_id']){
    		$where[] = 'bus.agent_id = '.$this->_lists['agent_id'];
    	}
    	if ($where != NULL) {
    		$query .= " WHERE ".implode(" AND ", $where);
    	}
    	
    	return $query;
    }
    function buildQueryAgent(){
    	$db = JFactory::getDbo();
    	$query = "
    				SELECT oi.id,DATE_FORMAT(`oi`.`start`,'%d-%m-%Y') AS date,COUNT(oi.order_id) AS total,SUM(oi.adult) + SUM(oi.child) AS sumbus ,CONCAT(`dest1`.`title`,".$this->_db->quote('-').",`dest2`.`title`) AS title
    			FROM #__bookpro_orderinfo AS oi
    			LEFT JOIN #__bookpro_bustrip AS bustrip ON oi.obj_id = bustrip.id
    			LEFT JOIN #__bookpro_bus AS bus ON bustrip.bus_id  = bus.id
    			LEFT JOIN #__bookpro_dest AS dest1 ON bustrip.from = dest1.id
    			LEFT JOIN #__bookpro_dest AS dest2 ON bustrip.to = dest2.id
    
    			";
    	
    	if ($this->_lists['from']) {
    		$where[] = 'bustrip.from='.$this->_lists['from'];
    	}
    	if($this->_lists['to']){
    		$where[] = 'bustrip.to ='.$this->_lists['to'];
    	}
    	if ($this->_lists['start']) {
    		$where[]='DATE_FORMAT(`oi`.`start`,"%Y-%m-%d")='. $this->_db->quote(JFactory::getDate($this->_lists['start'])->format('Y-m-d'));
    	
    		//$where[] = 'oi.start = '.$this->_lists['start'];
    	}
    	if($this->_lists['agent_id']){
    		$where[] = 'bus.agent_id='.$this->_lists['agent_id'];
    	
    	}
    	if ($where != NULL) {
    		$query .= " WHERE ".implode(" AND ", $where);
    	}
    	$query .= " GROUP BY oi.obj_id";
    	
    	return $query;
    }
    function buildQueryTicketAgent(){
    	$db = JFactory::getDbo();
    	$query = "SELECT oi.id,DATE_FORMAT(`oi`.`start`,'%d-%m-%Y') AS date,ord.order_number,COUNT(oi.order_id) AS total,SUM(oi.adult) + SUM(oi.child) AS sumbus ,CONCAT(`dest1`.`title`,".$this->_db->quote('-').",`dest2`.`title`) AS title
    			FROM #__bookpro_orderinfo AS oi
    			LEFT JOIN #__bookpro_orders AS ord ON oi.order_id = ord.id 
    			LEFT JOIN #__bookpro_bustrip AS bustrip ON oi.obj_id = bustrip.id
    			LEFT JOIN #__bookpro_bus AS bus ON bustrip.bus_id  = bus.id
    			LEFT JOIN #__bookpro_dest AS dest1 ON bustrip.from = dest1.id
    			LEFT JOIN #__bookpro_dest AS dest2 ON bustrip.to = dest2.id
    
    			";
    	 
    	if ($this->_lists['from']) {
    		$where[] = 'bustrip.from='.$this->_lists['from'];
    	}
    	if($this->_lists['to']){
    		$where[] = 'bustrip.to ='.$this->_lists['to'];
    	}
    	if ($this->_lists['start']) {
    		$where[]='DATE_FORMAT(`oi`.`start`,"%Y-%m-%d")='. $this->_db->quote(JFactory::getDate($this->_lists['start'])->format('Y-m-d'));
    		 
    		//$where[] = 'oi.start = '.$this->_lists['start'];
    	}
    	if($this->_lists['agent_id']){
    		$where[] = 'bus.agent_id='.$this->_lists['agent_id'];
    		 
    	}
    	if ($where != NULL) {
    		$query .= " WHERE ".implode(" AND ", $where);
    	}
    	$query .= " GROUP BY oi.obj_id";
    	 
    	return $query;
    }
    
    function buildQuerySaleReport(){
    	$db = JFactory::getDbo();
    	$query = "
    				SELECT oi.id,DATE_FORMAT(oi.start,'%d-%m-%Y') as date,CONCAT(dest1.title,".$this->_db->quote('-').",dest2.title) as title,SUM(oi.price) AS subprice,SUM(adult)+SUM(child) AS qty 
    				FROM #__bookpro_orderinfo AS oi 
    				LEFT JOIN #__bookpro_bustrip AS bustrip ON oi.obj_id = bustrip.id 
    				LEFT JOIN #__bookpro_bus AS bus ON bustrip.bus_id = bus.id 
    				LEFT JOIN #__bookpro_dest AS dest1 ON bustrip.from = dest1.id 
    				LEFT JOIN #__bookpro_dest AS dest2 ON bustrip.to = dest2.id
    			";
    	if ($this->_lists['from']) {
    		$where[] = 'bustrip.from='.$this->_lists['from'];
    		
    	}
    	if ($this->_lists['to']) {
    		$where[] = 'bustrip.to='.$this->_lists['to'];
    	}
    	if ($this->_lists['start']) {
    		$where[] = 'DATE_FORMAT(oi.start,"%Y-%m-%d")='.$this->_db->quote(JFactory::getDate($this->_lists['start'])->format('Y-m-d'));
    	}
    	if ($this->_lists['agent_id']) {
    		$where[] = 'bus.agent_id='.$this->_lists['agent_id'];
    	}
    	if ($where != NULL){
    		$query .=" WHERE ".implode(" AND ", $where);
    	}
    	$query .= " GROUP BY oi.obj_id";
    	
    	return $query;
    }
   	function getAgentSaleReport(){
   		$db = JFactory::getDbo();
   		$query = $this->buildQuerySaleReport();
   		$db->setQuery($query,$this->getState('limitstart'), $this->getState('limit'));
   		 
   		$rows = $db->loadObjectList();
   		 
   		return $rows;
   	}
   	function getTotalSaleReport(){
   		if (empty($this->_total)) {
   			$query = $this->buildQuerySaleReport();
   			$this->_total = $this->_getListCount($query);
   		}
   		return (int) $this->_total;
   	}
   	function getPaginSaleReport(){
   		if (empty($this->_pagination)) {
   			jimport('joomla.html.pagination');
   			AImporter::helper('pagination');
   			$this->_pagination = new BookProPagination($this->getTotalSaleReport($this), $this->getState('limitstart'), $this->getState('limit'));
   		
   		}
   		return $this->_pagination;
   	}
    function getAgentReportDriver(){
    	$db = JFactory::getDbo();
    	$query = $this->buildQueryDriver();
    	$db->setQuery($query,$this->getState('limitstart'), $this->getState('limit'));
    	$rows = $db->loadObjectList();
    	
    	
    	
    	return $rows;
    }
    function getAgentDriver($obj_id,$date){
    	$db = JFactory::getDbo();
    	$query = "
    			SELECT oi.*,COUNT(oi.order_id) AS ticket,SUM(oi.adult) + SUM(oi.child) AS seat 
    			FROM #__bookpro_orderinfo AS oi 
    			";
    	if ($obj_id) {
    		$where[] = "oi.obj_id=".$obj_id;
    	}
    	if ($date) {
    		$where[] = "DATE_FORMAT(`oi`.`start`,'%Y-%m-&d') = ".$date;
    	}
    	if ($where != NULL) {
    		$query .= " WHERE ".implode(" AND ", $where);
    	}
    	
    	
    	$db->setQuery($query);
    	return $db->loadObjectList();
    }
    function getAgentReport(){
    	$db = JFactory::getDbo();
		$query = $this->buildQueryAgent();
    	$db->setQuery($query,$this->getState('limitstart'), $this->getState('limit'));
    	
    	$rows = $db->loadObjectList();
    	
    	return $rows;
    }
    function getAgentTicketReport(){
    	$db = JFactory::getDbo();
    	$query = $this->buildQueryTicketAgent();
    	
    	$db->setQuery($query,$this->getState('limitstart'), $this->getState('limit'));
    	 
    	$rows = $db->loadObjectList();
    	 
    	return $rows;
    }
    function getTotalDriver(){
    	if (empty($this->_total)) {
    		$query = $this->buildQueryDriver();
    		$this->_total = $this->_getListCount($query);
    	}
    	return (int) $this->_total;
    }
    
    function getTotalAgent(){
    	if (empty($this->_total)) {
    		$query = $this->buildQueryAgent();
    		$this->_total = $this->_getListCount($query);
    	}
    	return (int) $this->_total;
    }
    function getTotalTicketAgent(){
    	if (empty($this->_total)) {
    		$query = $this->buildQueryTicketAgent();
    		$this->_total = $this->_getListCount($query);
    	}
    	return (int) $this->_total;
    }
    function getPaginAgent(){
    	if (empty($this->_pagination)) {
    		jimport('joomla.html.pagination');
    			AImporter::helper('pagination');
    			$this->_pagination = new BookProPagination($this->getTotalAgent($this), $this->getState('limitstart'), $this->getState('limit'));
    		
    	}
    	return $this->_pagination;
    }
    function getPaginTicketAgent(){
    	if (empty($this->_pagination)) {
    		jimport('joomla.html.pagination');
    		AImporter::helper('pagination');
    		$this->_pagination = new BookProPagination($this->getTotalTicketAgent(), $this->getState('limitstart'), $this->getState('limit'));
    
    	}
    	return $this->_pagination;
    }
    function getPaginDriver(){
    	if (empty($this->_pagination)) {
    		jimport('joomla.html.pagination');
    		AImporter::helper('pagination');
    		$this->_pagination = new BookProPagination($this->getTotalDriver($this), $this->getState('limitstart'), $this->getState('limit'));
    	
    	}
    	return $this->_pagination;
    }

    function getAgentByIds($Ids){
        if($Ids){
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $query->select("a.*");
            $query->from("#__bookpro_agent as a");
            $query->where("a.id IN"."(".$Ids.")");
            $db->setQuery($query);
            $this->setState('list.limit');
            return $db->loadObjectList();
        }


    }


    
}

?>