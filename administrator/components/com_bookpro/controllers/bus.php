<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_bookpro
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * bus controller class.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_bookpro
 * @since       1.6
 */
class BookproControllerbus extends JControllerForm
{
	/**
	 * Override parent add method.
	 *
	 * @return  mixed  True if the record can be added, a JError object if not.
	 *
	 * @since   1.6
	 */
	public function add()
	{
		$app = JFactory::getApplication();

		// Get the result of the parent method. If an error, just return it.
		$result = parent::add();
		if ($result instanceof Exception)
		{
			return $result;
		}

		// Parameters could be coming in for a new item, so let's set them.
		$params = $app->input->get('params', array(), 'array');
		$app->setUserState('com_bookpro.add.bus.params', $params);
	}

	/**
	 * Override parent cancel method to reset the add bus state.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 *
	 * @since   1.6
	 */
	public function cancel($key = null)
	{
		$app = JFactory::getApplication();

		$result = parent::cancel();

		$app->setUserState('com_bookpro.add.bus.extension_id', null);
		$app->setUserState('com_bookpro.add.bus.params', null);

		return $result;
	}

	/**
	 * Override parent allowSave method.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowSave($data, $key = 'id')
	{
		// use custom position if selected
		if (isset($data['custom_position']))
		{
			if (empty($data['position']))
			{
				$data['position'] = $data['custom_position'];
			}

			unset($data['custom_position']);
		}

		return parent::allowSave($data, $key);
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   3.2
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
        return true;
	}

	/**
	 * Method to run batch operations.
	 *
	 * @param   string  $model  The model
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.7
	 */
	public function batch($model = null)
	{
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		// Set the model
		$model	= $this->getModel('bus', '', array());

		// Preset the redirect
		$this->setRedirect(JRoute::_('index.php?option=com_bookpro&view=buss'.$this->getRedirectToListAppend(), false));

		return parent::batch($model);
	}

	/**
	 * Function that allows child controller access to model data after the data has been saved.
	 *
	 * @param   JModelLegacy  $model      The data model object.
	 * @param   array         $validData  The validated data.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		$app = JFactory::getApplication();
		$task = $this->getTask();

		switch ($task)
		{
			case 'save2new':
				$app->setUserState('com_bookpro.add.bus.extension_id', $model->getState('bus.extension_id'));
				break;

			default:
				$app->setUserState('com_bookpro.add.bus.extension_id', null);
				break;
		}

		$app->setUserState('com_bookpro.add.bus.params', null);
	}
}
