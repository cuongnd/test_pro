<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-2014 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v6.2.4
 * @build-date      2014/12/15
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('sourcecoast.adminHelper');

require_once(JPATH_ADMINISTRATOR . '/components/com_jfbconnect/includes/views.php');

include_once(JPATH_ADMINISTRATOR.'/components/com_jfbconnect/models/fields/channelusers.php');

class JFBConnectViewChannel extends JFBConnectAdminView
{
    function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->state = $this->get('State');
        $this->item = $this->get('Item');
        $this->channelModel = $this->getModel('channel');
        $this->pagination = $this->get('Pagination');

        JToolBarHelper::apply('channel.apply', 'Save');
        JToolBarHelper::save('channel.save', 'Save & Close');
        JToolBarHelper::cancel('channel.cancel', 'Cancel');

        JFactory::getDocument()->addScriptDeclaration('var jfbc_language_click_save="'.JText::_('COM_JFBCONNECT_CHANNEL_CLICK_SAVE_LOAD_SETTINGS_LABEL').'";');
        JFactory::getDocument()->addScriptDeclaration('var jfbc_language_select_provider="'.JText::_('COM_JFBCONNECT_CHANNEL_SELECT_PROVIDER_CHANNEL_LABEL').'";');

        JFactory::getDocument()->addStyleSheet(JURI::root(true) . "/media/system/css/modal.css");
        JFactory::getDocument()->addScript(JURI::root(true)."/media/system/js/mootools-core.js");
        if(defined('SC30')):
            JFactory::getDocument()->addScript(JURI::root(true)."/media/system/js/mootools-more.js");
        endif;//SC30
        JFactory::getDocument()->addScript(JURI::root(true)."/media/system/js/core.js");
        JFactory::getDocument()->addScript(JURI::root(true)."/media/system/js/modal.js");

        if(defined('SC16')):
            JFactory::getDocument()->addScriptDeclaration('function jSelectUser_jform_attribs_user_id(id, title) {
             var old_id = document.getElementById("jform_attribs_user_id_id").value;
             if (old_id != id) {
             document.getElementById("jform_attribs_user_id_id").value = id;
             document.getElementById("jform_attribs_user_id_name").value = title;
             jfbcAdmin.channels.outbound.onuserchange(this.value)
             }
            SqueezeBox.close();
            }');
        endif; //SC16
        if(defined('SC30')):
            JFactory::getDocument()->addScriptDeclaration('function jSelectUser_jform_attribs_user_id(id, title) {
             var old_id = document.getElementById("jform_attribs_user_id_id").value;
             if (old_id != id) {
                document.getElementById("jform_attribs_user_id_id").value = id;
                document.getElementById("jform_attribs_user_id").value = title;
                document.getElementById("jform_attribs_user_id").className = document.getElementById("jform_attribs_user_id").className.replace(" invalid" , "");
                jfbcAdmin.channels.outbound.onuserchange(this.value)
            }
            SqueezeBox.close();
            }');
        endif; //SC30

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode("\n", $errors));

            return false;
        }

        $title = "JFBConnect: Social Channels";

        JToolBarHelper::title($title, 'jfbconnect.png');

        SCAdminHelper::addAutotuneToolbarItem();

        parent::display($tpl);
    }

    function displayAttributes()
    {
        $this->_defaultModel = 'channel';
        $this->form = $this->get('Form');
        $this->state = $this->get('State');
        $this->item = $this->get('Item');

        // Check for errors.
        if (count($errors = $this->get('Errors')))
        {
            JError::raiseError(500, implode("\n", $errors));

            return false;
        }

        $attribs = '';
        foreach ($this->form->getFieldset("attribs") as $field)
            $attribs .= $this->formGetField($field);
        $attribs .= "</div>\n";

        return $attribs;
    }

    function displayTest()
    {
        $id = JRequest::getInt('id', 0);
        $channels[] = strval($id);
        $options = new JRegistry();
        $options->set('show_provider', '1');
        $options->set('show_date', '1');
        $options->set('show_link', '1');
        $options->set('post_limit', '5');
        $options->set('datetime_format', JText::_('DATE_FORMAT_LC2'));
        $stream = new JFBConnectStream($options, $channels);

        JFactory::getDocument()->addStyleSheet(JURI::root()."media/sourcecoast/themes/scsocialstream/default/styles.css");
        $streamHtml = '<div class="sourcecoast socialstream">';
        $streamTest = $stream->getStreamHtml();
        if(empty($streamTest))
            $streamTest = '<div class="jfbc-error">'.JText::_('COM_JFBCONNECT_CHANNEL_EDIT_STREAM_EMPTY').'</div>';
        $streamHtml .= $streamTest;
        $streamHtml .= '</div>';
        echo $streamHtml;
    }

    public function testStream($id)
    {

    }
}