<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: airports.php 100 2012-08-29 14:55:21Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
jimport('joomla.application.component.modellist');

class BookProModelSendemails extends JModelList {

	var $_table;

	function __construct() {
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = array(
                'l.id',
                'l.title',
			);
		}
		parent::__construct();
		$this->_table = $this->getTable('sendemail');
	}

	protected function populateState($ordering = null, $direction = null) {
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		$this->setState('filter.state', $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string'));
		parent::populateState('l.ordering', 'ASC');
	}

	protected function getListQuery() {
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$query -> select ( $this -> getState('list.select','l.*'));
		$query ->from($db->quoteName('#__bookpro_sendemail').' AS l');
		if ($this->getState('filter.search')) {
			$search = $db->quote('%' . $db->escape($this->getState('filter.search'), true) . '%');
			$query->where('(l.title LIKE ' . $search . ')');
		}
		$query->order($db->escape($this->getState('list.ordering', 'l.title')).' '.$db->escape($this->getState('list.direction', 'ASC')));
		return $query;
	}

}

?>