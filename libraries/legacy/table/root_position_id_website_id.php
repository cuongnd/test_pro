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
 * Content table
 *
 * @package     Joomla.Legacy
 * @subpackage  Table
 * @since       11.1
 * @deprecated  Class will be removed upon completion of transition to UCM
 */
class JTableRoot_position_id_website_id extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  A database connector object
	 *
	 * @since   11.1
	 */
	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__root_position_id_website_id', 'id', $db);
	}

    public function check()
    {

        return true;
    }
}
