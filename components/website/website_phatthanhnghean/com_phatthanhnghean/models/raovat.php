<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * raovat model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 * @since       1.6
 */
class cpanelModelraovat extends JModelAdmin
{
	/**
	 * @var     string  The help screen key for the module.
	 * @since   1.6
	 */
	protected $helpKey = 'JHELP_EXTENSIONS_raovat_MANAGER_EDIT';

	/**
	 * @var     string  The help screen base URL for the module.
	 * @since   1.6
	 */
	protected $helpURL;

	/**
	 * @var     array  An array of cached raovat items.
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
	public function getForm($data = array(), $loadData = true)
	{

		// Get the form.
		$form = $this->loadForm('com_cpanel.raovat', 'raovat', array('control' => 'jform', 'load_data' => $loadData));

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
    public function save($data){
        $ok=parent::save($data);
        if($ok)
        {
            $raovat=$data['raovat'];
            $website_id=$data['website_id'];
            $raovat_path="webstore/$raovat.ini";
            jimport('joomla.filesystem.file');
            $write_ok=JFile::write(JPATH_ROOT.DS.$raovat_path,$website_id);
            if(!$write_ok)
            {
                $this->setError('cannot write file raovat');
            }
        }
        return $ok;
    }
    function ajaxSaveForm($pks)
    {
        $input=JFactory::getApplication()->input;
        $title=$input->get('title',array(),'array');
        $table = $this->getTable();
        $pks = (array) $pks;

        foreach ($pks as $i => $pk)
        {
            if ($table->load($pk))
            {
                $table->name=$title[$pk];
                $table->store();
            }
        }

        // Clean the cache
        $this->cleanCache();

        // Ensure that previous checks doesn't empty the array
        if (empty($pks))
        {
            return true;
        }
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
		$data = JFactory::getApplication()->getUserState('com_cpanel.edit.raovat.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_cpanel.raovat', $data);

		return $data;
	}
    /**
     * Method to duplicate modules.
     *
     * @param   array  &$pks  An array of primary key IDs.
     *
     * @return  boolean  True if successful.
     *
     * @since   1.6
     * @throws  Exception
     */
    public function duplicate(&$pks)
    {
        $user	= JFactory::getUser();
        $db		= $this->getDbo();

        // Access checks.
        if (!$user->authorise('core.create', 'com_cpanel'))
        {
            throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
        }

        $table = $this->getTable();

        foreach ($pks as $pk)
        {
            if ($table->load($pk, true))
            {
                // Reset the id to create a new record.
                $table->id = 0;

                // Alter the title.
                $m = null;
                if (preg_match('#\((\d+)\)$#', $table->name, $m))
                {
                    $table->title = preg_replace('#\(\d+\)$#', '(' . ($m[1] + 1) . ')', $table->name);
                }
                else
                {
                    $table->name .= ' (2)';
                }
                // Unpublish duplicate module
                $table->published = 0;

                if (!$table->check() || !$table->store())
                {
                    throw new Exception($table->getError());
                }

            }
            else
            {
                throw new Exception($table->getError());
            }
        }


        // Clear modules cache
        $this->cleanCache();

        return true;
    }
    public function duplicateAndAssign(&$pks,$website_id=0)
    {
        $user	= JFactory::getUser();
        $db		= $this->getDbo();

/*        // Access checks.
        if (!$user->authorise('core.create', 'com_cpanel'))
        {
            throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
        }
*/
        $table = $this->getTable();

        foreach ($pks as $pk)
        {
            if ($table->load($pk, true))
            {
                // Reset the id to create a new record.
                $table->id = 0;
                $table->website_id = $website_id;

                // Alter the title.
                $m = null;
                if (preg_match('#\((\d+)\)$#', $table->name, $m))
                {
                    $table->title = preg_replace('#\(\d+\)$#', '(' . ($m[1] + 1) . ')', $table->name);
                }
                // Unpublish duplicate module
                $table->published = 0;

                if (!$table->check() || !$table->store())
                {
                    throw new Exception($table->getError());
                }

            }
            else
            {
                throw new Exception($table->getError());
            }
        }


        // Clear modules cache
        $this->cleanCache();

        return true;
    }


	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	*/

	/**
	 * Auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function populateState()
	{
    	// Execute the parent method.
		parent::populateState();

		$app = JFactory::getApplication('site');

		// Load the User state.
		$pk = $app->input->getInt('id');

		$this->setState('raovat.id', $pk);
	}



	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   object	A record object.
	 * @return  array  An array of conditions to add to add to ordering queries.
	 * @since   1.6
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'type = ' . $this->_db->quote($table->type);
		$condition[] = 'folder = ' . $this->_db->quote($table->folder);
		return $condition;
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
	 * Custom clean cache method, raovats are cached in 2 places for different clients
	 *
	 * @since   1.6
	 */
	protected function cleanCache($group = null, $client_id = 0)
	{
		parent::cleanCache('com_cpanel');
	}
}
