<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Access
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

jimport('joomla.utilities.arrayhelper');

/**
 * Class that handles all access authorisation routines.
 *
 * @package     Joomla.Platform
 * @subpackage  Access
 * @since       11.1
 */
class JAccess
{
	/**
	 * Array of view levels
	 *
	 * @var    array
	 * @since  11.1
	 */
	protected static $viewLevels = array();

	protected static $authorised = array();

	/**
	 * Array of rules for the asset
	 *
	 * @var    array
	 * @since  11.1
	 */
	protected static $assetRules = array();

	/**
	 * Array of user groups.
	 *
	 * @var    array
	 * @since  11.1
	 */
	protected static $userGroups = array();

	/**
	 * Array of user group paths.
	 *
	 * @var    array
	 * @since  11.1
	 */
	protected static $userGroupPaths = array();

	/**
	 * Array of cached groups by user.
	 *
	 * @var    array
	 * @since  11.1
	 */
	protected static $groupsByUser = array();

	/**
	 * Method for clearing static caches.
	 *
	 * @return  void
	 *
	 * @since   11.3
	 */
	public static function clearStatics()
	{
		self::$viewLevels = array();
		self::$assetRules = array();
		self::$userGroups = array();
		self::$userGroupPaths = array();
		self::$groupsByUser = array();
	}

	/**
	 * Method to check if a user is authorised to perform an action, optionally on an asset.
	 *
	 * @param   integer  $userId  Id of the user for which to check authorisation.
	 * @param   string   $action  The name of the action to authorise.
	 * @param   mixed    $asset   Integer asset id or the name of the asset as a string.  Defaults to the global asset node.
	 *
	 * @return  boolean  True if authorised.
	 *
	 * @since   11.1
	 */
	public static function check($userId, $action, $asset = null)
	{
		// Sanitise inputs.
		$userId = (int) $userId;

		$action = strtolower(preg_replace('#[\s\-]+#', '.', trim($action)));
		$asset = strtolower(preg_replace('#[\s\-]+#', '.', trim($asset)));

		// Default to the root asset node.
		if (empty($asset))
		{
			$db = JFactory::getDbo();
			$assets = JTable::getInstance('Asset', 'JTable', array('dbo' => $db));
			$rootId = $assets->getRootId();

			$asset = $rootId;
		}

		// Get the rules for the asset recursively to root if not already retrieved.
		if (empty(self::$assetRules[$asset]))
		{
			self::$assetRules[$asset] = self::getAssetRules($asset, true);
		}

		// Get all groups against which the user is mapped.
		$identities = self::getGroupsByUser($userId);
		array_unshift($identities, $userId * -1);

		return self::$assetRules[$asset]->allow($action, $identities);
	}

	/**
	 * Method to check if a group is authorised to perform an action, optionally on an asset.
	 *
	 * @param   integer  $groupId  The path to the group for which to check authorisation.
	 * @param   string   $action   The name of the action to authorise.
	 * @param   mixed    $asset    Integer asset id or the name of the asset as a string.  Defaults to the global asset node.
	 *
	 * @return  boolean  True if authorised.
	 *
	 * @since   11.1
	 */
	public static function checkGroup($groupId, $action, $asset = null)
	{
		// Sanitize inputs.
		$groupId = (int) $groupId;
		$action = strtolower(preg_replace('#[\s\-]+#', '.', trim($action)));
		$asset = strtolower(preg_replace('#[\s\-]+#', '.', trim($asset)));

		// Get group path for group
		$groupPath = self::getGroupPath($groupId);

		// Default to the root asset node.
		if (empty($asset))
		{
			// TODO: $rootId doesn't seem to be used!
			$db = JFactory::getDbo();
			$assets = JTable::getInstance('Asset', 'JTable', array('dbo' => $db));
			$rootId = $assets->getRootId();

		}

		// Get the rules for the asset recursively to root if not already retrieved.
		if (empty(self::$assetRules[$asset]))
		{
			self::$assetRules[$asset] = self::getAssetRules($asset, true);
		}

		return self::$assetRules[$asset]->allow($action, $groupPath);
	}

