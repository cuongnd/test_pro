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
class JTableRaovat extends JTable
{
    /**
     *
     * Constructor
     *
     * @param   JDatabaseDriver  $db  A database connector object
     *
     * @since   11.1
     */
    public $id=0;
    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__websitetemplatepro_raovat', 'id', $db);

    }
    public function check()
    {
        return true;
    }
    public function store($updateNulls = true)
    {
        return parent::store($updateNulls); // TODO: Change the autogenerated stub
    }
    /**
     * Overloaded bind function
     *
     * @param   array  $array   Named array
     * @param   mixed  $ignore  An optional array or space separated list of properties to ignore while binding.
     *
     * @return  mixed  Null if operation was satisfactory, otherwise returns an error
     *
     * @see     JTable::bind()
     * @since   11.1
     */


    #endregion
}
