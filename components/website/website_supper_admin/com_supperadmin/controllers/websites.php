<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * supperadmin list controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 * @since       1.6
 */
class supperadminControllerwebsites extends JControllerAdmin
{
	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'website', $prefix = 'supperadminModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
    public function is_template_supper_admin()
    {
        // Check for request forgeries
        JSession::checkToken() or (JText::_('JINVALID_TOKEN'));

        // Get items to publish from the request.
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        $cid=$cid[0];
        $data = array('is_template_supper_admin' => 1, 'is_not_template_supper_admin' => 0);
        $task = $this->getTask();
        $state = JArrayHelper::getValue($data, $task, 0, 'int');

        if (empty($cid))
        {
            JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
        }
        else
        {
            // Get the model.
            $model = $this->getModel();

            // Publish the items.
            try
            {

                $model->set_state_is_template_supper_admin($cid, $state);

                if ($state == 1)
                {
                    $ntext = $this->text_prefix . '_N_ITEMS_IS_SET_TEMPLATE_SUPPER_ADMIN';
                }
                elseif ($state == 0)
                {
                    $ntext = $this->text_prefix . '_N_ITEMS_IS_NOT_SET_TEMPLATE_SUPPER_ADMIN';
                }
                $this->setMessage(JText::plural($ntext, count($cid)));
            }
            catch (Exception $e)
            {
                $this->setMessage($e->getMessage(), 'error');
            }

        }
        $extension = $this->input->get('extension');
        $extensionURL = ($extension) ? '&extension=' . $extension : '';
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $extensionURL, false));
    }
    public function is_not_template_supper_admin()
    {
        // Check for request forgeries
        JSession::checkToken() or (JText::_('JINVALID_TOKEN'));

        // Get items to publish from the request.
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        $cid=$cid[0];
        $data = array('is_template_supper_admin' => 1, 'is_not_template_supper_admin' => 0);
        $task = $this->getTask();
        $state = JArrayHelper::getValue($data, $task, 0, 'int');

        if (empty($cid))
        {
            JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
        }
        else
        {
            // Get the model.
            $model = $this->getModel();
            // Make sure the item ids are integers

            // Publish the items.
            try
            {
                $model->set_state_is_template_supper_admin($cid, $state);

                if ($state == 1)
                {
                    $ntext = $this->text_prefix . '_N_ITEMS_PUBLISHED';
                }
                elseif ($state == 0)
                {
                    $ntext = $this->text_prefix . '_N_ITEMS_UNPUBLISHED';
                }
                $this->setMessage(JText::plural($ntext, count($cid)));
            }
            catch (Exception $e)
            {
                $this->setMessage($e->getMessage(), 'error');
            }

        }
        $extension = $this->input->get('extension');
        $extensionURL = ($extension) ? '&extension=' . $extension : '';
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $extensionURL, false));
    }
    public function enable_supper_admin_request_update()
    {
        // Check for request forgeries
        JSession::checkToken() or (JText::_('JINVALID_TOKEN'));

        // Get items to publish from the request.
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        $cid=$cid[0];
        $data = array('enable_supper_admin_request_update' => 1, 'disable_supper_admin_request_update' => 0);
        $task = $this->getTask();
        $state = JArrayHelper::getValue($data, $task, 0, 'int');

        if (empty($cid))
        {
            JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
        }
        else
        {
            // Get the model.
            $model = $this->getModel();

            // Publish the items.
            try
            {

                $model->set_state_supper_admin_request_update($cid, $state);

                if ($state == 1)
                {
                    $ntext = $this->text_prefix . '_N_ITEMS_IS_SET_TEMPLATE_SUPPER_ADMIN';
                }
                elseif ($state == 0)
                {
                    $ntext = $this->text_prefix . '_N_ITEMS_IS_NOT_SET_TEMPLATE_SUPPER_ADMIN';
                }
                $this->setMessage(JText::plural($ntext, count($cid)));
            }
            catch (Exception $e)
            {
                $this->setMessage($e->getMessage(), 'error');
            }

        }
        $extension = $this->input->get('extension');
        $extensionURL = ($extension) ? '&extension=' . $extension : '';
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $extensionURL, false));
    }
    public function disable_supper_admin_request_update()
    {
        // Check for request forgeries
        JSession::checkToken() or (JText::_('JINVALID_TOKEN'));

        // Get items to publish from the request.
        $cid = JFactory::getApplication()->input->get('cid', array(), 'array');
        $cid=$cid[0];
        $data = array('enable_supper_admin_request_update' => 1, 'disable_supper_admin_request_update' => 0);
        $task = $this->getTask();
        $state = JArrayHelper::getValue($data, $task, 0, 'int');

        if (empty($cid))
        {
            JLog::add(JText::_($this->text_prefix . '_NO_ITEM_SELECTED'), JLog::WARNING, 'jerror');
        }
        else
        {
            // Get the model.
            $model = $this->getModel();
            // Make sure the item ids are integers

            // Publish the items.
            try
            {
                $model->set_state_supper_admin_request_update($cid, $state);

                if ($state == 1)
                {
                    $ntext = $this->text_prefix . '_N_ITEMS_PUBLISHED';
                }
                elseif ($state == 0)
                {
                    $ntext = $this->text_prefix . '_N_ITEMS_UNPUBLISHED';
                }
                $this->setMessage(JText::plural($ntext, count($cid)));
            }
            catch (Exception $e)
            {
                $this->setMessage($e->getMessage(), 'error');
            }

        }
        $extension = $this->input->get('extension');
        $extensionURL = ($extension) ? '&extension=' . $extension : '';
        $this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list . $extensionURL, false));
    }




}
