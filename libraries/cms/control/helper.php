<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Module
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

/**
 * Module helper class
 *
 * @property  list_control_module
 * @package     Joomla.Libraries
 * @subpackage  Module
 * @since       1.5
 */

abstract class JControlHelper
{

    protected static $list_control_module_by_website_id=array();
    public static function get_list_control_by_website_id($website_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('control.*')
            ->from('#__control AS control')
            ->where('website_id='.(int)$website_id)
            ;
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    public static function get_list_control_module_by_website_id($website_id)
    {
        $list_control_module=self::$list_control_module_by_website_id[$website_id];
        if($list_control_module)
        {
            return $list_control_module;
        }
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('control.*')
            ->from('#__control AS control')
            ->where('website_id='.(int)$website_id)
            ->where('type='.$query->q('module'))
        ;
        $db->setQuery($query);
        static::$list_control_module_by_website_id[$website_id]= $db->loadObjectList();
        return static::$list_control_module_by_website_id[$website_id];

    }

    public static function get_fields_module_by_module_id($module_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('control.fields')
            ->from('#__control AS control')
            ->leftJoin('#__website AS website ON website.id=control.website_id')
            ->where('module.id='.(int)$module_id)
            ->where('control.type='.$query->q('module'))
        ;
        $db->setQuery($query);
        return $db->loadResult();
    }
    public static function get_control_module_by_module_id($module_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('control.*')
            ->from('#__modules AS module')
            ->leftJoin('#__extensions AS extension ON extension.id=module.extension_id')
            ->leftJoin('#__website AS website ON website.id=extension.website_id')
            ->leftJoin('#__control AS control ON control.element_path=CONCAT("modules/website/website_",website.name,"/", module.module) OR control.element_path = CONCAT("modules/", module.module) ')
            ->where('module.id='.(int)$module_id)
            ->where('control.type='.$query->q('module'))
            ->where('control.website_id=extension.website_id')

            ;
        $db->setQuery($query);
        return $db->loadObject();
    }
}
