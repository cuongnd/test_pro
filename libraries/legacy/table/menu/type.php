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
 * Menu Types table
 *
 * @package     Joomla.Legacy
 * @subpackage  Table
 * @since       11.1
 */
class JTableMenuType extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  Database driver object.
	 *
	 * @since  11.1
	 */
	public function __construct(JDatabaseDriver $db)
	{
		parent::__construct('#__menu_types', 'id', $db);
	}

	/**
	 * Overloaded check function
	 *
	 * @return  boolean  True on success, false on failure
	 *
	 * @see     JTable::check()
	 * @since   11.1
	 */
	public function check($website_id=0)
	{
        if(!$website_id)
        {
            $website=JFactory::getWebsite();
            $website_id=$website->website_id;
        }

		$this->menutype = JApplication::stringURLSafe($this->menutype);

		if (empty($this->menutype))
		{
			$this->setError(JText::_('JLIB_DATABASE_ERROR_MENUTYPE_EMPTY'));

			return false;
		}

		// Sanitise data.
		if (trim($this->title) == '')
		{
			$this->title = $this->menutype;
		}
        //check exit menu type title
        $query=$this->_db->getQuery(true);
        $query->select('COUNT(*)')
            ->from('#__menu_types')
            ->where('website_id='.(int)$website_id)
            ->where('title LIKE '.$query->q("%$this->title%"))
            ->where('id <>'.(int)$this->id)
            ;
        $this->_db->setQuery($query);
        $total=$this->_db->loadResult();
        if($total>0)
        {
            $this->setError('menu type title exists, please select other');
            return false;
        }
        //end check exit menu type title
		// Check for unique menutype.
		$query = $this->_db->getQuery(true);
			$query->select('COUNT(id)')
			->from($this->_db->quoteName('#__menu_types'))
			->where($this->_db->quoteName('id') . ' <> ' . (int) $this->id)
            ->where('website_id='.(int)$website_id)
            ->where('menutype='.$query->q($this->menutype))
        ;
        $this->_db->setQuery($query);

		if ($this->_db->loadResult())
		{
			$this->setError(JText::sprintf('JLIB_DATABASE_ERROR_MENUTYPE_EXISTS', $this->menutype));

			return false;
		}

		return true;
	}


	/**
	 * Method to delete a row from the database table by primary key value.
	 *
	 * @param   mixed  $pk  An optional primary key value to delete.  If not set the instance property value is used.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link    http://docs.joomla.org/JTable/delete
	 * @since   11.1
	 */
	public function delete($pk = null)
	{
		$k = $this->_tbl_key;
		$pk = (is_null($pk)) ? $this->$k : $pk;

		// If no primary key is given, return false.
		if ($pk !== null)
		{
			//check is menu home
            $list_menu_item=MenusHelperFrontEnd::get_list_all_menu_item_by_menu_type_id($pk);
            foreach($list_menu_item as $menu_item)
            {
                if($menu_item->home==1)
                {
                    $this->setError('you cannot delete this menu type because it exists menu item home page');
                    return false;
                }

          }

		}

		return parent::delete($pk);
	}
}
