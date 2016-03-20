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
 * Table class supporting modified pre-order tree traversal behavior.
 *
 * @package     Joomla.Platform
 * @subpackage  Table
 * @link        http://docs.joomla.org/JTableNested
 * @since       11.1
 */
class JTableMenuItemMenuType extends JTable
{
    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__menu_type_id_menu_id', 'id', $db);

    }
}
