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
class UtilityControllerField extends JControllerForm
{

    public function ajax_save_field_params()
    {
        $app=JFactory::getApplication();
        $fields=$app->input->get('fields','','string');
        $control_id=$app->input->get('control_id',0,'int');
        $db=JFactory::getDbo();
        require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
        $table_control=new JTableUpdateTable($db,'control');
        $table_control->load($control_id);
        $table_control->fields=$fields;
        $response=new stdClass();
        $response->e=0;
        if($control_id)
        {
            if(!$table_control->store())
            {
                $response->e=1;
                $response->r=$table_control->getError();
            }else{
                $response->r="save success";
            }
        }else{
            $response->e=1;
            $response->r="control id is null";
        }
        echo json_encode($response);
        die;
    }


}
