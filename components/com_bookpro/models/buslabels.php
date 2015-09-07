<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');
AImporter::helper('model','request');

class BookProModelRoomlabels extends AModelFrontEnd
{

	var $_table;
	
	function __construct()
	{
		parent::__construct();
		$this->_table = $this->getTable('roomlabel');
	}
	
	
	function buildQuery()
	{
		$query=null;
		$query = 'SELECT `obj`.* ';
		$query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
		$query .= $this->buildContentWhere();
		$query .= $this->buildContentOrderBy();
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
		$this->addStringProperty($where, 'title');
		$where= $this->getWhere($where);
		return $where;
			
	}
		
	
}