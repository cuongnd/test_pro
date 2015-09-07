<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-@CURRENT_YEAR@ by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v@VERSION@
 * @build-date      @DATE@
 */

class JFBConnectProviderFacebookChannelPage extends JFBConnectChannel
{
    public function setup()
    {
        $this->name = "Page";
        $this->outbound = true;
        $this->inbound = true;
        $this->requiredScope[] = 'manage_pages';
        $this->requiredScope[] = 'publish_actions';
    }

    public function getStream()
    {
        return array();
    }

    public function post(JRegistry $data)
    {
        $pageId = $this->options->get('page_id');
        $message = $data->get('message');
        $link = $data->get('link');

        $params = array();
        $params['access_token'] = $this->options->get('access_token');
        $params['message'] = $message;
        $params['link'] = $link;

        $return = $this->provider->api($pageId . '/feed', $params);
        if ($return !== false)
            return 'Post to Facebook Page was successful';
        else
            return false;
    }


    public function onBeforeSave($data)
    {
        if (isset($data['attribs']) && isset($data['attribs']['user_id']) &&
                isset($data['attribs']['page_id'])
        )
        {
            $pageId = $data['attribs']['page_id'];
            $userId = $data['attribs']['user_id'];
            $providerId = JFBCFactory::usermap()->getProviderUserId($userId, 'facebook');
            if ($providerId)
            {
                $access_token = JFBCFactory::usermap()->getUserAccessToken($userId, 'facebook');
                $params['access_token'] = $access_token;

                $pages = JFBCFactory::provider('facebook')->api('/' . $providerId . '/accounts/', $params, true, 'GET');
                if (isset($pages['data']) && count($pages['data']) > 0)
                {
                    foreach ($pages['data'] as $p)
                    {
                        if ($p['id'] == $pageId)
                        {
                            $data['attribs']['access_token'] = $p['access_token'];
                        }
                    }
                }
            }
        }
        return $data;
    }
}