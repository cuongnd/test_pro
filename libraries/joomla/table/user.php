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
 * Users table
 *
 * @package     Joomla.Platform
 * @subpackage  Table
 * @since       11.1
 */
class JTableUser extends JTable
{
    /**
     * Associative array of group ids => group ids for the user
     *
     * @var    array
     * @since  11.1
     */
    public $groups;

    /**
     * Constructor
     *
     * @param   JDatabaseDriver $db Database driver object.
     *
     * @since  11.1
     */
    public function __construct($db)
    {
        parent::__construct('#__users', 'id', $db);

        // Initialise.
        $this->id = 0;
        $this->sendEmail = 0;
    }

    public function rebuild()
    {
        $db = $this->_db;
        $query = $db->getQuery(true);
        $query->select('usergroups.id,usergroups.parent_id,user_group_id_website_id.website_id')
            ->from('#__usergroups AS usergroups')
            ->leftJoin('#__user_group_id_website_id AS user_group_id_website_id ON user_group_id_website_id.user_group_id=usergroups.id')
            ->leftJoin('#__user_usergroup_map AS user_usergroup_map ON user_usergroup_map.group_id=usergroups.id')
            ->select('user_usergroup_map.user_id AS user_id')
            ->group('usergroups.id')
        ;

        $list_group_user = $db->setQuery($query)->loadObjectList();
        $children_group_user = array();
        foreach ($list_group_user as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_group_user[$pt] ? $children_group_user[$pt] : array();
            array_push($list, $v);
            $children_group_user[$pt] = $list;
        }
        $list_root_user_group = $children_group_user['list_root'];
        foreach ($list_root_user_group as $root_user_group) {
            $update_user_group = function ($function_callback, $user_group, $children_group_user, $website_id,$level=0,$max_level=999) {
                $user_id = $user_group->user_id;
                if ($website_id&&$user_id) {
                    $db = JFactory::getDbo();
                    $db->rebuild_action=1;
                    $query = $db->getQuery(true);
                    $query->update('#__users')
                        ->set('website_id=' . (int)$website_id)
                        ->where('id=' . (int)$user_id);
                    $db->setQuery($query);
                    $ok = $db->execute();
                    if (!$ok) {
                        throw new Exception($db->getErrorMsg());
                    }
                }else if(!$website_id&&$user_id){
                    $db = JFactory::getDbo();
                    $db->rebuild_action=1;
                    $query = $db->getQuery(true);
                    $query->delete('#__users')
                        ->where('id=' . (int)$user_id);
                    $db->setQuery($query);
                    $ok = $db->execute();
                    if (!$ok) {
                        throw new Exception($db->getErrorMsg());
                    }
                }
                $list_user_group = $children_group_user[$user_group->id];
                if ($level<=$max_level&&count($list_user_group)) {
                    $level1=$level+1;
                    foreach ($list_user_group AS $user_group1) {
                        $function_callback($function_callback, $user_group1, $children_group_user, $website_id,$level1);
                    }
                }


            };
            $update_user_group($update_user_group, $root_user_group, $children_group_user, $root_user_group->website_id);

        }
        return true;
    }

