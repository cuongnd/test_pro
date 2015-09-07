<?php

/**
 * Support for work with request params.
 * 
 * @package Bookpro
 * @author Nguyen Dinh Cuong
 * @link http://ibookingonline.com
 * @copyright Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @version $Id: request.php 44 2012-07-12 08:05:38Z quannv $
 */

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('route');

class ARequest
{

    /**
     * Repair limitstart value by limit criterium
     * 
     * @param int $limit size of display set
     * @param int $limitstart first list member 
     * @return int repair limitstart
     */
    function getLimitstart($limit, $limitstart)
    {
        return ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
    }

    /**
     * Get array of integer values of multiple param from request
     *
     * @param string $name name of param
     * @param int $clean remove items with zero value
     * @return array only integers values
     */
    function getIntArray($name, $clean = false)
    {
        $array = ARequest::getArray($name);
        JArrayHelper::toInteger($array);
        if ($clean) {
            foreach ($array as $i => $item) {
                if (! $item) {
                    unset($array[$i]);
                }
            }
        }
        return $array;
    }

    /**
     * Get array of string values of multiple param from request
     *
     * @param string $name name of param
     * @param int $clean remove items with empty value
     * @return array only string values
     */
    function getStringArray($name, $clean = false)
    {
        $array = ARequest::getArray($name);
        foreach ($array as $i => $item) {
            $item = (string) $item;
            $item = JString::trim($item);
            if ($clean && ! $item) {
                unset($array[$i]);
            } else {
                $array[$i] = $item;
            }
        }
        return $array;
    }

    /**
     * Get array of multiple param values from request
     * 
     * @param string $name name of param 
     * @return array various values
     */
    function getArray($name)
    {
        return JRequest::getVar($name, array(), 'request', 'array');
    }

    /**
     * Get array from string where items are separated by comma.
     * 
     * @param string $name name of param
     * @return array
     */
    function getCommaArray($name)
    {
        return explode(',', JRequest::getString($name));
    }

    /**
     * Get array of standard param of Joomla! cid using in browse tables
     * 
     * @return array integer values
     */
    function getCids()
    {
        return ARequest::getIntArray('cid');
    }

    /**
     * Get standard param of Joomla cid using in browse and edit tables
     * 
     * @return int
     */
    function getCid()
    {
        $cids = ARequest::getIntArray('cid');
        return count($cids) ? reset($cids) : 0;
    }

    /**
     * Control empty statement of cids array
     * 
     * @param array $cids
     * @param string $operation using operation for error msg
     * @return boolean true .. not empty, false .. empty
     */
    function controlCids($cids, $operation)
    {
        if (! count($cids)) {
            $mainframe = &JFactory::getApplication();
            $mainframe->enqueueMessage(JText::_('Select an item(s) to ') . JText::_($operation), 'notice');
            return false;
        }
        return true;
    }

    /**
     * Remove cids from request with exception first
     * 
     */
    function setCidsOnlyFirst()
    {
        $cids = SpcrHelper::getIntArray('cid');
        JRequest::setVar('cid', array(reset($cids)));
    }

    /**
     * Change array into URL
     * 
     * @param string $key param name
     * @param array $array values
     * @return string URL
     */
    function arrayToUrl($key, $array)
    {
        $output = array();
        foreach ($array as $i => $item) {
            $output[] = $key . '[' . $i . ']=' . $item;
        }
        return implode('&', $output);
    }

    /**
     * Redirect into main page
     */
    function redirectMain()
    {
        $mainframe = &JFactory::getApplication();
        $mainframe->redirect(ARoute::root());
    }

    /**
     * Redirect page into browse table
     * 
     * @param string $controller name of entity controller
     */
    function redirectList($controller)
    {
        $mainframe = &JFactory::getApplication();
        $mainframe->redirect(ARoute::browse($controller));
    }

    /**
     * Redirect page into edit page
     * 
     * @param string $controller name of entity controller
     * @param int $id entity ID
     */
    function redirectEdit($controller, $id = null)
    {
        $mainframe = &JFactory::getApplication();
        $mainframe->redirect(ARoute::edit($controller, $id));
    }

    /**
     * Redirect page into detail page
     * 
     * @param string $controller name of entity controller
     * @param int $id entity ID
     */
    function redirectDetail($controller, $id = null, $customParams = array())
    {
        $mainframe = &JFactory::getApplication();
        $mainframe->redirect(ARoute::detail($controller, $id, $customParams));
    }

    /**
     * Redirect into view page
     * 
     * @param string $view name of view
     */
    function redirectView($view)
    {
        $mainframe = &JFactory::getApplication();
        $mainframe->redirect(ARoute::view($view));
    }

    /**
     * Get param value saved in session
     * 
     * @param string $param name
     * @param mixed $default default value
     * @param string $type data type
     * @return mixed find value or default value if not found in session or request
     */
    function getUserStateFromRequest($param, $default, $type, $useTester = false)
    {
        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $key = SESSION_PREFIX . $param;
        if (JRequest::getInt('reset')) {
            $mainframe->setUserState($key, $default);
            return $default;
        }
        if ($useTester && isset($_REQUEST[SESSION_TESTER])) {
            $value = isset($_REQUEST[$param]) ? $_REQUEST[$param] : ARequest::getEmptyValue($type);
            $mainframe->setUserState($key, $value);
            return $value;
        }
        return $mainframe->getUserStateFromRequest($key, $param, $default, $type);
    }

    /**
     * Get empty value for given datatype.
     * 
     * @param string $type
     * @return mixed acording to datatype
     */
    function getEmptyValue($type)
    {
        switch ($type) {
            case 'array':
                return array();
            case 'boolean':
                return false;
            case 'int':
                return false;
            case 'string':
                return '';
            default:
                return null;
        }
    }

    /**
     * Get property name used in user request.
     * 
     * @param int $template property template ID
     * @param int $name property ID
     * @return string
     */
    function getPropertyName($subject, $template, $name)
    {
        return 's' . $subject . 't' . $template . 'p' . $name;
    }

    /**
     * Load properties from request.
     * 
     * @param int $subject subject properties user request associated
     * @return array key is property name, value property value, array can be empty
     */
    function loadProperties($subject)
    {
        $session = &JFactory::getSession();
        /* @var $session JSession */
        $registry = &$session->get('registry');
        /* @var $registry JRegistry */
        $session = &$registry->_registry['session']['data'];
        /* @var array $request search in actual request */
        $properties = array();
        foreach (get_object_vars($session) as $key => $value) {
            /* @var string $key property from request saved in session */
            /* @var string $value property value */
            $match = array();
            /* @var array $match store search result */
            if (preg_match('#' . SESSION_PREFIX . 's' . $subject . 't([1-9][0-9]*)p([1-9][0-9]*)#', $key, $match) && $key = reset($match))
                $properties[$match[1]][$match[2]] = $value;
        }
        return $properties;
    }
}

?>