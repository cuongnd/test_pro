<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Component
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

/**
 * Component helper class
 *
 * @package     Joomla.Libraries
 * @subpackage  Component
 * @since       1.5
 */
class JComponentHelper
{
	/**
	 * The component list cache
	 *
	 * @var    array
	 * @since  1.6
	 */
	protected static $components = array();
	static $_cache = array();
    protected static $list_components = array();


	/**
	 * Get the component information.
	 *
	 * @param   string   $option  The component option.
	 * @param   boolean  $strict  If set and the component does not exist, the enabled attribute will be set to false.
	 *
	 * @return  object   An object with the information for the component.
	 *
	 * @since   1.5
	 */
	public static function getComponent($option, $strict = false)
	{


		if (!isset(static::$components[$option]))
		{
			if (static::load($option))
			{
				$result = static::$components[$option];
			}
			else
			{
				$result = new stdClass;
				$result->enabled = $strict ? false : true;
				$result->params = new JRegistry;
			}
		}
		else
		{
			$result = static::$components[$option];
		}
		return $result;
	}

	/**
	 * Checks if the component is enabled
	 *
	 * @param   string  $option  The component option.
	 *
	 * @return  boolean
	 *
	 * @since   1.5
	 */
	public static function isEnabled($option)
	{
		$result = static::getComponent($option, true);
		return $result->enabled;
	}

	/**
	 * Gets the parameter object for the component
	 *
	 * @param   string   $option  The option for the component.
	 * @param   boolean  $strict  If set and the component does not exist, false will be returned
	 *
	 * @return  JRegistry  A JRegistry object.
	 *
	 * @see     JRegistry
	 * @since   1.5
	 */
	public static function getParams($option, $strict = false)
	{
		$component = static::getComponent($option, $strict);
		if(is_string($component->params))
		{
			$temp = new JRegistry;
			$temp->loadString($component->params);
			$component->params=$temp;
		}

		return $component->params;
	}

	/**
	 * Applies the global text filters to arbitrary text as per settings for current user groups
	 *
	 * @param   string  $text  The string to filter
	 *
	 * @return  string  The filtered string
	 *
	 * @since   2.5
	 */
	public static function filterText($text)
	{
		// Filter settings
		$config     = static::getParams('com_config');
		$user       = JFactory::getUser();
		$userGroups = JAccess::getGroupsByUser($user->get('id'));

		$filters = $config->get('filters');

		$blackListTags       = array();
		$blackListAttributes = array();

		$customListTags       = array();
		$customListAttributes = array();

		$whiteListTags       = array();
		$whiteListAttributes = array();

		$whiteList  = false;
		$blackList  = false;
		$customList = false;
		$unfiltered = false;

		// Cycle through each of the user groups the user is in.
		// Remember they are included in the Public group as well.
		foreach ($userGroups as $groupId)
		{
			// May have added a group by not saved the filters.
			if (!isset($filters->$groupId))
			{
				continue;
			}

			// Each group the user is in could have different filtering properties.
			$filterData = $filters->$groupId;
			$filterType = strtoupper($filterData->filter_type);

			if ($filterType == 'NH')
			{
				// Maximum HTML filtering.
			}
			elseif ($filterType == 'NONE')
			{
				// No HTML filtering.
				$unfiltered = true;
			}
			else
			{
				// Black or white list.
				// Preprocess the tags and attributes.
				$tags           = explode(',', $filterData->filter_tags);
				$attributes     = explode(',', $filterData->filter_attributes);
				$tempTags       = array();
				$tempAttributes = array();

				foreach ($tags as $tag)
				{
					$tag = trim($tag);

					if ($tag)
					{
						$tempTags[] = $tag;
					}
				}

				foreach ($attributes as $attribute)
				{
					$attribute = trim($attribute);

					if ($attribute)
					{
						$tempAttributes[] = $attribute;
					}
				}

				// Collect the black or white list tags and attributes.
				// Each list is cummulative.
				if ($filterType == 'BL')
				{
					$blackList           = true;
					$blackListTags       = array_merge($blackListTags, $tempTags);
					$blackListAttributes = array_merge($blackListAttributes, $tempAttributes);
				}
				elseif ($filterType == 'CBL')
				{
					// Only set to true if Tags or Attributes were added
					if ($tempTags || $tempAttributes)
					{
						$customList           = true;
						$customListTags       = array_merge($customListTags, $tempTags);
						$customListAttributes = array_merge($customListAttributes, $tempAttributes);
					}
				}
				elseif ($filterType == 'WL')
				{
					$whiteList           = true;
					$whiteListTags       = array_merge($whiteListTags, $tempTags);
					$whiteListAttributes = array_merge($whiteListAttributes, $tempAttributes);
				}
			}
		}

		// Remove duplicates before processing (because the black list uses both sets of arrays).
		$blackListTags        = array_unique($blackListTags);
		$blackListAttributes  = array_unique($blackListAttributes);
		$customListTags       = array_unique($customListTags);
		$customListAttributes = array_unique($customListAttributes);
		$whiteListTags        = array_unique($whiteListTags);
		$whiteListAttributes  = array_unique($whiteListAttributes);

		// Unfiltered assumes first priority.
		if ($unfiltered)
		{
			// Dont apply filtering.
		}
		else
		{
			// Custom blacklist precedes Default blacklist
			if ($customList)
			{
				$filter = JFilterInput::getInstance(array(), array(), 1, 1);

				// Override filter's default blacklist tags and attributes
				if ($customListTags)
				{
					$filter->tagBlacklist = $customListTags;
				}

				if ($customListAttributes)
				{
					$filter->attrBlacklist = $customListAttributes;
				}
			}
			// Black lists take second precedence.
			elseif ($blackList)
			{
				// Remove the white-listed tags and attributes from the black-list.
				$blackListTags       = array_diff($blackListTags, $whiteListTags);
				$blackListAttributes = array_diff($blackListAttributes, $whiteListAttributes);

				$filter = JFilterInput::getInstance($blackListTags, $blackListAttributes, 1, 1);

				// Remove white listed tags from filter's default blacklist
				if ($whiteListTags)
				{
					$filter->tagBlacklist = array_diff($filter->tagBlacklist, $whiteListTags);
				}
				// Remove white listed attributes from filter's default blacklist
				if ($whiteListAttributes)
				{
					$filter->attrBlacklist = array_diff($filter->attrBlacklist, $whiteListAttributes);
				}
			}
			// White lists take third precedence.
			elseif ($whiteList)
			{
				// Turn off XSS auto clean
				$filter = JFilterInput::getInstance($whiteListTags, $whiteListAttributes, 0, 0, 0);
			}
			// No HTML takes last place.
			else
			{
				$filter = JFilterInput::getInstance();
			}

			$text = $filter->clean($text, 'html');
		}

		return $text;
	}

