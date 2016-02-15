<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_phpmyadmin
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * datasource model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_phpmyadmin
 * @since       1.6
 */
class phpMyAdminModelDataSource extends JModelAdmin
{
    /**
     * @var    string  The prefix to use with controller messages.
     * @since  1.6
     */
    protected $text_prefix = 'com_phpmyadmin';

    /**
     * @var    string  The help screen key for the datasource.
     * @since  1.6
     */
    protected $helpKey = 'JHELP_EXTENSIONS_datasource_MANAGER_EDIT';

    /**
     * @var    string  The help screen base URL for the datasource.
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

        if (!$pk) {
            if ($extensionId = (int)$app->getUserState('com_phpmyadmin.add.datasource.id')) {
                $this->setState('extension.id', $extensionId);
            }
        }

        $this->setState('datasource.id', $pk);

        // Load the parameters.
        $params = JComponentHelper::getParams('com_phpmyadmin');
        $this->setState('params', $params);
    }


    public function list_field_by_data_source($data_source,$block_id)
    {
        JTable::addIncludePath(JPATH_ROOT.'/libraries/legacy/table');
        $tablePosition=JTable::getInstance('PositionNested','JTable');
        $tablePosition->load($block_id);
        $modalDataSources=JModelLegacy::getInstance('DataSources','phpMyAdminModel');
        $list_item=$modalDataSources->getListDataSource($data_source,$tablePosition);
        $params_item = new JRegistry ;
        foreach($list_item as $key=> $item)
        {
            $params_item1 = new JRegistry;
            $params_item1->loadObject($item);
            $params_item1->merge($params_item);
            $params_item=$params_item1;

        }
        $item=$params_item->toObject();
        $list_field=array();
        phpMyAdminModelDataSource::push_to_object($item,$list_field);
        return $list_field;

    }
    function push_to_object($item,&$list_field=array(),$path='')
    {
        if(is_object($item)||is_array($item))
        {
            foreach ($item as $key_node=> $node) {
                $path1=$path?$path.'.'.$key_node:$key_node;
                if(is_object($node)||is_array($node))
                {
                    phpMyAdminModelDataSource::push_to_object($node,$list_field,$path1);
                }else {
                    $list_field[]=$path1;
                }
            }
        }
    }


    public function duplicateAndAssign(&$pks, $website_id = 0)
    {
        $user = JFactory::getUser();
        $db = $this->getDbo();
        $tuples = array();
        /*        // Access checks.
                if (!$user->authorise('core.create', 'com_phpmyadmin'))
                {
                    throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
                }
        */
        $table = $this->getTable();

        foreach ($pks as $pk) {
            if ($table->load($pk, true)) {
                // Reset the id to create a new record.
                $table->copy_from = $table->id;
                $table->id = 0;
                $table->website_id = $website_id;
                $table->updated = 0;

                // Alter the title.
                $m = null;
                if (preg_match('#\((\d+)\)$#', $table->title, $m)) {
                    $table->title = preg_replace('#\(\d+\)$#', '(' . ($m[1] + 1) . ')', $table->title);
                }
                // Unpublish duplicate datasource

                if (!$table->check() || !$table->store()) {
                    throw new Exception($table->getError());
                }
                $query = $db->getQuery(true)
                    ->select($db->quoteName('menuid'))
                    ->from($db->quoteName('#__datasources_menu'))
                    ->where($db->quoteName('datasourceid') . ' = ' . (int)$pk);

                $this->_db->setQuery($query);
                $rows = $this->_db->loadColumn();

                foreach ($rows as $menuid) {
                    $tuples[] = (int)$table->id . ',' . (int)$menuid;
                }


            } else {
                throw new Exception($table->getError());
            }
        }
        if (!empty($tuples)) {
            // datasource-Menu Mapping: Do it in one query
            $query = $db->getQuery(true)
                ->insert($db->quoteName('#__datasources_menu'))
                ->columns($db->quoteName(array('datasourceid', 'menuid')))
                ->values($tuples);

            $this->_db->setQuery($query);

            try {
                $this->_db->execute();
            } catch (RuntimeException $e) {
                return JError::raiseWarning(500, $e->getMessage());
            }
        }

        // Clear datasources cache
        $this->cleanCache();

