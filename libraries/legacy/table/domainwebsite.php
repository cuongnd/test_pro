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
class JTableDomainwebsite extends JTable
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
		parent::__construct('#__domain_website', 'id', $db);
	}

    public function check()
    {
        if(!$this->website_id)
        {
            $this->setError('there are no website setting');
            return false;
        }
        if(!$this->domain)
        {
            $this->setError('there are no domain website');
            return false;
        }
        $query=$this->_db->getQuery(true);
        $query->select('COUNT(*)')
            ->from('#__domain_website')
            ->where('domain='.$query->q($this->domain))
            ;
        $total=$this->_db->setQuery($query)->loadResult();
        if($total)
        {
            $this->setError('there are  domain exists');
            return false;
        }
        return true;
    }
}
