<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 5/20/2015
 * Time: 9:25 AM
 */

class TourModelGalleries extends JModelList {
    public function __construct($config = array()) {
        if (empty ( $config ['filter_fields'] )) {
            $config ['filter_fields'] = array (
                'id','a.id',
                'name','a.name',
            );
        }
        parent::__construct ( $config );
    }



    protected function populateState($ordering = null, $direction =null)
    {
        $city_id=$this->getUserStateFromRequest($this->context . '.filter.city_id', 'city_id', '', 'int');
        $this->setState('filter.city_id', $city_id);
        parent::populateState('a.id', 'asc');
    }
    protected function getListQuery() {
        $a=JRequest::get( 'post' );

        $db = $this->getDbo ();
        $query = $db->getQuery ( true );
        $query->select ('a.*' );
        $query->from ( $db->quoteName ( '#__skandal_gallery' ) . ' AS a' );


        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search))
        {
            if (stripos($search, 'id:') === 0)
            {
                $query->where('a.id = '.(int) substr($search, 3));
            } else {
                $search = $db->Quote('%'.$db->escape($search, true).'%');
                $query->where('(a.name LIKE '.$search.')');
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

        $city_id=$this->state->get('filter.city_id');
        if ($city_id){
            $query->where('a.city_id =' .$city_id);
        }
        return $query;
    }

}