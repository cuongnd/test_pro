<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-@CURRENT_YEAR@ by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v@VERSION@
 * @build-date      @DATE@
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

class JFBConnectToolbar
{
    private $buttons = array();

    public function onAfterDispatch()
    {
        if (JFactory::getUser()->authorise('jfbconnect.channels.post', 'com_jfbconnect'))
        {
            $path = JPATH_SITE . '/components/com_jfbconnect/libraries/toolbar/';
            $files = JFolder::files($path);
            foreach ($files as $f)
            {
                require_once($path . $f);
                $class = 'JFBConnectToolbar' . str_replace('.php', '', $f);
                $this->buttons[] = new $class();
            }

            $doc = JFactory::getDocument();
            if (JFBCFactory::config()->get('facebook_display_errors'))
            {
//                $doc->addScript('media/sourcecoast/js/jquery-ui.js');
                $doc->addStyleSheet('media/sourcecoast/css/jquery-ui/jquery-ui.css');
            }
            else
            {
//                $doc->addScript('media/sourcecoast/js/jquery-ui.min.js');
                $doc->addStyleSheet('media/sourcecoast/css/jquery-ui/jquery-ui.min.css');
            }
        }
    }

    public function onAfterRender()
    {
        if (JFactory::getUser()->authorise('jfbconnect.channels.post', 'com_jfbconnect'))
        {
            $buttons = array();
            $buttonHtml = array();
            foreach ($this->buttons as $b)
            {
                $buttons[] = '<button name="' . $b->systemName . '">' . $b->displayName . '</button>';
                $buttonHtml[] = $b->getHtml();
            }
            $buttons[] = '<button name="close">X</button>';
            $html = '<div id="social-toolbar" class="ui-widget-header ui-corner-all" style="position:fixed; bottom: 10px">';
            $html .= implode($buttons);
            $html .= '</div>';
            $html .= implode($buttonHtml);

            $body = JResponse::getBody();
            $body = str_ireplace("</body>", $html . "</body>", $body);
            JResponse::setBody($body);
        }
    }
}