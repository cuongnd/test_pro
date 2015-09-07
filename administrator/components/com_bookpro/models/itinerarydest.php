<?php


defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.model');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'model');
//import needed tables
//AImporter::table('admin');

class BookProModelItineraryDest extends AModel
{
	var $_table;

	var $_ids;

	function __construct()
	{
		parent::__construct();
		if (! class_exists('TableItineraryDest')) {
			AImporter::table('itinerarydest');
		}
		$this->_table = $this->getTable('itinerarydest');
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


	function deleteByItinerary($itinerary_id){
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		if ($itinerary_id) {
			$query->delete('#__bookpro_itinerarydest');
			$query->where('itinerary_id='.$itinerary_id);
			$db->setQuery($query);
			$db->query();
		}
	}
	function store($data)
	{
		$config = &AFactory::getConfig();
		/* @var $config BookingConfig */
        $data['meal']=implode(';',$data['meal']);
		$id = (int) $data['id'];
		$this->_table->init();
		$this->_table->load($id);

		if (! $this->_table->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		unset($data['id']);


		if (! $this->_table->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		if (! $this->_table->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		//store country
		return $this->_table->id;
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
	public function publish(&$pks, $value = 1)
	{
		$user = JFactory::getUser();
		$table = $this->getTable();
		$pks = (array) $pks;
	
		// Attempt to change the state of the records.
		if (!$table->publish($pks, $value, $user->get('id')))
		{
			$this->setError($table->getError());
	
			return false;
		}
	
		return true;
	}
	function unpublish($cids){
		return $this->state('state', $cids, 0, 1);
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