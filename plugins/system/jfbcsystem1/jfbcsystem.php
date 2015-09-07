<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.event.plugin');
jimport('sourcecoast.openGraph');
jimport('sourcecoast.utilities');
jimport('sourcecoast.easyTags');
jimport('sourcecoast.articleContent');
jimport('joomla.filesystem.file');

class plgSystemJFBCSystem extends JPlugin
{
    var $tagsToReplace = array(
        'jfbcaction' => array('provider'=>'facebook', 'widget'=>'action'),
        'jfbccommentscount' => array('provider'=>'facebook', 'widget'=>'commentscount'),
        'jfbccomments' => array('provider'=>'facebook', 'widget'=>'comments'),
        'jfbcembeddedposts' => array('provider'=>'facebook', 'widget'=>'embeddedposts'),
        'jfbcfriends' => array('provider'=>'facebook', 'widget'=>'facepile'),
        'jfbcfeed' => array('provider'=>'facebook', 'widget'=>'feed'),
        'jfbcfollow' => array('provider'=>'facebook', 'widget'=>'follow'),
        'jfbclike' => array('provider'=>'facebook', 'widget'=>'like'),
        'jfbcfan' => array('provider'=>'facebook', 'widget'=>'likebox'),
        'jfbclogin' => array('provider'=>'facebook', 'widget'=>'login'),
        'jfbcrecommendationsbar' => array('provider'=>'facebook', 'widget'=>'recommendationsbar'),
        'jfbcrecommendations' => array('provider'=>'facebook', 'widget'=>'recommendations'),
        'jfbcrequest' => array('provider'=>'facebook', 'widget'=>'request'),
        'jfbcsend' => array('provider'=>'facebook', 'widget'=>'send'),
        'jfbcsharedactivity' => array('provider'=>'facebook', 'widget'=>'sharedactivity'),
        'jfbcsharedialog' => array('provider'=>'facebook', 'widget'=>'share'), //Deprecated, but still take care of old tags
        'jfbcshare' => array('provider'=>'facebook', 'widget'=>'share'),
        'jfbcsubscribe' => array('provider'=>'facebook', 'widget'=>'follow'), //Deprecated, but still take care of old tags
        'scgooglelogin' => array('provider'=>'google', 'widget'=>'login'),
        'scgoogleplusone' => array('provider'=>'google', 'widget'=>'plusone'),
        'jlinkedapply' => array('provider'=>'google', 'widget'=>'apply'),
        'jlinkedcompanyinsider' => array('provider'=>'google', 'widget'=>'companyinsider'),
        'jlinkedcompanyprofile' => array('provider'=>'google', 'widget'=>'companyprofile'),
        'jlinkedfollowcompany' => array('provider'=>'google', 'widget'=>'followcompany'),
        'jlinkedjobs' => array('provider'=>'google', 'widget'=>'jobs'),
        'jlinkedlogin' => array('provider' => 'linkedin', 'widget'=>'login'),
        'jlinkedmember' => array('provider'=>'google', 'widget'=>'member'),
        'jlinkedrecommend' => array('provider'=>'google', 'widget'=>'recommend'),
        'jlinkedshare' => array('provider'=>'linkedin', 'widget'=>'share'),
        'scpinterest' => array('provider'=>'pinterest', 'widget'=>'share'),
        'sctwitterlogin' => array('provider'=>'twitter', 'widget'=>'login'),
        'sctwittershare' => array('provider'=>'twitter', 'widget'=>'share'),
    );

    var $metadataTagsToStrip = array('JFBC', 'JLinked', 'SCTwitterShare', 'SCGooglePlusOne');

    static $cssIncluded = false;

