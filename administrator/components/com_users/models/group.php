<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * User group model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_users
 * @since       1.6
 */
class UsersModelGroup extends JModelAdmin
{
	/**
	 * @var		string	The event to trigger after saving the data.
	 * @since   1.6
	 */
	protected $event_after_save = 'onUserAfterSaveGroup';

	/**
	 * @var		string	The event to trigger after before the data.
	 * @since   1.6
	 */
	protected $event_before_save = 'onUserBeforeSaveGroup';

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	 * @since   1.6
	*/
	public function getTable($type = 'Usergroup', $prefix = 'JTable', $config = array())
	{
		$return = JTable::getInstance($type, $prefix, $config);
		return $return;
	}
	public function rebuild($pks)
	{
		// Initialiase variables.
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$table = $this->getTable();
		foreach ($pks as $pk)
		{
			if (!$table->rebuild($pk))
			{
				$this->setError($table->getError());
				return false;
			}

		}


		return true;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array  $data		An optional array of data for the form to interogate.
	 * @param   boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return  JForm	A JForm object on success, false on failure
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_users.group', 'group', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_users.edit.group.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_users.group', $data);

		return $data;
	}
    public function duplicateAndAssign(&$pks,$website_id=0)
    {
        $app=JFactory::getApplication();
        $user	= JFactory::getUser();
        $db		= JFactory::getDbo();
        $option=$app->input->getString('option','');
        if ($app->getClientId()==0& $option!='com_website')
        {
            throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
        }

        // Access checks.
        if ($app->getClientId()==1& !$user->authorise('core.create', 'com_user'))
        {
            throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
        }

        $table = $this->getTable();
        require_once JPATH_ROOT.'/administrator/components/com_users/helpers/groups.php';
        $query=$db->getQuery(true);
        $rootId=GroupsHelper::createRootGroup($website_id);
        $firstpk=reset($pks);
        // We need to log the parent ID
        $parents = array();
        $query->clear();
        // Calculate the emergency stop count as a precaution against a runaway loop bug
        $query->select('ug1.*')
            ->from('#__usergroups AS ug1')
            ->leftJoin('#__usergroups AS ug2 ON ug2.website_id=ug1.website_id')
            ->where('ug2.id='.(int)$firstpk)
            ->where('ug1.title!='.$query->q('Public'))
        ;

        $db->setQuery($query);

        try
        {
            $count = count($db->loadObjectList());
        }
        catch (RuntimeException $e)
        {
            $this->setError($e->getMessage());
            return false;
        }
        $i = 0;
        // Parent exists so we let's proceed
        while (!empty($pks) && $count > 0)
        {
            // Pop the first id off the stack
            $pk = array_shift($pks);

            $table->reset();

            // Check that the row actually exists
            if (!$table->load($pk))
            {
                if ($error = $table->getError())
                {
                    // Fatal error
                    $this->setError($error);
                    return false;
                }
                else
                {
                    // Not fatal error
                    $this->setError(JText::sprintf('JGLOBAL_BATCH_MOVE_ROW_NOT_FOUND', $pk));
                    continue;
                }
            }

            // Copy is a bit tricky, because we also need to copy the children
            $query->clear()
                ->select('ug1.id')
                ->from('#__usergroups AS ug1')
                ->where('ug1.lft > ' . (int) $table->lft)
                ->where('ug1.rgt < ' . (int) $table->rgt)
            ;

            $db->setQuery($query);

            $childIds = $db->loadColumn();

            // Add child ID's to the array only if they aren't already there.
            foreach ($childIds as $childId)
            {
                if (!in_array($childId, $pks))
                {
                    array_push($pks, $childId);
                }
            }

            // Make a copy of the old ID and Parent ID
            $oldId = $table->id;
            $oldParentId = $table->parent_id;

            // Reset the id because we are making a copy.
            $table->id = 0;

            // If we a copying children, the Old ID will turn up in the parents list
            // otherwise it's a new top level item
            $table->parent_id = isset($parents[$oldParentId]) ? $parents[$oldParentId] : $rootId;
            $table->website_id=$website_id;
            // Set the new location in the tree for the node.
            $table->setLocation($table->parent_id, 'last-child');

            // TODO: Deal with ordering?
            //$table->ordering	= 1;
            $table->level = null;
            $table->lft = null;
            $table->rgt = null;


            // Check the row.
            if (!$table->check())
            {
                $this->setError($table->getError());
                return false;
            }

            // Store the row.

            if (!$table->store())
            {
                $this->setError($table->getError());
                return false;
            }

            // Get the new item ID
            $newId = $table->get('id');

            // Add the new ID to the array
            $newIds[$i] = $newId;
            $i++;

            // Now we log the old 'parent' to the new 'parent'
            $parents[$oldId] = $table->id;
            $count--;
        }
        return $newIds;
    }
    public function quick_assign_website(&$pks,$website_id)
    {
        $user	= JFactory::getUser();
        $db		= $this->getDbo();

        // Access checks.
        if (!$user->authorise('core.create', 'com_plugins'))
        {
            throw new Exception(JText::_('JERROR_CORE_CREATE_NOT_PERMITTED'));
        }

        $table = $this->getTable();

        foreach ($pks as $pk)
        {
            if ($table->load($pk, true))
            {

                $table->website_id=$website_id;

                if (!$table->check() || !$table->store())
                {
                    throw new Exception($table->getError());
                }

            }
            else
            {
                throw new Exception($table->getError());
            }
        }


        // Clear modules cache

        return true;
    }

	/**
	 * Override preprocessForm to load the user plugin group instead of content.
	 *
	 * @param   object	A form object.
	 * @param   mixed	The data expected for the form.
	 * @throws	Exception if there is an error in the form event.
	 * @since   1.6
	 */
	protected function preprocessForm(JForm $form, $data, $groups = '')
	{
		$obj = is_array($data) ? JArrayHelper::toObject($data, 'JObject') : $data;
		if (isset($obj->parent_id) && $obj->parent_id == 0 && $obj->id > 0)
		{
			$form->setFieldAttribute('parent_id', 'type', 'hidden');
			$form->setFieldAttribute('parent_id', 'hidden', 'true');
		}
		parent::preprocessForm($form, $data, 'user');
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  The form data.
	 * @return  boolean  True on success.
	 * @since   1.6
	 */
	public function save($data)
	{
		// Include the content plugins for events.
		JPluginHelper::importPlugin('user');

		// Check the super admin permissions for group
		// We get the parent group permissions and then check the group permissions manually
		// We have to calculate the group permissions manually because we haven't saved the group yet
		$parentSuperAdmin = JAccess::checkGroup($data['parent_id'], 'core.admin');
		// Get core.admin rules from the root asset
		$rules = JAccess::getAssetRules('root.1')->getData('core.admin');
		// Get the value for the current group (will be true (allowed), false (denied), or null (inherit)
		$groupSuperAdmin = $rules['core.admin']->allow($data['id']);

		// We only need to change the $groupSuperAdmin if the parent is true or false. Otherwise, the value set in the rule takes effect.
		if ($parentSuperAdmin === false)
		{
			// If parent is false (Denied), effective value will always be false
			$groupSuperAdmin = false;
		}
		elseif ($parentSuperAdmin === true)
		{
			// If parent is true (allowed), group is true unless explicitly set to false
			$groupSuperAdmin = ($groupSuperAdmin === false) ? false : true;
		}

		// Check for non-super admin trying to save with super admin group
		$iAmSuperAdmin	= JFactory::getUser()->authorise('core.admin');
		if ((!$iAmSuperAdmin) && ($groupSuperAdmin))
		{
			try
			{
				throw new Exception(JText::_('JLIB_USER_ERROR_NOT_SUPERADMIN'));
			}
			catch (Exception $e)
			{
				$this->setError($e->getMessage());
				return false;
			}
		}

		// Check for super-admin changing self to be non-super-admin
		// First, are we a super admin>
		if ($iAmSuperAdmin)
		{
			// Next, are we a member of the current group?
			$myGroups = JAccess::getGroupsByUser(JFactory::getUser()->get('id'), false);
			if (in_array($data['id'], $myGroups))
			{
				// Now, would we have super admin permissions without the current group?
				$otherGroups = array_diff($myGroups, array($data['id']));
				$otherSuperAdmin = false;
				foreach ($otherGroups as $otherGroup)
				{
					$otherSuperAdmin = ($otherSuperAdmin) ? $otherSuperAdmin : JAccess::checkGroup($otherGroup, 'core.admin');
				}
				// If we would not otherwise have super admin permissions
				// and the current group does not have super admin permissions, throw an exception
				if ((!$otherSuperAdmin) && (!$groupSuperAdmin))
				{
					try
					{
						throw new Exception(JText::_('JLIB_USER_ERROR_CANNOT_DEMOTE_SELF'));
					}
					catch (Exception $e)
					{
						$this->setError($e->getMessage());
						return false;
					}
				}
			}
		}

		// Proceed with the save
		return parent::save($data);
	}

	/**
	 * Method to delete rows.
	 *
	 * @param   array  An array of item ids.
	 * @return  boolean  Returns true on success, false on failure.
	 * @since   1.6
	 */
	public function delete(&$pks)
	{
		// Typecast variable.
		$pks = (array) $pks;
		$user	= JFactory::getUser();
		$groups = JAccess::getGroupsByUser($user->get('id'));

		// Get a row instance.
		$table = $this->getTable();

		// Load plugins.
		JPluginHelper::importPlugin('user');
		$dispatcher = JEventDispatcher::getInstance();

		// Check if I am a Super Admin
		$iAmSuperAdmin	= $user->authorise('core.admin');

		// do not allow to delete groups to which the current user belongs
		foreach ($pks as $pk)
		{
			if (in_array($pk, $groups))
			{
				JError::raiseWarning(403, JText::_('COM_USERS_DELETE_ERROR_INVALID_GROUP'));
				return false;
			}
		}
		// Iterate the items to delete each one.
		foreach ($pks as $i => $pk)
		{
			if ($table->load($pk))
			{
				// Access checks.
				$allow = $user->authorise('core.edit.state', 'com_users');
				// Don't allow non-super-admin to delete a super admin
				$allow = (!$iAmSuperAdmin && JAccess::checkGroup($pk, 'core.admin')) ? false : $allow;

				if ($allow)
				{
					// Fire the onUserBeforeDeleteGroup event.
					$dispatcher->trigger('onUserBeforeDeleteGroup', array($table->getProperties()));

					if (!$table->delete($pk))
					{
						$this->setError($table->getError());
						return false;
					} else {
						// Trigger the onUserAfterDeleteGroup event.
						$dispatcher->trigger('onUserAfterDeleteGroup', array($table->getProperties(), true, $this->getError()));
					}
				} else {
					// Prune items that you can't change.
					unset($pks[$i]);
					JError::raiseWarning(403, JText::_('JERROR_CORE_DELETE_NOT_PERMITTED'));
				}
			} else {
				$this->setError($table->getError());
				return false;
			}
		}

		return true;
	}
}
