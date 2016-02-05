<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Module
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

/**
 * Module helper class
 *
 * @package     Joomla.Libraries
 * @subpackage  Module
 * @since       1.5
 */
abstract class JModuleHelper
{
	/**
	 * Get module by name (real, eg 'Breadcrumbs' or folder, eg 'mod_breadcrumbs')
	 *
	 * @param   string  $name   The name of the module
	 * @param   string  $title  The title of the module, optional
	 *
	 * @return  object  The Module object
	 *
	 * @since   1.5
	 */
	public static function &getModule($name, $title = null,$screensize='')
	{
		$result = null;
		$modules =& static::load();
		$total = count($modules);
		for ($i = 0; $i < $total; $i++)
		{
			if($screensize!=''&&strtolower($modules[$i]->screensize)!=strtolower($screensize))
			{

				continue;
			}
			// Match the name of the module
			if ($modules[$i]->name == $name || $modules[$i]->module == $name)
			{
				// Match the title if we're looking for a specific instance of the module
				if (!$title || $modules[$i]->title == $title)
				{
					// Found it
					$result = &$modules[$i];
					break;
				}
			}
		}

		// If we didn't find it, and the name is mod_something, create a dummy object
		if (is_null($result) && substr($name, 0, 4) == 'mod_')
		{
			$result            = new stdClass;
			$result->id        = 0;
			$result->title     = '';
			$result->module    = $name;
			$result->position  = '';
			$result->content   = '';
			$result->showtitle = 0;
			$result->control   = '';
			$result->params    = '';
		}

		return $result;
	}
	public static function &getModuleById($id)
	{
		$result = null;
		$modules =& static::load();

		$total = count($modules);

		for ($i = 0; $i < $total; $i++)
		{
			// Match the name of the module
			if ($modules[$i]->id == $id)
			{
				$result = &$modules[$i];
				break;
			}
		}

		return $result;
	}

	/**
	 * Get modules by position
	 *
	 * @param   string  $position  The position of the module
	 *
	 * @return  array  An array of module objects
	 *
	 * @since   1.5
	 */
	public static function &getModules($position,$creensize='')
	{
        //JModuleHelper::modifyModuleShowInMenuItem();
		$position = strtolower($position);
		$result = array();
		$input  = JFactory::getApplication()->input;

		$modules =& static::load();

		$total = count($modules);

		for ($i = 0; $i < $total; $i++)
		{
			if ($modules[$i]->position == $position&&strtolower($modules[$i]->creensize) == strtolower($creensize))
			{
				$result[] = &$modules[$i];
			}
		}

		if (count($result) == 0)
		{
			if ($input->getBool('tp') && JComponentHelper::getParams('com_templates')->get('template_positions_display'))
			{
				$result[0] = static::getModule('mod_' . $position);
				$result[0]->title = $position;
				$result[0]->content = $position;
				$result[0]->position = $position;
			}
		}
		return $result;
	}

	/**
	 * Checks if a module is enabled. A given module will only be returned
	 * if it meets the following criteria: it is enabled, it is assigned to
	 * the current menu item or all items, and the user meets the access level
	 * requirements.
	 *
	 * @param   string  $module  The module name
	 *
	 * @return  boolean See description for conditions.
	 *
	 * @since   1.5
	 */
	public static function isEnabled($module)
	{
		$result = static::getModule($module);

		return (!is_null($result) && $result->id !== 0);
	}

