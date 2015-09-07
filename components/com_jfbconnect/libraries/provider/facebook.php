<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

JLoader::register('JFBConnectProviderFacebookCanvas', JPATH_SITE . '/components/com_jfbconnect/libraries/provider/facebook/canvas.php');
include_once(JPATH_ADMINISTRATOR . '/components/com_jfbconnect/assets/facebook-api/facebook.php');

class JFBConnectProviderFacebook extends JFBConnectProvider
{
    // Override model's getInstance to really only get the instance
    private $apiError;

    function __construct()
    {
        $this->name = 'Facebook';
        $this->usernamePrefix = "fb_";
        parent::__construct();

        $this->client = new JFBConnectProviderFacebookClient(array(
                'appId' => $this->appId,
                'secret' => $this->secretKey,
                'cookie' => true)
        );
    }

    // Deprecated. Use JFBCFactory::config();
    function getConfigModel()
    {
        JLog::add("JFBConnectProviderFacebook->getConfigModel is deprecated. Please use JFBCFactory::config()", JLog::WARNING, 'deprecated');
        return JFBCFactory::config();
    }

    function getFbClient()
    {
        JLog::add("JFBConnectFacebookLibrary getFbClient is deprecated. Use JFBCFactory::provider('facebook')->client", JLog::WARNING, 'deprecated');
        return $this->client;
    }

    function api($api, $params = null, $callAsUser = true, $method = null, $suppressErrors = false)
    {
        $this->apiError = null;
        if (!$method)
        {
            if ($params)
                $method = "POST";
            else
                $method = "GET";
        }

        if (!$callAsUser)
            $params['access_token'] = $this->appId . "|" . $this->secretKey;
        /*        else if (!$params || (is_array($params) && (!array_key_exists('access_token', $params))))
        {
            // Get the access token for the current user
            $jUser = JFactory::getUser();
            $userMapModel = new JFBConnectModelUserMap();
            $userMapModel->getData($jUser->get('id'));
            $accessToken = $userMapModel->_data->access_token;
            if ($accessToken != '' && $accessToken != null)
                $params['access_token'] = $accessToken;
        }*/

        try
        {
            if ($params != null) // Graph API call with paramters (either App call or POST call)
            $apiData = $this->client->api($api, $method, $params);
            else // Graph API call to only get data
            $apiData = $this->client->api($api);
        }
        catch (JFBCFacebookApiException $e)
        {
            $this->apiError = $e->getMessage();
            // Only display errors on the front-end if the config is set to do so
            $app = JFactory::getApplication();
            if (!$suppressErrors && ($app->isAdmin() || $this->configModel->get('facebook_display_errors')))
            {
                $app->enqueueMessage(JText::_('COM_JFBCONNECT_FB_API_ERROR') . $e->getMessage(), 'error');
            }
            $apiData = null;
        }

        return $apiData;
    }

    public function getLastError()
    {
        $return = $this->apiError;
        $this->apiError = null;
        return $return;
    }

    function rest($params, $callAsUser = true)
    {
        if (!$callAsUser)
            $params['access_token'] = $this->appId . "|" . $this->secretKey;

        try
        {
            $result = $this->client->api($params);
        }
        catch (JFBCFacebookApiException $e)
        {
            // Only display errors on the front-end if the config is set to do so
            $app = JFactory::getApplication();
            if ($app->isAdmin() || $this->configModel->get('facebook_display_errors'))
            {
                $app->enqueueMessage(JText::_('COM_JFBCONNECT_FB_API_ERROR') . $e->getMessage(), 'error');
            }
            $result = null;
        }

        // This should be decoded by the Facebook api, but for some reason, it returns not perfect
        // JSON encoding (difference between admin.getAppProperties and a FQL query
        // So, check if we're just getting a string and try a 2nd JSON decode, which seems to work.
        // .. ugh.
        if (is_string($result))
            $result = json_decode($result, true);

        return $result;
    }

    private function getLoginButtonJavascript($buttonSize = "medium", $showFaces = "false", $maxRows = '')
    {
        $perms = $this->profile->getRequiredScope();
        if ($perms != "")
            $perms = 'data-scope="' . $perms . '"'; // OAuth2 calls them 'scope'

        if ($showFaces == "1" || $showFaces == "true")
            $showFaces = 'data-show-faces="true" ';
        else
            $showFaces = 'data-show-faces="false" ';

        if ($maxRows != "")
            $maxRows = 'data-max-rows="' . $maxRows . '" ';

        SCStringUtilities::loadLanguage('com_jfbconnect');
        return '<div class="fb-login-button" '
        . 'data-size="' . $buttonSize . '" '
        . $showFaces
        . $maxRows
        . $perms
        . ' onlogin="javascript:jfbc.login.facebook_onlogin();">'
        . JText::_('COM_JFBCONNECT_LOGIN_USING_FACEBOOK')
        . '</div>';
    }