    function __construct(& $subject, $config)
    {
        $factoryFile = JPATH_ROOT . '/components/com_jfbconnect/libraries/factory.php';
        if (!JFile::exists($factoryFile))
        {
            JFactory::getApplication()->enqueueMessage("File missing: " . $factoryFile . "<br/>Please re-install JFBConnect or disable the JFBCSystem Plugin", 'error');
            return; // Don't finish loading this plugin to prevent other errors
        }
        require_once($factoryFile);
        // Need to load this as some custom developers expect this file to already be loaded and using the old JFBCFacebookLibrary classname
        // Doing this for backward compatibility in v5.1. Remove in the future
        require_once(JPATH_SITE . '/components/com_jfbconnect/libraries/provider/facebook.php');

        parent::__construct($subject, $config);
    }

    public function onAfterInitialise()
    {
        $app = JFactory::getApplication();
        if (!$app->isAdmin())
        {
            // Need to disable Page caching so that values fetched from Facebook are not saved for the next user!
            // Do this by setting the request type to POST. In the Cache plugin, it's checked for "GET". can't be that.
            $option = JRequest::getCmd("option");
            $view = JRequest::getCmd("view");
            if ($option == 'com_jfbconnect' && $view == 'loginregister')
                $_SERVER['REQUEST_METHOD'] = 'POST';

            // Need to load our plugin group early to be able to hook into to every step after
            JPluginHelper::importPlugin('opengraph');
            JPluginHelper::importPlugin('socialprofiles');

            $providers = JFBCFactory::getAllProviders();
            foreach ($providers as $provider)
                $provider->onAfterInitialise();
        }
    }

    public function onAfterRoute()
    {
        $app = JFactory::getApplication();
        if (!$app->isAdmin())
        {
            $app = JFactory::getApplication();
            $app->triggerEvent('onOpenGraphAfterRoute');
            if ($app->getUserState('com_jfbconnect.registration.alternateflow'))
                $app->triggerEvent('socialProfilesPrefillRegistration');
        }
    }

    // Called after the component has executed and it's output is available in the buffer
    // Modules have *not* executed yet
    public function onAfterDispatch()
    {
        $app = JFactory::getApplication();
        if (!$app->isAdmin())
        {
            $providers = JFBCFactory::getAllProviders();
            foreach ($providers as $provider)
                $provider->onAfterDispatch();

            foreach ($this->metadataTagsToStrip as $metadataTag)
            {
                $this->replaceTagInMetadata($metadataTag);
            }

            $doc = JFactory::getDocument();
            if ($doc->getType() == 'html')
            {
                $doc->addCustomTag('<SourceCoastProviderJSPlaceholder />');
                if (JFBCFactory::config()->getSetting('jquery_load'))
                    $doc->addScript(JURI::base(true) . '/media/sourcecoast/js/jq-bootstrap-1.8.3.js');
            }

            //Add Login with FB button to com_users login view and mod_login
            $showLoginWithJoomla = JFBCFactory::config()->getSetting('show_login_with_joomla_reg');
            if ($showLoginWithJoomla != SC_VIEW_NONE)
            {
                SCStringUtilities::loadLanguage('com_jfbconnect');
                $login = JFBCFactory::provider('facebook')->getLoginButton(JText::_('COM_JFBCONNECT_LOGIN_WITH'));
                $registration = JFBCFactory::provider('facebook')->getLoginButton(JText::_('COM_JFBCONNECT_REGISTER_WITH'));

                SCEasyTags::extendJoomlaUserForms($login, "login", false, $showLoginWithJoomla);
                SCEasyTags::extendJoomlaUserForms($registration, "registration", false, $showLoginWithJoomla);
            }

            // Add the Open Graph links to the user edit form.
            if ($this->showOpenGraphProfileLinks() && JFBCFactory::provider('facebook')->userIsConnected())
            {
                SCStringUtilities::loadLanguage('com_jfbconnect');

                $htmlTag = '<a href="' . JRoute::_('index.php?option=com_jfbconnect&view=opengraph&layout=activity') . '">' . JText::_('COM_JFBCONNECT_TIMELINE_ACTIVITY_LINK') . '</a>';
                $htmlTag .= '<br/><a href="' . JRoute::_('index.php?option=com_jfbconnect&view=opengraph&layout=settings') . '">' . JText::_('COM_JFBCONNECT_TIMELINE_CHANGESETTINGS') . '</a>';

                SCEasyTags::extendJoomlaUserForms($htmlTag, 'profile', true, SC_VIEW_BOTTOM);
            }

            JPluginHelper::importPlugin('opengraph');
            $app->triggerEvent('onOpenGraphAfterDispatch');

            // Finally, load the Toolbar classes
            JFBCFactory::library('toolbar')->onAfterDispatch();
        }
    }