	/**
	 * Render the module.
	 *
	 * @param   object  $module   A module object.
	 * @param   array   $attribs  An array of attributes for the module (probably from the XML).
	 *
	 * @return  string  The HTML content of the module output.
	 *
	 * @since   1.5
	 */
	public static function renderModule($module, $attribs = array())
	{
		static $chrome;
		$config=JFactory::getConfig();
		$admin_load_module=$config->get('admin_load_module',0);
        $input=JFactory::getApplication()->input;
        $only_module=$input->get('only_module','','string');
        if($only_module!='')
        {
            $modules=explode(',',$only_module);
            if(!in_array($module->id,$modules))
            {
                return;
            }
        }

		// Check that $module is a valid module object
		if (!is_object($module) || !isset($module->module) || !isset($module->params))
		{
			if (defined('JDEBUG') && JDEBUG)
			{
				JLog::addLogger(array('text_file' => 'jmodulehelper.log.php'), JLog::ALL, array('modulehelper'));
				JLog::add('JModuleHelper::renderModule($module) expects a module object', JLog::DEBUG, 'modulehelper');
			}

			return;
		}

		if (defined('JDEBUG'))
		{
			JProfiler::getInstance('Application')->mark('beforeRenderModule ' . $module->module . ' (' . $module->title . ')');
		}

		$app = JFactory::getApplication();

		// Record the scope.
		$scope = $app->scope;

		// Set scope to component name
		$app->scope = $module->module;
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
		// Get module parameters
		$params = new JRegistry;
		$params->loadString($module->params);



		// Get the template
		$template = $app->getTemplate();
		$website=JFactory::getWebsite();
		// Get module path
		$module->module = preg_replace('/[^A-Z0-9_\.-]/i', '', $module->module);
		$path = JPATH_BASE . '/modules/website/website_'.$website->website_id.'/' . $module->module . '/' . $module->module . '.php';

		// Load the module
		if (file_exists($path))
		{
			$lang = JFactory::getLanguage();

			// 1.5 or Core then 1.6 3PD
			$lang->load($module->module, JPATH_BASE, null, false, true) ||
				$lang->load($module->module, dirname($path), null, false, true);

			$content = '';
			ob_start();
			if($admin_load_module)
			{
				include $path;
			}else{
				echo $module->title;
			}
			$module->content = ob_get_contents() . $content;
			ob_end_clean();
		}

		// Load the module chrome functions
		if (!$chrome)
		{
			$chrome = array();
		}

		include_once JPATH_THEMES . '/system/html/modules.php';
		$chromePath = JPATH_THEMES . '/' . $template . '/html/modules.php';

		if (!isset($chrome[$chromePath]))
		{
			if (file_exists($chromePath))
			{
				include_once $chromePath;
			}

			$chrome[$chromePath] = true;
		}

		// Check if the current module has a style param to override template module style
		$paramsChromeStyle = $params->get('style');

		if ($paramsChromeStyle)
		{
			$attribs['style'] = preg_replace('/^(system|' . $template . ')\-/i', '', $paramsChromeStyle);
		}

		// Make sure a style is set
		if (!isset($attribs['style']))
		{
			$attribs['style'] = 'none';
		}

		// Dynamically add outline style
		if ($app->input->getBool('tp') && JComponentHelper::getParams('com_templates')->get('template_positions_display'))
		{
			$attribs['style'] .= ' outline';
		}

		foreach (explode(' ', $attribs['style']) as $style)
		{
			$chromeMethod = 'modChrome_' . $style;

			// Apply chrome and render module
			if (function_exists($chromeMethod))
			{
				$module->style = $attribs['style'];

				ob_start();
				$chromeMethod($module, $params, $attribs);
				$module->content = ob_get_contents();
				ob_end_clean();
			}
		}

		// Revert the scope
		$app->scope = $scope;

		if (defined('JDEBUG'))
		{
			JProfiler::getInstance('Application')->mark('afterRenderModule ' . $module->module . ' (' . $module->title . ')');
		}
        $module->content=JUtility::changeLanguageBody($module->content);
		return $module->content;
	}

