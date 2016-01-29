<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_utility
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Module model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_utility
 * @since       1.6
 */
class UtilityModelPosition extends JModelAdmin
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'com_utility';

	/**
	 * @var    string  The help screen key for the position.
	 * @since  1.6
	 */
	protected $helpKey = 'JHELP_EXTENSIONS_MODULE_MANAGER_EDIT';

	/**
	 * @var    string  The help screen base URL for the position.
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
			if ($extensionId = (int) $app->getUserState('com_utility.add.position.id'))
			{
				$this->setState('extension.id', $extensionId);
			}
		}

		$this->setState('position.id', $pk);

		// Load the parameters.
		$params	= JComponentHelper::getParams('com_utility');
		$this->setState('params', $params);
	}

    public function duplicateAndAssign(&$pks,$website_id=0)
    {
        $user	= JFactory::getUser();
        $db		= $this->getDbo();
        $tuples=array();
/*        // Access checks.
        if (!$user->authorise('core.create', 'com_utility'))
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
                $table->copy_from = $table->id;
                $table->id = 0;
                $table->website_id = $website_id;
                $table->updated = 0;

                // Alter the title.
                $m = null;
                if (preg_match('#\((\d+)\)$#', $table->title, $m))
                {
                    $table->title = preg_replace('#\(\d+\)$#', '(' . ($m[1] + 1) . ')', $table->title);
                }
                // Unpublish duplicate module

                if (!$table->check() || !$table->store())
                {
                    throw new Exception($table->getError());
                }
                $query	= $db->getQuery(true)
                    ->select($db->quoteName('menuid'))
                    ->from($db->quoteName('#__modules_menu'))
                    ->where($db->quoteName('moduleid') . ' = ' . (int) $pk);

                $this->_db->setQuery($query);
                $rows = $this->_db->loadColumn();

                foreach ($rows as $menuid)
                {
                    $tuples[] = (int) $table->id . ',' . (int) $menuid;
                }


            }
            else
            {
                throw new Exception($table->getError());
            }
        }
        if (!empty($tuples))
        {
            // Module-Menu Mapping: Do it in one query
            $query = $db->getQuery(true)
                ->insert($db->quoteName('#__modules_menu'))
                ->columns($db->quoteName(array('moduleid', 'menuid')))
                ->values($tuples);

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

        // Clear modules cache
        $this->cleanCache();

        return true;
    }
	public function duplicateBlock($block_id,&$a_listId=array(),$block_parent_id=0,$website_id=0,$menu_item_id=0)
	{
		$db=$this->_db;
		$tableBlock=$this->getTable();
		$tableBlock->load($block_id);
		$tableBlock->id=0;
		if($website_id)
		{
			$tableBlock->website_id=$website_id;
		}
		if($menu_item_id)
		{
			$tableBlock->menu_item_id=$menu_item_id;
		}
		$tableBlock->store();
		$new_id=$tableBlock->id;
		$a_listId[]=$new_id;
		if($block_parent_id!=0)
		{
			$tableBlock->parent_id=$block_parent_id;
			$tableBlock->store();
		}
		$query=$db->getQuery(true);
		$query->select('id')
			->from('#__position_config')
			->where('parent_id='.(int)$block_id)
		;
		$listId=$db->setQuery($query)->loadColumn();
		if(count($listId))
		{
			foreach($listId as $block_id) {
				$this->duplicateBlock($block_id,$a_listId,$new_id,$website_id,$menu_item_id);
			}
		}
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
                $table->title=$title[$pk];
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
	 * Method to perform batch operations on a set of modules.
	 *
	 * @param   array  $commands  An array of commands to perform.
	 * @param   array  $pks       An array of item ids.
	 * @param   array  $contexts  An array of item contexts.
	 *
	 * @return  boolean  Returns true on success, false on failure.
	 *
	 * @since   1.7
	 */
	public function batch($commands, $pks, $contexts)
	{
		// Sanitize user ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true))
		{
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks))
		{
			$this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));
			return false;
		}

		$done = false;

		if (!empty($commands['position_id']))
		{
			$cmd = JArrayHelper::getValue($commands, 'move_copy', 'c');

			if (!empty($commands['position_id']))
			{
				if ($cmd == 'c')
				{
					$result = $this->batchCopy($commands['position_id'], $pks, $contexts);
					if (is_array($result))
					{
						$pks = $result;
					}
					else
					{
						return false;
					}
				}
				elseif ($cmd == 'm' && !$this->batchMove($commands['position_id'], $pks, $contexts))
				{
					return false;
				}
				$done = true;
			}
		}

		if (!empty($commands['assetgroup_id']))
		{
			if (!$this->batchAccess($commands['assetgroup_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		if (!empty($commands['language_id']))
		{
			if (!$this->batchLanguage($commands['language_id'], $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		if (!$done)
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));
			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}
    public function quick_assign_website(&$pks,$website_id)
    {
        $user	= JFactory::getUser();
        $db		= $this->getDbo();

        // Access checks.
        if (!$user->authorise('core.create', 'com_utility'))
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
	 * Batch copy modules to a new position or current.
	 *
	 * @param   integer  $value     The new value matching a module position.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   2.5
	 */
	protected function batchCopy($value, $pks, $contexts)
	{
		// Set the variables
		$user = JFactory::getUser();
		$table = $this->getTable();
		$newIds = array();
		$i = 0;

		foreach ($pks as $pk)
		{
			if ($user->authorise('core.create', 'com_utility'))
			{
				$table->reset();
				$table->load($pk);

				// Set the new position
				if ($value == 'noposition')
				{
					$position = '';
				}
				elseif ($value == 'nochange')
				{
					$position = $table->position;
				}
				else
				{
					$position = $value;
				}
				$table->position = $position;

				// Alter the title if necessary
				$data = $this->generateNewTitle(0, $table->title, $table->position);
				$table->title = $data['0'];

				// Reset the ID because we are making a copy
				$table->id = 0;

				// Unpublish the new module
				$table->published = 0;

				if (!$table->store())
				{
					$this->setError($table->getError());
					return false;
				}

				// Get the new item ID
				$newId = $table->get('id');

				// Add the new ID to the array
				$newIds[$i]	= $newId;
				$i++;

				// Now we need to handle the module assignments
				$db = $this->getDbo();
				$query = $db->getQuery(true)
					->select($db->quoteName('menuid'))
					->from($db->quoteName('#__modules_menu'))
					->where($db->quoteName('moduleid') . ' = ' . $pk);
				$db->setQuery($query);
				$menus = $db->loadColumn();

				// Insert the new records into the table
				foreach ($menus as $menu)
				{
					$query->clear()
						->insert($db->quoteName('#__modules_menu'))
						->columns(array($db->quoteName('moduleid'), $db->quoteName('menuid')))
						->values($newId . ', ' . $menu);
					$db->setQuery($query);
					$db->execute();
				}
			}
			else
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_CREATE'));
				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return $newIds;
	}

	/**
	 * Batch move modules to a new position or current.
	 *
	 * @param   integer  $value     The new value matching a module position.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since   2.5
	 */
	protected function batchMove($value, $pks, $contexts)
	{
		// Set the variables
		$user = JFactory::getUser();
		$table = $this->getTable();

		foreach ($pks as $pk)
		{
			if ($user->authorise('core.edit', 'com_utility'))
			{
				$table->reset();
				$table->load($pk);

				// Set the new position
				if ($value == 'noposition')
				{
					$position = '';
				}
				elseif ($value == 'nochange')
				{
					$position = $table->position;
				}
				else
				{
					$position = $value;
				}
				$table->position = $position;

				// Alter the title if necessary
				$data = $this->generateNewTitle(0, $table->title, $table->position);
				$table->title = $data['0'];

				// Unpublish the moved module
				$table->published = 0;

				if (!$table->store())
				{
					$this->setError($table->getError());
					return false;
				}
			}
			else
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
				return false;
			}
		}

		// Clean the cache
		$this->cleanCache();

		return true;
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

		// Check for existing position.
		if (!empty($record->id))
		{
			return $user->authorise('core.edit.state', 'com_utility.position.' . (int) $record->id);
		}
		// Default to component settings if module not known.
		else
		{
			return parent::canEditState('com_utility');
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
				// Access checks.
				if (!$user->authorise('core.delete', 'com_utility.position.'.(int) $pk) || $table->published != -2)
				{
					JError::raiseWarning(403, JText::_('JERROR_CORE_DELETE_NOT_PERMITTED'));
					return;
				}

				if (!$table->delete($pk))
				{
					throw new Exception($table->getError());
				}
				else
				{
					// Delete the menu assignments
					$db    = $this->getDbo();
					$query = $db->getQuery(true)
						->delete('#__modules_menu')
						->where('moduleid=' . (int) $pk);
					$db->setQuery($query);
					$db->execute();
				}

				// Clear module cache
				parent::cleanCache($table->module, $table->client_id);
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
		if (!$user->authorise('core.create', 'com_utility'))
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
				// Unpublish duplicate module
				$table->published = 0;

				if (!$table->check() || !$table->store())
				{
					throw new Exception($table->getError());
				}

				$query	= $db->getQuery(true)
					->select($db->quoteName('menuid'))
					->from($db->quoteName('#__modules_menu'))
					->where($db->quoteName('moduleid') . ' = ' . (int) $pk);

				$this->_db->setQuery($query);
				$rows = $this->_db->loadColumn();

				foreach ($rows as $menuid)
				{
					$tuples[] = (int) $table->id . ',' . (int) $menuid;
				}
			}
			else
			{
				throw new Exception($table->getError());
			}
		}

		if (!empty($tuples))
		{
			// Module-Menu Mapping: Do it in one query
			$query = $db->getQuery(true)
				->insert($db->quoteName('#__modules_menu'))
				->columns($db->quoteName(array('moduleid', 'menuid')))
				->values($tuples);

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

		// Clear modules cache
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
			$clientId	= $item->client_id;
			$module		= $item->module;
			$id			= $item->id;
		}
		else
		{
			$clientId	= JArrayHelper::getValue($data, 'client_id');
			$module		= JArrayHelper::getValue($data, 'module');
			$id			= JArrayHelper::getValue($data, 'id');
		}

		$ui_path= $item->ui_path;

		if($ui_path[0]=='/')
		{
			$ui_path = substr($ui_path, 1);
		}

		$db=JFactory::getDbo();
		require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
		$query=$db->getQuery(true);
		$query->select('*')
			->from('#__control')
			->where('element_path LIKE '.$query->q('%'.$ui_path.'%'))
			->where('type='.$query->q('element'))
		;
		$control=$db->setQuery($query)->loadObject();
		$fields=$control->fields;
		$fields=base64_decode($fields);

		require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
		$fields = (array)up_json_decode($fields, false, 512, JSON_PARSE_JAVASCRIPT);
		ob_start();
		UtilityModelPosition::render_to_xml($fields);
		$string_xml=ob_get_clean();
		$string_xml='<config>'.$string_xml.'</config>';
		jimport('joomla.filesystem.file');

		$pathInfo=pathinfo($ui_path);
		$filename=$pathInfo['filename'];
		$dirName=$pathInfo['dirname'];

		JFile::write(JPATH_ROOT."/$dirName/$filename.xml",$string_xml);





		$query=$db->getQuery(true);
		$query->select('*')
			->from('#__control')
			->where('element_path LIKE '.$query->q('%root_element%'))
			->where('type='.$query->q('element'))
		;
		$control=$db->setQuery($query)->loadObject();
		$fields=$control->fields;
		$fields=base64_decode($fields);




		$fields = (array)up_json_decode($fields, false, 512, JSON_PARSE_JAVASCRIPT);
		ob_start();
		UtilityModelPosition::render_to_xml($fields);
		$string_xml=ob_get_clean();
		$string_xml='<form>'.$string_xml.'</form>';
		jimport('joomla.filesystem.file');
		JFile::write(JPATH_ROOT.'/components/com_utility/models/forms/position.xml',$string_xml);



		// These variables are used to add data from the plugin XML files.
		$this->setState('item.client_id', $clientId);
		$this->setState('item.module', $module);

		// Get the form.
		$form = $this->loadForm('com_utility.position', 'position', array('control' => 'jform', 'load_data' => $loadData),false,JPATH_ROOT.'/media/elements/ui/panel.xml');
		if (empty($form))
		{
			return false;
		}

		$form->setFieldAttribute('position', 'client', $this->getState('item.client_id') == 0 ? 'site' : 'administrator');

		$user = JFactory::getUser();

		// Check for existing module
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('core.edit.state', 'com_utility.position.'.(int) $id))
			|| ($id == 0 && !$user->authorise('core.edit.state', 'com_utility'))
		)
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('published', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('published', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
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
		$data = JFactory::getApplication()->getUserState('com_utility.edit.position.data', array());

		if (empty($data))
		{
			$data = $this->getItem();

			// This allows us to inject parameter settings into a new position.
			$params = $app->getUserState('com_utility.add.position.params');
			if (is_array($params))
			{
				$data->set('params', $params);
			}
		}

		$this->preprocessData('com_utility.position', $data);

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


		$pk = (!empty($pk)) ? (int) $pk : (int) $this->getState('position.id');
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
			// Check if we are creating a new extension.
			if (empty($pk))
			{
				if ($extensionId = (int) $this->getState('extension.id'))
				{
					$query	= $db->getQuery(true)
						->select('element, client_id')
						->from('#__extensions')
						->where('id = ' . $extensionId)
						->where('type = ' . $db->quote('module'));
					$db->setQuery($query);

					try
					{
						$extension = $db->loadObject();
					}
					catch (RuntimeException $e)
					{
						$this->setError($e->getMessage);

						return false;
					}

					if (empty($extension))
					{
						$this->setError('com_utility_ERROR_CANNOT_FIND_MODULE');

						return false;
					}

					// Extension found, prime some module values.
					$table->module    = $extension->element;
					$table->client_id = $extension->client_id;
				}
				else
				{
					$app = JFactory::getApplication();
					$app->redirect(JRoute::_('index.php?option=com_utility&view=modules', false));

					return false;
				}
			}

			// Convert to the JObject before adding other data.
			$properties        = $table->getProperties(1);
			$this->_cache[$pk] = JArrayHelper::toObject($properties, 'JObject');

			// Convert the params field to an array.
			$registry = new JRegistry;
			$registry->loadString($table->params);
			$this->_cache[$pk]->params = $registry->toArray();

			// Determine the page assignment mode.
			$query	= $db->getQuery(true)
				->select($db->quoteName('menuid'))
				->from($db->quoteName('#__modules_menu'))
				->where($db->quoteName('moduleid') . ' = ' . (int) $pk);
			$db->setQuery($query);
			$assigned = $db->loadColumn();

			if (empty($pk))
			{
				// If this is a new module, assign to all pages.
				$assignment = 0;
			}
			elseif (empty($assigned))
			{
				// For an existing module it is assigned to none.
				$assignment = '-';
			}
			else
			{
				if ($assigned[0] > 0)
				{
					$assignment = 1;
				}
				elseif ($assigned[0] < 0)
				{
					$assignment = -1;
				}
				else
				{
					$assignment = 0;
				}
			}

			$this->_cache[$pk]->assigned   = $assigned;
			$this->_cache[$pk]->assignment = $assignment;

			// Get the module XML.
			$client = JApplicationHelper::getClientInfo($table->client_id);
			$website=JFactory::getWebsite();
			$path   = JPath::clean($client->path . '/modules/website/website_'.$website->website_id.'/' . $table->module . '/' . $table->module . '.xml');
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
	public function getTable($type = 'Position', $prefix = 'JTable', $config = array())
	{
		JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Prepare and sanitise the table prior to saving.
	 *
	 * @param   JTable  $table  The database object
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function prepareTable($table)
	{
		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->position	= trim($table->position);
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
	function render_to_xml($fields,$maxLevel = 9999, $level = 0)
	{
		if($level<=$maxLevel)
		{
			foreach ($fields as $item) {
				$level1=$level+1;
				if(is_array($item->children)&&count($item->children)>0 ) {
					if($level==0){
						if(strtolower($item->name)!='option')
						{
							echo '<fields name="'.$item->name.'">';
						}
					}else{
						echo '<fields name="'.$item->name.'">';
					}
					UtilityModelPosition::render_to_xml($item->children,  $maxLevel, $level1);
					if($level==0){
						if(strtolower($item->name)!='option')
						{
							echo '</fields>';
						}
					}else{
						echo '</fields>';
					}
				}else{
					$config_property=$item->config_property;
					$config_property=base64_decode($config_property);
					$config_property = (array)up_json_decode($config_property, false, 512, JSON_PARSE_JAVASCRIPT);

					$config_params=$item->config_params;
					$config_params=base64_decode($config_params);
					$config_params = (array)up_json_decode($config_params, false, 512, JSON_PARSE_JAVASCRIPT);
					$name=strtolower($item->name);
					?>

					<field type="<?php echo $item->type?$item->type:'text' ?>" readonly="<?php echo $item->readonly==1?'true':'false' ?>" label="<?php echo $item->label ?>" default="<?php echo $item->default ?>"
						   name="<?php echo $item->name ?>"

						<?php
						foreach($config_property as $a_item){ ?>
							<?php if($a_item->property_key&&$a_item->property_value){
								echo " ";
								echo "{$a_item->property_key}=\"{$a_item->property_value}\"";
								echo " ";
							 } ?>
						<?php }


					?>
						>
						<?php if(count($config_params)){

							foreach($config_params as $a_item){ ?>
								<?php if($a_item->param_key!=''&&$a_item->param_value!=''){ ?>
									<option value="<?php echo $a_item->param_key ?>"><?php echo $a_item->param_value ?></option>
								<?php } ?>
							<?php }
						} ?>
					</field>
					<field type="checkbox" label="<?php echo $item->label ?>" default="0"
						   name="enable_<?php echo $name ?>" onchange="<?php echo strtolower($item->onchange) ?>">

					</field>

					<?php
				}
				?>
				<?php
			}

		}

	}




	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{

		jimport('joomla.filesystem.path');
		JForm::addFormPath(JPATH_ROOT . '/components/com_utility/models/forms');
		$form->loadFile('position', false);
		$lang     = JFactory::getLanguage();
		$clientId = $this->getState('item.client_id');
		$module   = $this->getState('item.module');
		$path=$data->ui_path;
		$pathInfo=pathinfo($path);
		$filename=$pathInfo['filename'];
		$dirName=$pathInfo['dirname'];
		$client   = JApplicationHelper::getClientInfo($clientId);
		$formFile = JPath::clean(JPATH_ROOT."/$dirName/$filename.xml");
		if($data->type=="row")
		{
			$formFile=JPATH_ROOT."/media/elements/ui/row.xml";
		}elseif($data->type=="column")
		{
			$formFile=JPATH_ROOT."/media/elements/ui/column.xml";
		}
		if (file_exists($formFile))
		{
			// Get the module form.
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

		//$form->loadFile('advanced', false);

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
		require_once JPATH_ROOT . '/components/com_content/helpers/content.php';

		return parent::validate($form, $data, $group);
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{

		$dispatcher = JEventDispatcher::getInstance();
		$input      = JFactory::getApplication()->input;
		$table      = $this->getTable();
		$pk         = (!empty($data['id'])) ? $data['id'] : (int) $this->getState('position.id');
		$isNew      = true;

		// Include the content modules for the onSave events.
		JPluginHelper::importPlugin('extension');

		// Load the row if saving an existing record.
		if ($pk > 0)
		{
			$table->load($pk);
			$isNew = false;
		}

		// Alter the title and published state for Save as Copy
		if ($input->get('task') == 'save2copy')
		{
			$orig_data  = $input->post->get('jform', array(), 'array');
			$orig_table = clone($this->getTable());
			$orig_table->load((int) $orig_data['id']);

			if ($data['title'] == $orig_table->title)
			{
				$data['title'] .= ' ' . JText::_('JGLOBAL_COPY');
				$data['published'] = 0;
			}
		}

		// Bind the data.
		if (!$table->bind($data))
		{
			$this->setError($table->getError());

			return false;
		}

		// Prepare the row for saving
		$this->prepareTable($table);

		// Check the data.
		if (!$table->check())
		{
			$this->setError($table->getError());

			return false;
		}

		// Trigger the onExtensionBeforeSave event.
		$result = $dispatcher->trigger('onExtensionBeforeSave', array('com_utility.position', &$table, $isNew));

		if (in_array(false, $result, true))
		{
			$this->setError($table->getError());

			return false;
		}

		// Store the data.
		if (!$table->store())
		{
			$this->setError($table->getError());

			return false;
		}

		// Process the menu link mappings.
		$assignment = isset($data['assignment']) ? $data['assignment'] : 0;

		// Delete old module to menu item associations
		$db    = $this->getDbo();
		$query = $db->getQuery(true)
			->delete('#__modules_menu')
			->where('moduleid = ' . (int) $table->id);
		$db->setQuery($query);

		try
		{
			$db->execute();
            
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		// If the assignment is numeric, then something is selected (otherwise it's none).
		if (is_numeric($assignment))
		{
			// Variable is numeric, but could be a string.
			$assignment = (int) $assignment;

			// Logic check: if no module excluded then convert to display on all.
			if ($assignment == -1 && empty($data['assigned']))
			{
				$assignment = 0;
			}

			// Check needed to stop a module being assigned to `All`
			// and other menu items resulting in a module being displayed twice.
			if ($assignment === 0)
			{
				// Assign new module to `all` menu item associations.
				$query->clear()
					->insert('#__modules_menu')
					->columns(array($db->quoteName('moduleid'), $db->quoteName('menuid')))
					->values((int) $table->id . ', 0');
				$db->setQuery($query);

				try
				{
					$db->execute();
                     
				}
				catch (RuntimeException $e)
				{
					$this->setError($e->getMessage());

					return false;
				}
			}
			elseif (!empty($data['assigned']))
			{
				// Get the sign of the number.
				$sign = $assignment < 0 ? -1 : 1;

				// Preprocess the assigned array.
				$tuples = array();

				foreach ($data['assigned'] as &$pk)
				{
					$tuples[] = '(' . (int) $table->id . ',' . (int) $pk * $sign . ')';
				}

				$this->_db->setQuery(
					'INSERT INTO #__modules_menu (moduleid, menuid) VALUES ' .
					implode(',', $tuples)
				);

				try
				{
					$db->execute();
				}
				catch (RuntimeException $e)
				{
					$this->setError($e->getMessage());

					return false;
				}
			}
		}

		// Trigger the onExtensionAfterSave event.
		$dispatcher->trigger('onExtensionAfterSave', array('com_utility.position', &$table, $isNew));

		// Compute the extension id of this module in case the controller wants it.
		$query	= $db->getQuery(true)
			->select('e.id as id')
			->from('#__extensions AS e')
			->join('LEFT', '#__modules AS m ON e.element = m.module')
			->where('m.id = ' . (int) $table->id);
		$db->setQuery($query);

		try
		{
			$extensionId = $db->loadResult();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());

			return false;
		}

		$this->setState('position.id', $extensionId);
		$this->setState('position.id', $table->id);

		// Clear modules cache
		$this->cleanCache();

		// Clean module cache
		parent::cleanCache($table->module, $table->client_id);

		return true;
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param   object  $table  A record object.
	 *
	 * @return  array  An array of conditions to add to add to ordering queries.
	 *
	 * @since   1.6
	 */
	protected function getReorderConditions($table)
	{
		$condition = array();
		$condition[] = 'client_id = ' . (int) $table->client_id;
		$condition[] = 'position = ' . $this->_db->quote($table->position);

		return $condition;
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
		parent::cleanCache('com_utility', $this->getClient());
	}
}
