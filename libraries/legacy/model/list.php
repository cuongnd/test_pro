<?php
/**
 * @package     Joomla.Legacy
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

/**
 * Model class for handling lists of items.
 *
 * @package     Joomla.Legacy
 * @subpackage  Model
 * @since       12.2
 */
class JModelList extends JModelLegacy
{
	/**
	 * Internal memory based cache array of data.
	 *
	 * @var    array
	 * @since  12.2
	 */
	protected $cache = array();

	/**
	 * Context string for the model type.  This is used to handle uniqueness
	 * when dealing with the getStoreId() method and caching data structures.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $context = null;
	public $column_fields = null;

	/**
	 * Valid filter fields or ordering.
	 *
	 * @var    array
	 * @since  12.2
	 */
	protected $filter_fields = array();

	/**
	 * An internal cache for the last query used.
	 *
	 * @var    JDatabaseQuery
	 * @since  12.2
	 */
	protected $query = array();

	/**
	 * Name of the filter form to load
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $filterFormName = null;
	protected $ShowColumn = null;

	/**
	 * Associated HTML form
	 *
	 * @var  string
	 */
	protected $htmlFormName = 'adminForm';

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JModelLegacy
	 * @since   12.2
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		// Add the ordering filtering fields white list.
		if (isset($config['filter_fields']))
		{
			$this->filter_fields = $config['filter_fields'];
		}

