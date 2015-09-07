<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: room.php 48 2012-07-13 14:13:31Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.model');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'model');
//import needed tables

class BookProModelPackageRatedaytripjoingroupLog extends AModelFrontEnd
{
	var $_table;

	var $_ids;

	function __construct()
	{
		parent::__construct();
		if (! class_exists('TablePackageRatedaytripjoingroupLog')) {
			AImporter::table('packageratedaytripjoingrouplog');
		}
		$this->_table = $this->getTable('packageratedaytripjoingrouplog');
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


	function store($data)
	{
		$config = &AFactory::getConfig();

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
    
	function unpublish($cids){
		return $this->state('state', $cids, 0, 1);
	}
	function publish($cids){
		return $this->state('state', $cids, 1, 0);
	}
	
  function saveorder($cids, $order)
    {
        $branches = array();
        for ($i = 0; $i < count($cids); $i ++) {
            $this->_table->load((int) $cids[$i]);
            $branches[] = $this->_table->parent;
            if ($this->_table->ordering != $order[$i]) {
                $this->_table->ordering = $order[$i];
                if (! $this->_table->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
        }
        $branches = array_unique($branches);
        foreach ($branches as $group) {
            $this->_table->reorder('parent = ' . (int) $group);
        }
        return true;
    }
	 

}

?>