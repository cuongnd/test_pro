<?php


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');



class BookProModelCarCategories extends AModel
{
	/**
	 * Constructor that retrieves the ID from the request
	 *
	 * @access	public
	 * @return	void
	 */
	var $_table;
	function __construct()
	{
		parent::__construct();
		$this->_table = $this->getTable('carcategory');
	}

	function buildQuery()
	{
		$query=null;
		if (is_null($query)) {
			$query = 'SELECT `carcategory`.* ';
			$query .= 'FROM `' . $this->_table->getTableName() . '` AS `carcategory` ';
			$query .= $this->buildContentWhere();
		}
		return $query;
	}
  
	function buildContentWhere()
	{
		$where = array();
		$this->addStringProperty($where, 'title');
		return $this->getWhere($where);
	}


}