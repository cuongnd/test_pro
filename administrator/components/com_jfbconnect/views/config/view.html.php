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

require_once(JPATH_ADMINISTRATOR . '/components/com_jfbconnect/includes/views.php');

class JFBConnectViewConfig extends JFBConnectAdminView
{
    function display($tpl = null)
    {
        JForm::addFieldPath(JPATH_ADMINISTRATOR . '/components/com_jfbconnect/models/fields');
        $this->formLoad('config', JPATH_ADMINISTRATOR . '/components/com_jfbconnect/models/forms/config.xml');
        $this->formBind('config', JFBCFactory::config()->getSettings());

        foreach (JFBCFactory::getAllProviders() as $p)
        {
            $configPath = JPATH_SITE . '/components/com_jfbconnect/libraries/provider/' . strtolower($p->name) . '/config/';
            JForm::addFieldPath($configPath . 'fields');
            $this->formLoad($p->name, $configPath . 'config.xml');
            $this->formBind($p->name, JFBCFactory::config()->getSettings());
        }
        parent::display($tpl);
    }

    function addToolbar()
    {
        JToolBarHelper::title('JFBConnect', 'jfbconnect.png');
        JToolBarHelper::apply('apply', JText::_('COM_JFBCONNECT_BUTTON_APPLY_CHANGES'));
        SCAdminHelper::addAutotuneToolbarItem();
    }
}
