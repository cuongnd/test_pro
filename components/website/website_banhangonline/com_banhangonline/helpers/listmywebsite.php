<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banhangonline
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * banhangonline component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_banhangonline
 * @since       1.6
 */
class listmywebsiteHelper
{
	public static $extension = 'com_banhangonline';

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string    The name of the active view.
	 */
	public static function addSubmenu($vName)
	{
		// No submenu for this component.
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return  JObject
	 *
	 * @deprecated  3.2  Use JHelperContent::getActions() instead
	 */
	public static function getActions()
	{
		// Log usage of deprecated function
		JLog::add(__METHOD__ . '() is deprecated, use JHelperContent::getActions() with new arguments order instead.', JLog::WARNING, 'deprecated');
        require_once JPATH_ROOT.DS.'components/website/website_banhangonline/com_banhangonline/helpers/banhangonline.php';
		// Get list of actions
		$result = JHelperBanhangonline::getActions('com_banhangonline');

		return $result;
	}


}
