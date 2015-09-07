<?php

    defined('_JEXEC') or die;

    jimport('joomla.application.component.modellist');

    class BookProModelCountries extends JModelList
    {

        public function __construct($config = array())
        {
            if (empty($config['filter_fields'])) {
                $config['filter_fields'] = array(
                    'l.id',
                    'l.country_name',
                );
            }

            parent::__construct($config);
        }
        protected function populateState($ordering = null, $direction = null)
        {
            $state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_search');
            $this->setState('filter.state', $state);

            $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
            $this->setState('filter.search', $search);
            parent::populateState('l.country_name', 'asc');
        }


        /**
        * (non-PHPdoc)
        * @see JModelList::getListQuery()
        */
        protected function getListQuery()
        {
            $db		= $this->getDbo();
            $query	= $db->getQuery(true);
            $query->select('*')->from('#__bookpro_country AS l');

            if($this->getState('filter.search')){
                $search = $db->quote('%' . $db->escape($this->getState('filter.search'), true) . '%');
                $query->where('(l.country_name LIKE ' . $search.')');
            }
           
            return $query;
        }

        public function getItems(){
            
            $this->get('list.limit');
            $query = $this->_getListQuery();	
            $items = $this->_getList($query, $this->getStart(), $this->getState('list.limit'));   // Third arguments provides limit
            
            return $items;
           
        }

        public function getFullItems(){
            $query = $this->_getListQuery();
            $items = $this->_getList($query);   // Third arguments provides limit

            return $items;

        }

        function getItemsByGroupSupplier()
        {
            $array_country_ids = array(0=>99, 1=>1, 2=>223);
            $array_country=implode(',',$array_country_ids);
            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->select('country.*');
            $query->from('#__bookpro_country AS country');
            $query->where('country.id IN ('.$array_country.')');
            $query->where('country.state=1');
            $query->order('country.country_name ASC');
            return $this->_getList($query);
        }


}