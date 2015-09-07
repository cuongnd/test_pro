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
class productHelper extends  JHelperProduct
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
        JSubMenuHelper::addEntry(
            JText::_('Products'),
            'index.php?option=com_product&view=products',
            $vName == 'products'
        );
        JSubMenuHelper::addEntry(
            JText::_('Categories'),
            'index.php?option=com_categories&extension=com_product',
            $vName == 'products');
        JSubMenuHelper::addEntry(
            JText::_('ORDER_MANAGEMENT'),
            'index.php?option=com_osemsc&view=orders',
            $vName == 'orders'
        );
        JSubMenuHelper::addEntry(
            JText::_('COUPON_MANAGEMENT'),
            'index.php?option=com_osemsc&view=coupons',
            $vName == 'coupons'
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