    function getLogoutButton()
    {
        SCStringUtilities::loadLanguage('com_jfbconnect');
        $logoutStr = JText::_('COM_JFBCONNECT_LOGOUT');

        return '<input type="submit" name="Submit" id="jfbcLogoutButton" class="button btn btn-primary" value="' . $logoutStr . '" onclick="javascript:jfbc.login.logout_button_click()" />';
    }

    function loginButton($params = null)
    {
        $jfbcLogin = "";
        if ($this->appId != "") // Basic check to make sure something is set and the Google Login has a chance of working
        {
            if (isset($params['buttonType']) && $params['buttonType'] == 'javascript')
            {
                $buttonSize = $params['buttonSize'];
                $loginButton = $this->getLoginButtonJavascript($buttonSize);
                $jfbcLogin = '<div class="jfbcLogin">' . $loginButton . '</div>';
            }
            else
                $jfbcLogin = $this->getLoginButtonWithImage($params, 'jfbcLogin', 'sc_fblogin');
        }
        return $jfbcLogin;
    }

    function connectButton($params)
    {
        $jfbcLogin = '';
        $userData = JFBCFactory::usermap()->getUser(JFactory::getUser()->id, strtolower($this->name))->_data;
        if ($this->appId != "" && empty($userData->provider_user_id))
        {
            if ($params['buttonType'] == 'javascript')
            {
                $connectText = $params['buttonText'];

                $perms = $this->profile->getRequiredScope();
                if ($perms != "")
                    $perms = 'data-scope="' . $perms . '"'; // OAuth2 calls them 'scope'

                $jfbcLogin = '<div class="fb-connect-user">';
                $jfbcLogin .= '<div class="fb-login-button" onlogin="javascript:jfbc.login.provider(\'facebook\');" ' . $perms . '>' . $connectText . '</div>';
                $jfbcLogin .= '</div>';
            }
            else
                $jfbcLogin = $this->getLoginButtonWithImage($params, 'jfbcConnect', 'sc_facebookconnect');
        }

        return $jfbcLogin;
    }

    /* getFbUserId
    * Use getProviderUserId instead
    * @deprecated
    */
    function getFbUserId()
    {
        return $this->getProviderUserId();
    }

    /* getProviderUserId
    * The new way to get the userId from Facebook as of v5.1. This is the ID from Facebook even if the user is not logged into Joomla.
    */
    function getProviderUserId()
    {
        return $this->client->getUser();
    }

    /* getMappedUserId
    * Gets the FB user id of user logged into Facebook if they have a usermapping to a Joomla user
    * Returns null if user is not mapped (or not logged into Facebook).
    */
    function getMappedFbUserId()
    {
        JLog::add('getMappedFbUserId is deprecated. Use getMappedUserId', JLog::WARNING, 'deprecated');
        return $this->getMappedUserId();
    }

    public function userIsConnected()
    {
        static $connected = null;
        if ($connected == null)
            $connected = $this->getMappedUserId() == null ? false : true;

        return $connected;
    }

    function setFacebookMessage($message)
    {
        if ($message)
        {
            try
            {
                $liveMessage = '';

                $response = $this->api('/me/feed');
                if (isset($response['data']) && isset($response['data'][0]) && isset($response['data'][0]['message']))
                    $liveMessage = $response['data'][0]['message'];

                $newMessage = is_array($message) && isset($message['message']) ? $message['message'] : $message;

                if ($liveMessage != $newMessage)
                {
                    if (is_array($message))
                        $response = $this->api('/me/feed', $message);
                    else
                        $response = $this->api('/me/feed', array('message' => $message));
                }
            }
            catch (JFBCFacebookApiException $e)
            {
                /*
                 Fatal error: Uncaught exception 'FacebookRestClientException' with message
                 'Updating status requires the extended permission status_update' in
                 .../com_jfbconnect/assets/facebook-api/facebookapi_php5_restlib.php:3007
                */
            }
        }
    }

    public function onBeforeLogin()
    {
        $this->client->setExtendedAccessToken();
        $this->client->getUser();

        // Ensure the cookie is deleted from the correct path
        setcookie('fbsr_' . $this->appId, '', time()-1, '/');

        parent::onBeforeLogin();
    }

    // Check that additional scope for a user exists. If not, reroute them through Facebook login to obtain it
    public function fetchNewScope($newScope)
    {
            $params = array();
            $params['scope'] = implode(',', $newScope);
            $params['redirect_uri'] = JUri::root() . 'index.php?option=com_jfbconnect&task=authenticate.callback&provider=facebook';
            $redirect = $this->client->getLoginUrl($params);
            JFactory::getApplication()->redirect($redirect);
    }

