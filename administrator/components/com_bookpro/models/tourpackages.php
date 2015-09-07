<?php

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelTourPackages extends AModel
{

	var $_table;

	function __construct()
	{
		parent::__construct();
		$this->_table = $this->getTable('tourpackage');

	}

	/**
	 * Get MySQL loading query for customers list
	 *
	 * @return string complet MySQL query
	 */
	function buildQuery()
	{
		
		$tourTable = &$this->getTable('tour');
		$packagetypeTable = &$this->getTable('packagetype');
		if (is_null($query)) {
			$query = 'SELECT `obj`.*, `tour`.`title` as `tour_name`,`packagetype`.`title` as `packagetype_name`,CONCAT(`packagetype`.`title`," ",`obj`.`min_person`) AS `packagetitle` ';
			$query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
			$query .= 'LEFT JOIN `' . $tourTable->getTableName() . '` AS `tour` ON `tour`.`id` = `obj`.`tour_id` ';
			$query .= 'LEFT JOIN `' . $packagetypeTable->getTableName() . '` AS `packagetype` ON `packagetype`.`id` = `obj`.`packagetype_id` ';
			
			$query .= $this->buildContentWhere();
			
			$query .= $this->buildContentOrderBy();
			//var_dump($query);
			//die("a");
			
		}
		return $query;
	}
	function  getByTourID($id){
		 
		$tourTable = &$this->getTable('tour');
		$categoryTable=&$this->getTable("category");
		$query = 'SELECT `obj`.* ';
		$query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';

		//$query .= 'LEFT JOIN `' . $categoryTable->getTableName() . '` AS `cat` ON `cat`.`id` = `obj`.`cat_id` ';

		$query .= 'WHERE `obj`.`tour_id`='.$id;
		$this->_db->setQuery($query);

		return $this->_db->loadObjectList();
	}
	 
	 function  getByPackageTypeID($id){
		
		$packagetypeTable = &$this->getTable('packagetype');
		$categoryTable=&$this->getTable("category");
		$query = 'SELECT `obj`.*, `cat`.`title` as `cat_title` ';
		$query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
		$query .= 'LEFT JOIN `' . $categoryTable->getTableName() . '` AS `cat` ON `cat`.`id` = `obj`.`cat_id` ';
		$query .= 'WHERE `obj`.`packagetype_id`='.$id;
		$this->_db->setQuery($query);
		return $this->_db->loadObjectList();
	}
	 
	function buildContentWhere()
	{
		$where = array();
		$this->addIntProperty($where, 'tour_id','obj-state');
		$this->addIntProperty($where, 'packagetype_id','obj-state');
		return $this->getWhere($where);
		 

	}
	function getTourPackagesByIDs($Ids){
	
	
		$query = 'SELECT `obj`.* ';
		$query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
		if (!empty($Ids)) {
			$tid=implode(',', $Ids);
			$query .='WHERE id IN (' . $tid.')';
		}else{
			$query .='WHERE id IS NULL';
		}
	
		return $this->_getList($query);
			
	}
}
	?>