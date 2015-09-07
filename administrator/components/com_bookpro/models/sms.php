<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: sms.php 108 2012-09-04 04:53:31Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.model');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'model');
//import needed tables


class BookProModelSms extends AModel
{

	/**
	 * Main table.
	 *
	 * @var TableSms
	 */
	var $_table;
	/**
	 * Map user ids to sms ids.
	 *
	 * @var array
	 */
	var $_ids;

	function __construct()
	{
		parent::__construct();
		if (! class_exists('TableSms')) {
			AImporter::table('sms');
		}
		$this->_table = $this->getTable('sms');
	}

 function getObject()
    {
       
            $query = 'SELECT `meal`.* FROM `' . $this->_table->getTableName() . '` AS `meal` ';
            $query .= 'WHERE `meal`.`id` = ' . (int) $this->_id;
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
}

?>