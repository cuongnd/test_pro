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
 * daskboard model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_supperadmin
 * @since       1.6
 */
class supperadminModeldaskboard extends JModelAdmin
{
	/**
	 * @var     string  The help screen key for the module.
	 * @since   1.6
	 */
	protected $helpKey = 'JHELP_EXTENSIONS_daskboard_MANAGER_EDIT';

	/**
	 * @var     string  The help screen base URL for the module.
	 * @since   1.6
	 */
	protected $helpURL;

	/**
	 * @var     array  An array of cached daskboard items.
	 * @since   1.6
	 */
	protected $_cache;

	/**
	 * @var     string  The event to trigger after saving the data.
	 * @since   1.6
	 */
	protected $event_after_save = 'onExtensionAfterSave';

	/**
	 * @var     string  The event to trigger after before the data.
	 * @since   1.6
	 */
	protected $event_before_save = 'onExtensionBeforeSave';

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 * 
	 * @return  JForm	A JForm object on success, false on failure
	 * @since   1.6
	 */
    protected function populateState()
    {
        return;
    }
    public function getItem($pk = null)
    {
        return new stdClass();

    }

    public function getForm($data = array(), $loadData = true)
	{


		// Get the form.
		$form = $this->loadForm('com_supperadmin.daskboard', 'daskboard', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data))
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('enabled', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('enabled', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_supperadmin.edit.daskboard.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_supperadmin.daskboard', $data);

		return $data;
	}



	/**
	 * Get the necessary data to load an item help screen.
	 *
	 * @return  object  An object with key, url, and local properties for loading the item help screen.
	 * @since   1.6
	 */
	public function getHelp()
	{
		return (object) array('key' => $this->helpKey, 'url' => $this->helpURL);
	}

	/**
	 * Custom clean cache method, daskboards are cached in 2 places for different clients
	 *
	 * @since   1.6
	 */
	protected function cleanCache($group = null, $client_id = 0)
	{
		parent::cleanCache('com_supperadmin');
	}
}
