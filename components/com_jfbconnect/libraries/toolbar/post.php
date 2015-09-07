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

class JFBConnectToolbarPost
{
    var $displayName = "Create Post";
    var $systemName = "post";

    public function getHtml()
    {
        return '<div id="social-post-container" style="display:none"></div>';
    }

    public function ajaxGetHtml()
    {

        $return = new stdClass();
        $return->target = '#social-post-container';
        $return->title = "Post to social networks";

        $channels = JFBCFactory::model('channel')->getChannels();
        $checkboxes = array();
        foreach ($channels as $c)
            $checkboxes[] = '<input type="checkbox" name="cids[]" class="' . $c->provider . '-' . $c->type . '" value="' . $c->id . '" id="cid_' . $c->id . '" /><label for="cid_' . $c->id . '" style="display:inline"><img src="' . JURI::root() . 'media/sourcecoast/images/provider/icon_' . $c->provider . '.png" />' . $c->title . '</label>';

        if (count($checkboxes) > 0)
        {
            $return->html = '<div id="social-post-popup">
            <form id="social-post-form" action="index.php?option=com_jfbconnect&task=ajax.fetch&library=toolbar.post&subtask=createPost">
          <p>Please enter a comment and select the social networks to post to.</p>
          <textarea name="message" style="width:90%"></textarea>
          <br/>
          ' . implode("<br/>", $checkboxes) .
                    JHTML::_('form.token') .
                    '</form>
                  </div>';
            $return->buttons = array();
            $button = array('name' => "Post", 'id' => 'submit-social-post', 'action' => 'jfbc.toolbar.post.submit');
            $button['name'] = "Post";
            $return->buttons[] = $button;
        }
        else
            $return->html = 'No social channels are enabled.<br/><br/> Please visit the JFBConnect -> Channels admin area.';

        echo json_encode($return);
    }

    public function ajaxCreatePost()
    {
        if (JFactory::getUser()->authorise('jfbconnect.channels.post', 'com_jfbconnect'))
        {
            $input = JFactory::getApplication()->input;
            $message = $input->post->getString('message');
            $link = $input->post->getString('link');

            $cids = $input->post->get('cids', array(), 'array');

            JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_jfbconnect/tables/');
            $row = JTable::getInstance('Channel', 'Table');
            $response = array();
            foreach ($cids as $cid)
            {
                $row->load($cid);
                $options = new JRegistry();
                $options->loadObject($row->attribs);
                $channel = JFBCFactory::provider($row->provider)->channel($row->type, $options);

                $post = new JRegistry();
                $post->set('message', $message);
                $post->set('link', $link);

                try
                {
                    $return = $channel->post($post);
                }
                catch (Exception $e)
                {
                    $return = false;
                }

                if (!$return)
                    $response[] = "Post to " . $row->provider . ' ' . $row->type . ' failed.';
                else
                    $response[] = $return;
            }
            echo implode("<br/>", $response);
        }
        exit;
    }

}