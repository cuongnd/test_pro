<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-@CURRENT_YEAR@ by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v@VERSION@
 * @build-date      @DATE@
 */

class JFBConnectProviderLinkedinChannelCompany extends JFBConnectChannel
{
    public function setup()
    {
        $this->name = "Company";
        $this->outbound = true;
        $this->inbound = true;
        $this->requiredScope[] = 'rw_company_admin';
    }

    public function getStream()
    {
        echo 'hiya';
    }

    public function post(JRegistry $data)
    {
        $user = $this->options->get('user_id');
        $access_token = JFBCFactory::usermap()->getUserAccessToken($user, 'linkedin');

        $this->provider->client->setToken((array)$access_token);

        $companyId = $this->options->get('company_id');
        $url = 'https://api.linkedin.com/v1/companies/' . $companyId . '/shares';

        $vals = array();
        $vals['visibility'] = array('code' => 'anyone');
        $vals['comment'] = $data->get('message');
        $vals['content'] = array('submitted-url' => $data->get('link'));

        $vals = json_encode($vals);
        $return = $this->provider->client->query($url, $vals, array(), 'post');

        if ($return !== false)
            return 'Post to LinkedIn was successful';
        else
            return false;
    }
}