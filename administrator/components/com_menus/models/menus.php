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
 * Menu List Model for Menus.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 * @since       1.6
 */
class MenusModelMenus extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.title',
				'menutype', 'a.menutype',
                'issystem', 'a.issystem'

            );
		}

		parent::__construct($config);
	}

	/**
	 * Overrides the getItems method to attach additional metrics to the list.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   1.6.1
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId('getItems');

		// Try to load the data from internal storage.
		if (!empty($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the list items.
		$items = parent::getItems();
		require_once JPATH_ROOT.'/components/com_website/helpers/website.php';
        $items=websiteHelperFrontEnd::setKeyWebsite($items);
		// If emtpy or an error, just return.
		if (empty($items))
		{
			return array();
		}
        $db = $this->getDbo();
        foreach($items as $key=>$item)
        {
            $query=$db->getQuery(true);
            $query->select('COUNT(m.id) AS count_published');
            $query->from('#__menu AS m');
            $query->where('m.published = 1');
            $query->where('m.menu_type_id='.(int)$item->id);
            $query->where('m.alias!='.$query->q('root'));
            $db->setQuery($query);
            try
            {
                $items[$key]->count_published=(int)$db->loadResult();
            }
            catch (RuntimeException $e)
            {
                $this->setError($e->getMessage());
                return false;
            }


            $query->clear('where')
                ->where('m.published = 0')
                ->where('m.menu_type_id='.(int)$item->id)
                ->where('m.alias!='.$query->q('root'));
            $db->setQuery($query);

            try
            {
                $items[$key]->count_unpublished=(int)$db->loadResult();
            }
            catch (RuntimeException $e)
            {
                $this->setError($e->getMessage());
                return false;
            }
            // Get the trashed menu counts.
            $query->clear('where')
                ->where('m.published = -2')
                ->where('m.menu_type_id='.(int)$item->id);
            $db->setQuery($query);
            try
            {
                $items[$key]->count_trashed=(int)$db->loadResult();
            }
            catch (RuntimeException $e)
            {
                $this->setError($e->getMessage);
                return false;
            }


        }

		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string  An SQL query
	 *
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		// Select all fields from the table.
		$query->select($this->getState('list.select', 'a.*'))
			->from($db->quoteName('#__menu_types') . ' AS a')

			->group('a.id,a.menutype, a.title, a.description');

		// Filter by search in title or menutype
		if ($search = trim($this->getState('filter.search')))
		{
			$search = $db->quote('%' . $db->escape($search, true) . '%');
			$query->where('(' . 'a.title LIKE ' . $search . ' OR a.menutype LIKE ' . $search . ')');
		}
        $supperAdmin=JFactory::isSupperAdmin();

        if($supperAdmin)
        {
            //filter by website
            $website_id = $this->getState('filter.website_id');
            if ($website_id)
            {
                $query->where('a.website_id = ' . $website_id);
            }
        }
        else
        {
            //allway filter by website
            $website=JFactory::getWebsite();
            $website_id=$website->website_id;
            $domain=$website->domain;
            $query->where('a.website_id = '.$website_id );
            $query->where('a.issystem =0 ' );
        }

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.id')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

		return $query;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$search = $this->getUserStateFromRequest($this->context . '.search', 'filter_search');
		$this->setState('filter.search', $search);
        $website_id = $this->getUserStateFromRequest($this->context . '.filter.website_id', 'filter_website_id', '');
        $this->setState('filter.website_id', $website_id);

		// List state information.
		parent::populateState('a.id', 'asc');
	}

	/**
	 * Gets the extension id of the core mod_menu module.
	 *
	 * @return  integer
	 *
	 * @since   2.5
	 */
	public function getModMenuId()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true)
			->select('e.id as id')
			->from('#__extensions AS e')
			->where('e.type = ' . $db->quote('module'))
			->where('e.element = ' . $db->quote('mod_menu'))
			->where('e.client_id = 0');
		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Gets a list of all mod_mainmenu modules and collates them by menutype
	 *
	 * @return  array
	 */
	public function &getModules()
	{
		$model = JModelLegacy::getInstance('Menu', 'MenusModel', array('ignore_request' => true));
		$result = & $model->getModules();

		return $result;
	}
}
