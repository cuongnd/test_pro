<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 5/20/2015
 * Time: 9:25 AM
 */

class TourModelTours extends JModelList {
    public function __construct($config = array()) {
        if (empty ( $config ['filter_fields'] )) {
            $config ['filter_fields'] = array (
                'id','a.id',
                'title','a.title',
                'name','a.name',
                'state', 'a.state',

                'publish_up', 'a.publish_up',
                'publish_down', 'a.publish_down',
                'ordering', 'a.ordering'
            );
        }
        parent::__construct ( $config );
    }
    protected function populateState($ordering = null, $direction =null)
    {
        $search = $this->getUserStateFromRequest($this->context.'.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $published = $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string');
        $this->setState('filter.state', $published);
        parent::populateState('a.ordering', 'asc');
    }
    protected function getListQuery() {
        $db = $this->getDbo ();
        $query = $db->getQuery ( true );
        $query->select ('a.*' );
        $query->from ( $db->quoteName ( '#__skandal_tour_details' ) . ' AS a' );
        //
        $published = $this->getState('filter.state');
        if (is_numeric($published))
        {
            $query->where('a.state = '.(int) $published);
        } elseif ($published === '')
        {
            $query->where('(a.state IN (0, 1))');
        }
        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            if (stripos($search, 'id:') === 0)
            {
                $query->where('a.id = '.(int) substr($search, 3));
            } else {
                $search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where('(a.title LIKE '.$search.' OR a.name LIKE'.$search.')');
            }
        }
        // Add the list ordering clause.
        $orderCol = $this->state->get('list.ordering');


        $orderDirn = $this->state->get('list.direction');

        if ($orderCol == 'a.ordering')
        {
            $orderCol = 'a.title '.$orderDirn.', a.ordering';
        }
        $query->order($db->escape($orderCol.' '.$orderDirn));

        return $query;
    }

}