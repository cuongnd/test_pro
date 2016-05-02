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
        $query->select('module.*,website.id AS website_id,website.name AS website_name')
            ->from('#__modules AS module')
            ->leftJoin('#__extensions AS extension ON extension.id=module.extension_id')
            ->leftJoin('#__website AS website ON website.id=extension.website_id')
            ->where('module.id='.(int)$module_id)
            ;
        $db->setQuery($query);
        $module=$db->loadObject();
        $ui_path = $module->module;
        $table_control = JTable::getInstance('control');

        $element_path='modules/website/website_'.$module->website_name.'/' . $ui_path;

        jimport('joomla.filesystem.folder');
        if(!JFolder::exists(JPATH_ROOT.DS.$element_path))
        {
            $element_path='modules/' . $ui_path;
        }
        require_once JPATH_ROOT.'/components/com_modules/helpers/module.php';
        $filter= array(
            "element_path" => $element_path,
            "type" => module_helper::ELEMENT_TYPE,
            'website_id'=>$module->website_id
        );
        $table_control->load(
            $filter
        );
        return $table_control;
    }

    public static function get_position_control_by_position_id($id)
    {
        $table_position=JTable::getInstance('positionnested');
        $table_position->load($id);
        $ui_path= $table_position->ui_path;

        if($ui_path[0]=='/')
        {
            $ui_path = substr($ui_path, 1);
        }
        if($table_position->type=='row')
        {
            $ui_path='media/elements/ui/row.php';
        }
        $db=JFactory::getDbo();
        require_once JPATH_ROOT.'/components/com_utility/helper/block_helper.php';
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__control')
            ->where('element_path LIKE '.$query->q('%'.$ui_path.'%'))
            ->where('type='.$query->q(block_helper::ELEMENT_TYPE_NAME))
        ;
        $control=$db->setQuery($query)->loadObject();
        return $control;
    }

    public static function get_main_control_element()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__control')
            ->where('element_path LIKE '.$query->q(block_helper::ROOT_ELEMENT_NAME))
            ->where('type='.$query->q(block_helper::ELEMENT_TYPE_NAME))
        ;

        $control=$db->setQuery($query)->loadObject();
        return $control;
    }
}
