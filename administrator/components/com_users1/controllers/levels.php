<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * User view levels list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_users
 * @since       1.6
 */
class UsersControllerLevels extends JControllerAdmin
{
	/**
	 * @var     string  The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_USERS_LEVELS';

	/**
	 * Proxy for getModel.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'Level', $prefix = 'UsersModel')
	{
		return parent::getModel($name, $prefix, array('ignore_request' => true));
	}
    function quick_assign_website()
    {
        $app=JFactory::getApplication();
        $input=JFactory::getApplication()->input;
        $pks=$input->get('cid',array(),'array');
        JArrayHelper::toInteger($pks);
        $copy=$input->get('copy',0,'int');
        $website_id=$input->get('website_id',0,'int');
        if($copy)
        {
            try {
                if (empty($pks))
                {
                    throw new Exception(JText::_('COM_USERS_ERROR_NO_LEVELS_SELECTED'));
                }
                $model = $this->getModel();
                $model->duplicateAndAssign($pks,$website_id);
                $this->setMessage(JText::plural('COM_USERS_N_LEVELS_DUPLICATED', count($pks)));
            } catch (Exception $e)
            {
                JError::raiseWarning(500, $e->getMessage());
            }
            $this->setRedirect('index.php?option=com_users&view=levels');
        }
        else {
            try {
                if (empty($pks)) {
                    throw new Exception(JText::_('COM_USERS_ERROR_NO_LEVELS_SELECTED'));
                }
                $model = $this->getModel();
                $model->quick_assign_website($pks, $website_id);
                $this->setMessage(JText::plural('COM_USERS_N_QUICK_ASSIGN', count($pks)));
            } catch (Exception $e) {
                JError::raiseWarning(500, $e->getMessage());
            }

            $this->setRedirect('index.php?option=com_users&view=levels');
        }
    }

}