	/**
	 * Get the path to a layout for a module
	 *
	 * @param   string  $module  The name of the module
	 * @param   string  $layout  The name of the module layout. If alternative layout, in the form template:filename.
	 *
	 * @return  string  The path to the module layout
	 *
	 * @since   1.5
	 */
	public static function getLayoutPath($module, $layout = 'default')
	{
		$template = JFactory::getApplication()->getTemplate();
		$defaultLayout = $layout;

		if (strpos($layout, ':') !== false)
		{
			// Get the template and file name from the string
			$temp = explode(':', $layout);
			$template = ($temp[0] == '_') ? $template : $temp[0];
			$layout = $temp[1];
			$defaultLayout = ($temp[1]) ? $temp[1] : 'default';
		}
		$website=JFactory::getWebsite();
		// Build the template and base path for the layout
		$tPath = JPATH_THEMES . '/' . $template . '/html/website/website_'.$website->website_id.'/' . $module . '/' . $layout . '.php';
		$bPath = JPATH_BASE . '/modules/website/website_'.$website->website_id.'/' . $module . '/tmpl/' . $defaultLayout . '.php';
		$dPath = JPATH_BASE . '/modules/website/website_'.$website->website_id.'/' . $module . '/tmpl/default.php';

		// If the template has a layout override use it
		if (file_exists($tPath))
		{
			return $tPath;
		}
		elseif (file_exists($bPath))
		{
			return $bPath;
		}
		else
		{
			return $dPath;
		}
	}
	/**
	 * Load published modules.
	 *
	 * @return  array
	 *
	 * @since   1.5
	 * @deprecated  4.0  Use JModuleHelper::load() instead
	 */
	public static function &_load()
	{
		return static::load();
	}

	/**
	 * Load published modules.
	 *
	 * @return  array
	 *
	 * @since   3.2
	 */
	public static function &load()
	{

		static $clean;

		if (isset($clean))
		{
			return $clean;
		}
		$app = JFactory::getApplication();
		$Itemid = $app->input->getInt('Itemid');
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());
		$lang = JFactory::getLanguage()->getTag();
		$clientId = (int) $app->getClientId();
		require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';

		$db = JFactory::getDbo();

		$query = $db->getQuery(true)
			->select('m.id, m.title, m.module,m.screensize,m.updated,m.copy_from, m.position, m.content, m.showtitle, m.params, mm.menuid')
			->from('#__modules AS m')
			->join('LEFT', '#__modules_menu AS mm ON mm.moduleid = m.id')
			->where('m.published = 1')

			->join('LEFT', '#__extensions AS e ON e.element = m.module AND e.client_id = m.client_id')
			->where('e.enabled = 1');

		$date = JFactory::getDate();
		$now = $date->toSql();
		$nullDate = $db->getNullDate();
		$query->where('(m.publish_up = ' . $db->quote($nullDate) . ' OR m.publish_up <= ' . $db->quote($now) . ')')
			->where('(m.publish_down = ' . $db->quote($nullDate) . ' OR m.publish_down >= ' . $db->quote($now) . ')')

			//->where('m.access IN (' . $groups . ')')
			->where('m.client_id = ' . $clientId)
			->where('(mm.menuid = ' . (int) $Itemid . ' OR mm.menuid <= 0)');

		// Filter by language
		if ($app->isSite() && $app->getLanguageFilter())
		{
			$query->where('m.language IN (' . $db->quote($lang) . ',' . $db->quote('*') . ')');
		}
		if($app->isSite())
		{
			$enableEditWebsite=UtilityHelper::getEnableEditWebsite();
			if($enableEditWebsite)
			{
				$screenSize=UtilityHelper::getCurrentScreenSizeEditing();
				$screenSize=UtilityHelper::getSelectScreenSize($screenSize);

			}else
			{
				$screenSize=UtilityHelper::getScreenSize();
				$screenSize=UtilityHelper::getSelectScreenSize($screenSize);


			}
			//$query->where('LOWER(m.screensize) LIKE '.$query->q(strtolower($screenSize)));
		}
        $website=JFactory::getWebsite();
        $website_id=$website->website_id;
        $domain=$website->domain;
        $query->where('m.website_id = '.$website_id);

        $query->order('m.position, m.ordering');
        $query->group('m.id');
		// Set the query
		$db->setQuery($query);
        $modules=$db->loadObjectList();

		$clean = array();
		// Apply negative selections and eliminate duplicates
		$negId = $Itemid ? -(int) $Itemid : false;
		$dupes = array();