    // Return an array of valid scope
    public function getUserScope($uid)
    {
        // get current scope for the user
        $return = array();
        $params['access_token'] = JFBCFactory::usermap()->getUserAccessToken($uid, 'facebook');
        $currentScope = $this->api('/' . $uid . '/permissions', $params, true, 'GET');
        if (isset($currentScope['data']) && isset($currentScope['data'][0]))
        {
            $currentScope = $currentScope['data'][0];
            foreach ($currentScope as $scope => $val)
            {
                if ($val == 1)
                    $return[] = $scope;
            }
        }
        return $return;
    }

    public function getLocale()
    {
        $fbLocale = JFBCFactory::config()->get('facebook_language_locale');

        // Get the language to use
        if ($fbLocale == '')
        {
            $lang = JFactory::getLanguage();
            $locale = $lang->getTag();
        }
        else
        {
            $locale = $fbLocale;
        }

        $locale = str_replace("-", "_", $locale);
        return $locale;
    }

    /** System plugin triggers */
    public function onAfterInitialise()
    {
        $fbCanvas = JFBConnectProviderFacebookCanvas::getInstance();
        $fbCanvas->setupCanvas();

        // Check if this was a Notification/Request, and redirect the user appropriately
        // If so, a redirect WILL occur here!
        $jfbcRequestLibrary = JFBConnectProviderFacebookRequest::getInstance();
        $jfbcRequestLibrary->checkForNotification();
    }

    /**
     * Perform initialization of JFBConnect variables into the document. Currently adds:
     * ** The (dynamic) login/logout redirects, used by jfbconnect.js
     * ** The {scopengraphplaceholder} tag to be replaced/removed by the system plugin
     * @return none
     */
    function onAfterDispatch()
    {
        $doc = JFactory::getDocument();
        if ($doc->getType() != 'html')
            return; // Only insert javascript on HTML pages, not AJAX, RSS, etc

        $doc->addStyleSheet(JURI::base(true) . '/media/sourcecoast/css/sc_bootstrap.css');

        $app = JFactory::getApplication();
        $state = $app->getUserState('users.login.form.data', null);
        $return = null;
        if (JRequest::getCmd('option') == 'com_users' && JRequest::getCmd('view') == 'login' && is_array($state) && isset($state['return']))
            $return = urldecode($state['return']);
        if (!$return)
            $return = urldecode(base64_decode($app->input->getBase64('return', '')));
        if (!$return)
        {
            $uri = JURI::getInstance();
            $return = $uri->toString(array('path', 'query'));
            if ($return == "")
                $return = 'index.php';
        }

        $requiredPerms = $this->profile->getRequiredScope();

        $fbUserId = $this->getMappedUserId();
        $logoutJoomlaOnly = $this->configModel->get('logout_joomla_only');

        if ($fbUserId && !$logoutJoomlaOnly)
            $logoutFacebookJavascript = "jfbc.login.logout_facebook = true;";
        else
            $logoutFacebookJavascript = "jfbc.login.logout_facebook = false;";

        $user = JFactory::getUser();
        $showLoginModal = $this->configModel->get('facebook_login_show_modal');

        if ($user->guest)
            setcookie('jfbconnect_permissions_granted', '', time() - 10000, "/"); // clear the granted permissions cookie

        $debugCmd = $this->configModel->get('facebook_display_errors') ? "jfbc.debug.enable = '1';\n" : '';

        $doc->addScript(JURI::base(true) . '/components/com_jfbconnect/includes/jfbconnect.js?v=6');
        $doc->addCustomTag('<script type="text/javascript">' .
        $logoutFacebookJavascript . "\n" .
        "jfbc.base = '" . JURI::base() . "';\n" .
        "jfbc.return_url = '" . base64_encode($return) . "';\n" .
        "jfbc.login.scope = '" . $requiredPerms . "';\n" .
        "jfbc.login.show_modal = '" . $showLoginModal . "';\n" .
        "jfbc.login.use_popup = " . ($this->configModel->get('login_use_popup') ? 'true' : 'false') . ";\n" .
        "jfbc.login.auto = '" . $this->configModel->get('facebook_auto_login') . "';\n" .
        "jfbc.login.logged_in = " . (!$user->guest && JFBCFactory::usermap()->getProviderUserId($user->id, 'facebook') ? 'true' : 'false') . ";\n" .
        "jfbc.token = '" . JSession::getFormToken() . "';\n" .
        $debugCmd .
        "jfbc.init();\n" .
        "</script>");

        $doc->addCustomTag('<SCOpenGraphPlaceholder />');
    }

