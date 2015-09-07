<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class BookProModelAirportairline extends JModelList
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
		
		$dest_id = $this->getUserStateFromRequest($this->context . '.filter.dest_id', 'filter_dest_id', 0, 'int');
		$this->setState('filter.dest_id', $dest_id);
			
		$app = JFactory::getApplication();
		$value = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
		$limit = $value;
		$this->setState('list.limit', $limit);
			
		$value = $app->getUserStateFromRequest($this->context.'.limitstart', 'limitstart', 0);
		$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
		$this->setState('list.start', $limitstart);
			
		$value = $app->getUserStateFromRequest($this->context.'.ordercol', 'filter_order', $ordering);
		$this->setState('list.ordering', $value);
		$value = $app->getUserStateFromRequest($this->context.'.orderdirn', 'filter_order_Dir', $direction);
		$this->setState('list.direction', $value);
	
			
	}
	
	/**
	 * (non-PHPdoc)
	 * @see JModelList::getListQuery()
	 */
	protected function getListQuery()
	{
		$db		= $this->getDbo();
		$query	= $db->getQuery(true);
		$query->select('*')->from('#__bookpro_airline AS l');
		
		$query->order($db->escape($this->state->get('list.ordering', 'l.title').' '.$this->state->get('list.direction', 'asc')));
		return $query;
	}
	
	
}