	/**
	 * Gets the parent groups that a leaf group belongs to in its branch back to the root of the tree
	 * (including the leaf group id).
	 *
	 * @param   mixed  $groupId  An integer or array of integers representing the identities to check.
	 *
	 * @return  mixed  True if allowed, false for an explicit deny, null for an implicit deny.
	 *
	 * @since   11.1
	 */
	protected static function getGroupPath($groupId)
	{
		// Preload all groups
		if (empty(self::$userGroups))
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true)
				->select('parent.id, parent.lft, parent.rgt')
				->from('#__usergroups AS parent')
				->order('parent.lft');
			$db->setQuery($query);
			self::$userGroups = $db->loadObjectList('id');
		}

		// Make sure groupId is valid
		if (!array_key_exists($groupId, self::$userGroups))
		{
			return array();
		}

		// Get parent groups and leaf group
		if (!isset(self::$userGroupPaths[$groupId]))
		{
			self::$userGroupPaths[$groupId] = array();

			foreach (self::$userGroups as $group)
			{
				if ($group->lft <= self::$userGroups[$groupId]->lft && $group->rgt >= self::$userGroups[$groupId]->rgt)
				{
					self::$userGroupPaths[$groupId][] = $group->id;
				}
			}
		}

		return self::$userGroupPaths[$groupId];
	}

	/**
	 * Method to return the JAccessRules object for an asset.  The returned object can optionally hold
	 * only the rules explicitly set for the asset or the summation of all inherited rules from
	 * parent assets and explicit rules.
	 *
	 * @param   mixed    $asset      Integer asset id or the name of the asset as a string.
	 * @param   boolean  $recursive  True to return the rules object with inherited rules.
	 *
	 * @return  JAccessRules   JAccessRules object for the asset.
	 *
	 * @since   11.1
	 */
	public static function getAssetRules($asset, $recursive = false)
	{
		// Get the database connection object.
		$db = JFactory::getDbo();
		$website=JFactory::getWebsite();
		// Build the database query to get the rules for the asset.
		$query = $db->getQuery(true)
			->select('a.rules')
			->from('#__assets AS a');

		// SQLsrv change
		$query->group('a.id, a.rules, a.lft');

		// If the asset identifier is numeric assume it is a primary key, else lookup by name.
		if (is_numeric($asset))
		{
			$query->where('(a.id = ' . (int) $asset . ')');
		}
		else
		{
			$query->where('(a.name = ' . $db->quote($asset) . ')');
		}

		// If we want the rules cascading up to the global asset node we need a self-join.
		if ($recursive)
		{
			$query->join('LEFT', '#__assets AS b ON b.id=a.parent_id')
				->order('a.lft');
		}
		$query->where('a.website_id='.(int)$website->website_id);
		// Execute the query and load the rules from the result.
		$db->setQuery($query);
		$result = $db->loadColumn();

		// Get the root even if the asset is not found and in recursive mode
		if (empty($result))
		{
			$db = JFactory::getDbo();
			$assets = JTable::getInstance('Asset', 'JTable', array('dbo' => $db));
			$rootId = $assets->getRootId();

			$query->clear()
				->select('rules')
				->from('#__assets')
				->where('id = ' . $db->quote($rootId))
                ->where('website_id='.(int)$website->website_id)
            ;
			$db->setQuery($query);
            $result = $db->loadResult();

			$result = array($result);
		}
		// Instantiate and return the JAccessRules object for the asset rules.
		$rules = new JAccessRules;
		$rules->mergeCollection($result);

		return $rules;
	}

	/**
	 * Method to return a list of user groups mapped to a user. The returned list can optionally hold
	 * only the groups explicitly mapped to the user or all groups both explicitly mapped and inherited
	 * by the user.
	 *
	 * @param   integer  $userId     Id of the user for which to get the list of groups.
	 * @param   boolean  $recursive  True to include inherited user groups.
	 *
	 * @return  array    List of user group ids to which the user is mapped.
	 *
	 * @since   11.1
	 */
	public static function getGroupsByUser($userId,$recursive)
	{
		$storeId = $userId . ':' . (int) $recursive;
		if (isset(self::$groupsByUser[$storeId]))
		{
			return self::$groupsByUser[$storeId];
		}

		if (!$userId) {
			$user_group_default=JUserHelper::get_user_group_default();
			self::$groupsByUser[$storeId][]=$user_group_default->id;
			return self::$groupsByUser[$storeId];
		}
		$list_user_group=JUserHelper::get_list_user_group();
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('user_usergroup_map.group_id')
			->from('#__user_usergroup_map AS user_usergroup_map')
			->where('user_usergroup_map.user_id='.(int)$userId)
		;
		$user_group_id=$db->setQuery($query)->loadResult();
		$children_list_user_group=array();
		foreach($list_user_group as $user_group)
		{
			$parent_id = $user_group->parent_id;
			$list = @$children_list_user_group[$parent_id] ? $children_list_user_group[$parent_id] : array();
			if($user_group->id!=$user_group->parent_id)
			{
				array_push($list, $user_group);
			}
			$children_list_user_group[$parent_id] = $list;
		}
		$list_user_group_id=array();
		if($recursive)
		{
			$list_user_group_id[]=$user_group_id;
		}
		if(!function_exists('sub_get_list_group_id')) {
			function sub_get_list_group_id($root_user_group_id, $children_list_user_group, &$list_user_group_id=array())
			{
				if(count($children_list_user_group[$root_user_group_id]))
				{
					foreach($children_list_user_group[$root_user_group_id] as $user_group)
					{
						$root_user_group_id=$user_group->id;
						array_push($list_user_group_id,$user_group);
						sub_get_list_group_id($root_user_group_id,$children_list_user_group,$list_user_group_id);
					}

				}
			};
		}
		sub_get_list_group_id($user_group_id, $children_list_user_group, $list_user_group_id);

		self::$groupsByUser[$storeId]=$list_user_group_id;
		return self::$groupsByUser[$storeId];
	}

	/**
	 * Method to return a list of user Ids contained in a Group
	 *
	 * @param   integer  $groupId    The group Id
	 * @param   boolean  $recursive  Recursively include all child groups (optional)
	 *
	 * @return  array
	 *
	 * @since   11.1
	 * @todo    This method should move somewhere else
	 */
	public static function getUsersByGroup($groupId, $recursive = false)
	{
		// Get a database object.
		$db = JFactory::getDbo();

		$test = $recursive ? '>=' : '=';

		// First find the users contained in the group
		$query = $db->getQuery(true)
			->select('DISTINCT(user_id)')
			->from('#__usergroups as ug1')
			->join('INNER', '#__usergroups AS ug2 ON ug2.lft' . $test . 'ug1.lft AND ug1.rgt' . $test . 'ug2.rgt')
			->join('INNER', '#__user_usergroup_map AS m ON ug2.id=m.group_id')
			->where('ug1.id=' . $db->quote($groupId));

		$db->setQuery($query);

		$result = $db->loadColumn();

		// Clean up any NULL values, just in case
		JArrayHelper::toInteger($result);

		return $result;
	}

	/**
	 * Method to return a list of view levels for which the user is authorised.
	 *
	 * @param   integer  $userId  Id of the user for which to get the list of authorised view levels.
	 *
	 * @return  array    List of view levels for which the user is authorised.
	 *
	 * @since   11.1
	 */
	public static function getAuthorisedViewLevels($userId)
	{
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('group_id')
			->from('#__user_usergroup_map')
			->where('user_id='.(int)$userId)
			;
		$list_group_user=$db->setQuery($query)->loadColumn();
		$query=$db->getQuery(true);
		$query->select('id,rules')
			->from('#__viewlevels')
			;
		$list_view_level=$db->setQuery($query)->loadObjectList();
		$list_view_level_id=array();

		foreach($list_view_level as $view_level)
		{
			$rules=$view_level->rules;
			$rules=json_decode($rules);
			foreach($rules AS $rule)
			{
				if(in_array($rule,$list_group_user))
				{
					array_push($list_view_level_id,$view_level->id);
					break;
				}
			}
		}
		return $list_view_level_id;
	}

	/**
	 * Method to return a list of actions for which permissions can be set given a component and section.
	 *
	 * @param   string  $component  The component from which to retrieve the actions.
	 * @param   string  $section    The name of the section within the component from which to retrieve the actions.
	 *
	 * @return  array  List of actions available for the given component and section.
	 *
	 * @since       11.1
	 * @deprecated  12.3 (Platform) & 4.0 (CMS)  Use JAccess::getActionsFromFile or JAccess::getActionsFromData instead.
	 * @codeCoverageIgnore
	 */
	public static function getActions($component, $section = 'component')
	{
		JLog::add(__METHOD__ . ' is deprecated. Use JAccess::getActionsFromFile or JAccess::getActionsFromData instead.', JLog::WARNING, 'deprecated');

		$actions = self::getActionsFromFile(
			JPATH_ADMINISTRATOR . '/components/' . $component . '/access.xml',
			"/access/section[@name='" . $section . "']/"
		);

		if (empty($actions))
		{
			return array();
		}
		else
		{
			return $actions;
		}
	}

	/**
	 * Method to return a list of actions from a file for which permissions can be set.
	 *
	 * @param   string  $file   The path to the XML file.
	 * @param   string  $xpath  An optional xpath to search for the fields.
	 *
	 * @return  boolean|array   False if case of error or the list of actions available.
	 *
	 * @since   12.1
	 */
	public static function getActionsFromFile($file, $xpath = "/access/section[@name='component']/")
	{
		if (!is_file($file) || !is_readable($file))
		{
			// If unable to find the file return false.
			return false;
		}
		else
		{
			// Else return the actions from the xml.
			$xml = simplexml_load_file($file);

			return self::getActionsFromData($xml, $xpath);
		}
	}

	/**
	 * Method to return a list of actions from a string or from an xml for which permissions can be set.
	 *
	 * @param   string|SimpleXMLElement  $data   The XML string or an XML element.
	 * @param   string                   $xpath  An optional xpath to search for the fields.
	 *
	 * @return  boolean|array   False if case of error or the list of actions available.
	 *
	 * @since   12.1
	 */
	public static function getActionsFromData($data, $xpath = "/access/section[@name='component']/")
	{
		// If the data to load isn't already an XML element or string return false.
		if ((!($data instanceof SimpleXMLElement)) && (!is_string($data)))
		{
			return false;
		}

		// Attempt to load the XML if a string.
		if (is_string($data))
		{
			try
			{
				$data = new SimpleXMLElement($data);
			}
			catch (Exception $e)
			{
				return false;
			}

			// Make sure the XML loaded correctly.
			if (!$data)
			{
				return false;
			}
		}

		// Initialise the actions array
		$actions = array();

		// Get the elements from the xpath
		$elements = $data->xpath($xpath . 'action[@name][@title][@description]');

		// If there some elements, analyse them
		if (!empty($elements))
		{
			foreach ($elements as $action)
			{
				// Add the action to the actions array
				$actions[] = (object) array(
					'name' => (string) $action['name'],
					'title' => (string) $action['title'],
					'description' => (string) $action['description']
				);
			}
		}

		// Finally return the actions array
		return $actions;
	}
}
