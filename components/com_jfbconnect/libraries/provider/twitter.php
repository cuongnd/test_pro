<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JLoader::register('JFBConnectProviderTwitterOauth1', JPATH_SITE . '/components/com_jfbconnect/libraries/provider/twitter/oauth1.php');

JLoader::register('JFBConnectProfileTwitter', JPATH_SITE . '/components/com_jfbconnect/libraries/profiles/twitter.php');

class JFBConnectProviderTwitter extends JFBConnectProvider
{
    function __construct()
    {
        $this->name = "Twitter";
        $this->usernamePrefix = "t_";
        parent::__construct();
    }

    function setupAuthentication()
    {
        $this->options = new JRegistry();
        $this->options->def('accessTokenURL', 'https://api.twitter.com/oauth/access_token');
        $this->options->def('authenticateURL', 'https://api.twitter.com/oauth/authorize');
        $this->options->def('authoriseURL', 'https://api.twitter.com/oauth/authorize');
        $this->options->def('requestTokenURL', 'https://api.twitter.com/oauth/request_token');
        $this->options->def('api.url', 'https://api.twitter.com/1.1/');
        $this->options->set('consumer_key', $this->appId);
        $this->options->set('consumer_secret', $this->secretKey);
        $this->options->set('callback', JURI::base() . 'index.php?option=com_jfbconnect&task=authenticate.callback&provider=twitter&state=' . JSession::getFormToken());
        $this->options->set('sendheaders', true); // Enabled for now. Should probably switch to force the redirect so we can just detect if user is logged in

        $this->client = new JFBConnectProviderTwitterOauth1($this->options);

        $token = JFactory::getApplication()->getUserState('com_jfbconnect.' . strtolower($this->name) . '.token', null);
        if ($token)
        {
            $token = (array)json_decode($token);
            $this->client->setToken($token);
        }
    }

    function loginButton($params = null)
    {
        $twitterLogin = "";
        if ($this->appId != "") // Basic check to make sure something is set and the Google Login has a chance of working
        {
            if (isset($params['buttonType']) && $params['buttonType'] == 'javascript')
            {
                $buttonSize = $params['buttonSize'];
                $renderKey = $this->getSocialTagRenderKey();
                $renderKeyStr = $renderKey != "" ? " key=" . $renderKey : "";
                return '{SCTwitterLogin size=' . $buttonSize . $renderKeyStr . '}';
            }
            else
                $twitterLogin = $this->getLoginButtonWithImage($params, 'scTwitterLogin', 'sc_twlogin');
        }

        return $twitterLogin;
    }

    /* getProviderUserId
    * Gets the provider User IdFacebook. This is regardless of whether they are mapped to an
    *  existing Joomla account.
    */
    function getProviderUserId()
    {
        $app = JFactory::getApplication();
        if ($app->getUserState('com_jfbconnect.twitter.provider_id', null) == null)
        {
            $token = $this->client->getToken();
            if (empty($token))
                return false;
            $creds = $this->client->verifyCredentials();
            if ($creds->code == 200)
            {
                $id = $creds->body->id;
            }

            if (!empty($id))
                $app->setUserState('com_jfbconnect.twitter.provider_id', $id);
//                $this->set('providerUserId', $id);
            else
                $app->setUserState('com_jfbconnect.twitter.provider_id', null);
        }
        return $app->getUserState('com_jfbconnect.twitter.provider_id');
    }

    public function getHeadData()
    {
        $head = '';
        if ($this->needsJavascript)
        {
            $uri = JURI::getInstance();
            $scheme = $uri->getScheme();

            $javascript = '<script src="' . $scheme . '://platform.twitter.com/widgets.js"></script>';

            $head .= $javascript;
        }

        return $head;
    }

    public function getUserScope($uid)
    {
        $scope = array();
        $creds = $this->client->verifyCredentials();
        if ($creds->code == 200)
        {
            $access = $creds->headers['x-access-level'];
            if ($access == 'read-write')
            {
                $scope[] = 'read';
                $scope[] = 'write';
            }
            else
                $scope[] = $access;
        }
        return $scope;
    }

    public function fetchNewScope($scope)
    {
        if (count($scope) > 0)
        {
            // Start the authentication process over and get the newly requested scope
            $this->client->resetConnection();
            JFactory::getApplication()->setUserState('com_jfbconnect.' . strtolower($this->name) . '.token', null);

            $this->options->set('x_auth_access_type', $scope[0]); // Really, this can only be 'write' since we already request read

            $this->client->authenticate();
        }
    }
}