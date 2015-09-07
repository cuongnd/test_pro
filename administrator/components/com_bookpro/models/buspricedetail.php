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

class BookProModelRoomPriceDetail extends AModel
{
	var $_table;

	var $_ids;

	function __construct()
	{
		parent::__construct();
		if (! class_exists('TableRoomPrice')) {
			AImporter::table('roomprice');
		}
		$this->_table = $this->getTable('roomprice');
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
		$startdate = new JDate($post['from']);
		$enddate   = new JDate($post['to']);
		//delete exist relation

		$db = JFactory::getDBO();
		$query = 'DELETE `obj`.* FROM `#__bookpro_roomprice` AS `obj` ';
		$query .= 'LEFT JOIN #__bookpro_tour_package AS tourpackage ON tourpackage.id = obj.tourpackage_id ';
		$query .= 'WHERE `tourpackage`.`tour_id` = ' . (int) $post['tour_id'] . ' AND `obj`.`tourpackage_id` = ' . (int) $post['tourpackage_id'] . ' ';
		$query .= ' AND `obj`.`date` BETWEEN '. $db->quote(JFactory::getDate($startdate)->format('Y-m-d',true));
		$query .= ' AND '. $db->quote(JFactory::getDate($enddate)->format('Y-m-d',true));
		$sql = (string)$query;
		$db->setQuery($sql);
		$db->execute();

		//insert others
		
		$price              = JRequest::getVar('price');
		$tourpackage_id     = JRequest::getVar('tourpackage_id');
		$date               = JRequest::getVar('date');
		$roomtype_id        = JRequest::getVar('roomtype_id');

		if(count($price)>0){
			for($i=0; $i<count($price);$i++)
			{
				$data = array();
				$data['id']             = '';
				$data['price']           = $price[$i];
				$data['tourpackage_id'] = $tourpackage_id;
				$data['date']           = $date[$i];
				$data['roomtype_id']           = $roomtype_id[$i];
				if($data['price'])
				{
					$tcTable=$this->getTable('roomprice');
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

}

?>