<?php


defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelSeattemplates extends AModel
{

	var $_table;

	function __construct()
	{
		parent::__construct();
		$this->_table = $this->getTable('seattemplate');

	}

	/**
	 * Get MySQL loading query for customers list
	 *
	 * @return string complet MySQL query
	 */
	function buildQuery()
	{
		$query=null;
		$busTable=&$this->getTable("bus");
		if (is_null($query)) {
			$query = 'SELECT bus_seattemplate.* ';
			$query .= 'FROM `' . $this->_table->getTableName() . '` AS `bus_seattemplate` ';
			$query .= $this->buildContentWhere();
			$query .= $this->buildContentOrderBy();
		}
		return $query;


	}



	function buildContentWhere()
	{
		$where = array();
		$this->addIntProperty($where, 'dest_id');
		$this->addIntProperty($where, 'bustrip_id');
		$this->addIntProperty($where, 'bus_id');
		return $this->getWhere($where);
	}



}

?>