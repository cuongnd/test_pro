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

require_once(JPATH_ADMINISTRATOR . '/components/com_jfbconnect/includes/views.php');

class JFBConnectViewProfiles extends JFBConnectAdminView
{
    function display($tpl = null)
    {
        $app = JFactory::getApplication();
        JPluginHelper::importPlugin('socialprofiles');
        $profilePlugins = $app->triggerEvent('socialProfilesGetPlugins');
        $this->assignRef('profilePlugins', $profilePlugins);

        foreach ($profilePlugins as $p)
        {
            $options = array();
            $options['control'] = "profiles_" . $p->getName();
            SCStringUtilities::loadLanguage('plg_socialprofiles_' . $p->getName(), JPATH_ADMINISTRATOR);
            $path = JPATH_SITE . '/plugins/socialprofiles/' . $p->getName();
            JForm::addFieldPath($path . '/fields/');
            $this->formLoad($p->getName(), $path . '/forms/config.xml', $options);

            $data = JFBCFactory::config()->get('profile_' . $p->getName());
            $reg = new JRegistry();
            $reg->loadString($data);
            $this->formBind($p->getName(), $reg);
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
