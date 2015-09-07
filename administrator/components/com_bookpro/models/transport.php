<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: transport.php 14 2012-06-26 12:42:05Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'model');

class BookProModelTransport extends AModel
{

	var $_table;

	var $_ids;

	function __construct()
	{
		parent::__construct();
		if (! class_exists('TableTransport')) {
			AImporter::table('transport');
		}
		$this->_table = $this->getTable('transport');
	}

	function getObject()	{
		$airportTable = &$this->getTable('airport');
		$busTable= $this->getTable('transport');
		$query = 'SELECT `transport`.*,`dest1`.`title` AS `tfrom`,`dest2`.`title` AS `tto` FROM `' . $this->_table->getTableName() . '` AS `transport` ';
		$query .= 'LEFT JOIN `' . $airportTable->getTableName() . '` AS `dest1` ON `transport`.`from` = `dest1`.`id` ';
		$query .= 'LEFT JOIN `' . $airportTable->getTableName() . '` AS `dest2` ON `transport`.`to` = `dest2`.`id` ';
		$query .= 'WHERE `transport`.`id` = ' . (int) $this->_id;
		$this->_db->setQuery($query);

		if (($object = &$this->_db->loadObject())) {
			$this->_table->bind($object);
			$this->_table->tfrom=$object->tfrom;
			$this->_table->tto=$object->tto;
			return $this->_table;
		}
		return parent::getObject();
	}
	
	public function publish(&$pks, $value = 1)
	{
		$user = JFactory::getUser();
		$table = $this->getTable();
		$pks = (array) $pks;
		
		// Attempt to change the state of the records.
		if (!$table->publish($pks, $value, $user->get('id')))
		{
			$this->setError($table->getError());
	
			return false;
		}
		
		return true;
	}
	function unpublish($cids){
		return $this->state('state', $cids, 0, 1);
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
