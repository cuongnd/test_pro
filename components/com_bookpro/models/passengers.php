<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: passengers.php 25 2012-07-08 13:02:59Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelPassengers extends JModelList
{
    /**
     * Main table
     * 
     * @var TablePassenger
     */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
					'passenger.id',
					
			);
		}

		parent::__construct($config);
	}
	
	protected function populateState($ordering = null, $direction = null)
	{
		parent::populateState();
		$app = JFactory::getApplication();
		$id = JRequest::getVar('id', 0, '', 'int');
		$this->setState('passengers.id', $id);
			
		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
			
	
			
		$state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_search');
		$this->setState('filter.state', $state);
		
		$order_id = $this->getUserStateFromRequest($this->context . '.filter.order_id', 'filter_order_id');
		$this->setState('filter.order_id', $order_id);
	
			
		$app = JFactory::getApplication();
	
	
	
		$value = $app->getUserStateFromRequest($this->context.'.ordercol', 'filter_order', $ordering);
		$this->setState('list.ordering', $value);
		$value = $app->getUserStateFromRequest($this->context.'.orderdirn', 'filter_order_Dir', $direction);
		$this->setState('list.direction', $value);
		parent::populateState('passenger.id', 'asc');
			
	}
	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select('passenger.*,country.country_name')->from('#__bookpro_passenger AS passenger');
		$query->join('LEFT', '#__bookpro_country AS country ON passenger.country_id = country.id');
		$query->join('LEFT', '#__bookpro_cgroup AS g ON g.id = passenger.group_id');
	
		$order_id = $this->getState('filter.order_id');
	
		if ($order_id)
		{
	
			$query->where('passenger.order_id = ' . (int) $order_id);
		}
		$query->order($db->escape($this->getState('list.ordering', 'l.title').' '.$this->getState('list.direction', 'asc')));
		
		return $query;
	}
    /**
     * Get MySQL loading query for passegers list
     * 
     * @return string complet MySQL query
     */
    function buildQuery()
    {
        $query=null;
        if (is_null($query)) {
            $query = 'SELECT `passenger`.*,c.country_name AS country,g.title AS group_title ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `passenger` ';
            $query .= 'LEFT OUTER JOIN #__bookpro_country AS c ON c.id= `passenger`.`country_id` ';
            $query .= 'LEFT OUTER JOIN #__bookpro_cgroup AS g ON g.id= `passenger`.`group_id` ';
            $query .= $this->buildContentWhere();
            $query .= $this->buildContentOrderBy();
        }
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
    	$this->addIntProperty($where, 'orderinfo_id','order_id');
    	return $this->getWhere($where);
    }
}

?>