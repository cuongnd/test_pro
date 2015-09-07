<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
jimport('joomla.user.helper');
jimport('sourcecoast.utilities');

class JFBConnectControllerLoginRegister extends JControllerLegacy
{
    var $_newUserPassword = "";

    function display($cachable = false, $urlparams = false)
    {
        #JRequest::setVar('tmpl', 'component');
        $provider = JFactory::getApplication()->input->getCmd('provider');
        JFactory::getApplication()->setUserState('com_jfbconnect.' . $provider . '.checkForNewMapping', true);
        parent::display();
    }

    function createNewUser()
    {
        $loginRegisterModel = $this->getModel('LoginRegister', 'JFBConnectModel');
        $provider = JFactory::getApplication()->input->getCmd('provider');
        $provider = JFBCFactory::provider($provider);
        if ($loginRegisterModel->createNewUser($provider))
        {
            require_once(JPATH_COMPONENT . '/controllers/login.php');
            $loginController = new JFBConnectControllerLogin();
            $loginController->login($provider);
        }
        else
        {
            $redirect = $loginRegisterModel->getLoginRedirect($provider);
            $returnParam = '&return=' . base64_encode($redirect);
            $this->setRedirect(JRoute::_('index.php?option=com_jfbconnect&view=loginregister&provider=' . strtolower($provider->name) . $returnParam, false));
        }
    }

    public function loginMap()
    {
        JRequest::checkToken('post') or jexit(JText::_('JInvalid_Token'));
        $app = JFactory::getApplication();

        $provider = JFactory::getApplication()->input->getCmd('provider');
        $provider = JFBCFactory::provider($provider);

        SCSocialUtilities::setJFBCNewMappingEnabled();
        $loginRegisterModel = $this->getModel('LoginRegister', 'JFBConnectModel');
        $redirect = $loginRegisterModel->getLoginRedirect($provider);
        $returnParam = '&return=' . base64_encode($redirect);

        $providerUserId = $provider->getProviderUserId();
        if (!$providerUserId)
            $app->redirect(JRoute::_('index.php?option=com_jfbconnect&view=loginregister&provider=' . strtolower($provider->name) . $returnParam, false));

        // Populate the data array:
        $data = array();
        $data['username'] = JRequest::getVar('username', '', 'method', 'username');
        $data['password'] = JRequest::getString('password', '', 'post', 'string', JREQUEST_ALLOWRAW);

        // Perform the log in.
        $error = $app->login($data);

        // Check if the log in succeeded.
        if (JError::isError($error) || $error == false)
        {
            $app->redirect(JRoute::_('index.php?option=com_jfbconnect&view=loginregister&provider=' . strtolower($provider->name) . $returnParam, false));
        }
        else //Logged in successfully
        {
            $jUser = JFactory::getUser();
            if (JFBCFactory::usermap()->map($jUser->get('id'), $providerUserId, strtolower($provider->name), $provider->client->getToken()))
                $app->enqueueMessage(JText::sprintf('COM_JFBCONNECT_MAP_USER_SUCCESS', $provider->name));

            /* Don't import on just a mapping update, for now. Need to investigate.
            $jUser = JFactory::getUser();
            $jfbcLibrary = JFBConnectFacebookLibrary::getInstance();
            $fbUserId = $jfbcLibrary->getMappedFbUserId();
            $args = array($jUser->get('id'), $fbUserId);

            JPluginHelper::importPlugin('jfbcprofiles');
            $app->triggerEvent('scProfilesImportProfile', $args);
            $app->enqueueMessage('Profile Imported!');*/

            JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_jfbconnect/' . 'models');
            $loginRegisterModel = JModelLegacy::getInstance('LoginRegister', 'JFBConnectModel');
            $redirect = $loginRegisterModel->getLoginRedirect($provider);
            $app->redirect($redirect);
        }
    }

}