        return true;
    }


    function ajaxSaveForm($pks)
    {
        $input = JFactory::getApplication()->input;
        $title = $input->get('title', array(), 'array');
        $table = $this->getTable();
        $pks = (array)$pks;

        foreach ($pks as $i => $pk) {
            if ($table->load($pk)) {
                $table->title = $title[$pk];
                $table->store();
            }
        }

        // Clean the cache
        $this->cleanCache();

        // Ensure that previous checks doesn't empty the array
        if (empty($pks)) {
            return true;
        }
    }

    /**
     * Method to perform batch operations on a set of datasources.
     *
     * @param   array $commands An array of commands to perform.
     * @param   array $pks An array of item ids.
     * @param   array $contexts An array of item contexts.
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
        if (array_search(0, $pks, true)) {
            unset($pks[array_search(0, $pks, true)]);
        }

        if (empty($pks)) {
            $this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));
            return false;
        }

        $done = false;

        if (!empty($commands['position_id'])) {
            $cmd = JArrayHelper::getValue($commands, 'move_copy', 'c');

            if (!empty($commands['position_id'])) {
                if ($cmd == 'c') {
                    $result = $this->batchCopy($commands['position_id'], $pks, $contexts);
                    if (is_array($result)) {
                        $pks = $result;
                    } else {
                        return false;
                    }
                } elseif ($cmd == 'm' && !$this->batchMove($commands['position_id'], $pks, $contexts)) {
                    return false;
                }
                $done = true;
            }
        }

        if (!empty($commands['assetgroup_id'])) {
            if (!$this->batchAccess($commands['assetgroup_id'], $pks, $contexts)) {
                return false;
            }

            $done = true;
        }

        if (!empty($commands['language_id'])) {
            if (!$this->batchLanguage($commands['language_id'], $pks, $contexts)) {
                return false;
            }

            $done = true;
        }

        if (!$done) {
            $this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));
            return false;
        }

        // Clear the cache
        $this->cleanCache();

        return true;
    }

    public function quick_assign_website(&$pks, $website_id)
    {
        $user = JFactory::getUser();
        $db = $this->getDbo();

        // Access checks.
        if (!$user->authorise('core.create', 'com_phpmyadmin')) {
            throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
        }

        $table = $this->getTable();

        foreach ($pks as $pk) {
            if ($table->load($pk, true)) {

                $table->website_id = $website_id;

                if (!$table->check() || !$table->store()) {
                    throw new Exception($table->getError());
                }

            } else {
                throw new Exception($table->getError());
            }
        }


        // Clear datasources cache
        $this->cleanCache();

        return true;
    }

    /**
     * Batch copy datasources to a new position or current.
     *
     * @param   integer $value The new value matching a datasource position.
     * @param   array $pks An array of row IDs.
     * @param   array $contexts An array of item contexts.
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

        foreach ($pks as $pk) {
            if ($user->authorise('core.create', 'com_phpmyadmin')) {
                $table->reset();
                $table->load($pk);

                // Set the new position
                if ($value == 'noposition') {
                    $position = '';
                } elseif ($value == 'nochange') {
                    $position = $table->position;
                } else {
                    $position = $value;
                }
                $table->position = $position;

                // Alter the title if necessary
                $data = $this->generateNewTitle(0, $table->title, $table->position);
                $table->title = $data['0'];

                // Reset the ID because we are making a copy
                $table->id = 0;

                // Unpublish the new datasource
                $table->published = 0;

                if (!$table->store()) {
                    $this->setError($table->getError());
                    return false;
                }

                // Get the new item ID
                $newId = $table->get('id');

                // Add the new ID to the array
                $newIds[$i] = $newId;
                $i++;

                // Now we need to handle the datasource assignments
                $db = $this->getDbo();
                $query = $db->getQuery(true)
                    ->select($db->quoteName('menuid'))
                    ->from($db->quoteName('#__datasources_menu'))
                    ->where($db->quoteName('datasourceid') . ' = ' . $pk);
                $db->setQuery($query);
                $menus = $db->loadColumn();

                // Insert the new records into the table
                foreach ($menus as $menu) {
                    $query->clear()
                        ->insert($db->quoteName('#__datasources_menu'))
                        ->columns(array($db->quoteName('datasourceid'), $db->quoteName('menuid')))
                        ->values($newId . ', ' . $menu);
                    $db->setQuery($query);
                    $db->execute();
                }
            } else {
                $this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_CREATE'));
                return false;
            }
        }

        // Clean the cache
        $this->cleanCache();

        return $newIds;
    }

    /**
     * Batch move datasources to a new position or current.
     *
     * @param   integer $value The new value matching a datasource position.
     * @param   array $pks An array of row IDs.
     * @param   array $contexts An array of item contexts.
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

        foreach ($pks as $pk) {
            if ($user->authorise('core.edit', 'com_phpmyadmin')) {
                $table->reset();
                $table->load($pk);

                // Set the new position
                if ($value == 'noposition') {
                    $position = '';
                } elseif ($value == 'nochange') {
                    $position = $table->position;
                } else {
                    $position = $value;
                }
                $table->position = $position;

                // Alter the title if necessary
                $data = $this->generateNewTitle(0, $table->title, $table->position);
                $table->title = $data['0'];

                // Unpublish the moved datasource
                $table->published = 0;

                if (!$table->store()) {
                    $this->setError($table->getError());
                    return false;
                }
            } else {
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
     * @param   object $record A record object.
     *
     * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
     * @since   3.2
     */
    protected function canEditState($record)
    {
        $user = JFactory::getUser();

        // Check for existing datasource.
        if (!empty($record->id)) {
            return $user->authorise('core.edit.state', 'com_phpmyadmin.datasource.' . (int)$record->id);
        } // Default to component settings if datasource not known.
        else {
            return parent::canEditState('com_phpmyadmin');
        }
    }

    /**
     * Method to delete rows.
     *
     * @param   array &$pks An array of item ids.
     *
     * @return  boolean  Returns true on success, false on failure.
     *
     * @since   1.6
     * @throws  Exception
     */
    public function delete(&$pks)
    {
        $pks = (array)$pks;
        $user = JFactory::getUser();
        $table = $this->getTable();

        // Iterate the items to delete each one.
        foreach ($pks as $pk) {
            if ($table->load($pk)) {
                // Access checks.
                if (!$user->authorise('core.delete', 'com_phpmyadmin.datasource.' . (int)$pk) || $table->published != -2) {
                    JError::raiseWarning(403, JText::_('JERROR_CORE_DELETE_NOT_PERMITTED'));
                    return;
                }

                if (!$table->delete($pk)) {
                    throw new Exception($table->getError());
                } else {
                    // Delete the menu assignments
                    $db = $this->getDbo();
                    $query = $db->getQuery(true)
                        ->delete('#__datasources_menu')
                        ->where('datasourceid=' . (int)$pk);
                    $db->setQuery($query);
                    $db->execute();
                }

                // Clear datasource cache
                parent::cleanCache($table->datasource, $table->client_id);
            } else {
                throw new Exception($table->getError());
            }
        }

        // Clear datasources cache
        $this->cleanCache();

        return true;
    }

    /**
     * Method to duplicate datasources.
     *
     * @param   array &$pks An array of primary key IDs.
     *
     * @return  boolean  True if successful.
     *
     * @since   1.6
     * @throws  Exception
     */
    public function duplicate(&$pks)
    {
        $user = JFactory::getUser();
        $db = $this->getDbo();

        // Access checks.
        if (!$user->authorise('core.create', 'com_phpmyadmin')) {
            throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
        }

        $table = $this->getTable();

        foreach ($pks as $pk) {
            if ($table->load($pk, true)) {
                // Reset the id to create a new record.
                $table->id = 0;

                // Alter the title.
                $m = null;
                if (preg_match('#\((\d+)\)$#', $table->title, $m)) {
                    $table->title = preg_replace('#\(\d+\)$#', '(' . ($m[1] + 1) . ')', $table->title);
                } else {
                    $table->title .= ' (2)';
                }
                // Unpublish duplicate datasource
                $table->published = 0;

                if (!$table->check() || !$table->store()) {
                    throw new Exception($table->getError());
                }

                $query = $db->getQuery(true)
                    ->select($db->quoteName('menuid'))
                    ->from($db->quoteName('#__datasources_menu'))
                    ->where($db->quoteName('datasourceid') . ' = ' . (int)$pk);

                $this->_db->setQuery($query);
                $rows = $this->_db->loadColumn();

                foreach ($rows as $menuid) {
                    $tuples[] = (int)$table->id . ',' . (int)$menuid;
                }
            } else {
                throw new Exception($table->getError());
            }
        }

        if (!empty($tuples)) {
            // datasource-Menu Mapping: Do it in one query
            $query = $db->getQuery(true)
                ->insert($db->quoteName('#__datasources_menu'))
                ->columns($db->quoteName(array('datasourceid', 'menuid')))
                ->values($tuples);

            $this->_db->setQuery($query);

            try {
                $this->_db->execute();
            } catch (RuntimeException $e) {
                return JError::raiseWarning(500, $e->getMessage());
            }
        }

        // Clear datasources cache
        $this->cleanCache();

        return true;
    }

    /**
     * Method to change the title.
     *
     * @param   integer $category_id The id of the category. Not used here.
     * @param   string $title The title.
     * @param   string $position The position.
     *
     * @return  array  Contains the modified title.
     *
     * @since   2.5
     */
    protected function generateNewTitle($category_id, $title, $position)
    {
        // Alter the title & alias
        $table = $this->getTable();
        while ($table->load(array('position' => $position, 'title' => $title))) {
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
     * @param   array $data Data for the form.
     * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return  JForm  A JForm object on success, false on failure
     *
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // The folder and element vars are passed when saving the form.
        if (empty($data)) {
            $item = $this->getItem();
            $clientId = $item->client_id;
            $datasource = $item->datasource;
            $id = $item->id;
        } else {
            $clientId = JArrayHelper::getValue($data, 'client_id');
            $datasource = JArrayHelper::getValue($data, 'datasource');
            $id = JArrayHelper::getValue($data, 'id');
        }

        // These variables are used to add data from the plugin XML files.
        $this->setState('item.client_id', $clientId);
        $this->setState('item.datasource', $datasource);

        // Get the form.
        $form = $this->loadForm('com_phpmyadmin.datasource', 'datasource', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }


        $user = JFactory::getUser();

        // Check for existing datasource
        // Modify the form based on Edit State access controls.
        if ($id != 0 && (!$user->authorise('core.edit.state', 'com_phpmyadmin.datasource.' . (int)$id))
            || ($id == 0 && !$user->authorise('core.edit.state', 'com_phpmyadmin'))
        ) {
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
        $data = JFactory::getApplication()->getUserState('com_phpmyadmin.edit.datasource.data', array());

        if (empty($data)) {
            $data = $this->getItem();

            // This allows us to inject parameter settings into a new datasource.
            $params = $app->getUserState('com_phpmyadmin.add.datasource.params');
            if (is_array($params)) {
                $data->set('params', $params);
            }
        }

        $this->preprocessData('com_phpmyadmin.datasource', $data);

        return $data;
    }

    /**
     * Method to get a single record.
     *
     * @param   integer $pk The id of the primary key.
     *
     * @return  mixed  Object on success, false on failure.
     *
     * @since   1.6
     */
    public function getItem($pk = null)
    {

        $pk = (!empty($pk)) ? (int)$pk : (int)$this->getState('datasource.id');
        $db = $this->getDbo();

        if (!isset($this->_cache[$pk])) {
            $false = false;

            // Get a row instance.
            $table = $this->getTable();

            // Attempt to load the row.
            $return = $table->load($pk);

            // Check for a table object error.
            if ($return === false && $error = $table->getError()) {
                $this->setError($error);

                return $false;
            }
            $pk = 551;
            // Check if we are creating a new extension.
            if (empty($pk)) {
                if ($extensionId = (int)$this->getState('extension.id')) {
                    $query = $db->getQuery(true)
                        ->select('element, client_id')
                        ->from('#__extensions')
                        ->where('id = ' . $extensionId)
                        ->where('type = ' . $db->quote('datasource'));
                    $db->setQuery($query);

                    try {
                        $extension = $db->loadObject();
                    } catch (RuntimeException $e) {
                        $this->setError($e->getMessage);

                        return false;
                    }

                    if (empty($extension)) {
                        $this->setError('com_phpmyadmin_ERROR_CANNOT_FIND_datasource');

                        return false;
                    }

                    // Extension found, prime some datasource values.
                    $table->datasource = $extension->element;
                    $table->client_id = $extension->client_id;
                } else {
                    $app = JFactory::getApplication();
                    $app->redirect(JRoute::_('index.php?option=com_phpmyadmin&view=datasources', false));

                    return false;
                }
            }

            // Convert to the JObject before adding other data.
            $properties = $table->getProperties(1);
            $this->_cache[$pk] = JArrayHelper::toObject($properties, 'JObject');

            // Convert the params field to an array.
            $registry = new JRegistry;
            $registry->loadString($table->params);
            $this->_cache[$pk]->params = $registry->toArray();


            // Get the datasource XML.
            $client = JApplicationHelper::getClientInfo($table->client_id);
            $path = JPath::clean($client->path . '/datasources/' . $table->datasource . '/' . $table->datasource . '.xml');

            if (file_exists($path)) {
                $this->_cache[$pk]->xml = simplexml_load_file($path);
            } else {
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
        return (object)array('key' => $this->helpKey, 'url' => $this->helpURL);
    }

    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param   string $type The table type to instantiate
     * @param   string $prefix A prefix for the table class name. Optional.
     * @param   array $config Configuration array for model. Optional.
     *
     * @return  JTable  A database object
     *
     * @since   1.6
     */
    public function getTable($type = 'DataSource', $prefix = 'JTable', $config = array())
    {
        JTable::addIncludePath(JPATH_ROOT . '/components/com_phpmyadmin/tables');
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @param   JTable $table The database object
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function prepareTable($table)
    {
        $table->title = htmlspecialchars_decode($table->title, ENT_QUOTES);
        $table->position = trim($table->position);
    }


    /**
     * Loads ContentHelper for filters before validating data.
     *
     * @param   object $form The form to validate against.
     * @param   array $data The data to validate.
     * @param   string $group The name of the group(defaults to null).
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
     * @param   array $data The form data.
     *
     * @return  boolean  True on success.
     *
     * @since   1.6
     */
    public function save($data)
    {
        $db = JFactory::getDbo();
        $dispatcher = JEventDispatcher::getInstance();
        $input = JFactory::getApplication()->input;
        $table = $this->getTable();
        $pk = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('datasource.id');
        $isNew = true;

        // Include the content datasources for the onSave events.
        JPluginHelper::importPlugin('extension');

        // Load the row if saving an existing record.
        if ($pk > 0) {
            $table->load($pk);
            $isNew = false;
        }

        // Alter the title and published state for Save as Copy
        if ($input->get('task') == 'save2copy') {
            $orig_data = $input->post->get('jform', array(), 'array');
            $orig_table = clone($this->getTable());
            $orig_table->load((int)$orig_data['id']);

            if ($data['title'] == $orig_table->title) {
                $data['title'] .= ' ' . JText::_('JGLOBAL_COPY');
                $data['published'] = 0;
            }
        }

        // Bind the data.
        if (!$table->bind($data)) {
            $this->setError($table->getError());

            return false;
        }

        // Prepare the row for saving
        $this->prepareTable($table);

        // Check the data.
        if (!$table->check()) {
            $this->setError($table->getError());

            return false;
        }

        // Trigger the onExtensionBeforeSave event.
        $result = $dispatcher->trigger('onExtensionBeforeSave', array('com_phpmyadmin.datasource', &$table, $isNew));

        if (in_array(false, $result, true)) {
            $this->setError($table->getError());

            return false;
        }

        // Store the data.
        if (!$table->store()) {
            $this->setError($table->getError());

            return false;
        }

        // Process the menu link mappings.
        $assignment = isset($data['assignment']) ? $data['assignment'] : 0;


        // If the assignment is numeric, then something is selected (otherwise it's none).
        if (is_numeric($assignment)) {
            // Variable is numeric, but could be a string.
            $assignment = (int)$assignment;

            // Logic check: if no datasource excluded then convert to display on all.
            if ($assignment == -1 && empty($data['assigned'])) {
                $assignment = 0;
            }

            // Check needed to stop a datasource being assigned to `All`
            // and other menu items resulting in a datasource being displayed twice.
        }

        // Trigger the onExtensionAfterSave event.
        $dispatcher->trigger('onExtensionAfterSave', array('com_phpmyadmin.datasource', &$table, $isNew));

        // Compute the extension id of this datasource in case the controller wants it.
        $query = $db->getQuery(true)
            ->select('e.id as id')
            ->from('#__extensions AS e')
            ->join('LEFT', '#__datasources AS m ON e.element = m.datasource')
            ->where('m.id = ' . (int)$table->id);
        $db->setQuery($query);

        try {
            $extensionId = $db->loadResult();
        } catch (RuntimeException $e) {
            JError::raiseWarning(500, $e->getMessage());

            return false;
        }

        $this->setState('datasource.id', $extensionId);
        $this->setState('datasource.id', $table->id);

        // Clear datasources cache
        $this->cleanCache();

        // Clean datasource cache
        parent::cleanCache($table->datasource, $table->client_id);

        return true;
    }

    /**
     * A protected method to get a set of ordering conditions.
     *
     * @param   object $table A record object.
     *
     * @return  array  An array of conditions to add to add to ordering queries.
     *
     * @since   1.6
     */
    protected function getReorderConditions($table)
    {
        $condition = array();
        $condition[] = 'client_id = ' . (int)$table->client_id;
        $condition[] = 'position = ' . $this->_db->quote($table->position);

        return $condition;
    }

    /**
     * Custom clean cache method for different clients
     *
     * @param   string $group The name of the plugin group to import (defaults to null).
     * @param   integer $client_id The client ID. [optional]
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function cleanCache($group = null, $client_id = 0)
    {
        parent::cleanCache('com_phpmyadmin', $this->getClient());
    }
}
