<?php

defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

class BookProModelMessages extends JModelList {

    var $_table;

    public function __construct($config = array()) {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'message.id',
                'message.created'
            );
        }
        parent::__construct($config);
    }

    public function getPagination() {
        jimport('joomla.html.pagination');
        $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        return $this->_pagination;
    }

    protected function populateState($ordering = null, $direction = null) {
        $search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);
        $this->setState('filter.state', $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string'));
        parent::populateState('message.created', 'DESC');
    }
    protected function getListQuery() {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $user = JFactory::getUser();
        $userId = $user->id;
        if (IS_ADMIN) {
            $query->select('message.*')
                    ->from('#__bookpro_messages AS message');

            $query->select('`uto`.`name` AS `cnameto`');
            $query->join('LEFT', '#__users AS `uto` ON `message`.`cid_to` = `uto`.`id`');

            $query->select('`ufrom`.`name` AS `cnamefrom`');
            $query->join('LEFT', '#__users AS `ufrom` ON `message`.`cid_from` = `ufrom`.`id`');

            $query->where('parent_id =0');
            $query->order('`message`.`created` DESC');
        } else {

            $user = JFactory::getUser();

            $query->select('messages.*')
                    ->from('#__bookpro_messages AS messages');

            $query->select('`ufrom`.`name` AS `fusername`');
            $query->join('LEFT', '`#__users` AS `ufrom` ON `messages`.`cid_from` = `ufrom`.`id`');

            $query->select('`uto`.`name` AS `tusername`');
            $query->join('LEFT', '`#__users` AS `uto` ON `messages`.`cid_to` = `uto`.`id`');

            $query->where('`messages`.`parent_id` =0');
            if ($user->id) {
                $query->where('(`messages`.`cid_to`=' . $user->id . ' OR `messages`.`cid_from`=' . $user->id.')');
            }

            $query->order('`messages`.`id` DESC,messages.user_state ASC');
            //echo $db->replacePrefix($query);
        }




        if ($this->getState('filter.search')) {
            $search = $db->quote('%' . $db->escape($this->getState('filter.search'), true) . '%');
            $query->where('(message.subject LIKE ' . $search . ')');
        }
        // $query->order($db->escape($this->getState('list.ordering', 'l.title')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
        //echo $db->replacePrefix($query);
        return $query;
    }

    function buildParentQuery() {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $parent_id = JFactory::getApplication()->input->get('parent_id');
        $query->select('messages.*')
                ->from('#__bookpro_messages AS messages');

        $query->select('`ufrom`.`name` AS `fusername`');
        $query->join('LEFT', '`#__users` AS `ufrom` ON `messages`.`cid_from` = `ufrom`.`id`');

        $query->select('`uto`.`name` AS `tusername`');
        $query->join('LEFT', '`#__users` AS `uto` ON `messages`.`cid_to` = `uto`.`id`');

        $query->where('`messages`.`parent_id`=' . $parent_id . ' OR `messages`.`id`=' . $parent_id);
        $query->order('`messages`.`id` DESC');

        $this->_db->setQuery($query);
        $obj = &$this->_db->loadObjectList();

        return $obj;
    }

}