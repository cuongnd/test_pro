<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: hotels.php 22 2012-07-07 07:56:02Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelPackageHotels extends AModel
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
		$this->_table = $this->getTable('packagehotel');
	}
                     
	function buildQuery()
	{
		$query=null;
		$query = 'SELECT `obj`.* ';
		$query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
		$query .= $this->buildContentWhere();
		return $query;
	}


	/**
	 * Get MySQL filter criteria for customers list
	 *
	 * @return string filter criteria in MySQL format
	 */
	function buildContentWhere()
	{
		$where = array();   
        $this->addIntProperty($where, 'package_id');  
		$where= $this->getWhere($where);
		return $where;
			
	}
	function buildSearchQuery(){
    	
    	 $query = 'SELECT `t`.* ';
         $query .= 'FROM `' . $this->_table->getTableName() . '` AS `t` ';
		 $where[]='`t`.`state`=1';
         $query .= ' WHERE ' . implode(' AND ', $where);
         $query.= $this->buildContentOrderBy();
         return $query;
		 
	}
	
	function getHotelsByPakage($package_id){
		$db =& JFactory::getDBO();
		$sql='SELECT `packagehotel`.hotel_id  FROM #__bookpro_packagehotel AS packagehotel ';
		$sql.=' WHERE packagehotel.packagetype_id='.$package_id;  
		$db->setQuery($sql);
		return $db->loadColumn();
	}



}

?>