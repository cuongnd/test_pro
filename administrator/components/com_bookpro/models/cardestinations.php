<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: airports.php 100 2012-08-29 14:55:21Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelCarDestinations extends AModel
{
	/**
	 * Main table
	 *
	 * @var TableCustomer
	 */
	var $_table;

	function __construct()
	{
		parent::__construct();
		$this->_table = $this->getTable('cardestination');
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
			$query = 'SELECT `cardest`.* ';
			$query .= 'FROM `' . $this->_table->getTableName() . '` AS `cardest` ';
			$query .= $this->buildContentWhere();
			//$query .= $this->buildContentOrderBy();
		}
		return $query;
	}
	function getDestinationByIds($Ids){
		 
		$query = 'SELECT `cardest`.* FROM `' . $this->_table->getTableName() . '` AS `cardest` ';
		$query .= 'WHERE `cardest`.`id` IN (' . implode(',', $Ids).')';
		$this->_db->setQuery($query);
		return  $this->_db->loadObjectList();
	}
	 


	/**
	 * Get MySQL filter criteria for customers list
	 *
	 * @return string filter criteria in MySQL format
	 */
	function buildContentWhere()
	{
		$where = array();
		$this->addStringProperty($where, 'title');
		return $this->getWhere($where);
	}
}

?>