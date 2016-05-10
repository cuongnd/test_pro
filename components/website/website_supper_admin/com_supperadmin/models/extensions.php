<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Methods supporting a list of component records.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 * @since       1.6
 */
class supperadminModelextensions extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  An optional associative array of configuration settings.
	 * @see     JController
	 * @since   1.6
	 */
    protected $context = 'extensions';
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'title', 'a.name',
                'website_id','a.website_id',
				'name', 'a.name',
				'folder', 'a.folder',
				'element', 'a.element',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'state', 'a.state',
				'enabled', 'a.enabled',
				'access', 'a.access', 'access_level',
				'ordering', 'a.ordering',
				'client_id', 'a.client_id',
                'issystem', 'a.issystem'
			);
		}

		parent::__construct($config);
	}
    function getItems()
    {
        $items=parent::getItems();
		require_once JPATH_ROOT.'/components/com_website/helpers/website.php';
        $items=websiteHelperFrontEnd::setKeyWebsite($items);
        return $items;
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
		// Load the filter state.
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$accessId = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', null, 'int');
		$this->setState('filter.access', $accessId);

		$state = $this->getUserStateFromRequest($this->context . '.filter.enabled', 'filter_enabled', '', 'string');
		$this->setState('filter.enabled', $state);
        $website_id = $this->getUserStateFromRequest($this->context . '.filter.website_id', 'filter_website_id', '', 'int');
		$this->setState('filter.website_id', $website_id);
		$folder = $this->getUserStateFromRequest($this->context . '.filter.folder', 'filter_folder', null, 'cmd');
		$this->setState('filter.folder', $folder);

		$language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		// Load the parameters.
		$params = JComponentHelper::getParams('com_supperadmin');
		$this->setState('params', $params);
		// List state information.


    }


	/**
	 * Returns an object list
	 *
	 * @param   string The query
	 * @param   int    Offset
	 * @param   int    The number of records
	 * @return  array
	 */
	protected function _getList($query, $limitstart = 0, $limit = 0)
	{
		$search = $this->getState('filter.search');
		$ordering = $this->getState('list.ordering', 'ordering');
		if ($ordering == 'name' || (!empty($search) && stripos($search, 'id:') !== 0))
		{
			$this->_db->setQuery($query);
			$result = $this->_db->loadObjectList();
			$this->translate($result);
			if (!empty($search))
			{
				foreach ($result as $i => $item)
				{
					if (!preg_match("/$search/i", $item->name))
					{
						unset($result[$i]);
					}
				}
			}

			$direction = ($this->getState('list.direction') == 'desc') ? -1 : 1;
			JArrayHelper::sortObjects($result, $ordering, $direction, true, true);

			$total = count($result);
			$this->cache[$this->getStoreId('getTotal')] = $total;
			if ($total < $limitstart)
			{
				$limitstart = 0;
				$this->setState('list.start', 0);
			}
			return array_slice($result, $limitstart, $limit ? $limit : null);
		}
		else
		{
			if ($ordering == 'ordering')
			{
				$query->order('a.folder ASC');
				$ordering = 'a.ordering';
			}
			$query->order($this->_db->quoteName($ordering) . ' ' . $this->getState('list.direction'));

			if ($ordering == 'folder')
			{
				$query->order('a.ordering ASC');
			}
			$result = parent::_getList($query, $limitstart, $limit);
			return $result;
		}
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

		// Select the required fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				'a.id ,a.issystem, a.name, a.element, a.folder, a.checked_out, a.checked_out_time,' .
					' a.enabled, a.access, a.ordering'
			)
		)
			->from($db->quoteName('#__extensions') . ' AS a');

		// Join over the users for the checked out user.
		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

		// Join over the asset groups.
		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		// Join over the asset extension.
		$query->select('website.title as website_name')
			->join('LEFT', '#__website AS website ON website.id = a.website_id');

		// Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . (int) $access);
		}

        // Filter by published state
        $published = $this->getState('filter.published');
        if (is_numeric($published))
        {
            $query->where('a.enabled = ' . (int) $published);
        }
        elseif ($published === '')
        {
            $query->where('(a.enabled IN (0, 1))');
        }

		// Filter by state
		$query->where('a.state >= 0');

		// Filter by folder.
		if ($folder = $this->getState('filter.folder'))
		{
			$query->where('a.folder = ' . $db->quote($folder));
		}

		// Filter by search in name or id
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
		}
		$website_id = $this->getState('filter.website_id');
		if ($website_id)
		{
			$query->where('a.website_id = ' . $website_id);
		}


        $query->group('a.id');
        $query->order('a.name');
        echo $query->dump();
		return $query;
	}
}