		for ($i = 0, $n = count($modules); $i < $n; $i++)
		{
			$module = &$modules[$i];

			// The module is excluded if there is an explicit prohibition
			$negHit = ($negId === (int) $module->menuid);

			if (isset($dupes[$module->id]))
			{
				// If this item has been excluded, keep the duplicate flag set,
				// but remove any item from the cleaned array.
				if ($negHit)
				{
					unset($clean[$module->id]);
				}

				continue;
			}

			$dupes[$module->id] = true;

			// Only accept modules without explicit exclusions.
			if (!$negHit)
			{
				$module->name = substr($module->module, 4);
				$module->style = null;
				$module->position = strtolower($module->position);
				$clean[$module->id] = $module;
			}
		}
		unset($dupes);
		// Return to simple indexing that matches the query order.
		$clean = array_values($clean);
		return $clean;
	}
    public function modifyModuleShowInMenuItem()
    {
        $website=JFactory::getWebsite();
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__modules')
            ->where('client_id=0')
            ->where('copy_from!=0')
            ->where('website_id='.(int)$website->website_id)
            ;
        $db->setQuery($query);

        $listModule=$db->loadObjectList();
        $listModuleId=array();
        if(count($listModule)){

            foreach($listModule as $module)
            {
                $listModuleId[]=$module->id;
                if($module->copy_from)
                {
                    $query->clear();
                    $query->select('modulemenu.moduleid AS moduleid,modulemenu.menuid AS menuid,menu.id AS new_menu_item_id')
                        ->from('#__modules_menu AS modulemenu')
                        ->where('moduleid='.(int)$module->copy_from)
                        ->leftJoin('#__menu AS menu ON menu.copy_from=modulemenu.menuid AND menu.copy_from!=0')
                    ;
                    $db->setQuery($query);
                    $listModuleMenu=$db->loadObjectList();
                    $query->clear();
                    //$query->insert('#__a')->columns('id, title')->values('1,2')->values('3,4');
                    $query->insert('#__modules_menu');
                    $query->columns('moduleid,menuid');
                    $enable_insert=false;
                    foreach($listModuleMenu AS $moduleMenu)
                    {
                        $menu_item_id=$moduleMenu->new_menu_item_id?$moduleMenu->new_menu_item_id:0;
                        $query2=$db->getQuery(true);
                        $query2->select('moduleid')
                            ->from('#__modules_menu')
                            ->where('moduleid='.(int)$module->id)
                            ->where('menuid='.(int)$menu_item_id)
                        ;
                        $db->setQuery($query2);
                        $listModuleId2=$db->loadObjectList();
                        if(!count((array)$listModuleId2))
                        {
                            $query->values($module->id.','.$menu_item_id);
                            $enable_insert=true;
                        }
                    }
                    $db->setQuery($query);
                    if($enable_insert&&!$db->execute())
                    {
                        JError::raiseWarning(403, $db->getErrorMsg());

                    }

                }


            }
        }
        if(count((array)$listModuleId))
        {
            $listModuleId=implode(',',$listModuleId);
            $query->clear();
            $query->update('#__modules')
                ->set('copy_from=0')
                ->where('id IN('.$listModuleId.')')
            ;
            $db->setQuery($query);
            if($db->execute())
            {
                JError::raiseWarning(403, $db->getErrorMsg());

            }
        }
        $query->clear();
        $query->select('*')
            ->from('#__modules')
            ->where('updated=0')
            ->where('website_id='.(int)$website->website_id)
        ;

        $db->setQuery($query);

        $listModule=$db->loadObjectList();
        if(!count((array)$listModule))
        {
            return false;
        }
		$website=JFactory::getWebsite();
        foreach($listModule as $module)
        {
            $params = new JRegistry;
            $params->loadString($module->params);
            $helperFile=JPATH_BASE . '/modules/website/website_'.$website->website_id.'/' . $module->module . '/helper.php';
            if(!$module->updated&&file_exists($helperFile))
                require_once $helperFile;
            $moduleName=$module->module;
            $moduleName=substr($moduleName,4);
            $classHelperModule='Mod'.$moduleName.'Helper';
            if(!$module->updated && class_exists($classHelperModule))
            {
                if(method_exists($classHelperModule,'changeParam'))
                {
                    $params=call_user_func_array($classHelperModule.'::changeParam', array($params));

                    $query->clear();
                    // $query->update('#__foo')->set(...);
                    $query->update('#__modules');
                    $query->set('params='.$query->q(json_encode($params)));
                    $query->set('updated=1');
                    $query->where('id='.(int)$module->id);
                    $db->setQuery($query);

                    if(!$db->execute())
                    {
                        JError::raiseWarning(403, $db->getErrorMsg());
                    }
                }
            }
        }

    }
	/**
	 * Module cache helper
	 *
	 * Caching modes:
	 * To be set in XML:
	 * 'static'      One cache file for all pages with the same module parameters
	 * 'oldstatic'   1.5 definition of module caching, one cache file for all pages
	 *               with the same module id and user aid,
	 * 'itemid'      Changes on itemid change, to be called from inside the module:
	 * 'safeuri'     Id created from $cacheparams->modeparams array,
	 * 'id'          Module sets own cache id's
	 *
	 * @param   object  $module        Module object
	 * @param   object  $moduleparams  Module parameters
	 * @param   object  $cacheparams   Module cache parameters - id or url parameters, depending on the module cache mode
	 *
	 * @return  string
	 *
	 * @see     JFilterInput::clean()
	 * @since   1.6
	 */
	public static function moduleCache($module, $moduleparams, $cacheparams)
	{
		if (!isset($cacheparams->modeparams))
		{
			$cacheparams->modeparams = null;
		}

		if (!isset($cacheparams->cachegroup))
		{
			$cacheparams->cachegroup = $module->module;
		}

		$user = JFactory::getUser();
		$cache = JFactory::getCache($cacheparams->cachegroup, 'callback');
		$conf = JFactory::getConfig();

		// Turn cache off for internal callers if parameters are set to off and for all logged in users
		if ($moduleparams->get('owncache', null) === '0' || $conf->get('caching') == 0 || $user->get('id'))
		{
			$cache->setCaching(false);
		}

		// Module cache is set in seconds, global cache in minutes, setLifeTime works in minutes
		$cache->setLifeTime($moduleparams->get('cache_time', $conf->get('cachetime') * 60) / 60);
		$wrkaroundoptions = array('nopathway' => 1, 'nohead' => 0, 'nomodules' => 1, 'modulemode' => 1, 'mergehead' => 1);

		$wrkarounds = true;
		$view_levels = md5(serialize($user->getAuthorisedViewLevels()));

		switch ($cacheparams->cachemode)
		{
			case 'id':
				$ret = $cache->get(
					array($cacheparams->class, $cacheparams->method),
					$cacheparams->methodparams,
					$cacheparams->modeparams,
					$wrkarounds,
					$wrkaroundoptions
				);
				break;

			case 'safeuri':
				$secureid = null;

				if (is_array($cacheparams->modeparams))
				{
					$uri = JRequest::get();
					$safeuri = new stdClass;

					foreach ($cacheparams->modeparams as $key => $value)
					{
						// Use int filter for id/catid to clean out spamy slugs
						if (isset($uri[$key]))
						{
							$noHtmlFilter = JFilterInput::getInstance();
							$safeuri->$key = $noHtmlFilter->clean($uri[$key], $value);
						}
					}
				}

				$secureid = md5(serialize(array($safeuri, $cacheparams->method, $moduleparams)));
				$ret = $cache->get(
					array($cacheparams->class, $cacheparams->method),
					$cacheparams->methodparams,
					$module->id . $view_levels . $secureid,
					$wrkarounds,
					$wrkaroundoptions
				);
				break;

			case 'static':
				$ret = $cache->get(
					array($cacheparams->class,
						$cacheparams->method),
					$cacheparams->methodparams,
					$module->module . md5(serialize($cacheparams->methodparams)),
					$wrkarounds,
					$wrkaroundoptions
				);
				break;

			// Provided for backward compatibility, not really useful.
			case 'oldstatic':
				$ret = $cache->get(
					array($cacheparams->class, $cacheparams->method),
					$cacheparams->methodparams,
					$module->id . $view_levels,
					$wrkarounds,
					$wrkaroundoptions
				);
				break;

			case 'itemid':
			default:
				$ret = $cache->get(
					array($cacheparams->class, $cacheparams->method),
					$cacheparams->methodparams,
					$module->id . $view_levels . JFactory::getApplication()->input->getInt('Itemid', null),
					$wrkarounds,
					$wrkaroundoptions
				);
				break;
		}

		return $ret;
	}
}
