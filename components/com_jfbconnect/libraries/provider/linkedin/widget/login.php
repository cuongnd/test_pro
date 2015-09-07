<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderLinkedinWidgetLogin extends JFBConnectWidget
{
    var $name = "Login";
    var $systemName = "login";
    var $examples = array (
        '{JLinkedLogin}',
        '{JLinkedLogin logout=true size=medium}'
    );

    protected function getTagHtml()
    {
        $showLogoutButton = $this->getParamValueEx('logout', null, 'boolean', 'false');
        $buttonSize = $this->getParamValue('size');

        $buttonHtml = '';
        $user = JFactory::getUser();
        if ($user->guest) // Only show login button if user isn't logged in (no remapping for now)
        {
            SCStringUtilities::loadLanguage('com_jlinked');
            $loginText = JText::_('COM_JLINKED_LOGIN_USING_LINKEDIN');

            $this->provider->needsCss = true;
            $buttonHtml .= '<div class="jLinkedLogin"><a href="javascript:void(0)" onclick="jlinked.login.login();"><span class="jlinkedButton' . $buttonSize . '"></span><span class="jlinkedLoginButton' . $buttonSize . '">' . $loginText . '</span></a></div>';
        } else
        {
            if ($showLogoutButton == 'true')
            {
                $buttonHtml .= $this->provider->getLogoutButton();
            }
        }
        return $buttonHtml;
    }
}
