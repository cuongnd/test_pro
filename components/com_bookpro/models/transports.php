<?php


defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelTransports extends AModelFrontEnd
{

	var $_table;

	function __construct()
	{
		parent::__construct();
		$this->_table = $this->getTable('transport');

	}

	/**
	 * Get MySQL loading query for customers list
	 *
	 * @return string complet MySQL query
	 */
	function buildQuery()
	{
		$query=null;
		$airportTable = &$this->getTable('airport');
		$bustripTable=&$this->getTable("transport");
		if (is_null($query)) {
			$query = 'SELECT `transport`.*, `dest1`.`title` as `destfrom`,`dest2`.`title` AS `destto`';
			$query .= 'FROM `' . $this->_table->getTableName() . '` AS `transport` ';
			$query .= 'LEFT JOIN `' . $airportTable->getTableName() . '` AS `dest1` ON `transport`.`from` = `dest1`.`id` ';
			$query .= 'LEFT JOIN `' . $airportTable->getTableName() . '` AS `dest2` ON `transport`.`to` = `dest2`.`id` ';
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 3.2//EN">

<html>
<head>
  <meta name="generator" content=
  "HTML Tidy for Windows (vers 14 February 2006), see www.w3.org">

  <title></title>
</head>

<body>
</body>
</html>
