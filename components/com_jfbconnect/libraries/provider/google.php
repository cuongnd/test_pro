<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JLoader::register('JFBConnectProfileGoogle', JPATH_SITE . '/components/com_jfbconnect/libraries/profiles/google.php');

class JFBConnectProviderGoogle extends JFBConnectProvider
{
    function __construct()
    {
        $this->name = "Google";
        $this->usernamePrefix = "g_";

        parent::__construct();
    }

    public function setupAuthentication()
    {
        $options = new JRegistry();
        $options->set('authurl', 'https://accounts.google.com/o/oauth2/auth');
        $options->set('tokenurl', 'https://accounts.google.com/o/oauth2/token');

        $scope = 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me';
        if (JFBCFactory::config()->getSetting('google_openid_fallback'))
            $scope .= " email profile";
        $options->set('scope', $scope);

        $this->client = new JFBConnectAuthenticationOauth2($options);

        $token = JFactory::getApplication()->getUserState('com_jfbconnect.' . strtolower($this->name) . '.token', null);
        if ($token)
        {
            $token = (array)json_decode($token);
            $this->client->setToken($token);
        }
        $this->client->initialize($this);
    }

    function loginButton($params = null)
    {
        $googleLogin = "";
        if ($this->appId != "") // Basic check to make sure something is set and the Google Login has a chance of working
        {
            if (isset($params['buttonType']) && $params['buttonType'] == 'javascript')
            {
                $buttonSize = $params['buttonSize'];
                $renderKey = $this->getSocialTagRenderKey();
                $renderKeyStr = $renderKey != "" ? " key=" . $renderKey : "";
                return '{SCGoogleLogin size=' . $buttonSize . $renderKeyStr . '}';
            }
            else
                $googleLogin = $this->getLoginButtonWithImage($params, 'scGoogleLogin', 'sc_gologin');
        }

        return $googleLogin;
    }

    /* getProviderUserId
    * Gets the provider User IdFacebook. This is regardless of whether they are mapped to an
    *  existing Joomla account.
    */
    function getProviderUserId()
    {
        if ($this->get('providerUserId', null) == null)
        {
            $profile = $this->profile->fetchProfile('me', 'id');
            $id = $profile->get('id');
            if (!empty($id))
                $this->set('providerUserId', $id);
            else
                $this->set('providerUserId', null);
        }
        return $this->get('providerUserId');
    }

    public function getHeadData()
    {
        $head = '';
        if ($this->needsJavascript)
        {
            $uri = JURI::getInstance();
            $scheme = $uri->getScheme();

            $javascript = "<script type=\"text/javascript\">
                  (function() {
                    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                    po.src = '" . $scheme . "://apis.google.com/js/plusone.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                  })();
                </script>";

            $head .= $javascript;
        }

        return $head;
    }

}