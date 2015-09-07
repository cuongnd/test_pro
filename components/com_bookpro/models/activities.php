<?php

    defined('_JEXEC') or die;

    jimport('joomla.application.component.modellist');

    class BookProModelActivities extends JModelList
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
            $this->_table = $this->getTable('activity');
        }
        protected function populateState($ordering = null, $direction = null)
        {
            $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
            $this->setState('filter.search', $search);
            $this->setState('filter.state', $this->getUserStateFromRequest($this->context.'.filter.state', 'filter_state', '', 'string'));
            parent::populateState('l.ordering', 'ASC');
        }
        function getActivityByTour($tour_id){
            //$tourcat = &$this->getTable('touractivity');
            $query = 'SELECT `obj`.* ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
            $query .= 'LEFT JOIN `#__bookpro_touractivity` AS `tc` ON `obj`.`id` = `tc`.`activity_id` ';
            $query .=' WHERE `tc`.`tour_id`='.(int) $tour_id;
            return $this->_getList($query);
        }

        protected function getListQuery()
        {
            $db		= $this->getDbo();
            $query	= $db->getQuery(true);

            $query -> select ( $this -> getState('list.select','l.*'));
            $query -> from($db->quoteName('#__bookpro_activity').' AS l'); 
            if ($this->getState('filter.search')) {
                $search = $db->quote('%' . $db->escape($this->getState('filter.search'), true) . '%');
                $query->where('(l.title LIKE ' . $search . ')');
            } 
            $query->order($db->escape($this->getState('list.ordering', 'l.title')).' '.$db->escape($this->getState('list.direction', 'ASC'))); 
            return $query;
        }


}