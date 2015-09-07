<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-@CURRENT_YEAR@ by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v@VERSION@
 * @build-date      @DATE@
 */

class JFBConnectProviderFacebookChannelGroup extends JFBConnectChannel
{
    var $name = "Group";

    public function setup()
    {
        $this->name = "Group";
        $this->outbound = true;
        $this->inbound = true;
        $this->requiredScope[] = 'user_groups';
        $this->requiredScope[] = 'publish_actions';
    }

    public function getStream()
    {
        echo 'hiya';
    }

    public function post(JRegistry $data)
    {
        $groupId = $this->options->get('group_id');
        $message = $data->get('message');
        $link = $data->get('link');

        $params = array();
        $params['access_token'] = JFBCFactory::usermap()->getUserAccessToken($this->options->get('user_id'), 'facebook');
        $params['message'] = $message;
        $params['link'] = $link;

        $return = $this->provider->api($groupId . '/feed', $params);
        if ($return !== false)
            return 'Post to Facebook Group was successful';
        else
            return false;
    }}