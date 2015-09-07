<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectProviderFacebookWidgetLogin extends JFBConnectProviderFacebookWidget
{
    var $name = "Login";
    var $systemName = "login";
    var $className = "jfbclogin";
    var $examples = array (
        '{JFBCLogin}',
        '{JFBCLogin text=Login With Facebook logout=true logout_url=http://www.sourcecoast.com}'
    );

    protected function getTagHtml()
    {
        $text = $this->getParamValue('text');
        $showLogoutButton = $this->getParamValueEx('logout', null, 'boolean', 'false');
        $logoutUrl = $this->getParamValueEx('logout_url', null, null, JURI::root());

        $user = JFactory::getUser();
        if ($user->guest) // Only show login button if user isn't logged in (no remapping for now)
            $fbLogin = $this->provider->getLoginButton($text);
        else
        {
            $fbLogin = ""; // return blank for registered users

            if ($showLogoutButton == 'true')
            {
                $logoutUrl = base64_encode(JRoute::_($logoutUrl, false));

                $fbLogin = '<input type="submit" name="Submit" id="jfbcLogoutButton" class="button btn btn-primary" value="'
                    . JText::_('JLOGOUT') . "\" onclick=\"javascript:jfbc.login.logout('" . $logoutUrl . "')\" />";
            }
        }

        return $fbLogin;
    }
}
