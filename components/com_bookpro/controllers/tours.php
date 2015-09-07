<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_weblinks
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package     Joomla.Site
 * @subpackage  com_weblinks
 * @since       1.5
 */
class BookproControllerTours extends JControllerForm
{
    var $_model;

    function __construct($config = array())
    {
        parent::__construct($config);
        $this->_model = $this->getModel('tours');
        $this->_controllerName = 'tours';
    }

    /**
     * Display default view - Airport list
     */
    function display()
    {
        switch ($this->getTask()) {
            case 'publish':
                $this->state($this->getTask());
                break;
            case 'unpublish':
                $this->state($this->getTask());
                break;
            case 'trash':
                $this->state($this->getTask());
                break;
            default:
                JRequest::setVar('view', 'tours');
        }

        parent::display();
    }
}
