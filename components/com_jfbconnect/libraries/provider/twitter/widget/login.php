<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderTwitterWidgetLogin extends JFBConnectWidget
{
    var $name = "Login";
    var $systemName = "login";
    var $className = "scTwitterLoginTag";

    protected function getTagHtml()
    {
        $buttonSize = $this->getParamValue('size');
        $showLogoutButton = $this->getParamValueEx('logout', null, 'boolean', 'false');
        $logoutUrl = $this->getParamValueEx('logout_url', null, null, JURI::root());

        $buttonHtml = '';
        $user = JFactory::getUser();
        if ($user->guest) // Only show login button if user isn't logged in (no remapping for now)
        {
            SCStringUtilities::loadLanguage('com_jfbconnect');
            $loginText = JText::_('COM_JFBCONNECT_LOGIN_USING_TWITTER');

            $this->provider->needsCss = true;
            $buttonHtml .= "<a href=\"javascript:void(0)\" onclick=\"jfbc.login.provider('twitter');\"><span class=\"scTwitterButton" . $buttonSize . '"></span><span class="scTwitterLoginButton' . $buttonSize . '">' . $loginText . '</span></a>';
        } else
        {
            $buttonHtml = '';
            if ($showLogoutButton == 'true')
            {
                $logoutUrl = base64_encode(JRoute::_($logoutUrl, false));

                $buttonHtml = '<input type="submit" name="Submit" id="scTwitterLogoutButton" class="button btn btn-primary" value="'
                    . JText::_('JLOGOUT') . "\" onclick=\"javascript:jfbc.login.logout_button_click('" . $logoutUrl . "')\" />";

            }
        }
        return $buttonHtml;
    }
}
