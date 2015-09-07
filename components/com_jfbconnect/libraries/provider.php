<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('sourcecoast.utilities');

include_once(JPATH_SITE . '/components/com_jfbconnect/libraries/profile.php');

class JFBConnectProvider extends JObject
{
    var $name;
    var $appId;
    var $secretKey;

    var $auth;
    var $profile;
    var $client;
    var $usernamePrefix;

    protected $providerUserId;
    private static $libraryInstance;
    private static $cssIncluded = false;
    var $needsCss = false;
    var $needsJavascript = false;
    var $widgetRendered = false;

    function __construct()
    {
        $this->configModel = JFBCFactory::config();
        $this->appId = $this->configModel->getSetting($this->systemName . '_app_id');
        $this->secretKey = $this->configModel->getSetting($this->systemName . '_secret_key');

        $profileClass = 'JFBConnectProfile' . $this->name;
        $this->profile = new $profileClass($this);
    }

    function __get($name)
    {
        switch ($name)
        {
            case 'systemName':
                return strtolower(str_replace(" ", "-", $this->name));
            default:
                return null;
        }
    }

    /* Override this to setup specialized parameters for the OAuth client.
        Necessary in cases where the scope is needed from the profile library.. which isn't fully initialized in the provider constructor.
    */
    public function setupAuthentication()
    {
    }

    /** Channels */
    public function channel($name, $options = null)
    {
        include_once(JPATH_SITE . '/components/com_jfbconnect/libraries/provider/' . $this->systemName . '/channel/' . strtolower($name) . '.php');
        $channelName = 'JFBConnectProvider' . $this->name . 'Channel' . ucfirst($name);
        $options = $options == null ? new JRegistry() : $options;
        $channel = new $channelName($this, $options);
        return $channel;
    }

    public function getChannelsOutbound()
    {
        $channels = array();
        $path = JPATH_SITE . '/components/com_jfbconnect/libraries/provider/' . $this->systemName . '/channel/';

        if (JFolder::exists($path))
        {
            $names = JFolder::files($path, '.*.php');
            foreach ($names as $name)
            {
                $name = str_replace(".php", "", $name);
                $channel = $this->channel($name);
                if ($channel->outbound)
                    $channels[] = $channel;
            }
        }
        return $channels;
    }

    /** End Channels */

    public function getUserAccessToken($jUserId)
    {
        return JFBCFactory::usermap()->getUserAccessToken($jUserId, $this->systemName);
    }

    var $initialRegistration = false;

    function setInitialRegistration()
    {
        $this->initialRegistration = true;
    }

    public function setSessionToken()
    {
        $token = $this->client->getToken();
        JFactory::getApplication()->setUserState('com_jfbconnect.' . $this->systemName . '.token', json_encode($token));
    }

    public function onAfterInitialise()
    {

    }

    public function onAfterDispatch()
    {

    }

    public function onAfterRender()
    {

    }

    /* Scope functions */
    public function hasScope($uid, $scope)
    {
        $currentScope = $this->getUserScope($uid, $scope);
        return in_array($scope, $currentScope);
    }

    public function getUserScope($uid) {
        return array();
    }

    private function checkRequiredScope($scope)
    {
        // get current scope for the user
        $currentScope = $this->getUserScope($this->getProviderUserId());
        $neededScope = array();
        foreach ($scope as $s)
        {
            if (!in_array($s, $currentScope))
                $neededScope[] = $s;
        }
        return $neededScope;
    }

    public function fetchNewScope($scope)
    {
        return true;
    }

    function loginButton($params = null)
    {
        return "";
    }

    function connectButton($params)
    {
        $connectHtml = '';
        // This is ugly. Need a real call to get the data for a user
        $userData = JFBCFactory::usermap()->getUser(JFactory::getUser()->id, $this->systemName)->_data;
        if ($this->appId != "" && empty($userData->provider_user_id))
        {
            if ($params['buttonType'] == 'javascript')
            {
                $connectText = $params['buttonText'];
                $buttonSize = $params['buttonSize'];

                $this->needsCss = true;
                $connectHtml = '<div class="sc-' . $this->systemName . '-connect-user">';
                $connectHtml .= '<div class="sc' . $this->name . 'Login"><a href="javascript:void(0)" onclick="jfbc.login.provider(\'' . $this->systemName . '\');"><span class="sc' . ucwords($this->name) . 'Button ' . $buttonSize . '"></span><span class="sc' . ucwords($this->name) . 'LoginButton ' . $buttonSize . '">' . $connectText . '</span></a></div>';
                $connectHtml .= '</div>';
            }
            else
                $connectHtml = $this->getLoginButtonWithImage($params, 'sc' . ucwords($this->name) . 'Connect', 'sc_' . $this->systemName . 'connect');
        }

        return $connectHtml;
    }

    public function getStylesheet()
    {
        $newText = '';
        if (!self::$cssIncluded)
        {
            self::$cssIncluded = true;
            $newText = '<link rel="stylesheet" href="' . JUri::root(true) . '/components/com_jfbconnect/assets/jfbconnect.css" type="text/css" />';
        }
        return $newText;
    }

    function logoutButton($params)
    {
        return "";
    }

    function avatar($params)
    {

    }

