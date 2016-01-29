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
class phpMyAdminControllerDataSources extends PhpmyadminController
{
    public function ajax_load_datasources()
    {
        $view = &$this->getView('DataSources', 'html', 'phpMyAdminView');
        $app = JFactory::getApplication();
        $input = $app->input;
        $respone_array = array();
        ob_start();
        JRequest::setVar('layout', 'default');
        JRequest::setVar('tpl', 'loaddatasources');

        $view->display();
        $contents = ob_get_clean();
        $respone_array[] = array(
            'key' => '.load_datasources'
        , 'contents' => $contents
        );
        echo json_encode($respone_array);
        exit();
    }

    public function ajax_copy_database_from_orther_website()
    {
        $app = JFactory::getApplication();
        $website_copy_database_name = $app->input->get('website', '', 'string');
        $website_copy_database = JFactory::getWebsite($website_copy_database_name);

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        JTable::addIncludePath(JPATH_ROOT . '/components/com_phpmyadmin/tables');
        $table_datasource = JTable::getInstance('DataSource', 'JTable');
        $query->select('id')
            ->from($table_datasource->getTableName() . ' AS datasource')
            ->where('datasource.website_id=' . $website_copy_database->website_id);
        $list_database_id = $db->setQuery($query)->loadColumn();
        $current_website = JFactory::getWebsite();
        $result = new stdClass();
        $result->e = 0;
        $result->m = JText::_('copy success');
        foreach ($list_database_id as $datasource_id) {
            $table_datasource->load($datasource_id);
            $table_datasource->id = 0;
            $table_datasource->website_id = $current_website->website_id;
            if(!$table_datasource->store())
            {
                $result->m=$table_datasource->getError();
                break;
            }

        }
        echo json_encode($result);
        die;
    }

}
