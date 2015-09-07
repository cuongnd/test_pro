<?php
/**
 * Bookpro model class
 *
 * @package Bookpro
 * @author Nguyen Dinh Cuong
 * @link http://ibookingonline.com
 * @copyright Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @version $Id: model.php 56 2012-07-21 07:53:28Z quannv $
 */

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.model');

class BPModelList extends JModelLegacy
{
	/**
	 * Loaded data
	 *
	 * @var array
	 */
	var $_data = null;

	/**
	 * Items total
	 *
	 * @var int
	 */
	var $_total = null;

	/**
	 * Pagination object
	 *
	 * @var JPagination
	 */
	var $_pagination = null;

	/**
	 * Filter criteria
	 *
	 * @var array
	 */
	var $_lists = null;

	/**
	 * Database connector
	 *
	 * @var JDatabaseMySQL
	 */
	var $_db = null;



	function __construct()
	{
		parent::__construct();
		$this->_db = &JFactory::getDBO();
		 
	}

		

	/**
	 * Help function to generate where MySQL statement.
	 *
	 * @param array $where
	 * @param mixed $properties
	 */
	function addStringProperty(&$where, $properties)
	{
		for ($i = 1; $i < func_num_args(); $i ++) {
			$property = func_get_arg($i);
			if (isset($this->_lists[$property]) && $this->_lists[$property]) {
				$where[] = 'LOWER(' . $this->rquoteProperty($property) . ') LIKE ' . $this->_db->Quote('%' . JString::strtolower($this->_lists[$property]) . '%');
			}
		}
	}

	/**
	 * Help function to generate where MySQL statement.
	 *
	 * @param array $where
	 * @param mixed $properties
	 */
	function addIntProperty(&$where, $properties)
	{
		for ($i = 1; $i < func_num_args(); $i ++) {
			$property = func_get_arg($i);
			if (isset($this->_lists[$property]) && $this->_lists[$property]) {
				$where[] = $this->rquoteProperty($property) . ' = ' . (int) $this->_lists[$property];
			}
		}
	}

	/**
	 * Help function to generate where MySQL statement. Add statement for multiple property.
	 *
	 * @param array  $where       property to saving SQL where criterias
	 * @param string $property    name of property in filter
	 * @param string $sqlProperty name of property in SQL query, if empty is used name of propety in filter
	 * @return void
	 */
	function addMultipleProperty(&$where, $property, $sqlProperty = null)
	{
		if (isset($this->_lists[$property])) {
			AModel::clean($this->_lists[$property]);
			if (count($this->_lists[$property]))
			$where[] = $this->rquoteProperty($sqlProperty ? $sqlProperty : $property) . ' IN (' . implode(',', $this->_lists[$property]) . ')';
			else
			$where[] = $this->rquoteProperty($sqlProperty ? $sqlProperty : $property) . ' IS NULL';
		}
	}

	/**
	 * Init object by set limitstart and limit criteria and set array with complet filter data
	 */
	function init($lists)
	{
		
		$this->_lists = $lists;
		$this->_lists['limit'] = isset($this->_lists['limit']) ? (int) $this->_lists['limit'] : null;
		$this->_lists['limitstart'] = isset($this->_lists['limitstart']) ? (int) $this->_lists['limitstart'] : null;
		$this->_lists['limitstart'] = ARequest::getLimitstart((int) $this->_lists['limit'], (int) $this->_lists['limitstart']);
		$this->setState('limit', $this->_lists['limit']);
		$this->setState('limitstart', $this->_lists['limitstart']);
	}


	/**
	 * Get complet list data
	 *
	 * @return array item objects
	 */
	function getData()
	{
		if (empty($this->_data)) {
			$query = $this->buildQuery();
			$this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_data;
	}

	

	/**
	 * Get standard Joomla! pagination object fill with total, limitstart and limit criteria for list
	 *
	 * @return JPagination
	 */
	function getPagination()
	{
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			if (IS_ADMIN)
			$this->_pagination = new JPagination($this->getTotal($this), $this->getState('limitstart'), $this->getState('limit'));
			else {
				AImporter::helper('pagination');
				$this->_pagination = new BookProPagination($this->getTotal($this), $this->getState('limitstart'), $this->getState('limit'));
			}
		}
		return $this->_pagination;
	}

	/**
	 * Get total items count
	 *
	 * @return int total count limited by filter
	 */
	function getTotal()
	{
		if (empty($this->_total)) {
			$query = $this->buildQuery();
			$this->_total = $this->_getListCount($query);
		}
		return (int) $this->_total;
	}

	/**
	 * Get copy of main table object.
	 *
	 * @return JTable
	 */
	function getMainTable()
	{
		return $this->_table;
	}

	/**
	 * Get MySQL order criteria for items list
	 *
	 * @return string order criteria in MySQL format
	 */
	function buildContentOrderBy()
	{
		return ' ORDER BY ' . $this->rquoteProperty(isset($this->_lists['order']) && $this->_lists['order'] ? $this->_lists['order'] : 'id') . ' ' . (isset($this->_lists['order_Dir']) && $this->_lists['order_Dir'] ? $this->_lists['order_Dir'] : 'DESC');
	}