    // This call has been replaced in v5.1 with the getLoginButtonJavascript() function
    // The new function of this is to iterate through the login providers and echo their buttons
    // This allows automatically adding the FB/Google buttons to apps that would normally add only FB
    public function getLoginButton($text = null)
    {
        $providers = JFBCFactory::getAllProviders();
        $html = "";
        foreach ($providers as $p)
            $html .= $p->loginButton();

        if ($text == null)
        {
            list(, $caller) = debug_backtrace(false);
            // Check if this is the JomSocial homepage calling us, and if so, add the JText'ed Login with string
            if (array_key_exists('class', $caller) && $caller['class'] == 'CommunityViewFrontpage')
            {
                SCStringUtilities::loadLanguage('com_jfbconnect');
                $text = JText::_('COM_JFBCONNECT_LOGIN_WITH');
            }
        }

        if ($html != "")
        {
            $text = empty($text) ? "" : '<div class="pull-left intro">' . $text . '</div>';
            $html = '<span class="sourcecoast"><div class="social-login row-fluid">' . $text . $html . '</div></span>';
        }
        return $html;
    }

    protected function getImageButton($buttonImage, $display, $alignment, $loginClass, $loginId)
    {
        SCStringUtilities::loadLanguage('com_jfbconnect');
        $buttonAltTitle = JText::_('COM_JFBCONNECT_LOGIN_USING_' . strtoupper($this->name));
        return '<div class="' . $loginClass . ' pull-' . $alignment . '"><a' . $display . ' id="' . $loginId . '" href="javascript:void(0)" onclick="jfbc.login.provider(\'' . $this->systemName . '\');"><img src="' . $buttonImage . '" alt="' . $buttonAltTitle . '" title="' . $buttonAltTitle . '"/></a></div>';
    }

    protected function getLoginButtonWithImage($params, $loginClass, $loginID)
    {
        $buttonType = isset($params['buttonType']) ? $params['buttonType'] : "icon_text_button";
        $alignment = isset($params['alignment']) ? $params['alignment'] : "left";
        $orientation = isset($params['orientation']) ? $params['orientation'] : "";

        if ($buttonType == 'icon_text_button')
        {
            $display = ' class="show"';
            $html = $this->getImageButton(JUri::root(true) . '/media/sourcecoast/images/provider/button_' . $this->systemName . '.png', $display, $alignment, $loginClass, $loginID);
        }
        else if ($buttonType == 'icon_button')
        {
            if ($orientation == 'side')
                $display = ' class="show"';
            else
                $display = '';
            $html = $this->getImageButton(JUri::root(true) . '/media/sourcecoast/images/provider/icon_' . $this->systemName . '.png', $display, $alignment, $loginClass, $loginID);
        }
        else if ($buttonType == "image_link")
        {
            $linkImage = $params[$this->systemName . 'LinkImage'];
            $display = ' class="show"';
            $html = $this->getImageButton($linkImage, $display, $alignment, $loginClass, $loginID);
        }
        else
        {
            $html = '';
        }

        return $html;
    }

    /* getProviderUserId
    * The new way to get the userId from Facebook as of v5.1
    */
    function getProviderUserId()
    {
    }

    /* getMappedUserId
     * Return the mapped userId from Joomla
     */
    public function getMappedUserId()
    {
        $jUser = JFactory::getUser();
        if ($jUser->guest)
            return null;

        $mappedUserId = JFactory::getApplication()->getUserState('com_jfbconnect.' . $this->systemName . '.mapped_user_id', null);
        if (!$mappedUserId)
        {
            $mappedUserId = $this->getProviderUserId();
            if (JFBCFactory::usermap()->getJoomlaUserId($mappedUserId, $this->systemName) != $jUser->get('id'))
                $mappedUserId = null;

            JFactory::getApplication()->setUserState('com_jfbconnect.' . $this->systemName . '.mapped_user_id', $mappedUserId);
        }
        return $mappedUserId;
    }

    /* onBeforeLogin
    * Any functional calls that the provider needs to make directly before the user is logged in (or automatically registered)
    */
    public function onBeforeLogin()
    {
        $userModel = JFBConnectModelUserMap::getUser(JFBCFactory::usermap()->getJoomlaUserId($this->getProviderUserId(), $this->systemName), $this->systemName);
        $requiredScope = $userModel->_data->params->get('required_scope', null);
        if ($requiredScope)
        {
            $checkScope = array();
            foreach ($requiredScope as $key => $val)
            {
                $checkScope[] = $key;
            }
            // This may redirect the user back through the login flow to get the specific permissions for them.
            $neededScope = $this->checkRequiredScope($checkScope);
            if (count($neededScope))
                $this->fetchNewScope($neededScope);
        }
    }

    public function checkNewMapping()
    {
        $newMappingEnabled = JFactory::getApplication()->getUserState('com_jfbconnect.' . $this->systemName . '.checkForNewMapping', false);
        if ($newMappingEnabled)
        {
            $providerId = $this->getProviderUserId();
            if ($providerId != null)
            {
                JFBCFactory::usermap()->map(JFactory::getUser()->get('id'), $providerId, $this->systemName);
            }
        }

        JFactory::getApplication()->setUserState('com_jfbconnect.' . $this->systemName . '.checkForNewMapping', null);
    }

    public function getSocialTagRenderKey()
    {
        return $this->configModel->getSetting('social_tag_admin_key');
    }

    public function getHeadData()
    {
    }
}