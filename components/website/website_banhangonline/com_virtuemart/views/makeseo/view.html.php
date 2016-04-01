<?php

/**
 *
 * Handle the category view
 *
 * @package    VirtueMart
 * @subpackage
 * @author RolandD
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: view.html.php 6504 2012-10-05 09:40:59Z alatak $
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

// Load the view framework
if (!class_exists('VmView')) require(JPATH_VM_SITE . DS . 'helpers' . DS . 'vmview.php');

/**
 * Handle the category view
 *
 * @package VirtueMart
 * @author RolandD
 * @todo set meta data
 * @todo add full path to breadcrumb
 */
class VirtuemartViewMakeSeo extends VmView
{

    public function display($tpl = null)
    {
        parent::display($tpl);
    }

}
