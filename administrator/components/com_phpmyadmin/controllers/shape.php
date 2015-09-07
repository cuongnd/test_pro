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

/**
 * Registration controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_users
 * @since       1.6
 */
class phpmyadminControllerShape extends JControllerForm
{

    function AjaxLoadTable()
    {
        $app=JFactory::getApplication();
        $table=$app->input->get('table','','string');
        $modelshape= $this->getModel();
        $modelshape->setState('table', $table);
        $view = &$this->getView('shape', 'html', 'phpMyAdminView');
        $view->setModel($modelshape , true );
        ob_start();
        $view->setLayout('default_table');
        $view->displayTable();
        $respone_array=array();
        $contents = ob_get_contents();

        ob_end_clean(); // get the callback function
        echo $contents;
        die;
    }
    function ajaxAjaxColumnTable()
    {
        $app=JFactory::getApplication();
        $table=$app->input->get('table','','string');
        $db=JFactory::getDbo();
        $columns=$db->getTableColumns($table);
        $listColumn=array();
        foreach($columns as $column=>$type)
        {
            $item=new stdClass();
            $item->name=$column;
            $item->type=$type;
            $listColumn[]=$item;
        }
        echo json_encode($listColumn);
        die;
    }
    public function getModel($name = 'shape', $prefix = 'phpMyAdminModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

}