	/**
	 * Get MySQL where statement.
	 *
	 * @param array $where
	 * @return string
	 */
	function getWhere(&$where)
	{
		return count($where) ? ' WHERE ' . implode(' AND ', $where) : '';
	}

	/**
	 * Build MySQL statement with date limit from to.
	 *
	 * @param array  $where array (reference) to store SQL query where criterias.
	 * @param string $from  name of property with from date value in filter and SQL query.
	 * @param string $to    name of property with to date value in filter and SQL query.
	 * @return void
	 */
	function addTimeLimit(&$where, $from, $to)
	{
		if (isset($this->_lists[$from]) && ($dbfrom = AModel::datetime2save($this->_lists[$from])))
		$where[] = $this->rquote($from) . ' >= \'' . $dbfrom . '\'';
		if (isset($this->_lists[$to]) && ($dbto = AModel::datetime2save($this->_lists[$to])))
		$where[] = $this->rquote($to) . ' <= \'' . $dbto . '\'';
	}
	function addTimeRange(&$where, $field, $from, $to)
	{
		if (isset($this->_lists[$from]) && ($dbfrom = AModel::datetime2save($this->_lists[$from])))
			$where[] = ($field) . ' >= \'' . $dbfrom . '\'';
		if (isset($this->_lists[$to]) && ($dbto = AModel::datetime2save($this->_lists[$to])))
			$where[] = ($field) . ' <= \'' . $dbto . '\'';
	}

