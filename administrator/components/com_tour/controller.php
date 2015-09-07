<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_templates
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Templates manager master display controller.
 *
 * @since  1.6
 */
class TourController extends JControllerLegacy
{
    protected $default_view = 'manager';
    public function display($cachable = false, $urlparams = false)
    {
        require_once JPATH_COMPONENT . '/helpers/tour.php';
        $view = $this->input->get ( 'view', 'tours' );
        $layout = $this->input->get ( 'layout', 'default' );

        parent::display();

        return $this;
    }
}
