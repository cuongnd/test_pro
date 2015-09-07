<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
require_once(JPATH_SITE . '/components/com_jfbconnect/models/opengraphobject.php');

class JFBConnectAdminModelOpenGraphObject extends JFBConnectModelOpenGraphObject
{
    var $actionData;

    public function store($object = null)
    {
        $row = $this->getTable("JFBConnectOpenGraphObject", "Table");

        if (!$object)
        {
            $postData = JRequest::get('post');
            if ($postData['id'] == 0)
            {
                $object = new ogObject();
                $object->loadDefaultObject($postData['plugin'], $postData['system_name']);
                if ($object == null)
                    return false; // Shouldn't happen..
            } else
                $object = $this->getObject($postData['id']);
        }

        $object->fb_built_in = '0'; // Using checkbox, so need to check if it's 1 each time.

        foreach (array_keys($postData) as $prop)
        {
            if (property_exists($object, $prop))
            {
                if ($prop == "params")
                {
                    $params = new JRegistry($postData['params']);
                    $object->params = $params->toString();
                }
                else
                    $object->$prop = $postData[$prop];
            }

        }

        if ($object->id == 0 || $object->id == null)
            $object->created = JFactory::getDate()->toSql();

        $object->modified = JFactory::getDate()->toSql();

        if (!$row->bind($object))
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

        return $row->id;
    }

    public function delete($id)
    {
        $this->_db->setQuery("DELETE FROM #__opengraph_object WHERE id = " . $id);
        $this->_db->execute();

        $this->_db->setQuery("DELETE FROM #__opengraph_action_object WHERE object_id = " . $id);
        $this->_db->execute();

        $this->_db->setQuery("DELETE FROM #__opengraph_activity WHERE object_id = " . $id);
        $this->_db->execute();
    }

    public function getObjects($publishedOnly = false)
    {
        $query = "SELECT * FROM #__opengraph_object";
        if ($publishedOnly)
            $query .= " WHERE published=1";
        $this->_db->setQuery($query);
        return $this->_db->loadObjectList();
    }
}