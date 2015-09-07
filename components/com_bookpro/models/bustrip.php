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
 * bustrip model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_bookpro
 * @since       1.6
 */
class BookproModelbustrip extends JModelAdmin
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'com_bookpro';

	/**
	 * @var    string  The help screen key for the bustrip.
	 * @since  1.6
	 */
	protected $helpKey = 'JHELP_EXTENSIONS_bustrip_MANAGER_EDIT';

	/**
	 * @var    string  The help screen base URL for the bustrip.
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
			if ($extensionId = (int) $app->getUserState('com_bookpro.add.bustrip.extension_id'))
			{
				$this->setState('extension.id', $extensionId);
			}
		}

		$this->setState('bustrip.id', $pk);

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

		// Check for existing bustrip.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_bookpro.bustrip.' . (int) $record->id);
		}
		// Default to component settings if bustrip not known.
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
			$bustrip		= $item->bustrip;
			$id			= $item->id;
		}
		else
		{
			$bustrip		= JArrayHelper::getValue($data, 'bustrip');
			$id			= JArrayHelper::getValue($data, 'id');
		}

		// These variables are used to add data from the plugin XML files.
		$this->setState('item.bustrip', $bustrip);

		// Get the form.
		$form = $this->loadForm('com_bookpro.bustrip', 'bustrip', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}


		return $form;
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


        //

        /* clone date
        $db=JFactory::getDbo();


        $query=$db->getQuery(true);
        $query->select('bustrip.id');
        $query->from('#__bookpro_bustrip AS bustrip');
        $db->setQuery($query);
        $listBustrip=$db->loadColumn();


        $query=$db->getQuery(true);
        $query->select('events_rec.id');
        $query->from('#__bookpro_events_rec AS events_rec');
        $db->setQuery($query);
        $listEvent=$db->loadColumn();
        $table = $this->getTable('Event');

        foreach ($listEvent as $pk)
        {
            if ($table->load($pk, true))
            {
                $table->id=0;
                $key= array_rand($listBustrip);
                $table->bustrip_id=$listBustrip[$key];
                $year=rand(2014,2016);
                $month=rand(1,12);
                $month=str_pad($month, 2, '0', STR_PAD_LEFT);
                $day=rand(1,30);
                $day=str_pad($day,2, '0', STR_PAD_LEFT);
                $year1=rand($year,$year+2);
                $month1=rand($month,$month+(12-$month-3)+3);
                $month1=str_pad($month1, 2, '0', STR_PAD_LEFT);
                $day1=rand($day,$day+(30-$day-20)+20);
                $day1=str_pad($day1, 2, '0', STR_PAD_LEFT);
                $event_start=$year.'-'.$month.'-'.$day;
                $event_end=$year1.'-'.$month1.'-'.$day1;
                $event_start=JFactory::getDate($event_start)->format('Y-m-d');
                $event_end=JFactory::getDate($event_end)->format('Y-m-d');

                $table->event_start=$event_start;
                $table->event_end=$event_end;
                $table->store();
                //echo $table->getDbo()->getQuery()->dump();
                //die;
            }
        }
        die('clone xong');
        */
        //
        /*
        AImporter::model('airports');
        $model_airports=&JModelLegacy::getInstance('airports', 'BookproModel');
        $model_airports->set('list.start', 0);
        $model_airports->set('list.limit', 100);
        $app=JFactory::getApplication();
        $app->setUserState('airport_filter_bus',1);
        $fullList = $model_airports->getItems();
        $listId=array();
        foreach($fullList as $dest)
        {
            $listId[]=$dest->id;
        }

        $listVehicle=array();
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('bus.id');
        $query->from('#__bookpro_bus AS bus');
        $db->setQuery($query);
        $listVehicle=$db->loadColumn();
        */
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

                /*
                $key= array_rand($listId);
                $table->from=$listId[$key];
                $key= array_rand($listId);
                $table->to=$listId[$key];


                $key= array_rand($listVehicle);
                $table->bus_id=$listVehicle[$key];
                $table->roundtrip=rand(0,1);
                $table->tax=rand(10,20);
                $table->km=rand(10,1000);
                $table->start_time=rand(6,14).':'.rand(0,60);
                $table->price=rand(10,1500);
                $table->duration=rand(0,30).'h';
                */

                // Alter the title.

                // Unpublish duplicate module
                $table->published = 1;

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
		$data = JFactory::getApplication()->getUserState('com_bookpro.edit.bustrip.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// This allows us to inject parameter settings into a new bustrip.
			$params = $app->getUserState('com_bookpro.add.bustrip.params');
			if (is_array($params))
			{
				$data->set('params', $params);
			}
		}

		$this->preprocessData('com_bookpro.bustrip', $data);

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
		$pk = (!empty($pk)) ? (int) $pk : (int) $this->getState('bustrip.id');
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
	public function getTable($type = 'bustrip', $prefix = 'JTable', $config = array())
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
		$bustrip   = $this->getState('item.bustrip');

		$client   = JApplicationHelper::getClientInfo($clientId);
		$formFile = JPath::clean($client->path . '/bustrips/' . $bustrip . '/' . $bustrip . '.xml');

		// Load the core and/or local language file(s).
		$lang->load($bustrip, $client->path, null, false, true)
			||	$lang->load($bustrip, $client->path . '/bustrips/' . $bustrip, null, false, true);

		if (file_exists($formFile))
		{
			// Get the bustrip form.
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
