<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bus.php 14 2012-06-26 12:42:05Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'model');

class BookProModelSeattemplate extends AModelFrontEnd
{
   
    var $_table;
   
    var $_ids;

    function __construct()
    {
        parent::__construct();
        if (! class_exists('Tableseattemplate')) {
            AImporter::table('seattemplate');
        }
        $this->_table = $this->getTable('seattemplate');
    }

    function getObject()
    {
       
            $query = 'SELECT `buss`.* FROM `' . $this->_table->getTableName() . '` AS `buss` ';
            $query .= 'WHERE `buss`.`id` = ' . (int) $this->_id;
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
        $generate_item=$data['row']*$data['column'];
        $array_remove= range($generate_item+1, count($data['block_type']));
        foreach($array_remove as $value)
        {
            unset($data['block_type'][$value]);
            unset($data['seatnumber'][$value]);
        }
        $data['block_layout']=json_encode(array(
        'row'=>$data['row'],
        'column'=>$data['column'],
        'block_type'=>$data['block_type'],
        'seatnumber'=>$data['seatnumber']
        ));
         
        
        
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
    function saveorder($cids, $order)
    {
    	// $branches = array();
    	for ($i = 0; $i < count($cids); $i ++) {
    		$this->_table->load((int) $cids[$i]);
    		//$branches[] = $this->_table->parent;
    		if ($this->_table->ordering != $order[$i]) {
    			$this->_table->ordering = $order[$i];
    			if (! $this->_table->store()) {
    				$this->setError($this->_db->getErrorMsg());
    				return false;
    			}
    		}
    	}
    	//         $branches = array_unique($branches);
    	//         foreach ($branches as $group) {
    	//             $this->_table->reorder('parent = ' . (int) $group);
    	//         }
    	return true;
    }

      
}

?>