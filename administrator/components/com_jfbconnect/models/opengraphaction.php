<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
require_once(JPATH_SITE . '/components/com_jfbconnect/models/opengraphaction.php');

class JFBConnectAdminModelOpenGraphAction extends JFBConnectModelOpenGraphAction
{
    var $actionData;

    public function store($action = null)
    {
        $row = $this->getTable("JFBConnectOpenGraphAction", "Table");

        if (!$action)
        {
            $postData = JRequest::get('post');
            if ($postData['id'] != 0)
                $action = $this->getAction($postData['id']);
            else
                $action = new ogAction();
            $action->fb_built_in = '0'; // Using checkbox, so need to check if it's 1 each time.
            foreach (array_keys($postData) as $prop)
            {
                if (property_exists($action, $prop))
                {
                    if ($prop == "params")
                    {
                        $params = new JRegistry($postData['params']);
                        $action->params = $params->toString();
                    }
                    else
                        $action->$prop = $postData[$prop];
                }
            }
        }

        if ($action->id == 0 || $action->id == null)
            $action->created = JFactory::getDate()->toSql();

        $action->modified = JFactory::getDate()->toSql();

        if (!$row->bind($action))
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->check())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$row->store())
        {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        $action->id = $row->id;
        // Store worked (should always..), lets update the action/object associations
        if (array_key_exists('objects', $postData))
        {
            $objects = $postData['objects'];
            $this->setObjectMappings($action->id, $objects);
        }
        return $row->id;
    }

    public function delete($id)
    {
        $this->_db->setQuery("DELETE FROM #__opengraph_action WHERE id = " . $id);
        $this->_db->execute();

        $this->_db->setQuery("DELETE FROM #__opengraph_action_object WHERE action_id = " . $id);
        $this->_db->execute();

        $this->_db->setQuery("DELETE FROM #__opengraph_activity WHERE action_id = " . $id);
        $this->_db->execute();
    }

    private function setObjectMappings($actionId, $objects)
    {
        $filter = JFilterInput::getInstance();
        // Delete all previous associations
        $this->_db->setQuery("DELETE FROM #__opengraph_action_object WHERE action_id = " . $actionId);
        $this->_db->execute();

        foreach ($objects as $objectId)
        {
            $objectId = $filter->clean($objectId, 'INT');
            if (is_int($objectId))
            {
                $this->_db->setQuery("INSERT INTO #__opengraph_action_object (`action_id`, `object_id`) VALUES " .
                        "(" . $actionId . ", " . $objectId . ")");
                $this->_db->execute();
            }
        }
    }
}