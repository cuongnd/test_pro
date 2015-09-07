<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: buss.php 48 2012-07-13 14:13:31Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelbusRates extends AModelFrontEnd
{
	/**
	 * Main table
	 *
	 * @var Table
	 */
	var $_table;

	function __construct()
	{
		parent::__construct();
		$this->_table = $this->getTable('busrate');
	}

	 
	function buildQuery()
	{
        $query=null; 
		if (is_null($query)) {
			$query = 'SELECT `obj`.* ';
			$query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
			$query .= $this->buildContentWhere();    
		}
		return $query;
	}

	 
	function buildContentWhere()
	{
		$where = array();
		$this->addStringProperty($where, 'date');   
        $this->addIntProperty($where, 'bus_id');   
		return $this->getWhere($where);
	}
	function getbussByIDs($Ids){
		$tid=implode(',', $Ids);
		$query = 'SELECT `obj`.* ';
		$query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
		$query .='WHERE id IN (' . $tid.')';
		return $this->_getList($query);
		 
	}
}

?>