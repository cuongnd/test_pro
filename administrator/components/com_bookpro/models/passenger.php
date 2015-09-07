<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: passenger.php 25 2012-07-08 13:02:59Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.model');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'model');

class BookProModelPassenger extends AModel
{
    
    /**
     * Main table.
     * 
     * @var TablePassenger
     */
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
        if (! class_exists('TablePassenger')) {
            AImporter::table('passenger');
        }
        $this->_table = $this->getTable('passenger');
    }

    function getObject()
    {
            $query = 'SELECT `passenger`.* FROM `' . $this->_table->getTableName() . '` AS `passenger` ';
            $query .= 'WHERE `passenger`.`id` = ' . (int) $this->_id;
            $this->_db->setQuery($query);
            
            if (($object = &$this->_db->loadObject())) {
                $this->_table->bind($object);
                return $this->_table;
            }
        return parent::getObject();
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
    function deleteByCustomerID($customeID){
    	$query='DELETE FROM ' . $this->_table->getTableName(). ' ';
    	$query.= 'WHERE `customer_id` = ' . $customeID;
    	$this->_db->setQuery($query);
	    if (!$this->_db->query()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
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
    function trash($cids)
    {
    
    	foreach ($cids as $id){
    
    		if( !$this->_table->delete($id))
    		{
    			$this->setError($this->_db->getErrorMsg());
    			return false;
    		}
    
    	}
    	return true;
    		
    }
    function getPassengerByOrderid($order_id){
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('a.*');
        $query->from('#__bookpro_passenger as a');

        $query->where('a.order_id='.$order_id);
        $db->setQuery($query);
        $result = $db->loadObjectList();

        return $result;
    }

    function getPassengerLeaderByOrderid($order_id){
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('a.*');
        $query->from('#__bookpro_passenger as a');
        $query->join('inner','#__bookpro_country as b ON b.id=a.country_id');
        $query->where('a.order_id='.$order_id.' and a.leader=1');
        $db->setQuery($query);
        $result = $db->loadObject();
        return $result;
    }
      
}

?>