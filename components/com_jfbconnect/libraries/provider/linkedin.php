<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JLoader::register('JFBConnectProfileLinkedin', JPATH_SITE . '/components/com_jfbconnect/libraries/profiles/linkedin.php');

class JFBConnectProviderLinkedin extends JFBConnectProvider
{
    var $timestampOffset;

    function __construct()
    {
        $this->name = "LinkedIn";
        $this->usernamePrefix = "li_";

        parent::__construct();
    }

    function setupAuthentication()
    {
        $options = new JRegistry();
        $options->set('authurl', 'https://www.linkedin.com/uas/oauth2/authorization');
        $options->set('tokenurl', 'https://www.linkedin.com/uas/oauth2/accessToken');
        $options->set('authmethod', 'get');
        $options->set('getparam', 'oauth2_access_token');

        $headers = array();
        $headers['Content-Type'] = 'application/json';
        $headers['x-li-format'] = 'json';
        $options->set('headers', $headers);

        $options->set('scope', $this->profile->getRequiredScope());

        $this->client = new JFBConnectAuthenticationOauth2($options);

        $token = JFactory::getApplication()->getUserState('com_jfbconnect.' . strtolower($this->name) . '.token', null);
        if ($token)
        {
            $token = (array)json_decode($token);
            $this->client->setToken($token);
        }
        $this->client->initialize($this);
    }

    function fetchNewScope($scope)
    {
        $session = JFactory::getSession();
        // With LinkedIn, we can't check if a scope is granted, so we need to always fetch new scope in addition to
        // previous scope. We also need to check if we're coming back from a previous scope fetch and just proceed if so
        // That prevents authentication looping.
        $alreadyFetching = $session->get('jfbconnect.linkedin.fetchingscope', false);
        if (!$alreadyFetching)
        {
            JFactory::getSession()->set('jfbconnect.linkedin.fetchingscope', true);
            // Clear the return code that may already be set, allowing for authentication to happen again
            JFactory::getApplication()->input->set('code', null);
            $this->client->setOption('scope', $this->profile->getRequiredScope() . ',' . implode(',', $scope));
            $this->client->authenticate();
        }
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
                return '{SCLinkedinLogin size=' . $buttonSize . $renderKeyStr . '}';
            }
            else
                $googleLogin = $this->getLoginButtonWithImage($params, 'scLinkedinLogin', 'sc_lilogin');
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
            $profile = $this->profile->fetchProfile('~', 'id');
            $id = $profile->get('id');
            if (!empty($id))
                $this->set('providerUserId', $id);
            else
                $this->set('providerUserId', null);
        }
        return $this->get('providerUserId');
    }

    /*function setTimestampOffset($offset)
    {
        $this->timestampOffset = $offset;
    }*/

    function getHeadData()
    {
        $head = '';
        if ($this->needsJavascript)
        {
            $uri = JURI::getInstance();
            $scheme = $uri->getScheme();

            $inJS = '<script type="text/javascript" src="//platform.linkedin.com/in.js?async=true"></script>' . "\n";
            $initJs = "IN.init({\n" .
                "api_key: '" . $this->appId . "',\n" .
                'authorize: false' . "\n" .
                '});';

            if ($scheme == 'https')
                $initJs .= "IN.Event.on(IN,'frameworkLoaded',function(){if(/^https:\/\//i.test(location.href)){IN.ENV.images.sprite='https://www.linkedin.com/scds/common/u/img/sprite/'+IN.ENV.images.sprite.split('/').pop()}});";

            $javascript = $inJS .
                '<script type="text/javascript">' .
                $initJs .
                "</script>";

            $head .= $javascript;
        }

        return $head;
    }

}