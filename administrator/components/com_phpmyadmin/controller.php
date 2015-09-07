<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Base controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_users
 * @since       1.5
 */
class PhpmyadminController extends JControllerLegacy
{
    function renderfile()
    {
        require_once JPATH_ROOT.'/'.'administrator/components/com_utility/helpers/utility.php';
        UtilityHelper::renderFile();
    }
}
