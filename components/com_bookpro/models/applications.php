<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: applications.php  23-06-2012 23:33:14
 **/

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelApplications extends AModelFrontEnd
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
		$this->_table = $this->getTable('application');
	}

	/**
	 * Get MySQL loading query for customers list
	 *
	 * @return string complet MySQL query
	 */
	function buildQuery()
	{
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
		return $this->getWhere($where);
	}
}

?>