	/**
	 * Set object state property. Allow set option from state.
	 *
	 * @param string $field name
	 * @param array $cids objects IDs
	 * @param int $toValue to set value
	 * @param mixed $fromValue set allow from state, can be array or single value or null
	 * @return boolean success sign
	 */
	function state($field, $cids, $toValue, $fromValue)
	{
		$args = func_num_args();
		$fromValue = array_slice(func_get_args(), 3, $args - 3);
		array_walk($fromValue, array($this , 'quote'));
		array_walk($cids, array($this , 'quote'));
		$field = $this->rquote($field);
		$query = 'UPDATE ' . $this->rquote($this->_table->getTableName()) . ' SET ' . $field . ' = ' . $this->quote($toValue);
		$id = 'id';
		$query .= ' WHERE ' . $this->rquote($id) . ' IN (' . implode(',', $cids) . ')';
		$query .= ' AND ' . $field . ' IN (' . implode(',', $fromValue) . ')';
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Empty trashed objects.
	 *
	 * @param string $field name of field where is saved infromation about trashed
	 * @param string $value value of field where is saved infromation about trashed
	 */
	function emptyTrash($field, $value)
	{
		$query = 'DELETE FROM ' . $this->rquote($this->_table->getTableName()) . ' WHERE ' . $this->rquote($field) . ' = ' . $this->_db->Quote($value);
		$this->_db->setQuery($query);
		return $this->_db->query();
	}

	/**
	 * Add single quote to begin and end of string. This function is usable to call back function in array walk method.
	 *
	 * @param string $str
	 * @param int $key
	 * @return string
	 */
	function quote(&$str, $key = 0)
	{
		$str = $this->_db->Quote($str);
		return $str;
	}

	/**
	 * Add reverse single quote to begin and end of string.
	 *
	 * @param string $str
	 * @return string
	 */
	function rquote(&$str, $key = null)
	{
		$str = '`' . $str . '`';
		return $str;
	}

	/**
	 * Add reverse single quote to begin and end of string, where string is
	 * contain from parts.
	 *
	 * @param string $property
	 * @return string
	 */
	function rquoteProperty($property)
	{
		$property = explode('-', $property);
		array_walk($property, array($this , 'rquote'));
		return implode('.', $property);
	}

	/**
	 * Check out object before editing.
	 *
	 * @return boolean success sign
	 */
	function checkout()
	{
		$user = &JFactory::getUser();
		return $this->_table->checkout($user->get('id'), $this->_id);
	}

	/**
	 * Check in object after editing.
	 *
	 * @return boolean success sign
	 */
	function checkin()
	{
		return $this->_table->checkin($this->_id);
	}

	function clean(&$array)
	{
		foreach ($array as $i => $item) {
			if ($item === false) {
				unset($array[$i]);
			} elseif (is_string($item)) {
				$array[$i] = $this->_db->Quote($item);
			}
		}
	}

	/**
	 * Help function to generate boolean property in MySQL statement.
	 *
	 * @param array $where
	 * @param string $field
	 * @param array $values
	 */
	function addBooleanPropertyIntoWhere(&$where, $field, $values)
	{
		$keys = array_keys($values);
		if (count($keys) == 1) {
			$where[] = $field . ' = ' . (int) reset($keys);
		}
	}

	/**
	 * Get table total rows count without applied any filter.
	 *
	 * @return int
	 */
	function getTableTotal()
	{
		$this->_db->setQuery('SELECT COUNT(`id`) FROM `' . $this->_table->getTableName() . '`');
		$tableTotel = $this->_db->loadResult();
		return $tableTotel;
	}

	/**
	 * Check majority browse filter params.
	 *
	 * @param $filter
	 */
	function checkBrowseFilter(&$filter)
	{
		if ($filter->limit == 0 || $filter->limit >= $filter->total)
		$filter->limitstart = 0;
		else
		$filter->limitstart = floor($filter->limitstart / $filter->limit) * $filter->limit;
		if ($filter->total < $filter->limitstart)
		$filter->limitstart = floor($filter->total / $filter->limit) * $filter->limit;
		if ($filter->total == $filter->limitstart)
		$filter->limitstart = (floor($filter->total / $filter->limit) - 1) * $filter->limit;
		$filter->count = $filter->limitstart + $filter->limit;
		if ($filter->count > $filter->total || $filter->limit == 0)
		$filter->count = $filter->total;
	}

	/**
	 * Check if table name exists in database.
	 *
	 * @param string $name add table name in Joomla internal format with prefix mask like #__content or #__users
	 * @return boolean
	 */
	function tableExists($name)
	{
		static $cache;
		$db = &JFactory::getDBO();
		/* @var $db JDatabaseMySQL */
		if (is_null($cache)) {
			$query = 'SHOW TABLES';
			$db->setQuery($query);
			$cache = &$db->loadResultArray();
		}
		$name = str_replace('#__', $db->getPrefix(), $name);
		$tableExists = in_array($name, $cache);
		return $tableExists;
	}

	/**
	 * Prepare datetime to save into database.
	 *
	 * @param string $datetime date string in format to work with PHP strftime (Joomla 1.5.x.) or date (Joomla 1.6.x) method.
	 * @return string database format in GMT0
	 */
	function datetime2save($datetime)
	{
		return AModel::jdate2save($datetime, ADATE_FORMAT_MYSQL_DATETIME, true);
	}

	/**
	 * Prepare date to save into database.
	 *
	 * @param string $date date string in format to work with PHP strftime (Joomla 1.5.x.) or date (Joomla 1.6.x) method.
	 * @return string database format in locale
	 */
	function date2save($date)
	{
		return AModel::jdate2save($date, ADATE_FORMAT_MYSQL_DATE, false);
	}

	/**
	 * Prepare time to save into database.
	 *
	 * @param string $time time string in format to work with PHP strftime (Joomla 1.5.x.) or date (Joomla 1.6.x) method.
	 * @return string database format in GMT0
	 */
	function time2save($time)
	{
		return AModel::jdate2save($time, ADATE_FORMAT_MYSQL_TIME, true);
	}

	/**
	 * Get current datetime in GMTO for using in SQL query.
	 *
	 * @return string
	 */
	function getNow()
	{
		return AModel::jdate2save('now', ADATE_FORMAT_MYSQL_DATETIME, true);
	}

	/**
	 * Get null date as 0000-00-00 00:00:00 for using in SQL query.
	 *
	 * @return string
	 */
	function getNullDate()
	{
		$db = &JFactory::getDBO();
		/* @var $db JDatabaseMySQL */
		return $db->getNullDate();
	}

	/**
	 * Prepare date to save into database.
	 *
	 * @param string  $date     date string in format to work with PHP strftime (Joomla 1.5.x.) or date (Joomla 1.6.x) method.
	 * @param string  $format   format string to ouput (database format).
	 * @param boolean $date2gmt true/false - return in GMT0/return in locale.
	 * @return string database format in locale or GMT0.
	 */
	function jdate2save($jdate, $format, $date2gmt, $test = false)
	{
		if (($jdate = JString::trim($jdate))) {
			$mainframe = &JFactory::getApplication();
			/* @var $mainframe JApplication */
			$jdate = &JFactory::getDate($jdate, $tzoffset = $mainframe->getCfg('offset'));
			/* @var $jdate JDate */
			return $jdate->format($format, ! $date2gmt);
		}
		return null;
	}

	/**
	 * Get concrete user access levels.
	 *
	 * @param $userId ID of user, if empty take current logged user
	 * @return array of available access levels
	 */
	function getAccess($userId = null)
	{
		$user = &JFactory::getUser($userId);
		 
		return $user->getAuthorisedViewLevels();
		 
	}

	/**
	 * Get list of available user access levels.
	 *
	 * @return array where key is ID and value title of access level order by set ordering
	 */
	function getAccesList()
	{
		static $access;
		if (is_null($access)) {
			
				$db = &JFactory::getDBO();
				/* @var $db JDatabaseMySQL */
				$db->setQuery('SELECT `id`,`title` FROM `#__viewlevels` ORDER BY `ordering`');
				foreach ($db->loadRowList() as $row)
				$access[$row[0]] = $row[1];
			
		}
		return $access;
	}

	/**
	 * Check if usergroup exists.
	 *
	 * @param int $set usergroup ID to check
	 * @return int given usergroup ID if exists or default value
	 */
	function checkUserGroup($set)
	{
		$db = &JFactory::getDBO();
		/* @var $db JDatabaseMySQL */
		 
		$db->setQuery('SELECT COUNT(`id`) FROM `#__usergroups` WHERE `id` = ' . (int) $set);
		 
		return $db->loadResult() == 1 ? $set : CUSTOMER_GID;
	}
}


?>