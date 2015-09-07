<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

/**
 * Facebook Authentication Plugin
 */
class plgAuthenticationJFBConnectAuth extends JPlugin
{
    var $configModel;

    function plgAuthenticationJFBConnectAuth(& $subject, $config)
    {
        $config['name'] = 'JFBConnectAuth';
        parent::__construct($subject, $config);
    }

    function onUserAuthenticate($credentials, $options, &$response)
    {
        $response->type = 'JFBConnectAuth';

        # authentication via facebook for Joomla always uses the FB API and secret keys
        # When this is present, the user's FB uid is used to look up their Joomla uid and log that user in
        jimport('joomla.filesystem.file');
        $provider = null;
        if (isset($options['provider']))
            $provider = $options['provider'];

        if (class_exists('JFBCFactory') && $provider)
        {
            # always check the secret username and password to indicate this is a JFBConnect login
            #echo "Entering JFBConnectAuth<br>";
            if (substr($credentials['password'], 0, 4) == '$2y$') // hack for J3.2.0 bug. Should remove after 3.2.1 is available.
            $credentials['password'] = substr($credentials['password'], 4);
            if (($credentials['username'] != $provider->appId) ||
                    ($credentials['password'] != $provider->secretKey)
            )
            {
                $response->status = JAuthentication::STATUS_FAILURE;
                return false;
            }

            #echo "Passed API/Secret key check, this is a FB login<br>";
            include_once(JPATH_ADMINISTRATOR . '/components/com_jfbconnect/models/usermap.php');
            $userMapModel = new JFBConnectModelUserMap();

            $providerUserId = $provider->getProviderUserId();
            $app = JFactory::getApplication();

            #echo "Facebook user = ".$fbUserId;
            # test if user is logged into Facebook
            if ($providerUserId)
            {
                # Test if user has a Joomla mapping
                $jUserId = $userMapModel->getJoomlaUserId($providerUserId, $provider->name);
                if ($jUserId)
                {
                    $jUser = JUser::getInstance($jUserId);
                    if ($jUser->id == null) // Usermapping is wrong (likely, user was deleted)
                    {
                        $userMapModel->deleteMapping($providerUserId, $provider->name);
                        return false;
                    }

                    if ($jUser->block)
                    {
                        $isAllowed = false;
                        $app->enqueueMessage(JText::_('JERROR_NOLOGIN_BLOCKED'), 'error');
                    }
                    else
                    {
                        JPluginHelper::importPlugin('socialprofiles');
                        $args = array($provider->name, $jUserId, $providerUserId);
                        $responses = $app->triggerEvent('socialProfilesOnAuthenticate', $args);
                        $isAllowed = true;
                        foreach ($responses as $prResponse)
                        {
                            if (is_object($prResponse) && !$prResponse->status)
                            {
                                $isAllowed = false;
                                $app->enqueueMessage($prResponse->message, 'error');
                            }
                        }
                    }

                    if ($isAllowed)
                    {
                        $response->status = JAuthentication::STATUS_SUCCESS;
                        $response->username = $jUser->username;

                        if (!JFBCFactory::config()->getSetting('create_new_users')) # psuedo-users
                        {
                            // Update the J user's email to what it is in Facebook
                            //$profileLibrary = JFBConnectProfileLibrary::getInstance();
                            $profile = $provider->profile->fetchProfile($providerUserId, array('email'));

                            if (!empty($profile))
                            {
                                $jUser->email = $profile->get('email');
                                $jUser->save();
                            }
                        }

                        $response->language = $jUser->getParam('language');
                        $response->email = $jUser->email;
                        $response->fullname = $jUser->name;
                        $response->error_message = '';
                        return true;
                    }
                }

            }
        }

        # catch everything else as an authentication failure
        $response->status = JAuthentication::STATUS_FAILURE;
        return false;
    }

}
