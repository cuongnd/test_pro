<?php


defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelInsurances extends AModelFrontEnd
{
    
    var $_table;

    function __construct()
    {
        parent::__construct();
        $this->_table = $this->getTable('insurance');
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
            $query = 'SELECT `insurance`.* ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `insurance` ';
            $query .= $this->buildContentWhere();
            //$query .= $this->buildContentOrderBy();
        }
        return $query;
    }
    function buildContentWhere()
    {
    	$where = array();
    	$this->addIntProperty($where, 'state');
    	return $this->getWhere($where);
    }

  }

?>