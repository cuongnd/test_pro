<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: insurance.php 14 2012-06-26 12:42:05Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'model');

class BookProModelInsurance extends AModel
{
   
    var $_table;
   
    var $_ids;

    function __construct()
    {
        parent::__construct();
        if (! class_exists('TableInsurance')) {
            AImporter::table('insurance');
        }
        $this->_table = $this->getTable('insurance');
    }

    function getObject()
    {
       
            $query = 'SELECT `insurance`.* FROM `' . $this->_table->getTableName() . '` AS `insurance` ';
            $query .= 'WHERE `insurance`.`id` = ' . (int) $this->_id;
            $this->_db->setQuery($query);
            
            if (($object = &$this->_db->loadObject())) {
                $this->_table->bind($object);
                return $this->_table;
            }
       
        return parent::getObject();
    }
    
	

 
    function store($data)
    {
              
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
    function trash($cids){
    	
    	foreach ($cids as $id){
    		
        	if( !$this->_table->delete($id))
        	{
        		$this->setError($this->_db->getErrorMsg());
                return false;
        	}
        	  	
        }
        return true;
    }
    public function publish(&$pks, $value = 1)
    {
    	$user = JFactory::getUser();
    	$table = $this->getTable();
    	$pks = (array) $pks;
    
    	// Attempt to change the state of the records.
    	if (!$table->publish($pks, $value, $user->get('id')))
    	{
    		$this->setError($table->getError());
    
    		return false;
    	}
    
    	return true;
    }
    function unpublish($cids){
    	return $this->state('state', $cids, 0, 1);
    }

      
}

?>