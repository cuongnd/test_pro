<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Menu Item List Model for Menus.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_menus
 * @since       1.6
 */
class phpMyAdminModelDataSources extends JModelList
{

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
	 */
	var $listPropertyTable=array();
	var $listDataSource=array();
	var $listPropertyColumn=array();
	static $current_data_source=array();
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'id', 'a.id',
				'menutype', 'a.menutype',
				'menu_type_id', 'mt.menu_type_id',
				'website_id', 'mt.website_id',
				'title', 'a.title',
				'alias', 'a.alias',
				'published', 'a.published',
				'access', 'a.access', 'access_level',
				'language', 'a.language',
				'checked_out', 'a.checked_out',
				'checked_out_time', 'a.checked_out_time',
				'lft', 'a.lft',
				'rgt', 'a.rgt',
				'level', 'a.level',
				'path', 'a.path',
				'client_id', 'a.client_id',
				'home', 'a.home',
				'a.ordering'
			);

			$app = JFactory::getApplication();
			$assoc = JLanguageAssociations::isEnabled();

			if ($assoc)
			{
				$config['filter_fields'][] = 'association';
			}
		}
		$this->listPropertyTable=array(
			'Name'
		,'Rows'
		,'Data_length'
		,'Collation'

		,'Index_length'
		);
		$this->listPropertyColumn=array(
			'TABLE_NAME'
		,'COLUMN_NAME'
		,'IS_NULLABLE'
		,'DATA_TYPE'
		,'COLLATION_NAME'
		,'COLUMN_TYPE'
		,'COLUMN_KEY'

		);
		parent::__construct($config);
	}
	public function getCurrentDataSources()
	{
		if(count(static::$current_data_source)>0)
		{
			return static::$current_data_source;
		}
		$website = JFactory::getWebsite();
		require_once JPATH_ROOT . '/components/com_utility/helper/utility.php';
		$screenSize = UtilityHelper::getCurrentScreenSizeEditing();
		$db=JFactory::getDbo();
		JTable::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/tables');
		$tableDataSource=JTable::getInstance('DataSource','JTable');
		$table_name_table_datasource=$tableDataSource->getTableName();
		$query=$db->getQuery(true);
		$query->from($table_name_table_datasource.' AS datasource')
			->select('*')
			->where('datasource.parent_id!=id')
			->where('datasource.website_id='.(int)$website->website_id)
		;
		$db->setQuery($query);
		$listDataSource=$db->loadObjectList();
		$listObject=array();
		$db->redirectPage(false);
		require_once JPATH_ROOT.'/components/com_phpmyadmin/helpers/datasource.php';
		foreach($listDataSource as $item_data_source)
		{
			$use_type=$item_data_source->use_type;
			//if use code php
			if($use_type=='code_php')
			{
				$file_php = JPATH_ROOT . '/cache/get_data_by_data_source_' . $item_data_source->id . '.php';
				$list = JUtility::get_content_file($item_data_source, $file_php, '#__datasource', 'php_content');
				for($i=0;$i<count($list);$i++)
				{
					$item=$list[$i];
					foreach($item as $key=>$value) {
						$value='['.$value.']';
						if (JUtility::isJson($value)) {
							$value=json_decode($value);
							if(is_object($value[0])||(is_array($value)&&count($value)>1))
							{
								if(count($value)==1)
								{
									$item->$key = $value[0];
								}else{
									$item->$key = $value;
								}
							}
						}
					}
					$list[$i]=(object)$item;
				}
				$object=new stdClass();
				$object->datasource=$item_data_source;
				$object->listField= $list;

				$listObject[]=$object;
			}else {
				$string_data_source = $item_data_source->datasource;
				$stringQuery1 = $string_data_source;
				require_once JPATH_ROOT . '/components/com_phpmyadmin/helpers/datasource.php';
				$string_data_source = DataSourceHelper::OverWriteDataSource($string_data_source);
				$query = $this->_db->getQuery(true);
				$query->setQuery(trim($string_data_source));
				$this->_db->setQuery($query,0,1);
				//echo $query->dump();
				if (trim($query->dump()) == '') {
					return array();
				}
				$this->_db->redirectPage(false);
				$list=array();
				$a_object=$this->_db->loadObject();
				if($a_object)
				{
					$list = array($a_object);
				}

				if (!count($list)) {
					require_once JPATH_ROOT . '/libraries/PHP-SQL-Parser/src/PHPSQLParser.php';
					$parser_query = new PHPSQLParser((string)$query, true);
					$list_field_select = $parser_query->parsed['SELECT'];
					$from = $parser_query->parsed['FROM'];

					$item = new stdClass();
					foreach ($list_field_select as $field) {
						if (is_array($field['alias'])) {
							$name = $field['alias']['name'];
							$item->$name = '';
						} else {
							$base_expr = $field['base_expr'];
							if($base_expr=='*')
							{
								$table_start='*';
								$table_name='';
							}else {
								$base_expr = explode('.', $base_expr);
								$table_name = $base_expr[0];
								$table_start = $base_expr[1];
							}
							$table_select = '';

							if ($table_start == '*') {
								$list_field = array();
								foreach ($from as $item_from) {
									$expr_type = $item_from['expr_type'];
									if ($expr_type == 'table') {
										$alias = $item_from['alias'];
										if (is_array($alias)) {
											if (trim($alias['name']) == trim($table_name)) {
												$table_select = $item_from['table'];
												break;
											}else if($table_name=='')
											{
												$table_select = $item_from['table'];
												break;
											}
										}
									}
								}
							}
							if ($table_select !== '') {
								$list_field = $db->getTableColumns($table_select);

							}
							foreach ($list_field as $field_name => $type) {
								$item->$field_name = '';
							}
						}
					}
					$list[] = $item;
				}

				for ($i = 0; $i < count($list); $i++) {
					$item = $list[$i];

					foreach ($item as $key => $value) {
						$value = '[' . $value . ']';
						if (JUtility::isJson($value)) {
							$value = json_decode($value);
							if (is_object($value[0]) || (is_array($value) && count($value) > 1)) {
								if (count($value) == 1) {
									$item->$key = $value[0];
								} else {
									$item->$key = $value;
								}
							}
						}
					}
					$list[$i] = $item;
				}
				$object=new stdClass();
				$object->datasource=$item_data_source;
				$object->listField= $list[0];
				$listObject[]=$object;
			}

		}
		$app=JFactory::getApplication();
		static::$current_data_source=$listObject;
		return  $listObject;
	}
	public function getListDataSource($bindingSource,$block)
	{
		$params = new JRegistry;
		$params->loadString($block->params);
		$debug=$params->get('debug',0);
		$app=JFactory::getApplication();
		$input=$app->input;
		$db=JFactory::getDbo();
		$bindingSource=explode('.',$bindingSource);
		$dataSourceId=$bindingSource[0];
		if($this->listDataSource[$dataSourceId]){
			return $this->listDataSource[$dataSourceId];
		}
		JTable::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/tables');
		$tableDataSource=JTable::getInstance('DataSource','JTable');
		$tableDataSource->load($dataSourceId);



		$use_type=$tableDataSource->use_type;
		//if use code php
		if($use_type=='code_php')
		{
			$file_php = JPATH_ROOT . '/cache/get_data_by_data_source_' . $tableDataSource->id . '.php';
			$list = JUtility::get_content_file($tableDataSource, $file_php, '#__datasource', 'php_content');
			for($i=0;$i<count($list);$i++)
			{
				$item=$list[$i];
				foreach($item as $key=>$value) {
					$value='['.$value.']';
					if (JUtility::isJson($value)) {
						$value=json_decode($value);
						if(is_object($value[0])||(is_array($value)&&count($value)>1))
						{
							if(count($value)==1)
							{
								$item->$key = $value[0];
							}else{
								$item->$key = $value;
							}
						}
					}
				}
				$list[$i]=(object)$item;
			}
			$this->listDataSource[$dataSourceId]=$list;
			return $list;



		}else {
			$datasource= $tableDataSource->datasource;
			$stringQuery1=$datasource;
			require_once JPATH_ROOT.'/components/com_phpmyadmin/helpers/datasource.php';
			$datasource=DataSourceHelper::OverWriteDataSource($datasource);
			$query=$this->_db->getQuery(true);

			$query->setQuery(trim($datasource));
			$this->_db->setQuery($query);
			if($debug)
			{
				echo $query->dump();
			}
			//echo $query->dump();
			if(trim($query)=='')
			{
				return array();
			}
			$this->_db->redirectPage(false);

			$list=$this->_db->loadObjectList();
			$list=DataSourceHelper::tree_node_data($stringQuery1,$list);
			if(!count($list))
			{
				require_once JPATH_ROOT.'/libraries/PHP-SQL-Parser/src/PHPSQLParser.php';
				$parser_query = new PHPSQLParser((string)$query, true);
				$list_field_select=$parser_query->parsed['SELECT'];
				$from=$parser_query->parsed['FROM'];
				$item=new stdClass();
				foreach($list_field_select as $field)
				{
					if(is_array($field['alias'])) {
						$name = $field['alias']['name'];
						$item->$name = '';
					}else
					{
						$base_expr=$field['base_expr'];
						$base_expr=explode('.',$base_expr);
						$table_name=$base_expr[0];
						$table_start=$base_expr[1];
						$table_select='';

						if($table_start=='*')
						{
							$list_field=array();
							foreach($from as $item_from)
							{
								$expr_type=$item_from['expr_type'];
								if($expr_type=='table')
								{
									$alias=$item_from['alias'];
									if(is_array($alias))
									{
										if(trim($alias['name'])==trim($table_name))
										{
											$table_select=$item_from['table'];
											break;
										}
									}
								}
							}
						}
						if($table_select!=='')
						{
							$list_field=$db->getTableColumns($table_select);
						}
						foreach($list_field as $field_name =>$type)
						{
							$item->$field_name = '';
						}
					}
				}
				$list[]=$item;
			}

			for($i=0;$i<count($list);$i++)
			{
				$item=$list[$i];

				foreach($item as $key=>$value) {
					$value='['.$value.']';
					if (JUtility::isJson($value)) {
						$value=json_decode($value);
						if(is_object($value[0])||(is_array($value)&&count($value)>1))
						{
							if(count($value)==1)
							{
								$item->$key = $value[0];
							}else{
								$item->$key = $value;
							}
						}
					}
				}
				$list[$i]=$item;
			}
			$this->listDataSource[$dataSourceId]=$list;
			return $list;
		}





	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication('administrator');

		$parentId = $this->getUserStateFromRequest($this->context . '.filter.parent_id', 'filter_parent_id', 0, 'int');
		$this->setState('filter.parent_id', $parentId);

		$search = $this->getUserStateFromRequest($this->context . '.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context . '.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);

		$parentId = $this->getUserStateFromRequest($this->context . '.filter.parent_id', 'filter_parent_id', 0, 'int');
		$this->setState('filter.parent_id', $parentId);

		$level = $this->getUserStateFromRequest($this->context . '.filter.level', 'filter_level', 0, 'int');
		$this->setState('filter.level', $level);
        $website_id = $this->getUserStateFromRequest($this->context . '.filter.website_id', 'filter_website_id',0, 'int');
        $this->setState('filter.website_id', $website_id);

        $website_id = $this->getUserStateFromRequest($this->context . '.filter.website_id', 'filter_website_id', 0);
        $this->setState('filter.website_id', $website_id);
        $menu_type_id = $this->getUserStateFromRequest($this->context . '.filter.menu_type_id', 'filter_menu_type_id',0 ,'int');
        if(!$menu_type_id)
        {
            $menu_type_id=$app->input->get('menu_type_id',0,'int');
        }
        if(!$menu_type_id)
        {
            $menu_type_id = $this->getDefaultMenuTypeId();
        }
        $this->setState('filter.menu_type_id', $menu_type_id);

		$language = $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', '');
		$this->setState('filter.language', $language);

		// Component parameters.
		$params = JComponentHelper::getParams('com_menus');
		$this->setState('params', $params);

		// List state information.
		parent::populateState('a.lft', 'asc');
	}

	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.language');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.parent_id');
		$id .= ':' . $this->getState('filter.menu_type_id');
		$id .= ':' . $this->getState('filter.website_id');

		return parent::getStoreId($id);
	}

	/**
	 * Finds the default menu type.
	 *
	 * In the absence of better information, this is the first menu ordered by title.
	 *
	 * @return  string    The default menu type
	 *
	 * @since   1.6
	 */
	public  function getDefaultMenuTypeId()
	{
		// Create a new query object.

        $db = $this->getDbo();
		$query = $db->getQuery(true)
			->select('id')
			->from('#__menu_types')
			->order('id');
        $upperAdmin=JFactory::isSupperAdmin();
        if($upperAdmin)
        {
            $website_id = $this->getUserStateFromRequest($this->context . '.filter.website_id', 'filter_website_id',0, 'int');
            $query->where('website_id='.(int)$website_id);
        }
        else
        {
            $website=JFactory::getWebsite();
            $query->where('website_id='.$website->website_id);

        }

		$db->setQuery($query, 0, 1);
        $menu_type_id = $db->loadResult();

		return $menu_type_id;
	}

	/**
	 * Builds an SQL query to load the list data.
	 *
	 * @return  JDatabaseQuery    A query object.
	 */
	public  function getListQuery()
	{
		// Create a new query object.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$user = JFactory::getUser();
		$app = JFactory::getApplication();
        $supperAdmin=JFactory::isSupperAdmin();
		// Select all fields from the table.
		$query->select(
			$this->getState(
				'list.select',
				$db->quoteName(
					array('a.id', 'a.menutype','mt.website_id', 'a.title', 'a.alias', 'a.note', 'a.path', 'a.link', 'a.type', 'a.parent_id', 'a.level', 'a.published', 'a.component_id', 'a.checked_out', 'a.checked_out_time', 'a.browserNav', 'a.access', 'a.img', 'a.template_style_id', 'a.params', 'a.lft', 'a.rgt', 'a.home', 'a.language', 'a.client_id'),
					array(null,null, null, null, null, null, null, null, null, null, null, 'apublished', null, null, null, null, null, null, null, null, null, null, null, null, null)
				)
			)
		);
		$query->select(
			'CASE a.type' .
				' WHEN ' . $db->quote('component') . ' THEN a.published+2*(e.enabled-1) ' .
				' WHEN ' . $db->quote('url') . ' THEN a.published+2 ' .
				' WHEN ' . $db->quote('alias') . ' THEN a.published+4 ' .
				' WHEN ' . $db->quote('separator') . ' THEN a.published+6 ' .
				' WHEN ' . $db->quote('heading') . ' THEN a.published+8 ' .
				' END AS published'
		);

		$query->from($db->quoteName('#__menu') . ' AS a');



		// Join over the language
		$query->select('l.title AS language_title, l.image as image')
			->join('LEFT', $db->quoteName('#__languages') . ' AS l ON l.lang_code = a.language');

		// Join over the users.
		$query->select('u.name AS editor')
			->join('LEFT', $db->quoteName('#__users') . ' AS u ON u.id = a.checked_out');

		// Join over components
		$query->select('c.element AS componentname')
			->join('LEFT', $db->quoteName('#__extensions') . ' AS c ON c.id = a.component_id');

		// Join over the asset groups.
		$query->select('ag.title AS access_level')
			->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');

		// Join over the associations.
		$assoc = JLanguageAssociations::isEnabled();

		if ($assoc)
		{
			$query->select('COUNT(asso2.id)>1 as association')
				->join('LEFT', '#__associations AS asso ON asso.id = a.id AND asso.context=' . $db->quote('com_menus.item'))
				->join('LEFT', '#__associations AS asso2 ON asso2.key = asso.key')
				->group('a.id');
		}

		// Join over the extensions
		$query->select('e.name AS name')
			->join('LEFT', '#__extensions AS e ON e.id = a.component_id');

		// Exclude the root category.

        if(!$supperAdmin)
        {
            $query->where('a.id > 1');
            $query->where('a.client_id = 0');
        }

		// Filter on the published state.
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published IN (0, 1))');
		}

		// Filter by search in title, alias or id
		if ($search = trim($this->getState('filter.search')))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			elseif (stripos($search, 'link:') === 0)
			{
				if ($search = substr($search, 5))
				{
					$search = $db->quote('%' . $db->escape($search, true) . '%');
					$query->where('a.link LIKE ' . $search);
				}
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(' . 'a.title LIKE ' . $search . ' OR a.alias LIKE ' . $search . ' OR a.note LIKE ' . $search . ')');
			}
		}

		// Filter the items over the parent id if set.
		$parentId = $this->getState('filter.parent_id');

		if (!empty($parentId))
		{
			$query->where('p.id = ' . (int) $parentId);
		}

		// Filter the items over the menu id if set.
        $menu_type_id = $this->getState('filter.menu_type_id');
        $query->leftJoin('#__menu_types AS mt ON mt.id=a.menu_type_id');
        $query->where('mt.id = ' . (int)$menu_type_id);
        $query->where('a.parent_id!=0');
		// Filter on the access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . (int) $access);
		}

		// Implement View Level Access
		if (!$user->authorise('core.admin'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}

		// Filter on the level.
		if ($level = $this->getState('filter.level'))
		{
			$query->where('a.level <= ' . (int) $level);
		}

		// Filter on the language.
		if ($language = $this->getState('filter.language'))
		{
			$query->where('a.language = ' . $db->quote($language));
		}

		// Add the list ordering clause.
		$query->order($db->escape($this->getState('list.ordering', 'a.lft')) . ' ' . $db->escape($this->getState('list.direction', 'ASC')));
        $supperAdmin=JFactory::isSupperAdmin();
        if($supperAdmin)
        {
            //filter by website
            $website_id = $this->getState('filter.website_id');
            if ($website_id)
            {
                $query->where('mt.website_id = ' . $website_id);
            }
        }
        else
        {
            //allway filter by website
            $website=JFactory::getWebsite();
            $website_id=$website->website_id;
            $domain=$website->domain;
            $query->where('mt.website_id = '.$website_id );
        }

        $query->where('a.alias !='.$query->q('root'));

		return $query;
	}
	public function getRemoteTables()
	{
		$db=JFactory::getDbo();
		$query='SHOW TABLE STATUS FROM webtemppro';
		$db->setQuery($query);
		$tables=$db->loadObjectList();
		$this->modifyFieldTable($tables);
		return $tables;
	}

	public function getRemotePropertiesTables()
	{

		$db=JFactory::getDbo();
		$query='SELECT * FROM information_schema.columns WHERE table_schema = "webtemppro"';
		$db->setQuery($query);

		$propertiesTables=$db->loadObjectList();

		$this->modifyFieldColumnOfTable($propertiesTables);
		return $propertiesTables;
	}
	public function modifyFieldTable(&$tables)
	{

		foreach($tables as $key1=>$properties)
		{

			foreach($properties as $key2=>$property)
			{

				if(!in_array($key2,$this->listPropertyTable))
				{
					unset($tables[$key1]->$key2);
				}
			}
		}
	}
	public function modifyFieldColumnOfTable(&$propertiesTables)
	{

		foreach($propertiesTables as $key1=>$properties)
		{

			foreach($properties as $key2=>$property)
			{

				if(!in_array($key2,$this->listPropertyColumn))
				{
					unset($propertiesTables[$key1]->$key2);
				}
			}
		}
	}


}
