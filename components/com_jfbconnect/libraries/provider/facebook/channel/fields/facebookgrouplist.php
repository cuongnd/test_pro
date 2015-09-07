<?php

/**
 * @package        JFBConnect
 * @copyright (C) 2009-2014 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFieldFacebookGroupList extends JFormFieldList
{
    public $type = 'FacebookGroupList';

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
                if (!JFBCFactory::provider('facebook')->hasScope($uid, 'user_groups'))
                    JFactory::getApplication()->enqueueMessage("The selected user has not granted the 'user_groups' permission. Please have them login on the front-end of the site and accept the correct permission.", 'warning');
                else if (!JFBCFactory::provider('facebook')->hasScope($uid, 'publish_actions'))
                    JFactory::getApplication()->enqueueMessage("The selected user has not granted the 'publish_actions' permission. Please have them login on the front-end of the site and accept the correct permission.", 'warning');
                else
                {
                    $access_token = JFBCFactory::usermap()->getUserAccessToken($jid, 'facebook');
                    $params['access_token'] = $access_token;
                    $groups = JFBCFactory::provider('facebook')->api('/' . $uid . '/groups/', $params, true, 'GET');

                    if (isset($groups['data']) && count($groups['data']) > 0)
                    {
                        foreach ($groups['data'] as $g)
                        {
                            $options[] = JHtml::_('select.option', strtolower($g['id']), $g['name']);
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