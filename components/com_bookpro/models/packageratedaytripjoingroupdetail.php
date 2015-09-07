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

class BookProModelPackageRatedaytripjoingroupDetail extends AModelFrontEnd
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
	function getObjectByTourIdAndDate($tour_id, $date)
	{

		$query = 'SELECT `obj`.* FROM `' . $this->_table->getTableName() . '` AS `obj` ';
		$query .= 'WHERE `obj`.`tour_id` = ' . (int) $tour_id . ' AND DATE_FORMAT(`obj`.`date`,"%d-%m-%Y")  =' .$this->_db->quote(JFactory::getDate($date)->format('d-m-Y',true));

		$this->_db->setQuery($query);

		if (($object = &$this->_db->loadObject())) {
			$this->_table->bind($object);
			return $this->_table;
		}

		return parent::getObject();
	}


	function store($post)
	{
			
		$startdate = new JDate($post['from']);
		$enddate = new JDate($post['to']);

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

		$date       = $post['date'];
		$tourpackage_id   = $post['tourpackage_id'];
		$adult      = $post['adult'];
		$teen       = $post['teen'];
		$child1     = $post['child1'];
		$child2     = $post['child2'];
		$child3     = $post['child3'];
		$extra_bed  = $post['extra_bed'];
		$pretransfer = $post['pretransfer'];
		$posttransfer = $post['posttransfer'];

		$adult_promo = $post['adult_promo'];
		$teen_promo = $post['teen_promo'];
		$child_promo = $post['child_promo'];

		if(count($date)>0){
			for($i=0; $i<count($date);$i++)
			{
				if($adult[$i] || $teen[$i] || $child1[$i] || $child2[$i] || $extra_bed[$i] || $id[$i]){
					$data = array();

					$data['available'] = $post['available'.$i];
					$data['request'] = $post['request'.$i];
					$data['guaranteed'] = $post['guaranteed'.$i];
					$data['close'] = $post['close'.$i];

					$data['id']     = '';
					$data['date']   = $date[$i];
					$data['tourpackage_id']  = $tourpackage_id;
					$data['adult']  = $adult[$i];
					$data['teen']   = $teen[$i];
					$data['child1'] = $child1[$i];
					$data['child2'] = $child2[$i];
					$data['child3'] = $child3[$i];
					$data['extra_bed'] = $extra_bed[$i];

					$data['adult_promo'] = $adult_promo[$i];
					$data['teen_promo'] = $teen_promo[$i];
					$data['child_promo'] = $child_promo[$i];


					$data['pretransfer'] = $pretransfer[$i];
					$data['posttransfer'] = $posttransfer[$i];
					$data['tour_id']      = $post['tour_id'];

					$tcTable=$this->getTable('packageratedaytripjoingroup');
					$tcTable->init();
					if(!$tcTable->check())
					return false;
					$tcTable->bind($data);
					if(!$tcTable->store())
					return false;
				}
			}
		}

		return true;
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