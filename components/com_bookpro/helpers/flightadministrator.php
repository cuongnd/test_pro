<?php

/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Content HTML helper
 *
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @since       3.0
 */
abstract class JHtmlFlightAdministrator {

    /**
     * Show the feature/unfeature links
     *
     * @param   int      $value      The state value
     * @param   int      $i          Row number
     * @param   boolean  $canChange  Is user allowed to change?
     *
     * @return  string       HTML code
     */
    public static function featured($value = 0, $i, $canChange = true) {
        JHtml::_('bootstrap.tooltip');

        // Array of image, task, title, action
        $states = array(
            0 => array('unfeatured', 'flights.featured', 'COM_BOOKPRO_UNFEATURED', 'COM_BOOKPRO_TOGGLE_TO_FEATURE'),
            1 => array('featured', 'flights.unfeatured', 'COM_BOOKRPO_FEATURED', 'COM_BOOKPRO_TOGGLE_TO_UNFEATURE'),
        );
        $state = JArrayHelper::getValue($states, (int) $value, $states[1]);
        $icon = $state[0];

        if ($canChange) {
            $html = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[3]) . '"><i class="icon-'
                    . $icon . '"></i></a>';
        } else {
            $html = '<a class="btn btn-micro hasTooltip disabled' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><i class="icon-'
                    . $icon . '"></i></a>';
        }

        return $html;
    }

    public static function gallery($value = 0, $i, $canChange = true) {
        JHtml::_('bootstrap.tooltip');
       

        // Array of image, task, title, action
        $states = array(
            0 => array('unfeatured', 'galleries.featured', 'COM_BOOKPRO_UNFEATURED', 'COM_BOOKPRO_TOGGLE_TO_FEATURE'),
            1 => array('featured', 'galleries.unfeatured', 'COM_BOOKRPO_FEATURED', 'COM_BOOKPRO_TOGGLE_TO_UNFEATURE'),
        );
        $state = JArrayHelper::getValue($states, (int) $value, $states[1]);
        $icon = $state[0];

        if ($canChange) {
            $html = '<a href="#" onclick="return listItemTask(\'cb' . $i . '\',\'' . $state[1] . '\')" class="btn btn-micro hasTooltip' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[3]) . '"><i class="icon-'
                    . $icon . '"></i></a>';
        } else {
            $html = '<a class="btn btn-micro hasTooltip disabled' . ($value == 1 ? ' active' : '') . '" title="' . JHtml::tooltipText($state[2]) . '"><i class="icon-'
                    . $icon . '"></i></a>';
        }

        return $html;
    }

}
