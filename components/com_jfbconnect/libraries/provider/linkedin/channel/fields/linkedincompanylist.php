<?php
/**
 * @package         JFBConnect
 * @copyright (c)   2009-@CURRENT_YEAR@ by SourceCoast - All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @version         Release v@VERSION@
 * @build-date      @DATE@
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldLinkedinCompanyList extends JFormFieldList
{
    public $type = 'LinkedinCompanyList';

    protected function getOptions()
    {
        $options = array();
        $options[] = JHtml::_('select.option', "--", "-- Select a Page --");

        $jid = $this->form->getValue('attribs.user_id');
        if ($jid)
        {
            $uid = JFBCFactory::usermap()->getProviderUserId($jid, 'linkedin');
            if ($uid)
            {
                $access_token = JFBCFactory::usermap()->getUserAccessToken($jid, 'linkedin');
                $params['access_token'] = $access_token;
                $liLibrary = JFBCFactory::provider('linkedin');
                $liLibrary->client->setToken((array)$access_token);

                $url = 'https://api.linkedin.com/v1/companies/?is-company-admin=true&start=0&count=20';
                $companies = $liLibrary->client->query($url);
                if ($companies->code == '200')
                {
                    $companies = json_decode($companies->body);
                    foreach ($companies->values as $c)
                    {
                            $options[] = JHtml::_('select.option', strtolower($c->id), $c->name);
                    }
                }
            }
            else
                JFactory::getApplication()->enqueueMessage("The selected user has not authenticated with LinkedIn. Please have them do so on the front-end of the site.", 'warning');

        }
        return $options;
    }
}