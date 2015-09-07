<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: orderinfo.php 102 2012-08-29 17:33:02Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.model');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'model');
//import needed tables

class BookProModelOrderInfo extends AModel
{
    
    var $_table;
    /**
     * Map user ids to customer ids.
     * 
     * @var array
     */
    var $_ids;

    function __construct()
    {
        parent::__construct();
        if (! class_exists('TableOrderInfo')) {
            AImporter::table('orderinfo');
        }
        $this->_table = $this->getTable('orderinfo');
    }

    function getObject()
    {
        
            $query = 'SELECT `obj`.* FROM `' . $this->_table->getTableName() . '` AS `obj` ';
            $query .= 'WHERE `obj`.`id` = ' . (int) $this->_id;
            
            $this->_db->setQuery($query);
            
            if (($object = &$this->_db->loadObject())) {
                $this->_table->bind($object);
                return $this->_table;
            }
       
       		return parent::getObject();
    }
    function getListByOrderID($id){
    	
   			
            $query = 'SELECT `obj`.*, f.desfrom AS from_id, f.desto AS to_id,f.eco_price AS f_price,f.start AS f_start,f.end AS f_end FROM `' . $this->_table->getTableName() . '` AS `obj` ';
            $query .= ' LEFT JOIN  #__bookpro_flight AS f ON f.id = `obj`.`obj_id`';
            $query .= ' WHERE `obj`.`order_id` = ' . (int) $id . ' AND `obj`.`type` =' . (int)FLIGHT; 
           
            return $this->_getList($query);
        
    }
    function getRawListByOrderID($id){
    	
   			
            $query = 'SELECT `obj`.* FROM `' . $this->_table->getTableName() . '` AS `obj` ';
           
            $query .= ' WHERE `obj`.`order_id` = ' . (int) $id ; 
           
            return $this->_getList($query);
        
     }

   
    /**
     * Save Passenger.
     * 
     * @param array $data request data
     * @return customer id if success, false in unsuccess
     */
    function store($data)
    {
        $config = &AFactory::getConfig();
        /* @var $config BookingConfig */
        
        $id = (int) $data['id'];
        $this->_table->init();
        $this->_table->load($id);
        
        if (! $this->_table->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        unset($data['id']);
        
              
        if (! $this->_table->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        
        if (! $this->_table->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        
        return $this->_table->id;
    }
    function delete($cid){
    	
    	foreach ($cids as $id){
    		
        	if( !$this->_table->delete($id))
        	{
        		$this->setError($this->_db->getErrorMsg());
                return false;
        	}
        
        }
        return true;
    }
  function deleteByOrderID($orderID){
    	$query='DELETE FROM ' . $this->_table->getTableName(). ' ';
    	$query.= 'WHERE `order_id` = ' . $orderID;
    	$this->_db->setQuery($query);
	    if (!$this->_db->query()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        return true;
    }
    
   function getBookedSeat($date, $obj_id,$package=null) {
    
    	$query = 'SELECT (SUM(adult)+SUM(child)+SUM(infant)) AS seat ';
    	$query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
    	$where=array();
    	$where[]='DATE_FORMAT(start,"%Y-%m-%d")='. $this->_db->quote($date);
    	$where[]='obj_id='. (int) $obj_id; 
    	if($package)
    		$where[]='package='.$package;
    	$query .= ' WHERE ' . implode(' AND ', $where);
    	$this->_db->setQuery($query);
    	return $this->_db->loadResult();
    
    }

    
      
}

?>