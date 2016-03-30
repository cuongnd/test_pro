<?php
/**
 * @package     Joomla.Legacy
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

/**
 * Module table
 *
 * @package     Joomla.Legacy
 * @subpackage  Table
 * @since       11.1
 */
class JTableModulemenu extends JTable
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
		parent::__construct('#__modules_menu', 'id', $db);

	}



	public function check()
	{

		return true;
	}

}
