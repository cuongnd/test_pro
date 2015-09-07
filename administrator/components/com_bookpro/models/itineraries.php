<?php


defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelItineraries extends AModel
{
    
    var $_table;

    function __construct()
    {
        parent::__construct();
        $this->_table = $this->getTable('itinerary');
        
    }

    /**
     * Get MySQL loading query for customers list
     * 
     * @return string complet MySQL query
     */
    function buildQuery()
    {
        $query=null;
        $destTable = &$this->getTable('airport');
        if (is_null($query)) {
           $query = 'SELECT `obj`.*, `dest`.`title` as dest_name,country.country_name AS country_name ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
            $query .= 'LEFT JOIN `' . $destTable->getTableName() . '` AS `dest` ON `dest`.`id` = `obj`.`dest_id` ';
            $query .= 'LEFT JOIN `#__bookpro_country` AS country ON dest.country_id = country.id ';
            
			$query .= $this->buildContentWhere();
            $query .= $this->buildContentOrderBy();
        }
        return $query;
    }
 	function  getByTourID($id){
    	
    	   $tourTable = &$this->getTable('tour');
           $query = 'SELECT `obj`.*, `cat`.`title` as `cat_title` ';
           $query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
           $query .= 'LEFT JOIN `' . $tourTable->getTableName() . '` AS `cat` ON `cat`.`id` = `obj`.`tour_id` ';
		   $query .= 'WHERE `obj`.`tour_id`='.$id;
           $this->_db->setQuery($query);
       	   return $this->_db->loadObjectList();
    }
    
  
    	
    function buildContentWhere()
    {
        $where = array();
        $this->addIntProperty($where, 'tour_id','dest_id','obj-state');
        return $this->getWhere($where);
    }
 
    
}

?>