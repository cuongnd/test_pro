<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  COM_BOOKPRO
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package     Joomla.Administrator
 * @subpackage  COM_BOOKPRO
 * @since       1.6
 */
abstract class JHtmlBuses
{

	/**
	 * Returns a published state on a grid
	 *
	 * @param   integer  $value     The state value.
	 * @param   integer  $i         The row index
	 * @param   boolean  $enabled   An optional setting for access control on the action.
	 * @param   string   $checkbox  An optional prefix for checkboxes.
	 *
	 * @return  string        The Html code
	 *
	 * @see     JHtmlJGrid::state
	 * @since   1.7.1
	 */
	public static function state($value, $i, $enabled = true, $checkbox = 'cb')
	{
		$states	= array(
			1 => array(
				'unpublish',
				'COM_BOOKPRO_EXTENSION_PUBLISHED_ENABLED',
				'COM_BOOKPRO_HTML_UNPUBLISH_ENABLED',
				'COM_BOOKPRO_EXTENSION_PUBLISHED_ENABLED',
				true,
				'publish',
				'publish'
			),
			0 => array(
				'publish',
				'COM_BOOKPRO_EXTENSION_UNPUBLISHED_ENABLED',
				'COM_BOOKPRO_HTML_PUBLISH_ENABLED',
				'COM_BOOKPRO_EXTENSION_UNPUBLISHED_ENABLED',
				true,
				'unpublish',
				'unpublish'
			),
			-1 => array(
				'unpublish',
				'COM_BOOKPRO_EXTENSION_PUBLISHED_DISABLED',
				'COM_BOOKPRO_HTML_UNPUBLISH_DISABLED',
				'COM_BOOKPRO_EXTENSION_PUBLISHED_DISABLED',
				true,
				'warning',
				'warning'
			),
			-2 => array(
				'publish',
				'COM_BOOKPRO_EXTENSION_UNPUBLISHED_DISABLED',
				'COM_BOOKPRO_HTML_PUBLISH_DISABLED',
				'COM_BOOKPRO_EXTENSION_UNPUBLISHED_DISABLED',
				true,
				'unpublish',
				'unpublish'
			),
		);

		return JHtml::_('jgrid.state', $states, $value, $i, 'buses.', $enabled, true, $checkbox);
	}

	
}
