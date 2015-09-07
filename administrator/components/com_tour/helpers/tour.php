<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_redirect
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

/**
 * Redirect component helper.
 *
 * @since  1.6
 */
class TourHelper
{
	public static $extension = 'com_tour';

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void.
	 *
	 * @since   1.6
	 */
    public static function addSubmenu($vName = 'tours')
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_TOUR_SUBMENU_TOUR'),
            'index.php?option=com_tour&view=tours',
            $vName == 'folios'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_TOUR_SUBMENU_TOUR_TARIFF'),
            'index.php?option=com_tour&view=tourtariffs',
            $vName == 'categories'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_TOUR_SUBMENU_TOURSTYLE'),
            'index.php?option=com_tour&view=tourstyles',
            $vName == 'preview'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_TOUR_SUBMENU_TOURPHOTOS'),
            'index.php?option=com_tour&view=tourphotos',
            $vName == 'preview'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_TOUR_SUBMENU_TOURACTIVITYS'),
            'index.php?option=com_tour&view=touractivities',
            $vName == 'preview'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_TOUR_SUBMENU_COUNTRIES'),
            'index.php?option=com_tour&view=countries',
            $vName == 'preview'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_TOUR_SUBMENU_CITY'),
            'index.php?option=com_tour&view=cities',
            $vName == 'preview'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_TOUR_SUBMENU_HOTEL'),
            'index.php?option=com_tour&view=hotels',
            $vName == 'preview'
        );
        JHtmlSidebar::addEntry(
            JText::_('COM_TOUR_SUBMENU_HOTELPHOTO'),
            'index.php?option=com_tour&view=hotelphotos',
            $vName == 'preview'
        );
    }
	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return  JObject
	 *
	 * @deprecated  3.2  Use JHelperContent::getActions() instead
	 */



}
