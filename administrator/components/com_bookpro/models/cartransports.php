<?php


defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelCarTransports extends AModel
{

	var $_table;

	function __construct()
	{
		parent::__construct();
		$this->_table = $this->getTable('cartransport');

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
			$query = 'SELECT `cartransport`.* ';
			$query .= 'FROM `' . $this->_table->getTableName() . '` AS `cartransport` ';
			$query .= $this->buildContentWhere();
			$query .= $this->buildContentOrderBy();       
		}
		return $query;

	}



	function buildContentWhere()
	{
		$where = array();
		$this->addIntProperty($where, 'from');
		$this->addIntProperty($where, 'to');
		return $this->getWhere($where);
	}

}

