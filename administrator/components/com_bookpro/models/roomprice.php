<?php

/**
 * @package     Bookpro
 * @author         Nguyen Dinh Cuong
 * @link         http://ibookingonline.com
 * @copyright     Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version     $Id: room.php 48 2012-07-13 14:13:31Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.model');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'model');
//import needed tables
AImporter::model('roomprice','roomprices');

class BookProModelRoomPrice extends AModel
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

		$startdate = new JDate($post['startdate']);
		$enddate   = new JDate($post['enddate']);
		$starttoend =  $startdate->diff($enddate)->days;
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

		$price      = $post['price'];
		$roomtypes  = $post['roomtype_id'];

		for($i=0; $i <= $starttoend; $i++)
		{
			$data                   = array();
			$data['date']           = null;
			$data['tourpackage_id'] = $post['tourpackage_id'];

			$dateend   = date('w',strtotime($startdate.$i.' day'));
			$date      = date('Y-m-d',strtotime($startdate.$i.' day'));
			$date1 = new JDate($date);
			$data['date']           = $date1-> toSql();

			if($roomtypes){
				for($j=0; $j<count($roomtypes); $j++)
				{
					$data['roomtype_id'] = $roomtypes[$j];
					$data['price']       = $price[$j];
					$data['id'] = '';
					
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
		$table = $this->getTable('roompricelog');
		foreach ($cids as $id){

			if( !$table->delete($id))
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

		}
		return true;
	}
}

?>