    // Called right before the page is rendered
    public function onBeforeRender()
    {
        if (!JFactory::getApplication()->isAdmin() && JFactory::getUser()->authorise('jfbconnect.opengraph.debug', 'com_jfbconnect') && JFBCFactory::config()->get('facebook_display_errors'))
            JFactory::getDocument()->addStyleSheet('components/com_jfbconnect/assets/jfbconnect.css');
    }

    public function onAfterRender()
    {
        if (!JFactory::getApplication()->isAdmin())
        {
            $this->doTagReplacements();

            $providers = JFBCFactory::getAllProviders();
            foreach ($providers as $provider)
                $provider->onAfterRender();

            JFBCFactory::library('toolbar')->onAfterRender();
        }
        return true;
    }

    private function showOpenGraphProfileLinks()
    {
        if (JFactory::getUser()->guest)
            return false;

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('COUNT(*)')
                ->from($db->qn('#__opengraph_action'))
                ->where($db->qn('published') . '=' . $db->q(1));
        $db->setQuery($query);
        $numOGActionsEnabled = $db->loadResult();

        $user = JFactory::getUser();
        $query = $db->getQuery(true);
        $query->select('COUNT(*)')
                ->from($db->qn('#__opengraph_activity'))
                ->where($db->qn('user_id') . '=' . $db->q($user->id))
                ->where($db->qn('status') . '=' . $db->q(1));
        $db->setQuery($query);
        $numActivities = $db->loadResult();

        return ($numOGActionsEnabled > 0) || ($numActivities > 0);
    }

    private function replaceTagInMetadata($metadataTag)
    {
        $doc = JFactory::getDocument();
        $description = $doc->getDescription();
        $replace = SCSocialUtilities::stripSystemTags($description, $metadataTag);

        if ($replace)
        {
            $description = SCStringUtilities::trimNBSP($description);
            $doc->setDescription($description);
        }
    }

    private function doTagReplacements()
    {
        $tagKeys = array_keys($this->tagsToReplace);
        foreach ($tagKeys as $tag)
        {
            $lowercaseTag = strtolower($tag);

            /*
             * Code to strip any {JFBCxyz} tags from head.
             */
            //Get the head
            $content = JResponse::getBody();
            $regex = '|<head(.*)?</head>|sui';
            if (preg_match($regex, $content, $matches))
            {
                if (count($matches) == 2) // more than one head is a problem, don't do anything
                {
                    //Remove the tag if it's in the head
                    $newHead = preg_replace('|{' . $tag . '(.*?)}|ui', '', $matches[0], -1, $count);

                    if ($count > 0)
                    {
                        //Replace the head
                        $content = preg_replace('|<head(.*)?</head>|sui', $newHead, $content, -1, $count);
                        if ($count == 1) // Only update the body if exactly one head was found and replaced
                        JResponse::setBody($content);
                    }
                }
            }

            //Tag like {JFBCTag} or {JFBCTag field=value field2=value2}
            // Need
            // {JFBCTag
            // at least one space or no space
            // then capture after the space if it exists, .* does not match special characters, so the closing squiggly breaks it
            // there has to be a closing squiggly
            $regex = '/\{' . $tag . '\s?(.*?)?}/i';
            $this->replaceTag($lowercaseTag, $regex);
        }
        $this->replaceGraphTags();
        $this->replaceJSPlaceholders();
    }

