<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_languages
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JLoader::register('MenusHelper', JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');

/**
 * Helper for mod_languages
 *
 * @package     Joomla.Site
 * @subpackage  mod_languages
 *
 * @since       1.6.0
 */
abstract class ModLanguagesHelper
{
	/**
	 * Gets a list of available languages
	 *
	 * @param   JRegistry  &$params  module params
	 *
	 * @return  array
	 */
	public static function getList(&$params)
	{
		$user	= JFactory::getUser();
		$lang 	= JFactory::getLanguage();
		$app	= JFactory::getApplication();
		$menu 	= $app->getMenu();

		// Get menu home items
		$homes = array();

		foreach ($menu->getMenu() as $item)
		{
			if ($item->home)
			{
				$homes[$item->language] = $item;
			}
		}

		// Load associations
		$assoc = JLanguageAssociations::isEnabled();

		if ($assoc)
		{
			$active = $menu->getActive();

			if ($active)
			{
				$associations = MenusHelper::getAssociations($active->id);
			}
			// Load component associations
			$option = $app->input->get('option');
			$eName = JString::ucfirst(JString::str_ireplace('com_', '', $option));
			$cName = JString::ucfirst($eName . 'HelperAssociation');
			JLoader::register($cName, JPath::clean(JPATH_COMPONENT_SITE . '/helpers/association.php'));

			if (class_exists($cName) && is_callable(array($cName, 'getAssociations')))
			{
				$cassociations = call_user_func(array($cName, 'getAssociations'));
			}
		}

		$levels		= $user->getAuthorisedViewLevels();
		$languages	= JLanguageHelper::getLanguages();
		// Filter allowed languages


		return $languages;
	}
}
