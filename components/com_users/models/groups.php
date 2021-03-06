<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Methods supporting a list of user group records.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_users
 * @since       1.6
 */
class UsersModelGroups extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  An optional associative array of configuration settings.
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'parent_id', 'a.parent_id',
				'title', 'a.title',
				'lft', 'a.lft',
				'rgt', 'a.rgt',
				'issystem', 'a.issystem',
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);
		$website_id = $this->getUserStateFromRequest($this->context . '.filter.webiste_id', 'filter_website_id');
		$this->setState('filter.website_id', $website_id);
        $parent_group_id = $this->getUserStateFromRequest($this->context . '.filter.parent_group_id', 'filter_parent_group_id');
		$this->setState('filter.parent_group_id', $parent_group_id);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_users');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.lft', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id    A prefix for the store id.
	 *
	 * @return  string  A store id.
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.search');

		return parent::getStoreId($id);
	}

	/**
	 * Gets the list of groups and adds expensive joins to the result set.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 * @since   1.6
	 */
	public function getItems()
	{
		$db = $this->getDbo();
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (empty($this->cache[$store]))
		{
			$items = parent::getItems();

			// Bail out on an error or empty list.
			if (empty($items))
			{
				$this->cache[$store] = $items;

				return $items;
			}

			// First pass: get list of the group id's and reset the counts.
			$groupIds = array();
			foreach ($items as $item)
			{
				$groupIds[] = (int) $item->id;
				$item->user_count = 0;
			}

			// Get the counts from the database only for the users in the list.
			$query = $db->getQuery(true);

			// Count the objects in the user group.
			$query->select('map.group_id, COUNT(DISTINCT map.user_id) AS user_count')
				->from($db->quoteName('#__user_usergroup_map') . ' AS map')
				->where('map.group_id IN (' . implode(',', $groupIds) . ')')
				->group('map.group_id');

			$db->setQuery($query);

			// Load the counts into an array indexed on the user id field.
			try
			{
				$users = $db->loadObjectList('group_id');
			}
			catch (RuntimeException $e)
			{
				$this->setError($e->getMessage);
				return false;
			}

			// Second pass: collect the group counts into the master items array.
			foreach ($items as &$item)
			{
				if (isset($users[$item->id]))
				{
					$item->user_count = $users[$item->id]->user_count;
				}
			}
            require_once JPATH_ROOT.'/administrator/components/com_website/helpers/website.php';
            require_once JPATH_ROOT.'/components/com_website/helpers/website.php';
            $items=websiteHelperFrontEnd::setKeyWebsite($items);
			// Add the items to the internal cache.
			$this->cache[$store] = $items;
		}

		return $this->cache[$store];
	}

	/**
	 * Build an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery
	 */
	protected function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
        $list_user_group=JUserHelper::get_list_user_group_id();
		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.*'
			)
		);
		$query->from($db->quoteName('#__usergroups') . ' AS a')
            ->where('a.id IN('.implode(',',$list_user_group).')')

        ;


		// Filter the comments over the search string if set.
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('a.title LIKE ' . $search);
			}
		}
		// Filter the comments over the search string if set.





		// Filter by group user

        $query->where('a.title!= ' .$query->q('root'));
		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.lft')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));

		//echo nl2br(str_replace('#__','jos_',$query));
		return $query;
	}
}