		// Guess the context as Option.ModelName.
		if (empty($this->context))
		{
			$this->context = strtolower($this->option . '.' . $this->getName());
		}
	}

	/**
	 * Method to cache the last query constructed.
	 *
	 * This method ensures that the query is constructed only once for a given state of the model.
	 *
	 * @return  JDatabaseQuery  A JDatabaseQuery object
	 *
	 * @since   12.2
	 */
	protected function _getListQuery()
	{
		// Capture the last store id used.
		static $lastStoreId;

		// Compute the current store id.
		$currentStoreId = $this->getStoreId();

		// If the last store id is different from the current, refresh the query.
		if ($lastStoreId != $currentStoreId || empty($this->query))
		{
			$lastStoreId = $currentStoreId;
			$this->query = $this->getListQuery();
		}

		return $this->query;
	}

	/**
	 * Function to get the active filters
	 *
	 * @return  array  Associative array in the format: array('filter_published' => 0)
	 *
	 * @since   3.2
	 */
	public function getActiveFilters()
	{
		$activeFilters = array();

		if (!empty($this->filter_fields))
		{
			foreach ($this->filter_fields as $filter)
			{
				$filterName = 'filter.' . $filter;

				if (property_exists($this->state, $filterName) && (!empty($this->state->{$filterName}) || is_numeric($this->state->{$filterName})))
				{
					$activeFilters[$filter] = $this->state->get($filterName);
				}
			}
		}

		return $activeFilters;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function getItems()
	{
		// Get a storage key.
		$store = $this->getStoreId();

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the list items.
		$query = $this->_getListQuery();

		try
		{

			$items = $this->_getList($query, $this->getStart(), $this->getState('list.limit'));
		}
		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		// Add the items to the internal cache.
		$this->cache[$store] = $items;

		return $this->cache[$store];
	}

	/**
	 * Method to get a JDatabaseQuery object for retrieving the data set from a database.
	 *
	 * @return  JDatabaseQuery   A JDatabaseQuery object to retrieve the data set.
	 *
	 * @since   12.2
	 */
	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);

		return $query;
	}
	/**
	 * Method to get a JPagination object for the data set.
	 *
	 * @return  JPagination  A JPagination object for the data set.
	 *
	 * @since   12.2
	 */
	public function getPagination()
	{
		// Get a storage key.
		$store = $this->getStoreId('getPagination');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Create the pagination object.
		$limit = (int) $this->getState('list.limit') - (int) $this->getState('list.links');
		$page = new JPagination($this->getTotal(), $this->getStart(), $limit);

		// Add the object to the internal cache.
		$this->cache[$store] = $page;

		return $this->cache[$store];
	}

	/**
	 * Method to get a store id based on the model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  An identifier string to generate the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   12.2
	 */
	protected function getStoreId($id = '')
	{
		// Add the list state to the store id.
		$id .= ':' . $this->getState('list.start');
		$id .= ':' . $this->getState('list.limit');
		$id .= ':' . $this->getState('list.ordering');
		$id .= ':' . $this->getState('list.direction');
		return md5($this->context . ':' . $id);
	}

	/**
	 * Method to get the total number of items for the data set.
	 *
	 * @return  integer  The total number of items available in the data set.
	 *
	 * @since   12.2
	 */
	public function getTotal()
	{
		// Get a storage key.
		$store = $this->getStoreId('getTotal');
		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		// Load the total.
		$query = $this->_getListQuery();

		try
		{
			$total = (int) $this->_getListCount($query);



		}

		catch (RuntimeException $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		// Add the total to the internal cache.
		$this->cache[$store] = $total;

		return $this->cache[$store];
	}

	/**
	 * Method to get the starting number of items for the data set.
	 *
	 * @return  integer  The starting number of items available in the data set.
	 *
	 * @since   12.2
	 */
	public function getStart()
	{
		$store = $this->getStoreId('getstart');

		// Try to load the data from internal storage.
		if (isset($this->cache[$store]))
		{
			return $this->cache[$store];
		}

		$start = $this->getState('list.start');
		$limit = $this->getState('list.limit');
		$total = $this->getTotal();

		if ($start > $total - $limit)
		{
			$start = max(0, (int) (ceil($total / $limit) - 1) * $limit);
		}

		// Add the total to the internal cache.
		$this->cache[$store] = $start;

		return $this->cache[$store];
	}

	/**
	 * Get the filter form
	 *
	 * @param   array    $data      data
	 * @param   boolean  $loadData  load current data
	 *
	 * @return  JForm/false  the JForm object or false
	 *
	 * @since   3.2
	 */
	public function getFilterForm($data = array(), $loadData = true)
	{
		$form = null;

		// Try to locate the filter form automatically. Example: ContentModelArticles => "filter_articles"
		if (empty($this->filterFormName))
		{
			$classNameParts = explode('Model', get_called_class());

			if (count($classNameParts) == 2)
			{
				$this->filterFormName = 'filter_' . strtolower($classNameParts[1]);
			}
		}

		if (!empty($this->filterFormName))
		{
			// Get the form.
			$form = $this->loadForm($this->context . '.filter', $this->filterFormName, array('control' => '', 'load_data' => $loadData));
		}

		return $form;
	}

	public function render_to_xml_show_column()
	{
		$model_name=$this->name;
		$component=$this->option;
		$component_path=JPath::get_component_path($component,false);
		$xml_show_column_path=$component_path.DS."models/forms/table_".$model_name.".xml";
		$table_control=JTable::getInstance('control');
		$table_control->load(array(
			"element_path"=>$xml_show_column_path
		));
		$fields=$table_control->fields;
		$fields=base64_decode($fields);
		require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
		$fields = (array)up_json_decode($fields, false, 512, JSON_PARSE_JAVASCRIPT);
		ob_start();
		self::render_to_xml($fields);
		$string_xml=ob_get_clean();
		$string_xml='<form>'.$string_xml.'</form>';
		jimport('joomla.filesystem.file');
		JFile::write(JPATH_ROOT.'/'.$xml_show_column_path,$string_xml);

	}

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
							echo '<fields name="'.strtolower($item->name).'">';
						}
					}else{
						echo '<fields name="'.strtolower($item->name).'">';
					}
					JModelAdmin::render_to_xml($item->children,  $maxLevel, $level1);
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
						   name="<?php echo $name ?>" <?php if($item->onchange){ ?> onchange="<?php echo strtolower($item->onchange) ?>" <?php } ?>


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
						   name="enable_<?php echo $name ?>" <?php if($item->onchange){ ?> onchange="<?php echo strtolower($item->onchange) ?>" <?php } ?> >

					</field>
					<?php
				}

			}

		}

	}

	public function getColumnFields(){
		$model_name=$this->name;
		$component=$this->option;
		$component_path=JPath::get_component_path($component,false);
		$xml_show_column_path=$component_path.DS."models/forms/table_".$model_name.".xml";
		$table_control=JTable::getInstance('control');
		$table_control->load(array(
			"element_path"=>$xml_show_column_path
		));
		$fields=$table_control->fields;
		$fields=base64_decode($fields);
		require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
		$fields = (array)up_json_decode($fields, false, 512, JSON_PARSE_JAVASCRIPT);
		foreach($fields as $key=>&$field){
			$field->config_property=base64_decode($field->config_property);
			$field->config_property=json_decode($field->config_property);

		}
		return $fields;
	}
	public function getListControlList(){
		$model_name=$this->name;
		$component=$this->option;
		$component_path=JPath::get_component_path($component,false);
		$xml_show_column_path=$component_path.DS."models/forms/control_list_".$model_name.".xml";
		$table_control=JTable::getInstance('control');
		$table_control->load(array(
			"element_path"=>$xml_show_column_path
		));
		$fields=$table_control->fields;
		$fields=base64_decode($fields);
		require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
		$fields = (array)up_json_decode($fields, false, 512, JSON_PARSE_JAVASCRIPT);
		return $fields;
	}
	public function getHiddenFieldList(){
		$model_name=$this->name;
		$component=$this->option;
		$component_path=JPath::get_component_path($component,false);
		$xml_show_column_path=$component_path.DS."models/forms/hidden_field_list_".$model_name.".xml";
		$table_control=JTable::getInstance('control');
		$table_control->load(array(
			"element_path"=>$xml_show_column_path
		));
		$fields=$table_control->fields;
		$fields=base64_decode($fields);
		require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
		$fields = (array)up_json_decode($fields, false, 512, JSON_PARSE_JAVASCRIPT);
		return $fields;
	}
	public function getShowColumn($data = array(), $loadData = true)
	{
		self::render_to_xml_show_column();
		$form = null;

		// Try to locate the filter form automatically. Example: ContentModelArticles => "filter_articles"
		if (empty($this->ShowColumn))
		{
			$classNameParts = explode('Model', get_called_class());

			if (count($classNameParts) == 2)
			{
				$this->ShowColumn = 'table_' . strtolower($classNameParts[1]);
			}
		}

		if (!empty($this->ShowColumn))
		{
			// Get the form.
			$form = $this->loadForm($this->context . '.table', $this->ShowColumn, array('control' => '', 'load_data' => $loadData));
		}

		return $form;
	}

	/**
	 * Method to get a form object.
	 *
	 * @param   string   $name     The name of the form.
	 * @param   string   $source   The form source. Can be XML string if file flag is set to false.
	 * @param   array    $options  Optional array of options for the form creation.
	 * @param   boolean  $clear    Optional argument to force load a new form.
	 * @param   string   $xpath    An optional xpath to search for the fields.
	 *
	 * @return  mixed  JForm object on success, False on error.
	 *
	 * @see     JForm
	 * @since   3.2
	 */
	protected function loadForm($name, $source = null, $options = array(), $clear = false, $xpath = false)
	{
		// Handle the optional arguments.
		$options['control'] = JArrayHelper::getValue($options, 'control', false);

		// Create a signature hash.
		$hash = md5($source . serialize($options));

		// Check if we can use a previously loaded form.
		if (isset($this->_forms[$hash]) && !$clear)
		{
			return $this->_forms[$hash];
		}

		// Get the form.
		JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT . '/models/fields');

		try
		{
			$form = JForm::getInstance($name, $source, $options, false, $xpath);

			if (isset($options['load_data']) && $options['load_data'])
			{
				// Get the data for the form.
				$data = $this->loadFormData();
			}
			else
			{
				$data = array();
			}

			// Allow for additional modification of the form, and events to be triggered.
			// We pass the data because plugins may require it.
			$this->preprocessForm($form, $data);

			// Load the data into the form after the plugins have operated.
			$form->bind($data);
		}
		catch (Exception $e)
		{
			$this->setError($e->getMessage());

			return false;
		}

		// Store the form for later.
		$this->_forms[$hash] = $form;

		return $form;
	}
    public function getQueryListCommand()
    {
        return $this->getListQuery();
    }

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 *
	 * @since	3.2
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState($this->context, new stdClass);

		// Pre-fill the list options
		if (!property_exists($data, 'list'))
		{
			$data->list = array(
				'direction' => $this->state->{'list.direction'},
				'limit'     => $this->state->{'list.limit'},
				'ordering'  => $this->state->{'list.ordering'},
				'start'     => $this->state->{'list.start'}
			);
		}

		return $data;
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * This method should only be called once per instantiation and is designed
	 * to be called on the first call to the getState() method unless the model
	 * configuration flag to ignore the request is set.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	protected function populateState($ordering = null, $direction = null)
	{

		// If the context is set, assume that stateful lists are used.
		if ($this->context)
		{
			$app = JFactory::getApplication();
			// Receive & set filters
			if ($filters = $app->getUserStateFromRequest($this->context . '.filter', 'filter', array(), 'array'))
			{
				foreach ($filters as $name => $value)
				{
					$this->setState('filter.' . $name, $value);
				}
			}

			$limit = 0;

			// Receive & set list options
			if ($list = $app->getUserStateFromRequest($this->context . '.list', 'list', array(), 'array'))
			{

				foreach ($list as $name => $value)
				{
					// Extra validations
					switch ($name)
					{
						case 'fullordering':
							$orderingParts = explode(' ', $value);

							if (count($orderingParts) >= 2)
							{
								// Latest part will be considered the direction
								$fullDirection = end($orderingParts);

								if (in_array(strtoupper($fullDirection), array('ASC', 'DESC', '')))
								{
									$this->setState('list.direction', $fullDirection);
								}

								unset($orderingParts[count($orderingParts) - 1]);

								// The rest will be the ordering
								$fullOrdering = implode(' ', $orderingParts);

								if (in_array($fullOrdering, $this->filter_fields))
								{
									$this->setState('list.ordering', $fullOrdering);
								}
							}
							else
							{
								$this->setState('list.ordering', $ordering);
								$this->setState('list.direction', $direction);
							}
							break;

						case 'ordering':
							if (!in_array($value, $this->filter_fields))
							{
								$value = $ordering;
							}
							break;

						case 'direction':
							if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
							{
								$value = $direction;
							}
							break;

						case 'limit':
							$limit = $value;
							break;

						// Just to keep the default case
						default:
							$value = $value;
							break;
					}

					$this->setState('list.' . $name, $value);
				}
			}
			else
			// Keep B/C for components previous to jform forms for filters
			{
				// Pre-fill the limits
				$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->get('list_limit'), 'uint');
				$this->setState('list.limit', $limit);

				// Check if the ordering field is in the white list, otherwise use the incoming value.
				$value = $app->getUserStateFromRequest($this->context . '.ordercol', 'filter_order', $ordering);

				if (!in_array($value, $this->filter_fields))
				{
					$value = $ordering;
					$app->setUserState($this->context . '.ordercol', $value);
				}

				$this->setState('list.ordering', $value);

				// Check if the ordering direction is valid, otherwise use the incoming value.
				$value = $app->getUserStateFromRequest($this->context . '.orderdirn', 'filter_order_Dir', $direction);

				if (!in_array(strtoupper($value), array('ASC', 'DESC', '')))
				{
					$value = $direction;
					$app->setUserState($this->context . '.orderdirn', $value);
				}

				$this->setState('list.direction', $value);
			}

			// Support old ordering field
			$oldOrdering = $app->input->get('filter_order');

			if (!empty($oldOrdering) && in_array($value, $this->filter_fields))
			{
				$this->setState('list.ordering', $oldOrdering);
			}

			// Support old direction field
			$oldDirection = $app->input->get('filter_order_Dir');

			if (!empty($oldDirection) && in_array(strtoupper($oldDirection), array('ASC', 'DESC', '')))
			{
				$this->setState('list.direction', $oldDirection);
			}

			$value = $app->getUserStateFromRequest($this->context . '.limitstart', 'limitstart', 0);
			$limitstart = ($limit != 0 ? (floor($value / $limit) * $limit) : 0);
			$this->setState('list.start', $limitstart);
		}
		else
		{
			$this->setState('list.start', 0);
			$this->setState('list.limit', 0);
		}
	}

	/**
	 * Method to allow derived classes to preprocess the form.
	 *
	 * @param   JForm   $form   A JForm object.
	 * @param   mixed   $data   The data expected for the form.
	 * @param   string  $group  The name of the plugin group to import (defaults to "content").
	 *
	 * @return  void
	 *
	 * @since   3.2
	 * @throws  Exception if there is an error in the form event.
	 */
	protected function preprocessForm(JForm $form, $data, $group = 'content')
	{
		// Import the appropriate plugin group.
		JPluginHelper::importPlugin($group);

		// Get the dispatcher.
		$dispatcher = JDispatcher::getInstance();

		// Trigger the form preparation event.
		$results = $dispatcher->trigger('onContentPrepareForm', array($form, $data));

		// Check for errors encountered while preparing the form.
		if (count($results) && in_array(false, $results, true))
		{
			// Get the last error.
			$error = $dispatcher->getError();

			if (!($error instanceof Exception))
			{
				throw new Exception($error);
			}
		}
	}

	/**
	 * Gets the value of a user state variable and sets it in the session
	 *
	 * This is the same as the method in JApplication except that this also can optionally
	 * force you back to the first page when a filter has changed
	 *
	 * @param   string   $key        The key of the user state variable.
	 * @param   string   $request    The name of the variable passed in a request.
	 * @param   string   $default    The default value for the variable if not found. Optional.
	 * @param   string   $type       Filter for the variable, for valid values see {@link JFilterInput::clean()}. Optional.
	 * @param   boolean  $resetPage  If true, the limitstart in request is set to zero
	 *
	 * @return  The request user state.
	 *
	 * @since   12.2
	 */
	public function getUserStateFromRequest($key, $request, $default = null, $type = 'none', $resetPage = true)
	{
		$app       = JFactory::getApplication();
		$input     = $app->input;
		$old_state = $app->getUserState($key);
		$cur_state = (!is_null($old_state)) ? $old_state : $default;
		$new_state = $input->get($request, null, $type);

		if (($cur_state != $new_state) && ($resetPage))
		{
			$input->set('limitstart', 0);
		}

		// Save the new value only if it is set in this request.
		if ($new_state !== null)
		{
			$app->setUserState($key, $new_state);
		}
		else
		{
			$new_state = $cur_state;
		}

		return $new_state;
	}
}