    /**
     * Method to load a user, user groups, and any other necessary data
     * from the database so that it can be bound to the user object.
     *
     * @param   integer $userId An optional user id.
     * @param   boolean $reset False if row not found or on error
     *                           (internal error state set in that case).
     *
     * @return  boolean  True on success, false on failure.
     *
     * @since   11.1
     */
    public function load($userId = null, $reset = true)
    {
        $website = JFactory::getWebsite();
        // Get the id to load.
        if ($userId !== null) {
            $this->id = $userId;
        } else {
            $userId = $this->id;
        }

        // Check for a valid id to load.
        if ($userId === null) {
            return false;
        }

        // Reset the table.
        $this->reset();
        // Load the user data.
        $query = $this->_db->getQuery(true)
            ->select('users.*')
            ->from($this->_db->quoteName('#__users') . ' AS users')
            ->where($this->_db->quoteName('users.id') . ' = ' . (int)$userId);
        $this->_db->setQuery($query);

        $data = (array)$this->_db->loadAssoc();
        if (!count($data)) {
            return false;
        }

        // Convert e-mail from punycode
        $data['email'] = JStringPunycode::emailToUTF8($data['email']);

        // Bind the data to the table.
        $return = $this->bind($data);

        $query=$this->_db->getQuery(true);
        $query->select('user_usergroup_map.group_id')
            ->from('#__user_usergroup_map AS user_usergroup_map')
            ->where('user_usergroup_map.user_id='.(int)$userId)
        ;
        // Add the groups to the user data.
        $this->groups =json_encode($this->_db->setQuery($query)->loadColumn() );

        return $return;
    }

    /**
     * Method to bind the user, user groups, and any other necessary data.
     *
     * @param   array $array The data to bind.
     * @param   mixed $ignore An array or space separated list of fields to ignore.
     *
     * @return  boolean  True on success, false on failure.
     *
     * @since   11.1
     */
    public function bind($array, $ignore = '')
    {
        if (array_key_exists('params', $array) && is_array($array['params'])) {
            $registry = new JRegistry;
            $registry->loadArray($array['params']);
            $array['params'] = (string)$registry;
        }

        // Attempt to bind the data.
        $return = parent::bind($array, $ignore);

        // Load the real group data based on the bound ids.
        if ($return && !empty($this->groups)) {
            // Set the group ids.
            JArrayHelper::toInteger($this->groups);

            // Get the titles for the user groups.
            $query = $this->_db->getQuery(true)
                ->select($this->_db->quoteName('id'))
                ->select($this->_db->quoteName('title'))
                ->from($this->_db->quoteName('#__usergroups'))
                ->where($this->_db->quoteName('id') . ' = ' . implode(' OR ' . $this->_db->quoteName('id') . ' = ', $this->groups));
            $this->_db->setQuery($query);

            // Set the titles for the user groups.
            $this->groups = $this->_db->loadAssocList('id', 'id');


        }

        return $return;
    }

