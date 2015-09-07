<?php



defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.model');
//import needed JoomLIB helpers
AImporter::helper('model');

class BookProModelTourCategory extends AModelFrontEnd
{


	var $_table;

	var $_cache;

	function __construct()
	{
		parent::__construct();
		$this->_table = $this->getTable('tourcategory');
	}


	function buildQuery($filter = true)
	{
		$query = 'SELECT * FROM `' . $this->_table->getTableName() . '` ';
		$query .= $this->buildContentWhere();
		$query .= $this->buildContentOrderBy();
		return $query;
	}

	/**
	 * Get MySQL order criteria for reservation types list
	 *
	 * @return string order criteria in MySQL format
	 */
	function buildContentOrderBy()
	{
		return ' ORDER BY `id` ASC ';
	}

	/**
	 * Get MySQL filter criteria for reservation types list.
	 *
	 * @return string filter criteria in MySQL format
	 */
	function buildContentWhere()
	{
		$where = array();
		$this->addIntProperty($where, 'tour_id','cat_id');
		return $this->getWhere($where);
	}

	/**
	 * Store reservation types.
	 *
	 * @param int $subject ID
	 * @param array $data request
	 */
	function store($tour_id,$category_ids)
	{
		$db=$this->_db;	
		//delete exist relation
			$query = 'DELETE FROM ' . $this->_table->getTableName() . ' WHERE tour_id =' .$tour_id ;
			$db->setQuery($query);
			$db->query();
			//insert others
			$items=array();	
			
			for ($i=0;$i<count($category_ids);$i++){
				$tcTable=$this->getTable('tourcategory');
				$items["cat_id"]=$category_ids[$i];
				$tcTable->init();
				if(!$tcTable->check())
					return false;
				$tcTable->bind($items);
				$tcTable->tour_id = $tour_id;
				if(!$tcTable->store())
					return false;
				
			}
		
	}


}

?>