<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.pagination');

class JFBConnectViewOpengraph extends JViewLegacy
{
    function display($tpl = null)
    {
        $actionModel = $this->getModel('OpenGraphAction', 'JFBConnectModel');
        $activityModel = $this->getModel('OpenGraphActivity', 'JFBConnectModel');
        $objectModel = $this->getModel('OpenGraphObject', 'JFBConnectModel');

        $user = JFactory::getUser();

        if ($this->getLayout() == 'activity')
        {
            $activityModel->setUserId($user->get('id'));
            $rows = $activityModel->getActivityForUser();
            $this->assignRef('rows', $rows);
            $this->assignRef('actionModel', $actionModel);
            $this->assignRef('objectModel', $objectModel);

            $pagination = $activityModel->getPagination();
            $this->assignRef('pagination', $pagination);
        } else if ($this->getLayout() == 'settings')
        {
            $user = JFactory::getUser();
            $userModel = JFBConnectModelUserMap::getUser($user->get('id'), 'facebook');
            $userData = $userModel->getData();
            $actionsDisabled = $userData->params->get('og_actions_disabled');
            $this->assignRef('actionsDisabled', $actionsDisabled);

            $actionModel = $this->getModel('OpenGraphAction', 'JFBConnectModel');
            $actions = $actionModel->getActions(true);
            $editableActions = array();
            foreach ($actions as $action)
            {
                if ($action->can_disable)
                    $editableActions[] = $action;
            }
            $this->assignRef('actions', $editableActions);
        }
        $doc = JFactory::getDocument();
        $doc->addStyleSheet(JURI::base(true) . '/components/com_jfbconnect/assets/jfbconnect.css');

        parent::display($tpl);
    }
}
