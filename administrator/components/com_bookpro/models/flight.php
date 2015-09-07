<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: flight.php 102 2012-08-29 17:33:02Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.modeladmin');
//import needed JoomLIB helpers

//import needed tables
//AImporter::table('admin');

class BookProModelFlight extends JModelAdmin
{
	var $_table;
	
	var $_ids;
	
	function __construct()
	{
		parent::__construct();
		if (! class_exists('TableFlight')) {
			AImporter::table('flight');
		}
		$this->_table = $this->getTable('flight');
	}
	protected function populateState()
	{
		$table = $this->getTable();
		$key = $table->getKeyName();
	
		// Get the pk of the record from the request.
		$pk = JFactory::getApplication()->input->getInt($key);
		if ($pk) {
			
			$this->setState($this->getName() . '.id', $pk);
		}
		
	
		// Load the parameters.
		$value = JComponentHelper::getParams($this->option);
		$this->setState('params', $value);
	}
  	public function getForm($data = array(), $loadData = true)
        {

            $form = $this->loadForm('com_bookpro.flight', 'flight', array('control' => 'jform', 'load_data' => $loadData));
            
            if (empty($form))
                return false;
            return $form;
        }

        /**
        * (non-PHPdoc)
        * @see JModelForm::loadFormData()
        */
     protected function loadFormData()
        {
            $data = JFactory::getApplication()->getUserState('com_bookpro.edit.flight.data', array());
            if (empty($data))
                $data = $this->getItem();
            return $data;
        }
     function save($data){
     	$frequency = $data['frequency'];
     
     	//$frequency = $this->getConvert(frequency);
     
     	$data['frequency'] = implode(",", $frequency);
     	
     	return parent::save($data);
     }  
	function getConvert($weekens){
		AImporter::helper('date');
		$days=DateHelper::dayofweek();
		$week = array();
		foreach ($weekens as $weeken){
			foreach ($days as $key=>$day){
				if ($key == $weeken) {
					$week[] = $key;
				}
			}
		}
		return implode(",", $week);
	}
	 public function getItem($pk = null)
        {
            if ($item = parent::getItem($pk))
            {
                // Convert the metadata field to an array.
                $registry = new JRegistry;
                $registry->loadString($item->metadata);
                $item->metadata = $registry->toArray();

                // Convert the images field to an array.
                $registry = new JRegistry;
                $registry->loadString($item->images);
                $item->images = $registry->toArray();

                if (!empty($item->id))
                {
                    $item->tags = new JHelperTags;
                    $item->tags->getTagIds($item->id, 'com_bookpro.flight');
                    $item->metadata['tags'] = $item->tags;
                }
            }

            return $item;
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

        function getFlightInfo($id){
        
        	$query='';
        	$airportTable = &$this->getTable('airport');
        	$flightTable=&$this->getTable("flight");
        	$airlineTable=&$this->getTable("airline");
        		
        	$query .= 'SELECT `flight`.*, `dest1`.`title` as `fromtitle`, `dest2`.`title` as `totitle`,`dest1`.`code` AS codefrom,`dest2`.`code` AS codeto, `airline`.`title` as `airline_name`, ';
        	$query .='CONCAT(`dest1`.`title`,'.$this->_db->quote('(').',dest1.code,'.$this->_db->quote(')').','.$this->_db->quote('-').',`dest2`.`title`,'.$this->_db->quote('(').',dest2.code,'.$this->_db->quote(')').') AS title ';
        	$query .= 'FROM `' . $this->_table->getTableName() . '` AS `flight` ';
        	$query .= 'LEFT JOIN `' . $airportTable->getTableName() . '` AS `dest1` ON `flight`.`desfrom` = `dest1`.`id` ';
        	$query .= 'LEFT JOIN `' . $airportTable->getTableName() . '` AS `dest2` ON `flight`.`desto` = `dest2`.`id` ';
        	$query .= 'LEFT JOIN `' . $airlineTable->getTableName() . '` AS `airline` ON `airline`.`id` = `flight`.`airline_id` ';
        	$query .= 'WHERE `flight`.`id`=' . $id;
        
        	$this->_db->setQuery($query);
        	return $this->_db->loadObject();
        		
        }
        function getObjectFullById($id){
        	$db = JFactory::getDbo();
        	$query = $db->getQuery(true);
        	$query->select('flight.*');
        	$query->select('CONCAT(`dest1`.`title`,'.$this->_db->quote('-').',`dest2`.`title`) AS title');
        	$query->from('#__bookpro_flight AS flight');
        
        	
        
        	$query->select('airline.title AS airline_name');
        	$query->join('LEFT', '#__bookpro_airline AS airline ON airline.id = flight.airline_id');
        
        	$query->select('dest1.title AS from_name');
        	$query->join('LEFT', '#__bookpro_dest AS dest1 ON flight.desfrom = dest1.id');
        
        	$query->select('dest2.title AS to_name');
        	$query->join('LEFT', '#__bookpro_dest AS dest2 ON flight.desto = dest2.id');
        	if ($id){
        		$query->where('flight.id = '.$id);
        	}
        	$db->setQuery($query);
        
        	$bustrip = $db->loadObject();
        	return $bustrip;
        
        }
       

}

?>