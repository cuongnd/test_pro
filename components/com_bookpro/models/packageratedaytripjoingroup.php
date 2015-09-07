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

class BookProModelPackageRatedaytripjoingroup extends AModelFrontEnd
{
	var $_table;

	var $_ids;

	function __construct()
	{
		parent::__construct();
		if (! class_exists('TablePackageRatedaytripjoingroup')) {
			AImporter::table('packageratedaytripjoingroup');
		}
		$this->_table = $this->getTable('packageratedaytripjoingroup');
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



	function store($post)
	{
		$startdate = new JDate($post['startdate']);
		$enddate = new JDate($post['enddate']);

		//delete exist relation

		$db = JFactory::getDBO();
		$query = 'DELETE `obj`.* FROM `#__bookpro_packageratedaytripjoingroup` AS `obj` ';
		$query .= 'WHERE `obj`.`tour_id` = ' . (int) $post['tour_id'] . ' ';
		$query .= ' AND `obj`.`date` BETWEEN '. $db->quote(JFactory::getDate($startdate)->format('Y-m-d',true));
		$query .= ' AND '. $db->quote(JFactory::getDate($enddate)->format('Y-m-d',true));
		$sql = (string)$query;
		$db->setQuery($sql);
		$db->execute();
		//insert others
		$state='';
		$starttoend = $startdate->diff($enddate)->days;
		for ($i = 0; $i <= $starttoend; $i++) {
			$data = array();
			$data['date'] = null;
			$dateend = date('w', strtotime($startdate . $i . ' day'));
			$date = date('Y-m-d', strtotime($startdate . $i . ' day'));

			$data['state'] = $state;
			$data['avalible'] = $post['avalible'];
			$data['request'] = $post['request'];
			$data['guaranteed'] = $post['guaranteed'];
			$data['close'] = $post['close'];
			$data['adult'] = $post['adult'];
			$data['teen'] = $post['teen'];
			$data['child1'] = $post['child1'];
			$data['child2'] = $post['child2'];
			$data['child3'] = $post['child3'];

			$data['adult_promo'] = $post['adult_promo'];
			$data['teen_promo'] = $post['teen_promo'];
			$data['child_promo'] = $post['child_promo'];
			$data['pretransfer'] = $post['pretransfer'];
			$data['posttransfer'] = $post['posttransfer'];
			$data['extra_bed'] = $post['extra_bed'];
			$data['tour_id']   = $post['tour_id'];
			$date1 = new JDate($date);
			$data['date'] = $date1->toSql();
			$data['id'] = '';
				
			$tcTable=$this->getTable('packageratedaytripjoingroup');
			$tcTable->init();
			if(!$tcTable->check())
			return false;
			$tcTable->bind($data);
			if(!$tcTable->store())
			return false;
		}

		return true;
	}

	function trash($cids)
	{
			
		$table = $this->getTable('packageratedaytripjoingrouplog');
		foreach ($cids as $id){
			if( !$table->delete($id))
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