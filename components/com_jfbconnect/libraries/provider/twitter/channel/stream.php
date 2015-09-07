<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-@CURRENT_YEAR@ by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v@VERSION@
 * @build-date      @DATE@
 */

class JFBConnectProviderTwitterChannelStream extends JFBConnectChannel
{
    public function setup()
    {
        $this->name = "Stream";
        $this->outbound = true;
        $this->inbound = true;
        $this->requiredScope[] = 'write';
    }

    public function getStream()
    {
        echo 'hiya';
    }

    public function post(JRegistry $data)
    {
        $path = $this->provider->options->get('api.url') . 'statuses/update.json';
        $message = $data->get('message');
        $link = $data->get('link');

        // t.co length. Should update this to use the help/configuration check to verify it hasn't grown.
        $urlLength = 22; // +1 for https
        $status = substr($message, 0, 140 - $urlLength - 1) . " " . $link;

        $user = $this->options->get('user_id');
        $accessToken = JFBCFactory::usermap()->getUserAccessToken($user, 'twitter');

        $params = array();
        $params['oauth_token'] = $accessToken->key;
        $this->provider->client->setToken((array)$accessToken);

        $data = array();
        $data['status'] = $status;

        $return = $this->provider->client->oauthRequest($path, 'POST', $params, $data);
        if ($return !== false)
            return 'Post to Twitter was successful';
        else
            return false;
    }
}