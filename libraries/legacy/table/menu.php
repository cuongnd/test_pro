<?php
/**
 * @package     Joomla.Legacy
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
require_once JPATH_ROOT.'/libraries/legacy/table/menuitemnested.php';
defined('_JEXEC') or die(__FILE__);

/**
 * Menu table
 *
 * @package     Joomla.Legacy
 * @subpackage  Table
 * @since       11.1
 */

class JTableMenu extends JTableMenuItemNested
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  Database driver object.
	 *
	 * @since   11.1
	 */
	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__menu', 'id', $db);

		// Set the default access level.
		$this->access = (int) JFactory::getConfig()->get('access');
	}
	/**
	 * Overloaded bind function
	 *
	 * @param   array  $array   Named array
	 * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
	 *
	 * @return  mixed  Null if operation was satisfactory, otherwise returns an error
	 *
	 * @see     JTable::bind()
	 * @since   11.1
	 */
	public function bind($array, $ignore = '')
	{
		// Verify that the default home menu is not unset
		if ($this->home == '1' && $this->language == '*' && ($array['home'] == '0'))
		{
			$this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_CANNOT_UNSET_DEFAULT_DEFAULT'));

			return false;
		}

		// Verify that the default home menu set to "all" languages" is not unset
		if ($this->home == '1' && $this->language == '*' && ($array['language'] != '*'))
		{
			$this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_CANNOT_UNSET_DEFAULT'));

			return false;
		}

		// Verify that the default home menu is not unpublished
		if ($this->home == '1' && $this->language == '*' && $array['published'] != '1')
		{
			$this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_UNPUBLISH_DEFAULT_HOME'));

			return false;
		}

		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Overloaded check function
	 *
	 * @return  boolean  True on success
	 *
	 * @see     JTable::check()
	 * @since   11.1
	 */
	public function check()
	{
		// Set correct component id to ensure proper 404 messages with separator items
		if ($this->type == "separator")
		{
			$this->component_id = 0;
		}

		// If the alias field is empty, set it to the title.
		$this->alias = trim($this->alias);

		if ((empty($this->alias)) && ($this->type != 'alias' && $this->type != 'url'))
		{
			$this->alias = $this->title;
		}

		// Make the alias URL safe.
		$this->alias = JApplication::stringURLSafe($this->alias);

		if (trim(str_replace('-', '', $this->alias)) == '')
		{
			$this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
		}

		// Cast the home property to an int for checking.
		$this->home = (int) $this->home;

		// Verify that a first level menu item alias is not 'component'.
		if ($this->parent_id && $this->alias == 'component')
		{
			$this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_ROOT_ALIAS_COMPONENT'));

			return false;
		}

		// Verify that a first level menu item alias is not the name of a folder.
		jimport('joomla.filesystem.folder');

		if ($this->parent_id && in_array($this->alias, JFolder::folders(JPATH_ROOT)))
		{
			$this->setError(JText::sprintf('JLIB_DATABASE_ERROR_MENU_ROOT_ALIAS_FOLDER', $this->alias, $this->alias));

			return false;
		}

		// Verify that the home item a component.
		if ($this->home && $this->type != 'component')
		{
			$this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_HOME_NOT_COMPONENT'));

			return false;
		}

		return true;
	}

	/**
	 * Overloaded store function
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  mixed  False on failure, positive integer on success.
	 *
	 * @see     JTable::store()
	 * @since   11.1
	 */
	public function store($updateNulls = false)
	{
		$db = JFactory::getDbo();
        $this->getRootId();
		// Verify that the alias is unique
		$table = JTable::getInstance('Menu', 'JTable', array('dbo' => $this->getDbo()));

        $query=$db->getQuery(true);
        $query->select('m.*');
        $query->from('#__menu AS m');
        $query->leftJoin('#__menu_types AS mt ON mt.id=m.menu_type_id');
        $query->where(
            array(
                'm.id!='.$this->id,
                'm.alias='.$db->q($this->alias),
                'm.language='.$db->q($this->language),
                'm.client_id='.(int) $this->client_id,
                'm.menu_type_id='.$this->menu_type_id
            )
        );
        $db->setQuery($query);
        $menu_item=$db->loadObject();
		if ($menu_item && ($menu_item->id != $this->id || $this->id == 0))
		{
            $this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_UNIQUE_ALIAS'));
			return false;
		}

		// Verify that the home page for this language is unique
		if ($this->home == '1')
		{
            $query=$db->getQuery(true);
            $query->update('#__menu AS m');
            $query->leftJoin('#__menu_types AS mt ON mt.id=m.menu_type_id');
            $query->where(array('m.home=1', 'm.language='.$db->q($this->language),'mt.website_id='.(int)$this->website_id));
            $query->set('home=0');
            $query->set('checked_out=0');
            $query->set('checked_out_time='. $db->q($db->getNullDate()));
            $db->setQuery($query);
            if(!$db->execute())
            {
                $this->setError($db->getErrorMsg());
                return false;
            }

            $menu_item=$table->load(array('home' => '1', 'menu_type_id' => $this->menu_type_id));
			// Verify that the home page for this menu is unique.
			if ($menu_item && ($table->id != $this->id || $this->id == 0))
			{
				$this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_HOME_NOT_UNIQUE_IN_MENU'));

				return false;
			}
		}
        unset($this->website_id);
		if (!parent::store($updateNulls))
		{
			$this->setError(JText::_('Error parent::store($updateNulls)'));
			return false;
		}

		// Get the new path in case the node was moved
		$pathNodes = $this->getPath();
		$segments = array();

		foreach ($pathNodes as $node)
		{
			// Don't include root in path
			if ($node->alias != 'root')
			{
				$segments[] = $node->alias;
			}
		}

		$newPath = trim(implode('/', $segments), ' /\\');

		// Use new path for partial rebuild of table
		// Rebuild will return positive integer on success, false on failure

		return ($this->rebuild($this->menu_type_id,0, 0, 0, $newPath) > 0);
	}
}
