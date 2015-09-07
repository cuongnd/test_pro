<?php


defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelBuses extends AModelFrontEnd
{
    
    var $_table;

    function __construct()
    {
        parent::__construct();
        $this->_table = $this->getTable('bus');
    }

    /**
     * Get MySQL loading query for customers list
     * 
     * @return string complet MySQL query
     */
    function buildQuery()
    {
        $query=null;
        
        if (is_null($query)) {
            $query = 'SELECT `bus`.*,agent.company ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `bus` ';
            $query .= 'LEFT JOIN `#__bookpro_agent` AS `agent` ON `agent`.`id` = `bus`.`agent_id` ';
            $query .= $this->buildContentWhere();
        }
        return $query;
    }
    function buildContentWhere()
    {
    	$where = array();
    	$this->addIntProperty($where, 'bus-state');
    	$this->addIntProperty($where, 'agent_id');
        $this->addStringProperty($where, 'title');
    	return $this->getWhere($where);
    }

  }

?>