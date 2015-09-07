<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_components
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * component model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_components
 * @since       1.6
 */
class componentsModelcomponent extends JModelAdmin
{
	/**
	 * @var     string  The help screen key for the module.
	 * @since   1.6
	 */
	protected $helpKey = 'JHELP_EXTENSIONS_component_MANAGER_EDIT';

	/**
	 * @var     string  The help screen base URL for the module.
	 * @since   1.6
	 */
	protected $helpURL;

	/**
	 * @var     array  An array of cached component items.
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
		// The folder and element vars are passed when saving the form.
		if (empty($data))
		{
			$item		= $this->getItem();
			$folder		= $item->folder;
			$element	= $item->element;
		}
		else
		{
			$folder		= JArrayHelper::getValue($data, 'folder', '', 'cmd');
			$element	= JArrayHelper::getValue($data, 'element', '', 'cmd');
		}

		// These variables are used to add data from the component XML files.
		$this->setState('item.folder',	$folder);
		$this->setState('item.element',	$element);

		// Get the form.
		$form = $this->loadForm('com_components.component', 'component', array('control' => 'jform', 'load_data' => $loadData));
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
		$data = JFactory::getApplication()->getUserState('com_components.edit.component.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_components.component', $data);

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
        if (!$user->authorise('core.create', 'com_components'))
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
    /**
     * Method to delete rows.
     *
     * @param   array  &$pks  An array of item ids.
     *
     * @return  boolean  Returns true on success, false on failure.
     *
     * @since   1.6
     * @throws  Exception
     */
    public function delete(&$pks)
    {
        $pks	= (array) $pks;
        $user	= JFactory::getUser();
        $table	= $this->getTable();

        // Iterate the items to delete each one.
        foreach ($pks as $pk)
        {
            if ($table->load($pk))
            {
                // Access checks.
                if ($table->enabled != -2)
                {
                    $this->setError('You cannot delete');
                    return false;
                }
                if($table->issystem)
                {
                    $this->setError('you cannot delete component system');
                    return false;
                }
                if (!$table->delete($pk))
                {
                    throw new Exception($table->getError());
                }


                // Clear module cache
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
        $tuples=array();
/*        // Access checks.
        if (!$user->authorise('core.create', 'com_component'))
        {
            throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
        }*/

        $table = $this->getTable();

        foreach ($pks as $pk)
        {
            if ($table->load($pk, true))
            {
                // Reset the id to create a new record.
                $table->id = 0;
                $table->website_id = $website_id;


                // Unpublish duplicate module

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
        return true;
    }



    public function quick_assign_website(&$pks,$website_id)
    {
        $user	= JFactory::getUser();
        $db		= $this->getDbo();

        // Access checks.
        if (!$user->authorise('core.create', 'com_components'))
        {
            throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
        }

        $table = $this->getTable();

        foreach ($pks as $pk)
        {
            if ($table->load($pk, true))
            {

                $table->website_id=$website_id;

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
	 * Method to get a single record.
	 *
	 * @param   integer	The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{


		$pk = (!empty($pk)) ? $pk : (int) $this->getState('component.id');

		if (!isset($this->_cache[$pk]))
		{
			$false	= false;

			// Get a row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			$return = $table->load($pk);

			// Check for a table object error.
			if ($return === false && $table->getError())
			{
				$this->setError($table->getError());
				return $false;
			}
			// Convert to the JObject before adding other data.
			$properties = $table->getProperties(1);
			$this->_cache[$pk] = JArrayHelper::toObject($properties, 'JObject');

			// Convert the params field to an array.
			$registry = new JRegistry;
			$registry->loadString($table->params);
			$this->_cache[$pk]->params = $registry->toArray();

			// Get the component XML.
			$path = JPath::clean(JPATH_componentS . '/' . $table->folder . '/' . $table->element . '/' . $table->element . '.xml');

			if (file_exists($path))
			{
				$this->_cache[$pk]->xml = simplexml_load_file($path);
			}
			else
			{
				$this->_cache[$pk]->xml = null;
			}


        }
		return $this->_cache[$pk];
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	*/
	public function getTable($type = 'component', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

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

		$app = JFactory::getApplication('administrator');

		// Load the User state.
		$pk = $app->input->getInt('id');

		$this->setState('component.id', $pk);
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
	 * Custom clean cache method, components are cached in 2 places for different clients
	 *
	 * @since   1.6
	 */
	protected function cleanCache($group = null, $client_id = 0)
	{
		parent::cleanCache('com_components');
	}
}