    /**
     * Validation and filtering
     *
     * @return  boolean  True if satisfactory
     *
     * @since   11.1
     */
    public function check()
    {
        $website = JFactory::getWebsite();
        // Set user id to null istead of 0, if needed
        if ($this->id === 0) {
            $this->id = null;
        }

        // Validate user information
        if (trim($this->name) == '') {
            $this->setError(JText::_('JLIB_DATABASE_ERROR_PLEASE_ENTER_YOUR_NAME'));

            return false;
        }

        if (trim($this->username) == '') {
            $this->setError(JText::_('JLIB_DATABASE_ERROR_PLEASE_ENTER_A_USER_NAME'));

            return false;
        }

        if (preg_match('#[<>"\'%;()&\\\\]|\\.\\./#', $this->username) || strlen(utf8_decode($this->username)) < 2
            || trim($this->username) != $this->username
        ) {
            $this->setError(JText::sprintf('JLIB_DATABASE_ERROR_VALID_AZ09', 2));

            return false;
        }

        if ((trim($this->email) == "") || !JMailHelper::isEmailAddress($this->email)) {
            $this->setError(JText::_('JLIB_DATABASE_ERROR_VALID_MAIL'));

            return false;
        }

        // Convert e-mail to punycode for storage
        $this->email = JStringPunycode::emailToPunycode($this->email);

        // Set the registration timestamp
        if (empty($this->registerDate) || $this->registerDate == $this->_db->getNullDate()) {
            $this->registerDate = JFactory::getDate()->toSql();
        }

        // Set the lastvisitDate timestamp
        if (empty($this->lastvisitDate)) {
            $this->lastvisitDate = $this->_db->getNullDate();
        }
        $groups = $this->groups;
        $list_user_group_id = JUserHelper::get_list_user_group_id();
        $firstGroup = reset($groups);
        // Check for existing username
        $query = $this->_db->getQuery(true)
            ->select('u.id')
            ->from('#__users AS u')
            ->where('u.username = ' . $this->_db->quote($this->username))
            ->leftJoin('#__user_usergroup_map AS ugm ON ugm.user_id=u.id')
            ->leftJoin('#__usergroups AS usergroups ON usergroups.id=ugm.group_id')
            ->where('ugm.group_id=' . (int)$firstGroup)
            ->where('usergroups.id IN(' . implode(',', $list_user_group_id) . ')')
            ->where('u.id != ' . (int)$this->id);
        $this->_db->setQuery($query);

        $xid = (int)$this->_db->loadResult();

        if ($xid && $xid != (int)$this->id) {
            $this->setError(JText::_('JLIB_DATABASE_ERROR_USERNAME_INUSE'));

            return false;
        }
        $list_user_group_id = JUserHelper::get_list_user_group_id();
        // Check for existing email
        $query->clear()
            ->select($this->_db->quoteName('u.id'))
            ->from($this->_db->quoteName('#__users') . ' AS u ')
            ->leftJoin('#__user_usergroup_map AS ugm ON ugm.user_id=u.id')
            ->leftJoin('#__usergroups AS usergroups ON usergroups.id=ugm.group_id')
            ->where('usergroups.id IN(' . implode(',', $list_user_group_id) . ')')
            ->where($this->_db->quoteName('email') . ' = ' . $this->_db->quote($this->email))
            ->where($this->_db->quoteName('u.id') . ' != ' . (int)$this->id);
        $this->_db->setQuery($query);
        $xid = (int)$this->_db->loadResult();

        if ($xid && $xid != (int)$this->id) {
            $this->setError(JText::_('JLIB_DATABASE_ERROR_EMAIL_INUSE'));

            return false;
        }

        // Check for root_user != username
        $config = JFactory::getConfig();
        $rootUser = $config->get('root_user');

        if (!is_numeric($rootUser)) {
            $query->clear()
                ->select($this->_db->quoteName('id'))
                ->from($this->_db->quoteName('#__users'))
                ->where($this->_db->quoteName('username') . ' = ' . $this->_db->quote($rootUser));
            $this->_db->setQuery($query);
            $xid = (int)$this->_db->loadResult();

            if ($rootUser == $this->username && (!$xid || $xid && $xid != (int)$this->id)
                || $xid && $xid == (int)$this->id && $rootUser != $this->username
            ) {
                $this->setError(JText::_('JLIB_DATABASE_ERROR_USERNAME_CANNOT_CHANGE'));

                return false;
            }
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
     * @param   boolean $updateNulls True to update fields even if they are null.
     *
     * @return  boolean  True on success.
     *
     * @link    http://docs.joomla.org/JTable/store
     * @since   11.1
     */
    public function store($updateNulls = false)
    {
        // Get the table key and key value.
        $k = $this->_tbl_key;
        $key = $this->$k;

        // TODO: This is a dumb way to handle the groups.
        // Store groups locally so as to not update directly.
        $groups = $this->groups;

        unset($this->groups);

        // Insert or update the object based on presence of a key value.
        if ($key) {
            // Already have a table key, update the row.
            $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
        } else {
            // Don't have a table key, insert the row.
            $this->_db->insertObject($this->_tbl, $this, $this->_tbl_key);
        }

        // Reset groups to the local object.
        $this->groups = $groups;
        unset($groups);

        $query = $this->_db->getQuery(true);
        // Store the group data if the user data was saved.
        if (is_array($this->groups) && count($this->groups)) {
            // Delete the old user group maps.
            $query->delete($this->_db->quoteName('#__user_usergroup_map'))
                ->where('user_id = ' . (int)$this->id);
            $this->_db->setQuery($query);
            $this->_db->execute();
            $ok = $this->_db->execute();
            if (!$ok) {
                throw new Exception($this->_db->getErrorMsg());
            }
            // Set the new user group maps.
            $query->clear()
                ->insert($this->_db->quoteName('#__user_usergroup_map'))
                ->columns('user_id,group_id');
            // Have to break this up into individual queries for cross-database support.
            foreach ($this->groups as $group) {
                $query->clear('values')
                    ->values($this->id . ', ' . $group);
                $this->_db->setQuery($query);
                $ok = $this->_db->execute();
                if (!$ok) {
                    throw new Exception($this->_db->getErrorMsg());
                }
            }
        }

        // If a user is blocked, delete the cookie login rows
        if ($this->block == (int)1) {
            $query->clear()
                ->delete($this->_db->quoteName('#__user_keys'))
                ->where($this->_db->quoteName('user_id') . ' = ' . $this->_db->quote($this->username));
            $this->_db->setQuery($query);
            $this->_db->execute();
        }

        return true;
    }

    /**
     * Method to delete a user, user groups, and any other necessary data from the database.
     *
     * @param   integer $userId An optional user id.
     *
     * @return  boolean  True on success, false on failure.
     *
     * @since   11.1
     */
    public function delete($userId = null)
    {
        echo "hello delete user";
        echo "<pre>";
        print_r(JUtility::printDebugBacktrace());
        echo "</pre>";
        die;
        // Set the primary key to delete.
        $k = $this->_tbl_key;

        if ($userId) {
            $this->$k = (int)$userId;
        }

        // Delete the user.
        $query = $this->_db->getQuery(true)
            ->delete($this->_db->quoteName($this->_tbl))
            ->where($this->_db->quoteName($this->_tbl_key) . ' = ' . (int)$this->$k);
        $this->_db->setQuery($query);
        $this->_db->execute();

        // Delete the user group maps.
        $query->clear()
            ->delete($this->_db->quoteName('#__user_usergroup_map'))
            ->where($this->_db->quoteName('user_id') . ' = ' . (int)$this->$k);
        $this->_db->setQuery($query);
        $this->_db->execute();

        /*
         * Clean Up Related Data.
         */

        $query->clear()
            ->delete($this->_db->quoteName('#__messages_cfg'))
            ->where($this->_db->quoteName('user_id') . ' = ' . (int)$this->$k);
        $this->_db->setQuery($query);
        $this->_db->execute();

        $query->clear()
            ->delete($this->_db->quoteName('#__messages'))
            ->where($this->_db->quoteName('user_id_to') . ' = ' . (int)$this->$k);
        $this->_db->setQuery($query);
        $this->_db->execute();

        $query->clear()
            ->delete($this->_db->quoteName('#__user_keys'))
            ->where($this->_db->quoteName('user_id') . ' = ' . $this->_db->quote($this->username));
        $this->_db->setQuery($query);
        $this->_db->execute();

        return true;
    }

    /**
     * Updates last visit time of user
     *
     * @param   integer $timeStamp The timestamp, defaults to 'now'.
     * @param   integer $userId The user id (optional).
     *
     * @return  boolean  False if an error occurs
     *
     * @since   11.1
     */
    public function setLastVisit($timeStamp = null, $userId = null)
    {
        // Check for User ID
        if (is_null($userId)) {
            if (isset($this)) {
                $userId = $this->id;
            } else {
                jexit('No userid in setLastVisit');
            }
        }

        // If no timestamp value is passed to function, than current time is used.
        $date = JFactory::getDate($timeStamp);

        // Update the database row for the user.
        $db = $this->_db;
        $query = $db->getQuery(true)
            ->update($db->quoteName($this->_tbl))
            ->set($db->quoteName('lastvisitDate') . '=' . $db->quote($date->toSql()))
            ->where($db->quoteName('id') . '=' . (int)$userId);
        $db->setQuery($query);
        $db->execute();

        return true;
    }
}
