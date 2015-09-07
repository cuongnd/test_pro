<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: rooms.php 48 2012-07-13 14:13:31Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelRooms extends AModelFrontEnd
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
		$this->_table = $this->getTable('room');
	}

	 
	function buildQuery()
	{
		$query = null;

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
		$this->addIntProperty($where, 'hotel_id','state');
        $this->addStringProperty($where,'title');
		return $this->getWhere($where);
	}
	function getRoomsByIDs($Ids){
		
		
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