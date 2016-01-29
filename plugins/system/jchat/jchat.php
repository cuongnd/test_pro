<?php
/**
 * App runner
 * @package JCHAT::plugins::system
 * @author Joomla! Extensions Store
 * @copyright (C) 2013 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
// no direct access
defined('_JEXEC') or die ('Restricted access');
jimport('joomla.plugin.plugin');

class plgSystemJChat extends JPlugin
{
    /**
     * Class Constructor
     * @access    protected
     * @param    object $subject The object to observe
     * @param    array $config An array that holds the plugin configuration
     * @since    1.0
     */
    public function plgSystemJChat(& $subject, $config)
    {
        parent::__construct($subject, $config);
    }

    /**
     * onAfterInitialise handler
     *
     * @access    public
     * @return null
     */
    public function onAfterInitialise()
    {
        $app = JFactory::getApplication();
        $component = JComponentHelper::getComponent('com_jchat');
        $cParams = $component->params;
        if (!$app->getClientId() && $cParams->get('includeevent') == 'afterinitialize') {
            $this->injectApp($cParams, $app);
        }
    }

    /**
     * JS App Inject
     *
     * @access    private
     * @param Object $cParams
     * @return void
     */
    private function injectApp($cParams, $app)
    {
        $user = JFactory::getUser();
        if (!$user->id && !$cParams->get('guestenabled', false)) {
            return;
        }

        // Check for menu exclusion
        $menu = $app->getMenu()->getActive();
        if (is_object($menu)) {
            $menuItemid = $menu->id;
            $menuExcluded = $cParams->get('chat_exclusions');
            if (is_array($menuExcluded) && !in_array(0, $menuExcluded, false) && in_array($menuItemid, $menuExcluded)) {
                return;
            }
        }

        require_once JPATH_BASE . '/components/com_jchat/libraries/jchatlanguage.php';

        //load the translation
        $base = JURI::base();

        // Create language object
        $language = JFactory::getLanguage();
        $language->load('com_jchat', JPATH_SITE . '/components/com_jchat');
        $chatLanguage = JChatLanguage::getInstance(null);

        // Ottenimento document
        $doc = JFactory::getDocument();
        // Output JS APP nel Document
        if ($doc->getType() !== 'html' || JRequest::getCmd('tmpl') === 'component') {
            return false;
        }

        // Inject js translations
        $translations = array('chat',
            'privatechat',
            'nousers',
            'gooffline',
            'available',
            'statooccupato',
            'statooffline',
            'defaultstatus',
            'sent_file',
            'received_file',
            'sent_file_waiting',
            'sent_file_downloaded',
            'sent_file_downloaded_realtime',
            'sent_file_download',
            'error_deleted_file',
            'error_notfound_file',
            'groupchat_filter',
            'mystatus',
            'addfriend',
            'optionsbutton',
            'closesidebarbutton',
            'spacer',
            'scegliemoticons',
            'wall_msgs',
            'wall_msgs_refresh',
            'manage_avatars',
            'seconds',
            'minutes',
            'hours',
            'days',
            'years',
            'groupchat_request_sent',
            'groupchat_request_received',
            'groupchat_request_accepted',
            'groupchat_request_removed',
            'groupchat_request_received',
            'groupchat_request_accepted_owner',
            'groupchat_nousers',
            'audio_onoff',
            'trigger_fileupload',
            'trigger_export',
            'trigger_delete',
            'trigger_refresh',
            'trigger_skypesave',
            'trigger_skypedelete',
            'search',
            'invite',
            'pending',
            'remove',
            'userprofile_link',
            'you',
            'startskypecall',
            'startskypedownload',
            'skypeidsaved',
            'skypeid_deleted'
        );
        $chatLanguage->injectJsTranslations($translations, $doc);

        // Output JS APP nel Document
        $doc->addStyleSheet(JURI::root(true) . '/components/com_jchat/css/mainstyle.css');
        JHtml::_('jquery.framework');
        $doc->addScriptDeclaration("var jchat_livesite='$base';");
        $doc->addScript(JURI::root(true) . '/components/com_jchat/js/utility.js');
        $doc->addScript(JURI::root(true) . '/components/com_jchat/js/jstorage.min.js');
        $doc->addScript(JURI::root(true) . '/components/com_jchat/js/main.js');
        $doc->addScript(JURI::root(true) . '/components/com_jchat/sounds/soundmanager2.js');
        $doc->addScript(JURI::root(true) . '/components/com_jchat/js/sounds.js');
        $doc->addScript(JURI::root(true) . '/components/com_jchat/js/emoticons.js');
    }

    /**
     * onAfterInitialise handler
     *
     * @access    public
     * @return null
     */
    public function onAfterDispatch()
    {
        $app = JFactory::getApplication();
        $component = JComponentHelper::getComponent('com_jchat');
        $cParams = $component->params;
        if (!$app->getClientId() && $cParams->get('includeevent') == 'afterdispatch') {
            $this->injectApp($cParams, $app);
        }
    }
}
