<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-@CURRENT_YEAR@ by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v@VERSION@
 * @build-date      @DATE@
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('sourcecoast.adminHelper');

class JFBConnectViewJfbconnect extends JViewLegacy
{

    var $versionChecker;

    function display($tpl = null)
    {
        require_once(JPATH_COMPONENT_ADMINISTRATOR . '/assets/sourcecoast.php');

        $jfbcLibrary = JFBCFactory::provider('facebook');
        $configModel = JFBCFactory::config();
        $usermapModel = JFBCFactory::usermap();
        $autotuneModel = JModelLegacy::getInstance('AutoTune', 'JFBConnectModel');

        if ($jfbcLibrary->appId)
        {
            $appConfig = $autotuneModel->getAppConfig();
            if (count($appConfig) == 0 || $appConfig == "")
            {
                $app = JFactory::getApplication();
                $app->enqueueMessage(JText::sprintf('COM_JFBCONNECT_MSG_RUN_AUTOTUNE', '<a href="index.php?option=com_jfbconnect&view=autotune">AutoTune</a>'), 'error');
            }

            /*            $fql = "SELECT monthly_active_users, weekly_active_users, daily_active_users FROM application WHERE app_id=" . $jfbcLibrary->appId;
                        $params = array(
                            'method' => 'fql.query',
                            'query' => $fql,
                        );
                        $appStats = $jfbcLibrary->rest($params, FALSE);
                        $appStats = $appStats[0];
                        $appStats['monthly_active_users'] = isset($appStats['monthly_active_users']) && $appStats['monthly_active_users'] != ""
                                ? $appStats['monthly_active_users'] : "0";
                        $appStats['weekly_active_users'] = isset($appStats['weekly_active_users']) && $appStats['weekly_active_users'] != ""
                                ? $appStats['weekly_active_users'] : "0";
                        $appStats['daily_active_users'] = isset($appStats['daily_active_users']) && $appStats['daily_active_users'] != ""
                                ? $appStats['daily_active_users'] : "0";*/
        }

        $userCounts = array();
        foreach (JFBCFactory::getAllProviders() as $p)
            $userCounts[$p->systemName] = $usermapModel->getTotalMappings($p->systemName);

        $this->versionChecker = new sourceCoastConnect();

        $this->assignRef('configModel', $configModel);
        $this->assignRef('autotuneModel', $autotuneModel);
        $this->assignRef('jfbcLibrary', $jfbcLibrary);
        $this->assignRef('usermapModel', $usermapModel);
        $this->assignRef('userCounts', $userCounts);

        $this->addToolbar();

        parent::display($tpl);
    }

    function addToolbar()
    {
        JToolBarHelper::title('JFBConnect', 'jfbconnect.png');
        SCAdminHelper::addAutotuneToolbarItem();
    }
}
