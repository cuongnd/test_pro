<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class BookProModelAirlines extends JModelList
{

	public function __construct($config = array())
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
					'l.id',
					'l.title',
			);
		}

		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		parent::populateState();
		$app = JFactory::getApplication();
		$id = JRequest::getVar('id', 0, '', 'int');
		$this->setState('airlines.id', $id);
			
		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
			
	
			
		$state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_search');
		$this->setState('filter.state', $state);
	
		
		$air = $this->getUserStateFromRequest($this->context . '.filter.air', 'filter_search');
		$this->setState('filter.air', $air);
			
		$level = $this->getUserStateFromRequest($this->context . '.filter.level', 'filter_level', 0, 'int');
		$this->setState('filter.level', $level);
	
	
	
		$country_id = $this->getUserStateFromRequest($this->context . '.filter.country_id', 'filter_country_id', 0, 'int');
		$this->setState('filter.country_id', $country_id);
	
		$app = JFactory::getApplication();
	
	
	
		$value = $app->getUserStateFromRequest($this->context.'.ordercol', 'filter_order', $ordering);
		$this->setState('list.ordering', $value);
		$value = $app->getUserStateFromRequest($this->context.'.orderdirn', 'filter_order_Dir', $direction);
		$this->setState('list.direction', $value);
		parent::populateState('l.id', 'asc');
			
	}
	/**
	 * (non-PHPdoc)
	 * @see JModelList::getListQuery()
	 */
	
	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select('l.*,country.country_name')->from('#__bookpro_airline AS l');
		$query->join('LEFT', '#__bookpro_country AS country ON l.country_id = country.id');
		
		$country_id = $this->getState('filter.country_id');
		
		if ($country_id)
		{
				
			$query->where('l.country_id = ' . (int) $country_id);
		}
		$query->order($db->escape($this->getState('list.ordering', 'l.title').' '.$this->getState('list.direction', 'asc')));
		return $query;
	}
	
	
}