    private function replaceJSPlaceholders()
    {
        $contents = JResponse::getBody();
        $javascript = '';

        $providers = JFBCFactory::getAllProviders();
        foreach ($providers as $provider)
        {
            $javascript .= $provider->getHeadData();
            $provider->needsJavascript = false;
        }

        $pinterestWidgets = JFBCFactory::getAllWidgets('pinterest');
        $javascript .= $pinterestWidgets[0]->getHeadData();
        JFBConnectProviderPinterestWidgetShare::$needsJavascript = false;

        $contents = str_replace('<SourceCoastProviderJSPlaceholder />', $javascript, $contents);

        JResponse::setBody($contents);
    }

    private function replaceTag($method, $regex)
    {
        $replace = FALSE;
        $contents = JResponse::getBody();
        if (preg_match_all($regex, $contents, $matches, PREG_SET_ORDER))
        {
            $count = count($matches[0]);
            if ($count == 0)
                return true;

            $jfbcRenderKey = SCSocialUtilities::getJFBConnectRenderKeySetting();

            foreach ($matches as $match)
            {
                if (isset($match[1]))
                    $val = $match[1];
                else
                    $val = '';

                $cannotRender = SCEasyTags::cannotRenderEasyTag($val, $jfbcRenderKey);
                if ($cannotRender)
                    continue;

                if (array_key_exists($method, $this->tagsToReplace))
                {
                    $widgetInfo = $this->tagsToReplace[$method];
                    $widget = JFBCFactory::widget($widgetInfo['provider'], $widgetInfo['widget'], $val);
                    $newText = $widget->render();
                    $replace = TRUE;
                }
                else
                {
                    $newText = '';
                    $replace = FALSE;
                }

                $search = '/' . preg_quote($match[0], '/') . '/';
                $contents = preg_replace($search, $newText, $contents, 1);
            }
            if ($replace)
                JResponse::setBody($contents);
        }

        return $replace;
    }

    private function getGraphContents($regex, &$contents, &$newGraphTags)
    {
        if (preg_match_all($regex, $contents, $matches, PREG_SET_ORDER))
        {
            $count = count($matches[0]);
            if ($count == 0)
                return true;

            $jfbcRenderKey = SCSocialUtilities::getJFBConnectRenderKeySetting();

            foreach ($matches as $match)
            {
                if (isset($match[1]))
                    $val = $match[1];
                else
                    $val = '';

                $cannotRenderJFBC = SCEasyTags::cannotRenderEasyTag($val, $jfbcRenderKey);

                if ($cannotRenderJFBC)
                    continue;

                $newGraphTags[] = $val;
                $contents = str_replace($match[0], '', $contents);
            }
        }
    }

    private function replaceGraphTags()
    {
        $placeholder = '<SCOpenGraphPlaceholder />';
        $regex1 = '/\{SCOpenGraph\s+(.*?)\}/i';
        $regex2 = '/\{JFBCGraph\s+(.*?)\}/i';

        $newGraphTags1 = array();
        $newGraphTags2 = array();

        $contents = JResponse::getBody();
        $this->getGraphContents($regex1, $contents, $newGraphTags1);
        $this->getGraphContents($regex2, $contents, $newGraphTags2);

        $newGraphTags = array_merge($newGraphTags1, $newGraphTags2);
        //Replace Placeholder with new Head tags
        $defaultGraphFields = JFBCFactory::config()->getSetting('social_graph_fields');
        $locale = JFBCFactory::provider('facebook')->getLocale();

        $openGraphLibrary = OpenGraphLibrary::getInstance();
        $openGraphLibrary->addOpenGraphEasyTags($newGraphTags);
        $openGraphLibrary->addDefaultSettingsTags($defaultGraphFields);
        $openGraphLibrary->addAutoGeneratedTags($locale);
        $graphTags = $openGraphLibrary->buildCompleteOpenGraphList();

        $contents = $openGraphLibrary->removeOverlappingTags($contents);
        $search = '/' . preg_quote($placeholder, '/') . '/';
        $graphTags = str_replace('$', '\$', $graphTags);
        $contents = preg_replace($search, $graphTags, $contents, 1);
        $contents = str_replace($placeholder, '', $contents); //If JLinked attempts to insert, ignore
        JResponse::setBody($contents);
    }
}
