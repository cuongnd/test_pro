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
 * bus model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_bookpro
 * @since       1.6
 */
class BookproModelBus extends JModelAdmin
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'com_bookpro';

	/**
	 * @var    string  The help screen key for the bus.
	 * @since  1.6
	 */
	protected $helpKey = 'JHELP_EXTENSIONS_bus_MANAGER_EDIT';

	/**
	 * @var    string  The help screen base URL for the bus.
	 * @since  1.6
	 */
	protected $helpURL;

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('administrator');

		// Load the User state.
		$pk = $app->input->getInt('id');

		if (!$pk)
		{
			if ($extensionId = (int) $app->getUserState('com_bookpro.add.bus.id'))
			{
				$this->setState('extension.id', $extensionId);
			}
		}

		$this->setState('bus.id', $pk);

		// Load the parameters.
		$params	= JComponentHelper::getParams('com_bookpro');
		$this->setState('params', $params);
	}






	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object    $record    A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since   3.2
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		// Check for existing bus.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_bookpro.bus.' . (int) $record->id);
		}
		// Default to component settings if bus not known.
		else
		{
			return parent::canEditState('com_bookpro');
		}
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

				if (!$table->delete($pk))
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

	/**
	 * Method to duplicate buss.
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
		if (!$user->authorise('core.create', 'com_bookpro'))
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
				if (preg_match('#\((\d+)\)$#', $table->title, $m))
				{
					$table->title = preg_replace('#\(\d+\)$#', '(' . ($m[1] + 1) . ')', $table->title);
				}
				else
				{
					$table->title .= ' (2)';
				}
				// Unpublish duplicate bus
				$table->published = 0;

				if (!$table->check() || !$table->store())
				{
					throw new Exception($table->getError());
				}

				// $query = 'SELECT menuid'
				//	. ' FROM #__buss_menu'
				//	. ' WHERE busid = ' . (int) $pk
				//	;

				$query	= $db->getQuery(true)
					->select('menuid')
					->from('#__buss_menu')
					->where('busid=' . (int) $pk);

				$this->_db->setQuery($query);
				$rows = $this->_db->loadColumn();

				foreach ($rows as $menuid)
				{
					$tuples[] = '(' . (int) $table->id . ',' . (int) $menuid . ')';
				}
			}
			else
			{
				throw new Exception($table->getError());
			}
		}

		if (!empty($tuples))
		{
			// bus-Menu Mapping: Do it in one query
			$query = 'INSERT INTO #__buss_menu (busid,menuid) VALUES ' . implode(',', $tuples);
			$this->_db->setQuery($query);

			try
			{
				$this->_db->execute();
			}
			catch (RuntimeException $e)
			{
				return JError::raiseWarning(500, $e->getMessage());
			}
		}

		// Clear buss cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Method to change the title.
	 *
	 * @param   integer  $category_id  The id of the category. Not used here.
	 * @param   string   $title        The title.
	 * @param   string   $position     The position.
	 *
	 * @return  array  Contains the modified title.
	 *
	 * @since   2.5
	 */
	protected function generateNewTitle($category_id, $title, $position)
	{
		// Alter the title & alias
		$table = $this->getTable();
		while ($table->load(array('position' => $position, 'title' => $title)))
		{
			$title = JString::increment($title);
		}

		return array($title);
	}

	/**
	 * Method to get the client object
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public function &getClient()
	{
		return $this->_client;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  JForm  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// The folder and element vars are passed when saving the form.
		if (empty($data))
		{
			$item		= $this->getItem();
			$bus		= $item->bus;
			$id			= $item->id;
		}
		else
		{
			$bus		= JArrayHelper::getValue($data, 'bus');
			$id			= JArrayHelper::getValue($data, 'id');
		}

		// These variables are used to add data from the plugin XML files.
		$this->setState('item.bus', $bus);

		// Get the form.
		$form = $this->loadForm('com_bookpro.bus', 'bus', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}


		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		$app = JFactory::getApplication();

		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_bookpro.edit.bus.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// This allows us to inject parameter settings into a new bus.
			$params = $app->getUserState('com_bookpro.add.bus.params');
			if (is_array($params))
			{
				$data->set('params', $params);
			}
		}

		$this->preprocessData('com_bookpro.bus', $data);

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function getItem($pk = null)
	{
		$pk = (!empty($pk)) ? (int) $pk : (int) $this->getState('bus.id');
		$db = $this->getDbo();

		if (!isset($this->_cache[$pk]))
		{
			$false = false;

			// Get a row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			$return = $table->load($pk);

			// Check for a table object error.
			if ($return === false && $error = $table->getError())
			{
				$this->setError($error);

				return $false;
			}

			// Convert to the JObject before adding other data.
			$properties        = $table->getProperties(1);
			$this->_cache[$pk] = JArrayHelper::toObject($properties, 'JObject');
		}

		return $this->_cache[$pk];
	}

	/**
	 * Get the necessary data to load an item help screen.
	 *
	 * @return  object  An object with key, url, and local properties for loading the item help screen.
	 *
	 * @since   1.6
	 */
	public function getHelp()
	{
		return (object) array('key' => $this->helpKey, 'url' => $this->helpURL);
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A database object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'bus', $prefix = 'JTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}



	/**
	 * Method to preprocess the form
	 *
	 * @param   JForm   $form   A form object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @since   1.6
	 * @throws  Exception if there is an error loading the form.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		jimport('joomla.filesystem.path');

		$lang     = JFactory::getLanguage();
		$clientId = $this->getState('item.client_id');
		$bus   = $this->getState('item.bus');

		$client   = JApplicationHelper::getClientInfo($clientId);
		$formFile = JPath::clean($client->path . '/buss/' . $bus . '/' . $bus . '.xml');

		// Load the core and/or local language file(s).
		$lang->load($bus, $client->path, null, false, true)
			||	$lang->load($bus, $client->path . '/buss/' . $bus, null, false, true);

		if (file_exists($formFile))
		{
			// Get the bus form.
			if (!$form->loadFile($formFile, false, '//config'))
			{
				throw new Exception(JText::_('JERROR_LOADFILE_FAILED'));
			}

			// Attempt to load the xml file.
			if (!$xml = simplexml_load_file($formFile))
			{
				throw new Exception(JText::_('JERROR_LOADFILE_FAILED'));
			}

			// Get the help data from the XML file if present.
			$help = $xml->xpath('/extension/help');
			if (!empty($help))
			{
				$helpKey = trim((string) $help[0]['key']);
				$helpURL = trim((string) $help[0]['url']);

				$this->helpKey = $helpKey ? $helpKey : $this->helpKey;
				$this->helpURL = $helpURL ? $helpURL : $this->helpURL;
			}

		}

		// Load the default advanced params
		JForm::addFormPath(JPATH_ADMINISTRATOR . '/components/com_bookpro/models/forms');
		$form->loadFile('advanced', false);

		// Trigger the default form events.
		parent::preprocessForm($form, $data, $group);
	}

	/**
	 * Loads ContentHelper for filters before validating data.
	 *
	 * @param   object  $form   The form to validate against.
	 * @param   array   $data   The data to validate.
	 * @param   string  $group  The name of the group(defaults to null).
	 *
	 * @return  mixed  Array of filtered data if valid, false otherwise.
	 *
	 * @since   1.1
	 */
	public function validate($form, $data, $group = null)
	{
		require_once JPATH_ADMINISTRATOR . '/components/com_content/helpers/content.php';

		return parent::validate($form, $data, $group);
	}




	/**
	 * Custom clean cache method for different clients
	 *
	 * @param   string   $group      The name of the plugin group to import (defaults to null).
	 * @param   integer  $client_id  The client ID. [optional]
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function cleanCache($group = null, $client_id = 0)
	{
		parent::cleanCache('com_bookpro', $this->getClient());
	}
}
