<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_phatthanhnghean
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Routing class from com_phatthanhnghean
 *
 * @package     Joomla.Site
 * @subpackage  com_phatthanhnghean
 * @since       3.3
 */
class phatthanhngheanRouter extends JComponentRouterBase
{
	/**
	 * Build the route for the com_phatthanhnghean component
	 *
	 * @param   array  &$query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   3.3
	 */
	public function build(&$query)
	{
		$segments = array();

		// Get a menu item based on Itemid or currently active
		$app = JFactory::getApplication();
		$menu = $app->getMenu();
		$params = JComponentHelper::getParams('com_phatthanhnghean');
		$advanced = $params->get('sef_advanced_link', 0);

		// We need a menu item.  Either the one specified in the query, or the current active one if none specified
		if (empty($query['Itemid']))
		{
			$menuItem = $menu->getActive();
			$menuItemGiven = false;
		}
		else
		{
			$menuItem = $menu->getItem($query['Itemid']);
			$menuItemGiven = true;
		}
		// Check again
		if ($menuItemGiven && isset($menuItem) && $menuItem->component != 'com_phatthanhnghean')
		{
			$menuItemGiven = false;
			unset($query['Itemid']);
		}

		if (isset($query['view']))
		{
			$view = $query['view'];
		}else if(isset($query['task'])){
			$segments[]="task";
			$segments[]=$query['task'];
			unset($query['task']);
		}else
		{
			// We need to have a view in the query or it is an invalid URL
			return $segments;
		}
		return $segments;
	}

	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 *
	 * @since   3.3
	 */
	public function parse(&$segments)
	{
		$total = count($segments);
		$vars = array();
		if($segments[0]="task")
		{
			$vars['task'] = $segments[1];
		}

		return $vars;
	}
}

/**
 * Content router functions
 *
 * These functions are proxys for the new router interface
 * for old SEF extensions.
 *
 * @deprecated  4.0  Use Class based routers instead
 */
function ContentBuildRoute(&$query)
{
	$router = new ContentRouter;

	return $router->build($query);
}

function ContentParseRoute($segments)
{
	$router = new ContentRouter;

	return $router->parse($segments);
}