    public function onAfterRender()
    {
        $body = JResponse::getBody();

        // Add FB built-in and custom OG tag for namespace, if applicable
        $ogNamespaces = 'og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#';
        $appConfig = JFBCFactory::config()->get('autotune_app_config', array());
        if (array_key_exists('namespace', $appConfig))
        {
            $appNamespace = $appConfig['namespace'];
            if ($appNamespace != '')
                $ogNamespaces .= " " . $appNamespace . ': http://ogp.me/ns/fb/' . $appNamespace . '#';
        }
        $body = str_ireplace("<html ", '<html prefix="' . $ogNamespaces . '" ', $body);

        // Should the modal popup be displayed?
        $showLoginModal = JFBCFactory::config()->get('facebook_login_show_modal');
        if ($showLoginModal)
        {
            jimport('sourcecoast.utilities');
            SCStringUtilities::loadLanguage('com_jfbconnect');
            $loginModalDiv = '<div id="jfbcLoginModal" class="sourcecoast modal" style="display:none"><div class="modal-body">' . JText::_('COM_JFBCONNECT_LOGIN_POPUP') . '</div></div>';
        }
        else
            $loginModalDiv = "";

        $body = str_ireplace("</body>", $loginModalDiv . "</body>", $body);

        $locale = $this->getLocale();
        // get Event Notification subscriptions
        $subs = "\nFB.Event.subscribe('comment.create', jfbc.social.comment.create);";
        $subs .= "\nFB.Event.subscribe('edge.create', jfbc.social.like.create);";
        if (JFBCFactory::config()->get('social_notification_google_analytics'))
            $subs .= "\njfbc.social.googleAnalytics.trackFacebook();";

        $fbCanvas = JFBConnectProviderFacebookCanvas::getInstance();
        if ($fbCanvas->get('resizeEnabled', false))
            $resizeCode = "window.setTimeout(function() {\n" .
                    "  FB.Canvas.setAutoGrow();\n" .
                    "}, 250);";
        else
            $resizeCode = "";

        if ($fbCanvas->get('canvasEnabled', false))
            $canvasCode = "jfbc.canvas.checkFrame();";
        else
            $canvasCode = "";

        // Figure out if status:true should be set. When false, makes page load faster
        $user = JFactory::getUser();
        $guest = $user->guest;
        // Check cookie to make sure autologin hasn't already occurred once. If so, and we try again, there will be loops.
        $autoLoginPerformed = JRequest::getInt('jfbconnect_autologin_disable', 0, 'COOKIE');
        if (JFBCFactory::config()->get('facebook_auto_login') && $guest && !$autoLoginPerformed)
        {
            $status = 'status: true,';
            // get Event Notification subscriptions
            $subs .= "\nFB.Event.subscribe('auth.authResponseChange', function(response) {jfbc.login.facebook_onlogin();});";
        }
        else
            $status = 'status: false,';

        $debugEnabled = $this->configModel->get('facebook_display_errors');
        $script = $debugEnabled ? "all/debug.js" : "all.js";

        if ($this->appId)
            $appIdCode = "appId: '" . $this->appId . "', ";
        else
            $appIdCode = "";

        $xfbml = ($this->widgetRendered ? 'true' : 'false');

        $javascript =
                <<<EOT
<div id="fb-root"></div>
<script type="text/javascript">
    {$canvasCode}\n
    window.fbAsyncInit = function() {
    FB.init({{$appIdCode}{$status} cookie: true, xfbml: {$xfbml}});{$subs}{$resizeCode}
    };
     (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/{$locale}/{$script}";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
    </script>
EOT;
        if (preg_match('/\<body[\s\S]*?\>/i', $body, $matches))
        {
            $newBody = str_replace($matches[0], $matches[0] . $javascript, $body);
            JResponse::setBody($newBody);
        }
    }
    /** End System trigger calls */

}

class JFBConnectProviderFacebookClient extends JFBCFacebook
{
    var $provider;

    public function isAuthenticated()
    {
        return (bool)$this->getUser();
    }

    function getToken()
    {
        return $this->getAccessToken();
    }

    // We don't actually authenticate with OAuth here, we do it through the Javascript library, so nothing to do here.
    public function authenticate()
    {
        $input = JFactory::getApplication()->input;
        if ($input->getCmd('task') == 'login' && !JFBCFactory::config()->get('login_use_popup'))
        {
            $perms = JFBCFactory::provider('facebook')->profile->getRequiredScope();
            $params = array();
            $params['scope'] = $perms;
            $params['redirect_uri'] = JUri::root() . 'index.php?option=com_jfbconnect&task=authenticate.callback&provider=facebook';
            $redirect = $this->getLoginUrl($params);
            JFactory::getApplication()->redirect($redirect);
        }
        return;
    }
}

// Deprecated as of v5.1. Use JFBConnectProviderFacebook going forward
class JFBConnectFacebookLibrary extends JFBConnectProviderFacebook
{
    static public function getInstance()
    {
        return JFBCFactory::provider('facebook');
    }
}
