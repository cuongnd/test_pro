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

class MenusHelperFrontEnd
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
	public static function get_list_menu_item_by_website_id($website_id)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->clear();
		$query->select('menu.*,menu_types.id as menu_type_id,menu_types.website_id')
			->from('#__menu AS menu')
			->leftJoin('#__menu_type_id_menu_id AS menu_type_id_menu_id ON menu_type_id_menu_id.menu_id=menu.id')
			->leftJoin('#__menu_types AS menu_types ON menu_types.id=menu_type_id_menu_id.menu_type_id')
			;
		$db->setQuery($query);
		$list_menu_item = $db->loadObjectList('id');

		$children = array();
		// First pass - collect children
		foreach ($list_menu_item as $v) {
			$pt = $v->parent_id;
			$pt=($pt==''||$pt==$v->id)?'root':$pt;
			$list = @$children[$pt] ? $children[$pt] : array();
            array_push($list, $v);
			$children[$pt] = $list;
		}

		$list_menu_item=array();
		$list_root_menu_item=$children['root'];
		unset($children['root']);
		foreach($list_root_menu_item as $root_menu_item)
		{
			if($root_menu_item->website_id!='' && $root_menu_item->website_id==$website_id)
			{
				$sub_list_menu_item=array();
				$sub_list_menu_item[]=$root_menu_item;
				MenusHelperFrontEnd::get_list_children_menu_item_by_root_menu_item_id($root_menu_item->id,$sub_list_menu_item,$children);
				$list_menu_item=array_merge($list_menu_item,$sub_list_menu_item);
			}
		}
		return $list_menu_item;
	}
	public static function get_list_children_menu_item_by_root_menu_item_id($root_menu_item_id=0, &$list_menu_item=array(), $children)
	{
		if ($children[$root_menu_item_id]) {

			usort($children[$root_menu_item_id], function ($item1, $item2) {
				if ($item1->ordering == $item2->ordering) return 0;
				return $item1->ordering < $item2->ordering ? -1 : 1;
			});
			foreach ($children[$root_menu_item_id] as $v) {
				$id = $v->id;
				$list_menu_item[]=$v;
				MenusHelperFrontEnd::get_list_children_menu_item_by_root_menu_item_id($id, $list_menu_item, $children);
			}
		}
	}

	public static function get_children_menu_item_id_by_menu_item_id($menu_item_id)
	{
		$db    = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->clear();
		$query->select('menu.*,menu_types.id as menu_type_id,menu_types.website_id')
			->from('#__menu AS menu')
			->leftJoin('#__menu_type_id_menu_id AS menu_type_id_menu_id ON menu_type_id_menu_id.menu_id=menu.id')
			->leftJoin('#__menu_types AS menu_types ON menu_types.id=menu_type_id_menu_id.menu_type_id')
		;
		$db->setQuery($query);
		$list_menu_item = $db->loadObjectList('id');

		$children = array();
		// First pass - collect children
		foreach ($list_menu_item as $v) {
			$pt = $v->parent_id;
			$pt=$pt?$pt:'root';
			$list = @$children[$pt] ? $children[$pt] : array();
			if ($v->id != $v->parent_id || $v->parent_id!=null) {
				array_push($list, $v);
			}
			$children[$pt] = $list;
		}
		$list_menu_item=array();
		MenusHelperFrontEnd::get_list_children_menu_item_by_root_menu_item_id($menu_item_id,$list_menu_item,$children);
		$list_menu_item_id=array();
		foreach($list_menu_item as $menu_item)
		{
			$list_menu_item_id[]=$menu_item->id;
		}
		return $list_menu_item_id;
	}
	public static function get_children_menu_item_by_menu_item_id($menu_item_id)
	{
		$db    = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->clear();
		$query->select('menu.*,menu_types.id as menu_type_id,menu_types.website_id')
			->from('#__menu AS menu')
			->leftJoin('#__menu_type_id_menu_id AS menu_type_id_menu_id ON menu_type_id_menu_id.menu_id=menu.id')
			->leftJoin('#__menu_types AS menu_types ON menu_types.id=menu_type_id_menu_id.menu_type_id')
		;
		$db->setQuery($query);
		$list_menu_item = $db->loadObjectList('id');

		$children = array();
		// First pass - collect children
		foreach ($list_menu_item as $v) {
			$pt = $v->parent_id;
			$pt=($pt==''||$pt==$v->id)?'list_root':$pt;
			$list = @$children[$pt] ? $children[$pt] : array();
            array_push($list, $v);
			$children[$pt] = $list;
		}
        unset($children['list_root']);
		$list_menu_item=array();
		MenusHelperFrontEnd::get_list_children_menu_item_by_root_menu_item_id($menu_item_id,$list_menu_item,$children);
		return $list_menu_item;
	}

    public static function get_menu_type_id_by_menu_item_id($menu_item_id)
    {
        $db    = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->clear();
        $query->select('menu.*')
            ->from('#__menu AS menu')
        ;
        $db->setQuery($query);
        $list_menu_item = $db->loadObjectList('id');


        if (!function_exists('sub_get_root_menu_item_id_by_menu_item_id')) {
            function sub_get_root_menu_item_id_by_menu_item_id( $menu_item_id = 0, $list_menu_item)
            {
                $menu_item=$list_menu_item[$menu_item_id];
                if(!$menu_item)
                {
                    //throw new Exception('there are no exists this menu item');
                }
                if($menu_item->id==$menu_item->parent_id||$menu_item->parent_id=='')
                {
                    return $menu_item_id;
                }else{

                    return sub_get_root_menu_item_id_by_menu_item_id($menu_item->parent_id,$list_menu_item);
                }
            }
        }
        $root_menu_item_id=sub_get_root_menu_item_id_by_menu_item_id($menu_item_id,$list_menu_item);
        if($root_menu_item_id)
        {
            $query->clear();
            $query->select('menu_type_id_menu_id.menu_type_id')
                ->from('#__menu_type_id_menu_id AS menu_type_id_menu_id')
                ->where('menu_type_id_menu_id.menu_id='.(int)$root_menu_item_id)
                ;
            $db->setQuery($query);
            return $db->loadResult();
        }
        return false;
    }

    public static function get_list_menu_item_id_by_website_id($website_id)
    {
        $list_menu_item_item=MenusHelperFrontEnd::get_list_menu_item_by_website_id($website_id);
        $list_menu_item_item_id=JArrayHelper::pivot($list_menu_item_item,'id');
        $list_menu_item_item_id=array_keys($list_menu_item_item_id);
        return $list_menu_item_item_id;
    }

    public static function get_list_root_menu_item_id_by_website_id($website_id)
    {
        $db    = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->clear();
        $query->select('menu.id')
            ->from('#__menu AS menu')
            ->leftJoin('#__menu_type_id_menu_id AS menu_type_id_menu_id ON menu_type_id_menu_id.menu_id=menu.id')
            ->leftJoin('#__menu_types AS menu_types ON menu_types.id=menu_type_id_menu_id.menu_type_id')
            ->where('menu_types.website_id='.(int)$website_id)
        ;
        $db->setQuery($query);
        $list_menu_item = $db->loadColumn();
        return $list_menu_item;
    }

    public static function get_list_root_menu_item_by_website_id($website_id)
    {
        $db    = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->clear();
        $query->select('menu.*,menu_types.id as menu_type_id,menu_types.website_id')
            ->from('#__menu AS menu')
            ->leftJoin('#__menu_type_id_menu_id AS menu_type_id_menu_id ON menu_type_id_menu_id.menu_id=menu.id')
            ->leftJoin('#__menu_types AS menu_types ON menu_types.id=menu_type_id_menu_id.menu_type_id')
            ->where('menu_types.website_id='.(int)$website_id)
        ;
        $db->setQuery($query);
        $list_menu_item = $db->loadObjectList();
        return $list_menu_item;
    }

    public static function get_menu_type_by_website_id($website_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__menu_types')
            ->where('website_id='.(int)$website_id);
        $db->setQuery($query);
        return $db->loadObjectList();
    }

    public static function get_list_all_menu_item_by_menu_type_id($menu_type_id)
    {
        $root_menu_item=MenusHelperFrontEnd::get_root_menu_item_by_menu_type_id($menu_type_id);
        $list_children_menu_item=MenusHelperFrontEnd::get_children_menu_item_by_menu_item_id($root_menu_item->id);
        array_unshift($list_children_menu_item,$root_menu_item);
        return $list_children_menu_item;

    }

    public static function get_root_menu_item_id_by_menu_type_id($menu_type_id)
    {
        $db    = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear();
        $query->select('menu_type_id_menu_id.menu_id')
            ->from('#__menu_type_id_menu_id AS menu_type_id_menu_id')
            ->where('menu_type_id_menu_id.menu_type_id='.(int)$menu_type_id)
        ;
        $db->setQuery($query);
        $root_menu_item_id = $db->loadResult();
        return $root_menu_item_id;
    }

    private static function get_root_menu_item_by_menu_type_id($menu_type_id)
    {
        $db    = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->clear();
        $query->select('menu.*')
            ->from('#__menu AS menu')
            ->leftJoin('#__menu_type_id_menu_id AS menu_type_id_menu_id ON menu_type_id_menu_id.menu_id=menu.id')
            ->where('menu_type_id_menu_id.menu_type_id='.(int)$menu_type_id)
        ;
        $db->setQuery($query);
        $item = $db->loadObject();
        return $item;
    }

    public static function get_root_menu_item_id_by_menu_item_id($menu_item_id)
    {
        $menu_type_id=MenusHelperFrontEnd::get_menu_type_id_by_menu_item_id($menu_item_id);
        $root_menu_item_id=MenusHelperFrontEnd::get_root_menu_item_id_by_menu_type_id($menu_type_id);
        return $root_menu_item_id;
    }

    public static function get_all_menu_item_not_root_menu_item($website_id)
    {
        $list_menu_item=MenusHelperFrontEnd::get_list_menu_item_by_website_id($website_id);
        $list_menu_item_return=array();
        foreach($list_menu_item as $item)
        {
            if($item->title=='Menu_item_root' OR $item->alias=='root')
            {
                continue;
            }else{
                $list_menu_item_return[]=$item;
            }
        }
        return $list_menu_item_return;
    }

    public static function get_menu_item_by_menu_item_id($menu_item_id)
    {
        $website=JFactory::getWebsite();
        $list_menu_item=MenusHelperFrontEnd::get_list_menu_item_by_website_id($website->website_id);
        $list_menu_item=JArrayHelper::pivot($list_menu_item,'id');
        return $list_menu_item[$menu_item_id];
    }

    public static function get_menu_daskboard_item_id()
    {
        $website=JFactory::getWebsite();
        $list_menu_item=MenusHelperFrontEnd::get_list_menu_item_by_website_id($website->website_id);
        foreach($list_menu_item as $menu_item)
        {
            if($menu_item->is_main_dashboard==1)
            {
                return $menu_item->id;
            }
        }
        return 0;
    }

    public static function get_dashboard_menu_supper_admin_id()
    {
        $website=JFactory::getWebsite();
        $list_menu_item=MenusHelperFrontEnd::get_list_menu_item_by_website_id($website->website_id);
        foreach($list_menu_item as $menu_item)
        {
            if($menu_item->is_dashboard_menu_supper_admin==1)
            {
                return $menu_item->id;
            }
        }
        return 0;
    }


    public function get_list_menu_type_by_website_id($website_id=0)
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
	public static function getMenuLinks($menuType = null, $parentId = 0, $mode = 0, $published = array(), $languages = array())
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.id AS value, a.title AS text, a.alias, a.level, a.menutype, a.type, a.template_style_id, a.checked_out')
			->from('#__menu AS a')
			->join('LEFT', $db->quoteName('#__menu') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt');

		// Filter by the type
		if ($menuType)
		{
			$query->where('(a.menutype = ' . $db->quote($menuType) . ' OR a.parent_id = 0)');
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
			->group('a.id, a.title, a.level, a.menutype, a.type, a.template_style_id, a.checked_out, a.lft')
			->order('a.lft ASC');

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

		if (empty($menuType))
		{
			// If the menutype is empty, group the items by menutype.
			$query->clear()
				->select('*')
				->from('#__menu_types')
				->where('menutype <> ' . $db->quote(''))
				->order('title, menutype');
			$db->setQuery($query);

			try
			{
				$menuTypes = $db->loadObjectList();
			}
			catch (RuntimeException $e)
			{
				JError::raiseWarning(500, $e->getMessage());
				return false;
			}

			// Create a reverse lookup and aggregate the links.
			$rlu = array();
			foreach ($menuTypes as &$type)
			{
				$rlu[$type->menutype] = & $type;
				$type->links = array();
			}

			// Loop through the list of menu links.
			foreach ($links as &$link)
			{
				if (isset($rlu[$link->menutype]))
				{
					$rlu[$link->menutype]->links[] = & $link;

					// Cleanup garbage.
					unset($link->menutype);
				}
			}

			return $menuTypes;
		}
		else
		{
			return $links;
		}
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

	public function get_list_menu_item_not_root($parent_id, &$list_menu_item_not_root)
	{
		$nodes=array();
		foreach($list_menu_item_not_root as $key=> $menu_item)
		{
			if($menu_item->parent_id==$parent_id)
			{
				$nodes[]=$menu_item;
				unset($list_menu_item_not_root[$key]);
			}


		}
		if(count($nodes))
		{
			foreach($nodes as $node)
			{
				MenusHelperFrontEnd::get_list_menu_item_not_root($node->id,$list_menu_item_not_root);
			}
		}
	}
}
