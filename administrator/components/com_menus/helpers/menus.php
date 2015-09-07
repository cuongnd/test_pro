<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Menus component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 * @since       1.6
 */
class MenusHelper
{
	/**
	 * Defines the valid request variables for the reverse lookup.
	 */
	protected static $_filter = array('option', 'view', 'layout');

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string    The name of the active view.
	 */
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('COM_MENUS_SUBMENU_MENUS'),
			'index.php?option=com_menus&view=menus',
			$vName == 'menus'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_MENUS_SUBMENU_ITEMS'),
			'index.php?option=com_menus&view=items',
			$vName == 'items'
		);
	}
    public function getMenuTypesByWebsiteId($website_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__menu_types')
            ->where('website_id='.(int)$website_id);
        $db->setQuery($query);
        return $db->loadObjectList();

    }
    public function getMenuItemDefault($website_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__menu AS m')
            ->leftJoin('#__menu_types AS mt ON mt.id=m.menu_type_id')
            ->where('mt.website_id='.(int)$website_id)
            ->where('m.home=1');
        $db->setQuery($query);
        return $db->loadObject();
    }
    public function getTotalMenuItemByWebsiteId($website_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('COUNT(*)')
            ->from('#__menu AS m')
            ->leftJoin('#__menu_types AS mt ON mt.id=m.menu_type_id')
            ->where('mt.website_id='.(int)$website_id);
        $db->setQuery($query);
        return $db->loadResult();

    }
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   integer  The menu ID.
	 *
	 * @return  JObject
	 *
	 * @since   1.6
	 * @deprecated  3.2  Use JHelperContent::getActions() instead
	 */
	public static function getActions($parentId = 0)
	{
		// Log usage of deprecated function
		JLog::add(__METHOD__ . '() is deprecated, use JHelperContent::getActions() with new arguments order instead.', JLog::WARNING, 'deprecated');

		// Get list of actions
		$result = JHelperContent::getActions('com_menus');

		return $result;
	}

	/**
	 * Gets a standard form of a link for lookups.
	 *
	 * @param   mixed    A link string or array of request variables.
	 *
	 * @return  mixed  A link in standard option-view-layout form, or false if the supplied response is invalid.
	 */
	public static function getLinkKey($request)
	{
		if (empty($request))
		{
			return false;
		}

		// Check if the link is in the form of index.php?...
		if (is_string($request))
		{
			$args = array();
			if (strpos($request, 'index.php') === 0)
			{
				parse_str(parse_url(htmlspecialchars_decode($request), PHP_URL_QUERY), $args);
			}
			else
			{
				parse_str($request, $args);
			}
			$request = $args;
		}
		// Only take the option, view and layout parts.
		foreach ($request as $name => $value)
		{
			if ((!in_array($name, self::$_filter)) && (!($name == 'task' && !array_key_exists('view', $request))))
			{
				// Remove the variables we want to ignore.
				unset($request[$name]);
			}
		}

		ksort($request);

		return 'index.php?' . http_build_query($request, '', '&');
	}

	/**
	 * Get the menu list for create a menu module
	 *
	 * @return    array    The menu array list
	 * @since        1.6
	 */
	public static function getMenuTypes()
	{
        $supperAdmin=JFactory::isSupperAdmin();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.menutype')
			->from('#__menu_types AS a');

        if($supperAdmin)
        {

        }
        else
        {
            $website=JFactory::getWebsite();
            $query->where('website_id='.(int)$website->website_id);
            $query->where('a.issystem=0');
        }
		$db->setQuery($query);

		return $db->loadColumn();
	}

	/**
	 * Get a list of menu links for one or all menus.
	 *
	 * @param   string    An option menu to filter the list on, otherwise all menu links are returned as a grouped array.
	 * @param   integer   An optional parent ID to pivot results around.
	 * @param   integer   An optional mode. If parent ID is set and mode=2, the parent and children are excluded from the list.
	 * @param   array     An optional array of states
	 */
	public static function getMenuLinks($menu_type_id = 0, $parentId = 0, $mode = 0, $published = array(), $languages = array())
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.id AS value, a.title AS text, a.alias, a.level, a.menu_type_id, a.type, a.template_style_id, a.checked_out')
			->from('#__menu AS a')
			->leftJoin('#__menu AS  b ON a.lft > b.lft AND a.rgt < b.rgt');

		// Filter by the type
		if ($menu_type_id)
		{
			$query->where('a.menu_type_id = ' . (int)$menu_type_id);
		}
		if ($parentId)
		{
			if ($mode == 2)
			{
				// Prevent the parent and children from showing.
				$query->join('LEFT', '#__menu AS p ON p.id = ' . (int) $parentId)
					->where('(a.lft <= p.lft OR a.rgt >= p.rgt)');
			}
		}

		if (!empty($languages))
		{
			if (is_array($languages))
			{
				$languages = '(' . implode(',', array_map(array($db, 'quote'), $languages)) . ')';
			}
			$query->where('a.language IN ' . $languages);
		}

		if (!empty($published))
		{
			if (is_array($published))
			{
				$published = '(' . implode(',', $published) . ')';
			}
			$query->where('a.published IN ' . $published);
		}

		$query->where('a.published != -2')
			->group('a.id, a.title, a.level, a.menu_type_id, a.type, a.template_style_id, a.checked_out, a.lft')
			->order('a.lft ASC');
		$website=JFactory::getWebsite();
		$query->leftJoin('#__menu_types AS menu_type ON menu_type.id=a.menu_type_id');
		$query->select('menu_type.title AS menu_type_title');
		$query->where('menu_type.website_id='.(int)$website->website_id);
		$query->where('a.alias!="root"');
		// Get the options.
		$db->setQuery($query);
		try
		{
			$links = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
			return false;
		}

		return $links;
	}

	static public function getAssociations($pk)
	{
		$associations = array();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->from('#__menu as m')
			->join('INNER', '#__associations as a ON a.id=m.id AND a.context=' . $db->quote('com_menus.item'))
			->join('INNER', '#__associations as a2 ON a.key=a2.key')
			->join('INNER', '#__menu as m2 ON a2.id=m2.id')
			->where('m.id=' . (int) $pk)
			->select('m2.language, m2.id');
		$db->setQuery($query);

		try
		{
			$menuitems = $db->loadObjectList('language');
		}
		catch (RuntimeException $e)
		{
			throw new Exception($e->getMessage(), 500);
		}

		foreach ($menuitems as $tag => $item)
		{
			// Do not return itself as result
			if ((int) $item->id != $pk)
			{
				$associations[$tag] = $item->id;
			}
		}
		return $associations;
	}
}
