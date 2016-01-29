<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';
require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';

/**
 * Registration controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_users
 * @since       1.6
 */
class UtilityControllerParams extends JControllerForm
{

    public function ajax_get_attribute_config()
    {

        $app=JFactory::getApplication();
        $type=$app->input->get('type','','string');
        $path=$app->input->get('path','','string');
        $db=JFactory::getDbo();
        require_once JPATH_ROOT.'/'.$path;
        $type='JFormField'.$type;
        $field_type=new $type();
        $list_attribute=$field_type::get_attribute_config();
        echo json_encode($list_attribute);
        die;
    }


}