	/**
	 * Render the component.
	 *
	 * @param   string  $option  The component option.
	 * @param   array   $params  The component parameters
	 *
	 * @return  object
	 *
	 * @since   1.5
	 * @throws  Exception
	 */
	public static function renderComponent($option, $params = array())
	{
		$app = JFactory::getApplication();
        $input=$app->input;
		$config=JFactory::getConfig();
        $document=JFactory::getDocument();
		$admin_load_component=$config->get('admin_load_component',0);
        $disable_component=$input->get('disable_component',0,'int');
        if($disable_component)
            return;
		// Load template language files.
		$template = $app->getTemplate(true)->template;
		$lang = JFactory::getLanguage();
		$lang->load('tpl_' . $template, JPATH_BASE, null, false, true)
			|| $lang->load('tpl_' . $template, JPATH_THEMES . "/$template", null, false, true);


		if (empty($option))
		{
			$option='com_utility';
			$input->set('view','blank');
			//throw new Exception(JText::_('JLIB_APPLICATION_ERROR_COMPONENT_NOT_FOUND'), 404);
		}

		// Record the scope
		$scope = $app->scope;

		// Set scope to component name
		$app->scope = $option;

		// Build the component path.
		$option = preg_replace('/[^A-Z0-9_\.-]/i', '', $option);
		$file = substr($option, 4);
		// Define component path.
		$website=JFactory::getWebsite();
        $website_name=JFactory::get_website_name();
		$folder_component_path=JPATH_BASE . '/components/website/website_'.$website_name.'/' . $option;
		jimport('joomla.filesystem.folder');
		if(JFolder::exists($folder_component_path))
		{
			define('JPATH_COMPONENT', $folder_component_path);
			define('JPATH_COMPONENT_SITE', JPATH_SITE .'/components/website/website_'.$website_name.'/'. $option);
		}else {
			define('JPATH_COMPONENT', JPATH_BASE . '/components/' . $option);
			define('JPATH_COMPONENT_SITE', JPATH_SITE . '/components/' . $option);
		}


		$path = JPATH_COMPONENT . '/' . $file . '.php';
		// If component is disabled throw error
		if (!file_exists($path))
		{
			throw new Exception($option.':'.JText::_('JLIB_APPLICATION_ERROR_COMPONENT_NOT_FOUND'), 404);
		}

		// Load common and local language files.
		$lang->load($option, JPATH_BASE, null, false, true) || $lang->load($option, JPATH_COMPONENT, null, false, true);

		// Handle template preview outlining.
		$contents = null;
		// Execute the component.
		$contents = static::executeComponent($path);
		// Revert the scope
		$app->scope = $scope;

		return $contents;
	}

