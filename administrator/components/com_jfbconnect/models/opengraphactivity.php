<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

require_once(JPATH_SITE . '/components/com_jfbconnect/models/opengraphactivity.php');

class JFBConnectAdminModelOpenGraphActivity extends JFBConnectModelOpenGraphActivity
{
    function getRows()
    {
        $app =JFactory::getApplication();
        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');

        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
        $limitstart = $app->getUserStateFromRequest($option . $view . '.limitstart', 'limitstart', 0, 'int');
        $filter_order = $app->getUserStateFromRequest($option . $view . 'filter_order', 'filter_order', 'id', 'cmd');
        $filter_order_Dir = $app->getUserStateFromRequest($option . $view . 'filter_order_Dir', 'filter_order_Dir', 'DESC', 'word');

        $query = "SELECT * FROM #__opengraph_activity";
        $query .= $this->getFilters();
        $query .= " ORDER BY " . $filter_order . " " . $filter_order_Dir . " ";
        $this->_db->setQuery($query, $limitstart, $limit);
        $rows = $this->_db->loadObjectList();
        return $rows;
    }

    function getFilters()
    {
        $app =JFactory::getApplication();
        $option = JRequest::getCmd('option');
        $view = JRequest::getCmd('view');

        $search = $app->getUserStateFromRequest($option . $view . 'search', 'search', '', 'string');
        $search = JString::strtolower($search);

        $filter_state = $app->getUserStateFromRequest($option . $view . 'filter_state', 'filter_state', -1, 'int');
        $filter_object = $app->getUserStateFromRequest($option . $view . 'filter_object', 'filter_object', -1, 'int');
        $filter_action = $app->getUserStateFromRequest($option . $view . 'filter_action', 'filter_action', -1, 'int');

        $query = '';
        if ($search != '')
        {
            $query =  " WHERE url LIKE '%" . $search . "%'";
        }

        if ($filter_state > -1)
            $query .= $this->getFilterSelect($query, 'status', $filter_state);

        if ($filter_object > -1)
            $query .= $this->getFilterSelect($query, 'object_id', $filter_object);

        if($filter_action > -1)
            $query .= $this->getFilterSelect($query, 'action_id', $filter_action);

        return $query;
    }

    function getFilterSelect($query, $selectName, $selectValue)
    {
        if($query != '')
            $newValue = ' AND';
        else
            $newValue = ' WHERE';

        $newValue .= ' ' . $selectName . '=' . $selectValue;
        return $newValue;
    }

    function getTotal()
    {
        $query = "SELECT COUNT(*) FROM #__opengraph_activity";
        $query .= $this->getFilters();
        $this->_db->setQuery($query);
        $total = $this->_db->loadResult();
        return $total;
    }

    function delete($id)
    {
        $this->_db->setQuery("DELETE FROM #__opengraph_activity WHERE status <> " . OG_ACTIVITY_PUBLISHED . " AND id = ".$this->_db->quote($id));
        return $this->_db->execute();
    }

    function getObjectList()
    {
        $this->_db->setQuery("SELECT id, display_name FROM #__opengraph_object");
        return $this->_db->loadObjectList();
    }

    function getActionList()
    {
        $this->_db->setQuery("SELECT id, display_name FROM #__opengraph_action");
        return $this->_db->loadObjectList();
    }
}