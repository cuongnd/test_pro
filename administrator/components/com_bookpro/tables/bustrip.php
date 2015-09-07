<?php
/**
 * @package     Joomla.Legacy
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * bustrip table
 *
 * @package     Joomla.Legacy
 * @subpackage  Table
 * @since       11.1
 */
class JTablebustrip extends JTable
{
	/**
	 * Constructor.
	 *
	 * @param   JDatabaseDriver  $db  Database driver object.
	 *
	 * @since   11.1
	 */
	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__bookpro_bustrip', 'id', $db);

	}



	/**
	 * Overloaded check function.
	 *
	 * @return  boolean  True if the instance is sane and able to be stored in the database.
	 *
	 * @see     JTable::check()
	 * @since   11.1
	 */
	public function check()
	{


		return true;
	}

}
