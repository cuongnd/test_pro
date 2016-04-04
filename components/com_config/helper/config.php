<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_config
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Components helper for com_config
 *
 * @package     Joomla.Administrator
 * @subpackage  com_config
 * @since       3.0
 */
class ConfigHelperConfig extends JHelperContent
{
	/**
	 * Get an array of all enabled components.
	 *
	 * @return  array
	 *
	 * @since   3.0
	 */
	public static function getAllComponents()
	{
		$website=JFactory::getWebsite();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('component.*')
			->from('#__components AS component')
            ->leftJoin('#__extensions AS extension ON extension.id=component.id')
			->where('component.enabled = 1')
            ->leftJoin('#__extensions AS extensions ON extensions.id=component.extension_id')
            ->where('extensions.website_id='.(int)$website->website_id)
        ;
		$db->setQuery($query);
		$result = $db->loadObjectList();
 		return $result;
	}

	/**
	 * Returns true if the component has configuration options.
	 *
	 * @param   string  $component  Component name
	 *
	 * @return  boolean
	 *
	 * @since   3.0
	 */
	public static function hasComponentConfig($component)
	{
        $website_name=JFactory::get_website_name();
        $component_path='components/website/website_'.$website_name.'/' . $component->name;
        if(!JFolder::exists(JPATH_ROOT.DS.$component_path))
        {
            $component_path='components/' . $component->name;
        }
        $path_config_xml=JPATH_ROOT.DS.$component_path  . '/config.xml';
		return is_file($path_config_xml);
	}

	/**
	 * Returns an array of all components with configuration options. By only
	 * components for which the current user has 'core.manage' rights are returned.
	 *
	 * @param   boolean  $authCheck
	 *
	 * @return  array
	 *
	 * @since   3.0
	 */
	public static function getComponentsWithConfig($authCheck = true)
	{
		$result = array();
		$components = self::getAllComponents();
		$user = JFactory::getUser();

		// Remove com_config from the array as that may have weird side effects
		foreach ($components as $key=> $component)
		{
            if($component->name=='com_config')
            {
                unset($components[$key]);
                continue;
            }
            //&& (!$authCheck || $user->authorise('core.manage', $component->element))
			if (self::hasComponentConfig($component) )
			{
				$result[] = $component;
			}
		}

		return $result;
	}

	/**
	 * Load the sys language for the given component.
	 *
	 * @param   string  $components
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public static function loadLanguageForComponents($components)
	{
		$lang = JFactory::getLanguage();

		foreach ($components as $component)
		{
			if (!empty($component))
			{
				// Load the core file then
				// Load extension-local file.
				$lang->load($component->element . '.sys', JPATH_BASE, null, false, true)
				|| $lang->load($component->element . '.sys', JPATH_ROOT . '/components/' . $component->element, null, false, true);
			}
		}
	}
}