	/**
	 * Execute the component.
	 *
	 * @param   string  $path  The component path.
	 *
	 * @return  string  The component output
	 *
	 * @since   1.7
	 */
	public static function executeComponent($path)
	{
		$config=JFactory::getConfig();
		$app=JFactory::getApplication();

		$admin_load_component=$config->get('admin_load_component',0);
		ob_start();
		JHtml::_('jquery.framework');
		$tmpl=$app->input->get('tmpl','','string');
		$option=$app->input->get('option','','string');
		$controller=$app->input->get('controller','','string');
		$view=$app->input->get('view','','string');
		$task=$app->input->get('task','','string');
		$enable_load_component=$app->input->get('enable_load_component',0,'int');
		$ajaxgetcontent=$app->input->get('ajaxgetcontent',0,'int');
/*		echo "<pre>";
		print_r($app->input->getArray());
		echo "</pre>";
		die;*/
		if($tmpl=='sourcecss'||$tmpl=='ajax_json')
		{
			$admin_load_component=1;
		}elseif($tmpl=='tmpl'){
			$admin_load_component=1;
		}elseif($option=='com_users'&&$task=='user.logout'){
			$admin_load_component=1;
		}elseif($option=='com_users'&&$view=='login'){
			$admin_load_component=1;
		}elseif($option=='com_users'&&$task=='user.login'){
			$admin_load_component=1;
		}elseif($option=='com_utility'&&$task=='utility.aJaxChangeScreenSize'){
			$admin_load_component=1;
		}elseif($enable_load_component==1)
		{
			$admin_load_component=1;
		}
		require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
		$isAdminSite = UtilityHelper::isAdminSite();
		if(!$isAdminSite)
		{
			$admin_load_component=1;
		}
		if($ajaxgetcontent)
		{
			$admin_load_component=1;
		}
		if($admin_load_component)
		{
			require_once $path;
		}else{
			echo "show component here";
			echo "<br/>";
			echo $path;
		}
		$contents = ob_get_clean();
        jimport('joomla.utilities.utility');
        $contents=JUtility::changeLanguageBody($contents);
		$doc=JFactory::getDocument();
		require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
		$enableEditWebsite=UtilityHelper::getEnableEditWebsite();
		$html='';
		$html.=$contents;
		$app=JFactory::getApplication();
		$template=$app->getTemplate();
		if($template=='system'){
			$html=  $contents;
		}
		return $html;
	}

    public static function get_extension_id_by_component_name($website_id, $name)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('id')
            ->where('website_id='.(int)$website_id)
            ->where('element='.$query->q($name))
            ->where('type='.$query->q('component'))
            ;
        return $db->setQuery($query)->loadResult();
    }

    /**
	 * Load the installed components into the components property.
	 *
	 * @param   string  $option  The element value for the extension
	 *
	 * @return  boolean  True on success
	 *
	 * @since   1.5
	 * @deprecated  4.0  Use JComponentHelper::load() instead
	 */
	protected static function _load($option)
	{
		return static::load($option);
	}

	/**
	 * Load the installed components into the components property.
	 *
	 * @param   string  $option  The element value for the extension
	 *
	 * @return  boolean  True on success
	 *
	 * @since   3.2
	 */
	protected static function load($option)
	{

        $website=JFactory::getWebsite();

        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('components.id, components.name AS "option", components.params, components.enabled')
            ->from('#__components AS components')
			->leftJoin('#__extensions AS extensions ON extensions.id=components.extension_id')
            ->where('extensions.website_id='.(int)$website->website_id)
        ;
		$db->setQuery($query);
		$cache = JFactory::getCache('_system', 'callback');
		try
		{
			$components = $cache->get(array($db, 'loadObjectList'), array('option'), $option, false);
			/**
			 * Verify $components is an array, some cache handlers return an object even though
			 * the original was a single object array.
			 */
			if (!is_array($components))
			{
				static::$components[$option] = $components;
			}
			else
			{
				static::$components = $components;
			}
		}
		catch (RuntimeException $e)
		{
			// Fatal error.
			JLog::add(JText::sprintf('JLIB_APPLICATION_ERROR_COMPONENT_NOT_LOADING', $option, $e->getMessage()), JLog::WARNING, 'jerror');

			return false;
		}

		if (empty(static::$components[$option]))
		{
			// Fatal error.
			$error = JText::_('JLIB_APPLICATION_ERROR_COMPONENT_NOT_FOUND');
			JLog::add(JText::sprintf('JLIB_APPLICATION_ERROR_COMPONENT_NOT_LOADING', $option, $error), JLog::WARNING, 'jerror');

			return false;
		}


		$temp = new JRegistry;
		$temp->loadString(static::$components[$option]->params);
		static::$components[$option]->params = $temp;
        return true;
	}
}
