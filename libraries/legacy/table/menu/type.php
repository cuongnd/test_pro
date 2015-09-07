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
	public function check()
	{
        $supperAdmin=JFactory::isSupperAdmin();
        if($supperAdmin)
        {
            if (!$this->website_id)
            {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_WEBSITE_EMPTY'));
                return false;
            }
        }
        else
        {
            $app=JFactory::getApplication();
            $option=$app->input->getString('option','');
            if($app->getClientId()==0&&$option=='com_website')
            {

            }
            else
            {
                $website=JFactory::getWebsite();
                $this->website_id=$website->website_id;
            }

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

		// Check for unique menutype.
		$query = $this->_db->getQuery(true)
			->select('COUNT(id)')
			->from($this->_db->quoteName('#__menu_types'))
			->where($this->_db->quoteName('menutype') . ' = ' . $this->_db->quote($this->menutype))
			->where($this->_db->quoteName('id') . ' <> ' . (int) $this->id);
        if($supperAdmin)
        {
                $query->where('website_id='.(int)$this->website_id);
        }
        else
        {
            $app=JFactory::getApplication();
            $option=$app->input->getString('option','');
            if($app->getClientId()==0&&$option=='com_website')
            {
                $query->where('website_id='.(int)$this->website_id);
            }
            else
            {
                $website=JFactory::getWebsite();
                $this->website_id=$website->website_id;
                $query->where('website_id='.(int)$this->website_id);
            }

        }
        $this->_db->setQuery($query);

		if ($this->_db->loadResult())
		{
			$this->setError(JText::sprintf('JLIB_DATABASE_ERROR_MENUTYPE_EXISTS', $this->menutype));

			return false;
		}

		return true;
	}

	/**
	 * Method to store a row in the database from the JTable instance properties.
	 * If a primary key value is set the row with that primary key value will be
	 * updated with the instance property values.  If no primary key value is set
	 * a new row will be inserted into the database with the properties from the
	 * JTable instance.
	 *
	 * @param   boolean  $updateNulls  True to update fields even if they are null.
	 *
	 * @return  boolean  True on success.
	 *
	 * @link    http://docs.joomla.org/JTable/store
	 * @since   11.1
	 */
	public function store($updateNulls = false)
	{
		if ($this->id)
		{
			// Get the user id
			$userId = JFactory::getUser()->id;

			// Get the old value of the table
			$table = JTable::getInstance('Menutype', 'JTable');
			$table->load($this->id);

			// Verify that no items are checked out
			$query = $this->_db->getQuery(true)
				->select('id')
				->from('#__menu')
				->where('menu_type_id=' . (int)$this->id)
				->where('checked_out !=' . (int) $userId)
				->where('checked_out !=0');
			$this->_db->setQuery($query);

			if ($this->_db->loadRowList())
			{

				$this->setError(
					JText::sprintf('JLIB_DATABASE_ERROR_STORE_FAILED', get_class($this), JText::_('JLIB_DATABASE_ERROR_MENUTYPE_CHECKOUT'))
				);

				return false;
			}

		}

        if(parent::store($updateNulls))
        {

            $query = $this->_db->getQuery(true)
                ->select('id')
                ->from('#__menu')
                ->where('parent_id = id')
                ->where('menu_type_id='.(int)$this->id)
            ;
            $result = $this->_db->setQuery($query)->loadResult();
            if(!$result)
            {

                $query->clear();
                $query->select('MAX(id)')
                    ->from('#__menu');
                $parent_id=$this->_db->setQuery($query)->loadResult();
                if($parent_id)
                    $parent_id++;
                else
                    $parent_id=1;
                $root=new stdClass();
                $root->id=$parent_id;
                $root->menu_type_id=$this->id;
                $root->title=$query->q('Menu_Item_Root');
                $root->alias=$query->q('root');
                $root->published=1;
                $root->parent_id=$parent_id;
                $root->level=0;
                $root->lft=0;
                $root->rgt=0;
                $listKeyOfObjectRoot=array();
                $listValueOfObjectRoot=array();
                foreach($root as $key=>$value)
                {
                    $listKeyOfObjectRoot[]=$key;
                    $listValueOfObjectRoot[]=$value;

                }
                $query->clear();
                //	 * $query->insert('#__a')->columns('id, title')->values(array('1,2', '3,4'));
                $query->insert('#__menu')
                    ->columns(implode(',',$listKeyOfObjectRoot))
                    ->values(implode(',',$listValueOfObjectRoot));
                $this->_db->setQuery($query);
                if(!$this->_db->execute())
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
                $insertId=$this->_db->insertid();
                $query=$query->clear();
                $query->update('#__menu')->set('parent_id='.(int)$insertId)->where('id='.(int)$insertId);
                $this->_db->setQuery($query);
                if(!$this->_db->execute())
                {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
            return true;
        }
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
			// Get the user id
			$userId = JFactory::getUser()->id;

			// Get the old value of the table
			$table = JTable::getInstance('Menutype', 'JTable');
			$table->load($pk);

			// Verify that no items are checked out
			$query = $this->_db->getQuery(true)
				->select('id')
				->from('#__menu')
				->where('menu_type_id=' . $pk)
				->where('client_id=0')
				->where('(checked_out NOT IN (0,' . (int) $userId . ') OR home=1 AND language=' . $this->_db->quote('*') . ')');
			$this->_db->setQuery($query);
			if ($this->_db->loadRowList())
			{
				$this->setError(JText::sprintf('JLIB_DATABASE_ERROR_DELETE_FAILED', get_class($this), JText::_('JLIB_DATABASE_ERROR_MENUTYPE')));

				return false;
			}

			// Verify that no module for this menu are checked out
			$query->clear()
				->select('id')
				->from('#__modules')
                ->where(array(
                    '(module=' . $this->_db->quote('mod_menu').' AND client_id=0)',
                    '(module=' . $this->_db->quote('mod_jbmenu').' AND client_id=0)'
                ),'OR')
                ->where('params LIKE ' . $this->_db->quote('%"menu_type_id":' . $pk . '%'))
				->where('checked_out !=' . (int) $userId)
				->where('checked_out !=0');
			$this->_db->setQuery($query);
			if ($this->_db->loadRowList())
			{
				$this->setError(JText::sprintf('JLIB_DATABASE_ERROR_DELETE_FAILED', get_class($this), JText::_('JLIB_DATABASE_ERROR_MENUTYPE')));

				//return false;
			}

			// Delete the menu items
			$query->clear()
				->delete('#__menu')
				->where('menu_type_id=' . (int)$pk)
				->where('client_id=0');
			$this->_db->setQuery($query);
			$this->_db->execute();

			// Update the module items
			$query->clear()
				->delete('#__modules')
                ->where(array(
                    '(module=' . $this->_db->quote('mod_menu').' AND client_id=0)',
                    '(module=' . $this->_db->quote('mod_jbmenu').' AND client_id=0)'
                ),'OR')
                ->where('params LIKE ' . $this->_db->quote('%"menu_type_id":' . $pk . '%'))
            ;
			$this->_db->setQuery($query);

			$this->_db->execute();
		}

		return parent::delete($pk);
	}
}
