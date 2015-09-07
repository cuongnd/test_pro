<?php


// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');



class BookProModelCategories extends AModelFrontEnd
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
		$this->_table = $this->getTable('category');
	}

	function buildQuery()
	{
		$query=null;
		if (is_null($query)) {
			$query = 'SELECT `category`.* ';
			$query .= 'FROM `' . $this->_table->getTableName() . '` AS `category` ';
			$query .= $this->buildContentWhere();
		}
		return $query;
	}

	function getAll($type)
	{
		$query='SELECT obj.id AS value, obj.title AS text  FROM ' . $this->_table->getTableName() . ' as obj';
		$query .=' WHERE obj.type = ' .$type;
		return $this->_getList($query);

	}
	function getCategoryByTour($tour_id){

		$tourcat = &$this->getTable('tourcategory');
		$query = 'SELECT `obj`.* ';
		$query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
		$query .= 'LEFT JOIN `' . $tourcat->getTableName() . '` AS `tc` ON `obj`.`id` = `tc`.`cat_id` ';
		$query .=' WHERE `tc`.`tour_id`='.(int) $tour_id;
		return $this->_getList($query);
	}
	function buildContentWhere()
	{
		$where = array();
		$this->addIntProperty($where, 'type');
		return $this->getWhere($where);
	}


}