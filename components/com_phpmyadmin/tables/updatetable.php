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
 * product table
 *
 * @package     Joomla.Legacy
 * @subpackage  Table
 * @since       11.1
 * @deprecated  Class will be removed upon completion of transition to UCM
 */
class JTableUpdateTable extends JTable
{
    /**
     * Constructor
     *
     * @param   JDatabaseDriver  $db  A database connector object
     *
     * @since   11.1
     */
    public function __construct(JDatabaseDriver $db,$table,$table_key='id')
    {
        parent::__construct('#__'.$table, $table_key, $db);

    }
    public function getFields()
    {
        $name   = $this->_tbl;
        $fields = $this->_db->getTableColumns($name, false);

        if (empty($fields))
        {
            throw new UnexpectedValueException(sprintf('No columns found for %s table', $name));
        }

        return $fields;
    }

}
