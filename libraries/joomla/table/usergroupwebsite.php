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
 * Extension table
 * Replaces components table
 *
 * @package     Joomla.Platform
 * @subpackage  Table
 * @since       11.1
 */
class JTableUserGroupWebsite extends JTable
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
		parent::__construct('#__user_group_id_website_id', 'id', $db);
	}


}
