<?php

/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectControllerConfig extends JFBConnectController
{
    function apply()
    {
        $app = JFactory::getApplication();
        $configs = JRequest::get('POST', 4);
        $model = JFBCFactory::config();

        //$configs = JRequest::getVar('config', array(), 'post', 'array');
        $providers = JFBCFactory::getAllProviders();
        foreach ($providers as $p)
        {
            $pConfig = JRequest::getVar('jfbconnect_provider_' . $p->name, array(), 'post', 'array');
            $configs = array_merge($configs, $pConfig);
        }

        $model->saveSettings($configs);
        $app->enqueueMessage(JText::_('COM_JFBCONNECT_MSG_SETTINGS_UPDATED'));
        $this->display();
    }

    public function migrate()
    {
        $app = JFactory::getApplication();
        $migration = $app->input->getCmd('migration');
        $parts = explode('.', $migration);
        if ($parts[0])
        {
            include_once(JPATH_ADMINISTRATOR . '/components/com_jfbconnect/helpers/migrator/' . $parts[0] . '.php');
            $class = 'JFBConnectMigrator' . $parts[0];
            $migrator = new $class();
            $subtask = isset($parts[1]) ? $parts[1] : 'migrate';
            $result = $migrator->$subtask();
            if ($result)
                $app->enqueueMessage("Migration Step Complete!");
            else
                $app->enqueueMessage("There was an error with the migration.", 'error');
        }
        $this->setRedirect('index.php?option=com_jfbconnect&view=config');
    }

}