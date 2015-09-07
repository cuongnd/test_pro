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
 * Bustrips Component Module Model
 *
 * @package     Joomla.Administrator
 * @subpackage
 * @since       1.5
 */
class BookproModelBustrips extends JModelList
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
        if (empty($config['bustrip_filter_fields']))
        {
            $config['bustrip_filter_fields'] = array(
                'id', 'bustrip.id',
                'start',
                'roundtrip', 'bustrip.roundtrip',
                'published', 'bustrip.published',
                'km', 'bustrip.km',
                'from', 'bustrip.from',
                'to', 'bustrip.to',
                'state', 'bustrip.state',
                'minRate','maxRate',
                'featured', 'bustrip.featured',
                'publish_date', 'bustrip.publish_date',
                'unpublish_date', 'bustrip.unpublish_date',
                'duration', 'bustrip.duration',
                'duration2', 'bustrip.duration2'
                ,'dest_from_title'
                ,'dest_to_title'
                ,'dest_from_parent_title'
                ,'dest_to_parent_title'
                ,'bus_title'
                ,'from_country_name'
                ,'to_country_name'

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
        $search = $this->getUserStateFromRequest('bustrip_filter_search','bustrip_filter_search');
        $this->setState('bustrip_filter_search', $search);

        //set state filter by vehicle
        $vehicles = $this->getUserStateFromRequest('bustrip_filter_vehicles','bustrip_filter_vehicles');
        $this->setState('bustrip_filter_vehicles', $vehicles);
        //set state filter by start
        $start = $this->getUserStateFromRequest('bustrip_filter_start','bustrip_filter_start');
        $this->setState('bustrip_filter_start', $start);
        //set state filter by bustrip_id
        $bustrip_id = $this->getUserStateFromRequest('bustrip_filter_bustrip_id','bustrip_filter_bustrip_id');
        $this->setState('bustrip_filter_bustrip_id', $bustrip_id);
        //set state filter by from
        $from = $this->getUserStateFromRequest('bustrip_filter_from','bustrip_filter_from');
        $this->setState('bustrip_filter_from', $from);
        //set state filter by to
        $to = $this->getUserStateFromRequest('bustrip_filter_to','bustrip_filter_to');
        $this->setState('bustrip_filter_to', $to);
        //set state filter by state
        $state = $this->getUserStateFromRequest('bustrip_filter_state','bustrip_filter_state');
        $this->setState('bustrip_filter_state', $state);


        //set state filter by publish
        $published = $this->getUserStateFromRequest('bustrip_filter_published','bustrip_filter_published');
        $this->setState('bustrip_filter_published', $published);
        //set state filter by min_rate
        $minRate = $this->getUserStateFromRequest('bustrip_filter_minRate','bustrip_filter_minRate');
        if($minRate)
            $this->setState('bustrip_filter_minRate', $minRate);
        //set state filter by featured
        $featured = $this->getUserStateFromRequest('bustrip_filter_featured','bustrip_filter_featured');
        $this->setState('bustrip_filter_featured', $featured);
        //set state filter by max_rate
        $maxRate = $this->getUserStateFromRequest('bustrip_filter_maxRate','bustrip_filter_maxRate');
        if($maxRate)
            $this->setState('bustrip_filter_maxRate', $maxRate);
        //set state filter by roundtrip
        $roundtrip = $this->getUserStateFromRequest('bustrip_filter_roundtrip','bustrip_filter_roundtrip');
        $this->setState('bustrip_filter_roundtrip', $roundtrip);

        //set state filter by from country_id
        $from_country_id = $this->getUserStateFromRequest('bustrip_filter_from_country_id','bustrip_filter_from_country_id');
        $this->setState('bustrip_filter_from_country_id', $from_country_id);

        //set state filter by to country_id
        $to_country_id = $this->getUserStateFromRequest('bustrip_filter_to_country_id','bustrip_filter_to_country_id');
        $this->setState('bustrip_filter_to_country_id', $to_country_id);



        // List state information.
        parent::populateState('bustrip.from', 'asc');

    }

    /**
     * Returns an object list
     *
     * @param   string The query
     * @param   int    Offset
     * @param   int    The number of records
     * @return  array
     */
    protected function _getList($query, $limitstart = 0, $limit = 0)
    {
        $ordering = $this->getState('list.ordering', 'ordering');
        if ($ordering == 'ordering')
        {
            $query->order('dest_from.title ASC');
            $ordering = 'dest_from.ordering';
        }
        $query->order($this->_db->quoteName($ordering) . ' ' . $this->getState('list.direction'));
        $result = parent::_getList($query, $limitstart, $limit);
        return $result;
    }
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different Bustrips that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string    A prefix for the store id.
	 *
	 * @return  string    A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('bustrip_filter_search');
		$id .= ':' . $this->getState('bustrip_filter_access');
		$id .= ':' . $this->getState('bustrip_filter_state');
		$id .= ':' . $this->getState('bustrip_filter_position');
		$id .= ':' . $this->getState('bustrip_filter_module');
		$id .= ':' . $this->getState('bustrip_filter_client_id');
		$id .= ':' . $this->getState('bustrip_filter_language');

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
        $ordering = $this->getState('bustrip_filter_ordering');
        $keywords = $this->getState('bustrip_filter_keywords');
        $nullDate = $db->quote($db->getNullDate());

        $query->select(
            $this->getState(
                'list.select',
                'bustrip.id, bustrip.from, bustrip.to,bustrip.state,bustrip.published,bustrip.roundtrip'
                .',bustrip.publish_date, bustrip.unpublish_date, bustrip.duration,bustrip.duration2'
                .',bustrip.checked_out,bustrip.checked_out_time,bustrip.start_time'
            )
        );
        $query->from('#__bookpro_bustrip as bustrip');
        $query->join('LEFT', '#__bookpro_bus AS bus ON bus.id = bustrip.bus_id');
        $query->select('bus.id AS bus_id,bus.title as bus_title,bus.image AS bus_image');

        $query->join('LEFT', '#__bookpro_dest AS dest_from ON dest_from.id = bustrip.from');
        $query->select('dest_from.id AS dest_from_id,dest_from.title as dest_from_title');
        $query->join('LEFT', '#__bookpro_dest AS dest_to ON dest_to.id = bustrip.to');
        $query->select('dest_to.id AS dest_to_id,dest_to.title as dest_to_title');

        $query->join('LEFT', '#__bookpro_dest AS dest_from_parent ON dest_from_parent.id = dest_from.parent_id');
        $query->select('dest_from_parent.id AS dest_from_parent_id,dest_from_parent.title as dest_from_parent_title');
        $query->join('LEFT', '#__bookpro_dest AS dest_to_parent ON dest_to_parent.id = dest_to.parent_id');
        $query->select('dest_to_parent.id AS dest_to_parent_id,dest_to_parent.title as dest_to_parent_title');


        // Filter by published state
        $published = $this->getState('bustrip_filter_published');
        if (is_numeric($published))
        {
            $query->where('bustrip.published = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(bustrip.published IN (0, 1))');
        }
        $query->leftJoin('#__bookpro_busrate AS busrate ON busrate.bustrip_id=bustrip.id');
        $query->select('busrate.date,busrate.adult');
        $start=$this->getState('bustrip_filter_start');
        $start_timetemp=JFactory::getDate($start)->getTimestamp();
        if($start)
        {
            $query->where('busrate.date>='.$start_timetemp);
        }
        $bustrip_id=$this->getState('bustrip_filter_bustrip_id');
        if($bustrip_id)
        {
            $query->where('bustrip.id='.$bustrip_id);
        }
        $from_country_id=$this->getState('bustrip_filter_from_country_id');
        $query->leftJoin('#__bookpro_country AS from_country ON from_country.id=dest_from.country_id');
        $query->select('from_country.country_name as from_country_name');
        if($from_country_id)
        {
            $query->where('from_country.id='.$from_country_id);
        }
        $to_country_id=$this->getState('bustrip_filter_to_country_id');
        $query->leftJoin('#__bookpro_country AS to_country ON to_country.id=dest_to.country_id');
        $query->select('to_country.country_name as to_country_name');
        if($to_country_id)
        {
            $query->where('to_country.id='.$to_country_id);
        }


        $from=$this->getState('bustrip_filter_from');
        if($from)
        {
            $from_query=$db->getQuery(true);
            $from_query->select('id');
            $from_query->from('#__bookpro_dest');
            $from_query->where(array('bus=1','parent_id='.(int)$from));
            $db->setQuery($from_query);
            $listChildDest=$db->loadColumn();
            $listChildDest[]=$from;
            $query->where('bustrip.from IN('.implode(',',$listChildDest).')');
        }
        $to=$this->getState('bustrip_filter_to');
        if($to)
        {
            $to_query=$db->getQuery(true);
            $to_query->select('id');
            $to_query->from('#__bookpro_dest');
            $to_query->where(array('bus=1','parent_id='.(int)$to));
            $db->setQuery($to_query);
            $listChildDest=$db->loadColumn();
            $listChildDest[]=$to;
            $query->where('bustrip.to IN('.implode(',',$listChildDest).')');
        }
        $featured=$this->getState('bustrip_filter_featured');
        if($featured)
        {
            $query->where('bustrip.featured=1');
        }
        $publish_date=$this->getState('bustrip_filter_publish_date');
        if($publish_date)
        {
            //$query->where('bustrip.publish_date>='.$publish_date);
        }
        // Filter by search in title
        $search = $this->getState('bustrip_filter_search');
        if (!empty($search))
        {
            if (stripos($search, 'id:') === 0)
            {
                $query->where('bustrip.id = ' . (int) substr($search, 3));
            }
            else
            {
                $search = $db->quote('%' . $db->escape($search, true) . '%');
                $query->where('(' . 'dest_from.title  LIKE ' . $search . ' OR dest_to.title LIKE ' . $search . ')');
            }
        }


        $vehicles=$this->getState('bustrip_filter_vehicles');
        if($vehicles)
        {
            $query->where('bustrip.bus_id IN ('.$vehicles.')');
        }
        $roundtrip=$this->getState('bustrip_filter_roundtrip');
        if($roundtrip===0||$roundtrip==1)
        {
            $query->where('bustrip.roundtrip ='.$roundtrip);
        }

        $query->group('bustrip.id');
        $query->order($db->escape($this->getState('list_ordering', 'bustrip.id')) . ' ' . $db->escape($this->getState('list_direction', 'ASC')));
        return $query;
	}

}
