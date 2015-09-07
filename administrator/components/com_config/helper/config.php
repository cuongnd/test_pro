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
			->select('element,website_id')
			->from('#__components')
			->where('website_id='.(int)$website->website_id)
			->where('enabled = 1');
		$db->setQuery($query);
		$result = $db->loadObjectList();
        require_once JPATH_ROOT.'/administrator/components/com_website/helpers/website.php';
        $result=websiteHelperFrontEnd::setKeyWebsite($result);
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
		return is_file(JPATH_ADMINISTRATOR . '/components/' . $component->element . '/config.xml');
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
            if($component->element=='com_config')
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
				|| $lang->load($component->element . '.sys', JPATH_ADMINISTRATOR . '/components/' . $component->element, null, false, true);
			}
		}
	}
}
