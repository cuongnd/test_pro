<?php

/**
 * @package        JFBConnect
 * @copyright (C) 2009-2014 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldFacebookPageList extends JFormFieldList
{
    public $type = 'FacebookPageList';

    protected function getOptions()
    {
        $options = array();
        $options[] = JHtml::_('select.option', "--", "-- Select a Page --");

        $jid = $this->form->getValue('attribs.user_id');
        if ($jid)
        {
            $uid = JFBCFactory::usermap()->getProviderUserId($jid, 'facebook');
            if ($uid)
            {
                if (!JFBCFactory::provider('facebook')->hasScope($uid, 'manage_pages'))
                    JFactory::getApplication()->enqueueMessage("The selected user has not granted the 'manage_pages' permission. Please have them login on the front-end of the site and accept the correct permission.", 'warning');
                else if (!JFBCFactory::provider('facebook')->hasScope($uid, 'publish_actions'))
                    JFactory::getApplication()->enqueueMessage("The selected user has not granted the 'publish_actions' permission. Please have them login on the front-end of the site and accept the correct permission.", 'warning');
                else
                {
                    $access_token = JFBCFactory::usermap()->getUserAccessToken($jid, 'facebook');
                    $params['access_token'] = $access_token;
                    $pages = JFBCFactory::provider('facebook')->api('/' . $uid . '/accounts/', $params, true, 'GET');

                    if (isset($pages['data']) && count($pages['data']) > 0)
                    {
                        foreach ($pages['data'] as $p)
                        {
                            $options[] = JHtml::_('select.option', strtolower($p['id']), $p['name'] . " (" . $p['category'] . ')');
                        }
                    }
                }
            }
            else
                JFactory::getApplication()->enqueueMessage("The selected user has not authenticated with Facebook. Please have them do so on the front-end of the site.", 'warning');
        }
        return $options;
    }
}