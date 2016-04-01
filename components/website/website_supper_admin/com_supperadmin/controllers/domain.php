<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_plugins
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Plugin controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_plugins
 * @since       1.6
 */
class supperadminControllerDomain extends JControllerForm
{
    /**
     * Method override to check if you can edit an existing record.
     *
     * @param   array   $data  An array of input data.
     * @param   string  $key   The name of the key for the primary key.
     *
     * @return  boolean
     *
     * @since   3.2
     */
    protected function allowEdit($data = array(), $key = 'id')
    {

        // Initialise variables.
        $recordId = (int) isset($data[$key]) ? $data[$key] : 0;
        $user = JFactory::getUser();
        $userId = $user->get('id');

        // Check general edit permission first.
        if ($user->authorise('core.edit', 'com_plugins.plugin.' . $recordId))
        {
            return true;
        }

        // Since there is no asset tracking, revert to the component permissions.
        return parent::allowEdit($data, $key);
    }
    public function getModel($name = 'Plugin', $prefix = 'PluginsModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }

    public function enablePlugin()
    {
        $app=JFactory::getApplication();
        $enablePlugin=$app->input->get('enablePlugin',false,'boolean');
        $data_plugin_id=$app->input->get('data_plugin_id',0,'int');
        $pluginModel=$this->getModel();
        $tablePlugin=$pluginModel->getTable();
        $tablePlugin->load($data_plugin_id);
        $tablePlugin->enabled=$enablePlugin;
        $tablePlugin->store();
        die;
    }

}
