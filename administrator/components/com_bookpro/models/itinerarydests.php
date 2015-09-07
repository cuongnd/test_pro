<?php


defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelItineraryDests extends AModel
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
           $query = 'SELECT `obj`.*, `dest`.`title` as dest_name ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
            $query .= 'LEFT JOIN `' . $destTable->getTableName() . '` AS `dest` ON `dest`.`id` = `obj`.`dest_id` ';
			$query .= $this->buildContentWhere();
            $query .= $this->buildContentOrderBy();
        }
        return $query;
    }
    function getDestIdByItinerary($itinerary_id){
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(true);
    	$query->select('id.dest_id');
    	$query->from('#__bookpro_itinerarydest AS id');
    	if ($itinerary_id) {
    		
    		$query->where('id.itinerary_id='.$itinerary_id);
    		
    	}
    	$db->setQuery($query);
    	return $db->loadColumn();
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