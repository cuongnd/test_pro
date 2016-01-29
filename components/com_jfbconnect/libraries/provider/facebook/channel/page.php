<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-2014 by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v6.2.4
 * @build-date      2014/12/15
 */

class JFBConnectProviderFacebookChannelPage extends JFBConnectChannel
{
    public function setup()
    {
        $this->name = "Page";
        $this->outbound = true;
        $this->inbound = true;
        $this->requiredScope[] = 'manage_pages';
    }

    public function onAfterSave($newData, $oldData)
    {
        if($newData['attribs']['allow_posts'])
        {
            $this->requiredScope[] = 'publish_actions';
        }

        parent::onAfterSave($newData, $oldData);
    }

    public function canPublish($data)
    {
        $canPublish = false;

        $jid = $data['attribs']['user_id'];
        if ($jid)
        {
            $uid = JFBCFactory::usermap()->getProviderUserId($jid, 'facebook');
            if ($uid && isset($data['attribs']['page_id']) && ($data['attribs']['page_id'] != '--') &&
                JFBCFactory::provider('facebook')->hasScope($uid, 'manage_pages') &&
                (JFBCFactory::provider('facebook')->hasScope($uid, 'publish_actions') || !$data['attribs']['allow_posts'])
            )
            {
                $canPublish = true;
            }
        }

        return $canPublish;
    }

    public function getStream($stream)
    {
        $pageId = $this->options->get('page_id');
        if (!$pageId || $pageId == '--')
            return;

        $feed = JFBCFactory::cache()->get('facebook.page.stream.' . $pageId);
        if ($feed === false)
        {
            $params = array();
            $params['access_token'] = JFBCFactory::usermap()->getUserAccessToken($this->options->get('user_id'), 'facebook');
            $feed = $this->provider->api($pageId . '/feed', $params, true, 'GET');
            JFBCFactory::cache()->store($feed, 'facebook.page.stream.' . $pageId);
        }

        if($feed['data'])
        {
            foreach($feed['data'] as $data)
            {
                if(array_key_exists('from', $data) && ($this->options->get('show_admin_only') == 0 || $data['from']['id'] == $pageId))
                {
                    $post = new JFBConnectPost();

                    $post->message = (array_key_exists('message', $data)?$data['message']:"");
                    $post->authorScreenName = $data['from']['name'];
                    $post->updatedTime = (array_key_exists('updated_time', $data)?$data['updated_time']:"");
                    $post->thumbTitle = (array_key_exists('name', $data)?$data['name']:"");
                    $post->thumbLink = (array_key_exists('link', $data)?$data['link']:"");
                    $post->thumbPicture = (array_key_exists('picture', $data)?$data['picture']:"");
                    $post->thumbCaption = (array_key_exists('caption', $data)?$data['caption']:"");
                    $post->thumbDescription = (array_key_exists('description', $data)?$data['description']:"");
                    $post->comments = (array_key_exists('comments', $data)?$data['comments']:"");

                    $stream->addPost($post);
                }
            }
        }
    }

    public function post(JRegistry $data)
    {
        $pageId = $this->options->get('page_id');
        $message = $data->get('message', '');
        $link = $data->get('link', '');

        $params = array();
        $params['access_token'] = JFBCFactory::usermap()->getUserAccessToken($this->options->get('user_id'), 'facebook');
        $params['message'] = $message;
        $params['link'] = $link;

        $return = $this->provider->api($pageId . '/feed', $params);
        if ($return !== false)
            return JText::_('COM_JFBCONNECT_CHANNELS_FACEBOOK_PAGE_POST_SUCCESS');
        else
            return false;
    }
}