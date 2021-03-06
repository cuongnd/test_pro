<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

/**
 * Viewlevels table class.
 *
 * @package     Joomla.Platform
 * @subpackage  Table
 * @since       11.1
 */
class JTableViewlevel extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  Database driver object.
	 *
	 * @since   11.1
	 */
	public function __construct($db)
	{
		parent::__construct('#__viewlevels', 'id', $db);
	}

	/**
	 * Method to bind the data.
	 *
	 * @param   array  $array   The data to bind.
	 * @param   mixed  $ignore  An array or space separated list of fields to ignore.
	 *
	 * @return  boolean  True on success, false on failure.
	 *
	 * @since   11.1
	 */
	public function bind($array, $ignore = '')
	{
		// Bind the rules as appropriate.
		if (isset($array['rules']))
		{
			if (is_array($array['rules']))
			{
				$array['rules'] = json_encode($array['rules']);
			}
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Method to check the current record to save
	 *
	 * @return  boolean  True on success
	 *
	 * @since   11.1
	 */
	public function check()
	{
		// Validate the title.
		if ((trim($this->title)) == '')
		{
			$this->setError(JText::_('JLIB_DATABASE_ERROR_VIEWLEVEL'));

			return false;
		}

		return true;
	}
	public function store($updateNulls = false)
	{
		if (parent::store($updateNulls)) {

			if($this->is_publish==1)
			{
				$db=JFactory::getDbo();
				$query=$db->getQuery(true)
					->update('#__viewlevels')
					->set('is_publish=0')
					->where('website_id='.(int)$this->website_id)
					->where('id!='.(int)$this->id)
					;
				if($db->setQuery($query)->execute())
				{
					return true;
				}else{
					$this->setError($db->getErrorMsg());
					return false;
				}
			}
			return true;
		}else{
			return false;
		}
	}

}
