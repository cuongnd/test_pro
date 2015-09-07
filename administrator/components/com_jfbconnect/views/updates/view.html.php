<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('sourcecoast.adminHelper');

class JFBConnectViewUpdates extends JViewLegacy
{
    var $versionChecker;

    function display($tpl = null)
    {
        require_once(JPATH_COMPONENT_ADMINISTRATOR . '/assets/sourcecoast.php');

        $jfbcLibrary = JFBCFactory::provider('facebook');
        $autotuneModel = JModelLegacy::getInstance('AutoTune', 'JFBConnectModel');

        if ($jfbcLibrary->appId)
        {
            $appConfig = $autotuneModel->getAppConfig();
            if (count($appConfig) == 0 || $appConfig == "")
            {
                $app = JFactory::getApplication();
                $app->enqueueMessage(JText::sprintf('COM_JFBCONNECT_MSG_RUN_AUTOTUNE', '<a href="index.php?option=com_jfbconnect&view=autotune">AutoTune</a>'), 'error');
            }
        }

        if (defined('SC16')):
            $this->versionChecker = new sourceCoastConnect('jfbconnect_j16', 'components/com_jfbconnect/assets/images/');
        endif; //SC16
        if (defined('SC30')):
            $this->versionChecker = new sourceCoastConnect('jfbconnect_j30', 'components/com_jfbconnect/assets/images/');
        endif; //SC30

        $this->addToolbar();

        parent::display($tpl);
    }

    function addToolbar()
    {
        JToolBarHelper::title('JFBConnect', 'jfbconnect.png');
        SCAdminHelper::addAutotuneToolbarItem();
    }
}
