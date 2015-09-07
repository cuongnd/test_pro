<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_menu
 *
 * @package     Joomla.Administrator
 * @subpackage  mod_menu
 * @since       1.5
 */
abstract class ModMenuHelper
{
	/**
	 * Get a list of the available menus.
	 *
	 * @return  array  An array of the available menus (from the menu types table).
	 *
	 * @since   1.6
	 */
	public static function getMenus()
	{
        $supperAdmin=JFactory::isSupperAdmin();
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true)
			->select('a.*, SUM(b.home) AS home')
			->from('#__menu_types AS a')
			->join('LEFT', '#__menu AS b ON b.menu_type_id = a.id AND b.home != 0')
			->select('b.language')
			->join('LEFT', '#__languages AS l ON l.lang_code = language')
			->select('l.image')
			->select('l.sef')
			->select('l.title_native')
			->where('(b.client_id = 0 OR b.client_id IS NULL)');

        if($supperAdmin)
        {

        }
        else
        {
            $website=JFactory::getWebsite();
            $query->where('a.website_id='.$website->website_id);
            $query->where('a.issystem=0');
        }
		// Sqlsrv change
		$query->group('a.id, a.menutype, a.description, a.title, b.menutype,b.language,l.image,l.sef,l.title_native');

		$db->setQuery($query);

		$result = $db->loadObjectList();
		return $result;
	}

	/**
	 * Get a list of the authorised, non-special components to display in the components menu.
	 *
	 * @param   boolean  $authCheck	  An optional switch to turn off the auth check (to support custom layouts 'grey out' behaviour).
	 *
	 * @return  array  A nest array of component objects and submenus
	 *
	 * @since   1.6
	 */
	public static function getComponents($authCheck = true,$params)
	{

        $menu_type_id=$params->get('menu_type_id',0);
        $supperAdmin=JFactory::isSupperAdmin();
		$lang	= JFactory::getLanguage();
		$user	= JFactory::getUser();
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$result	= array();

		// Prepare the query.
		$query->select('m.id, m.title,m.level,m.lft,m.rgt,. m.alias, m.link, m.parent_id, m.img')
			->from('#__menu AS m');

		// Filter on the enabled states.
        //$query->where('m.client_id = 1')
			//$query->where('m.id > 1');
        $website=JFactory::getWebsite();
        $query->leftJoin('#__menu_types AS mt ON mt.id=m.menu_type_id');
        $query->where('mt.website_id='.$website->website_id);
        $query->where('mt.id='.$menu_type_id);
        $query->where('m.alias!='.$query->quote('root'));

		// Order by lft.
		$query->order('m.lft');
		$db->setQuery($query);
		// Component list
		$components	= $db->loadObjectList();


		return $components;
	}
    public function changeParam($params)
    {
        //change menu_type_id
        $menu_type_id= $params->get('menu_type_id');
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('mt.id')
            ->from('#__menu_types as mt')
            ->where('mt.copy_from='.(int)$menu_type_id);
        $db->setQuery($query);
        $new_menu_type_id=$db->loadResult();
        if($new_menu_type_id)
        {
            $params->set('menu_type_id',$new_menu_type_id);
            $query->clear();
            $query->update('#__menu_types');
        }
        return $params;

    }

}
