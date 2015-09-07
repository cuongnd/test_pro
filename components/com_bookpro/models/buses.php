<?php
/**
 * @package     Joomla.Administrator
 * @subpackage
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * buses Component Module Model
 *
 * @package     Joomla.Administrator
 * @subpackage
 * @since       1.5
 */
class BookproModelbuses extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  An optional associative array of configuration settings.
	 * @see     JController
	 * @since   1.6
	 */
    protected $context = null;

    public function __construct($config = array())
    {
        if (empty($config['bus_filter_fields']))
        {
            $config['bus_filter_fields'] = array(
                'id', 'bus.id',
                'title', 'bus.title',
                'image', 'bus.image',
                'published', 'bus.published',
                'state', 'bus.state'


            );
        }

        parent::__construct($config);
    }

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 */
    protected function populateState($ordering = null, $direction = null)
    {
        //set state filter by search
        $search = $this->getUserStateFromRequest('bus_filter_search','bus_filter_search');
        $this->setState('bus_filter_search', $search);

        //set state filter by bus_id
        $bus_id = $this->getUserStateFromRequest('bus_filter_bus_id','bus_filter_bus_id');
        $this->setState('bus_filter_bus_id', $bus_id);
        //set state filter by state
        $state = $this->getUserStateFromRequest('bus_filter_state','bus_filter_state');
        $this->setState('bus_filter_state', $state);
        $filter_order = $this->getUserStateFromRequest('filter_order','filter_order');
        $this->setState('filter_order', $filter_order);
        $filter_order_Dir = $this->getUserStateFromRequest('filter_order_Dir','filter_order_Dir');
        $this->setState('filter_order_Dir', $filter_order_Dir);


        //set state filter by publish
        $published = $this->getUserStateFromRequest('bus_filter_published','bus_filter_published');
        $this->setState('bus_filter_published', $published);



        //set state filter by featured
        $featured = $this->getUserStateFromRequest('bus_filter_featured','bus_filter_featured');
        $this->setState('bus_filter_featured', $featured);
        // List state information.
        parent::populateState('bus.title', 'asc');

    }


	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different buses that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string    A prefix for the store id.
	 *
	 * @return  string    A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('bus_filter_search');
		$id .= ':' . $this->getState('bus_filter_state');
		$id .= ':' . $this->getState('bus_filter_publish');
		$id .= ':' . $this->getState('bus_filter_image');
		return parent::getStoreId($id);
	}



	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 */
	protected function getListQuery()
	{
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $ordering = $this->getState('filter_order');
        $keywords = $this->getState('bus_filter_keywords');
        $nullDate = $db->quote($db->getNullDate());

        $query->select(
            $this->getState(
                'list.select',
                'bus.id, bus.title,bus.desc,bus.state,bus.image,bus.published,bus.ordering'
            )
        );
        $query->from('#__bookpro_bus as bus');



        // Filter by published state
        $published = $this->getState('bus_filter_published');
        if (is_numeric($published))
        {
            $query->where('bus.published = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(bus.published IN (0, 1))');
        }
        $bus_id=$this->getState('bus_filter_bus_id');
        if($bus_id)
        {
            $query->where('bus.id='.$bus_id);
        }
        $featured=$this->getState('bus_filter_featured');
        if($featured)
        {
            $query->where('bus.featured=1');
        }
        // Filter by search in title
        $search = $this->getState('bus_filter_search');
        if (!empty($search))
        {
            if (stripos($search, 'id:') === 0)
            {
                $query->where('bus.id = ' . (int) substr($search, 3));
            }
            else
            {
                $search = $db->quote('%' . $db->escape($search, true) . '%');
                $query->where('(' . 'bus.title  LIKE ' . $search . ' OR bus.desc LIKE ' . $search . ')');
            }
        }
        $query->group('bus.id');
        $query->order($db->escape($this->getState('filter_order', 'bus.id')) . ' ' . $db->escape($this->getState('filter_order_Dir', 'ASC')));
        return $query;
	}

}
