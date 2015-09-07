<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class BookProModelFaqs extends JModelList {

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'l.id',
                'l.title',
            );
        }

        parent::__construct($config);
    }

    /**
     * (non-PHPdoc)
     * @see JModelList::getListQuery()
     */
    protected function getListQuery() {
       
        $tour_id= JFactory::getApplication()->getUserStateFromRequest('tour_id', 'tour_id', 0);
        $db = $this->getDbo();
        $query = $db->getQuery(true);
        $query->select('l.*')->from('#__bookpro_faq AS l');
        $query->select('tour.title AS tourtitle');
        $query->join('LEFT', '#__bookpro_tour AS tour ON tour.id=l.tour_id');
        $query->where('(tour_id=' . $tour_id . ')');
        $query->order($db->escape($this->state->get('list.ordering', 'l.title') . ' ' . $this->state->get('list.direction', 'asc')));
        return $query;
    }

}