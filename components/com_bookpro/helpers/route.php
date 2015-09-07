<?php

/**
 * Defined component routes
 *
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: route.php 21 2012-07-06 04:06:17Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

class ARoute
{

	/**
	 * URL root prefix
	 *
	 * @return string URL fragment
	 */
	static function root()
	{
		$Itemid = '';
		if (IS_SITE) {
			$mainframe = &JFactory::getApplication();
			/* @var $mainframe JApplication */
			$menu = &$mainframe->getMenu();
			/* @var $menu JMenuSite */
			$active = &$menu->getActive();
			if (is_object($active) && $active->home == 1) {
				$Itemid = '&Itemid=' . $active->id;
			}
		}
		return 'index.php?option=' . OPTION . $Itemid;
	}

	/**
	 * Get route to browse list items.
	 *
	 * @param string $controller items controller name
	 * @param boolean $element add params to open element window
	 * @return string URL
	 */
	function browse($controller, $element = false, $extra = '')
	{
		return ARoute::root() . ARoute::controller($controller) . ($element ? ARoute::element() : '') . $extra;
	}

	/**
	 * URL to edit item
	 *
	 * @param string $controller item scontroller name
	 * @param $id item ID
	 * @return string URL
	 */
	static function  edit($controller, $id = null, $customParams = array())
	{
		return ARoute::root() . ARoute::controller($controller) . ARoute::task('edit') . ARoute::id($id) . ARoute::customUrl($customParams, false);
	}

	/**
	 * URL to view detail item
	 *
	 * @param string $controller item scontroller name
	 * @param $id item ID
	 * @return string URL
	 */
	function detail($controller, $id = null, $customParams = array())
	{
		return ARoute::root() . ARoute::controller($controller) . ARoute::task('detail') . ARoute::id($id) . ARoute::customUrl($customParams, false);
	}

	/**
	 * URL to view frontend page.
	 *
	 * @param string $view
	 * @param mixed $id
	 * @param string $alias entity title alias
	 * @param array $customParams next custome URL parameters
	 * @return string URL
	 */
	static function view($view, $id = null, $alias = null, $customParams = array())
	{
		return ARoute::root() . '&view=' . $view . ARoute::simpleId($id, $alias) . ARoute::customUrl($customParams, false);
	}

	/**
	 * URL part with controller param
	 *
	 * @param string $name controller name
	 * @return string URL fragment
	 */
	static function controller($name)
	{
		return '&controller=' . $name;
	}

	/**
	 * URL part with task param
	 *
	 * @param string $task task name
	 * @return string URL fragment
	 */
	static function task($task)
	{
		return '&task=' . $task;
	}

	/**
	 * URL part with id param like array
	 *
	 * @param string $id id value
	 * @return string URL fragment
	 */
	static function id($id)
	{
		return $id ? '&cid[]=' . $id : '';
	}

	/**
	 * URL part with id param
	 *
	 * @param string $id id value
	 * @param string $alias entity title alias
	 * @return string URL fragment
	 */
	static function simpleId($id, $alias)
	{
		if ($id) {
			return '&id=' . $id . ($alias ? (':' . $alias) : '');
		}
		return '';
	}

	/**
	 * Add params for open element window.
	 */
	function element()
	{
		return '&task=element&tmpl=component';
	}

	/**
	 * Get user edit route to standard Joomla! users component.
	 *
	 * @param int $id user ID
	 * @return String URL
	 */
	function editUser($id = null)
	{
		if (IS_ADMIN) {
			 
			return 'index.php?option=com_users&task=user.edit&id=' . $id;

		}

		return JRoute::_('index.php?option=com_users&view=profile');

	}

	/**
	 * Get login use route.
	 *
	 * @return String URL
	 */
	function loginUser()
	{
		return JRoute::_( 'index.php?option=com_users&view=login' );
	}

	/**
	 * Get logout user route.
	 *
	 * @return String URL
	 */
	function logoutUser()
	{
		return JRoute::_( 'index.php?option=com_users&task=user.logout');
	}

	/**
	 * Create custom URL from given params.
	 *
	 * @param array $params where key is param name and value param value
	 * @param boolean add live site URL root
	 * @return string URL
	 */
	static function customUrl($params, $root = true)
	{
		$url = $root ? ARoute::root() : '';
		foreach ($params as $param => $value) {
			if (is_array($value)) {
				$count = count($value);
				for ($i = 0; $i < $count; $i ++) {
					$url .= '&' . $param . '[]=' . $value[$i];
				}
			} else {
				$url .= '&' . $param . '=' . $value;
			}

		}
		return $url;
	}

	static function config()
	{
		return JURI::root() . 'administrator/index.php?option=com_config&controller=component&component=' . OPTION . '&path=';
	}

	/**
	 * URL to view with layout specified.
	 *
	 * @param string $view
	 * @param string $layout
	 * @return string URL
	 */
	function viewlayout($view, $layout)
	{
		return ARoute::root() . '&view=' . $view . '&layout=' . $layout;
	}

	/**
	 * Convert special HTML chars.
	 *
	 * @param string $url
	 * @return string
	 */
	function convertUrl($url)
	{
		return str_replace('&amp;', '&', $url);
	}

	/**
	 * Get URL to save payment result
	 *
	 * @param string $type payment method type alias
	 */
	function payment($type, $id, $paid)
	{
		return ARoute::root() . ARoute::controller(CONTROLLER_RESERVATION) . ARoute::task('payment') . '&type=' . $type . '&paid=' . $paid . '&cid[]=' . $id . '&hash=' . md5(session_id());
	}
	function safeURL($url)
	{
		return str_replace('&', '&amp;', $url);
	}
}

?>