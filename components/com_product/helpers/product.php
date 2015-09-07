<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_product
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * product component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_product
 * @since       1.6
 */
class productHelper
{
	public static $extension = 'com_product';

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('JGLOBAL_products'),
			'index.php?option=com_product&view=products',
			$vName == 'products'
		);
		JHtmlSidebar::addEntry(
			JText::_('com_product_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_product',
			$vName == 'categories');
		JHtmlSidebar::addEntry(
			JText::_('com_product_SUBMENU_FEATURED'),
			'index.php?option=com_product&view=featured',
			$vName == 'featured'
		);
	}

	/**
	 * Applies the product tag filters to arbitrary text as per settings for current user group
	 *
	 * @param   text  $text  The string to filter
	 *
	 * @return  string  The filtered string
	 *
	 * @deprecated  4.0  Use JComponentHelper::filterText() instead.
	*/
	public static function filterText($text)
	{
		JLog::add('productHelper::filterText() is deprecated. Use JComponentHelper::filterText() instead.', JLog::WARNING, 'deprecated');

		return JComponentHelper::filterText($text);
	}
}
