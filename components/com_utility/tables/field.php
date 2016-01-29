<?php
/**
 * @package     Joomla.Legacy
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);
require_once JPATH_ROOT.'/libraries/legacy/table/positionnested.php';
/**
 * product table
 *
 * @package     Joomla.Legacy
 * @subpackage  Table
 * @since       11.1
 * @deprecated  Class will be removed upon completion of transition to UCM
 */
class JTableField extends JTablePositionNested
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
        parent::__construct('#__control', 'id', $db);
        $this->access = (int) JFactory::getConfig()->get('access');

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

    /**
     * Overloaded check function
     *
     * @return  boolean  True on success
     *
     * @see     JTable::check()
     * @since   11.1
     */
    public function check()
    {
        // Set correct component id to ensure proper 404 messages with separator items
        if ($this->type == "separator")
        {
            $this->component_id = 0;
        }

        // If the alias field is empty, set it to the title.
        $this->alias = trim($this->alias);

        if ((empty($this->alias)) && ($this->type != 'alias' && $this->type != 'url'))
        {
            $this->alias = $this->title;
        }

        // Make the alias URL safe.
        $this->alias = JApplication::stringURLSafe($this->alias);

        if (trim(str_replace('-', '', $this->alias)) == '')
        {
            $this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
        }

        // Cast the home property to an int for checking.
        $this->home = (int) $this->home;

        // Verify that a first level menu item alias is not 'component'.
        if ($this->parent_id && $this->alias == 'component')
        {
            $this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_ROOT_ALIAS_COMPONENT'));

            return false;
        }

        // Verify that a first level menu item alias is not the name of a folder.
        jimport('joomla.filesystem.folder');

        if ($this->parent_id && in_array($this->alias, JFolder::folders(JPATH_ROOT)))
        {
            $this->setError(JText::sprintf('JLIB_DATABASE_ERROR_MENU_ROOT_ALIAS_FOLDER', $this->alias, $this->alias));

            return false;
        }

        // Verify that the home item a component.
        if ($this->home && $this->type != 'component')
        {
            $this->setError(JText::_('JLIB_DATABASE_ERROR_MENU_HOME_NOT_COMPONENT'));

            return false;
        }

        return true;
    }

    /**
     * Overloaded store function
     *
     * @param   boolean  $updateNulls  True to update fields even if they are null.
     *
     * @return  mixed  False on failure, positive integer on success.
     *
     * @see     JTable::store()
     * @since   11.1
     */
    public function store($updateNulls = false)
    {
        $db = JFactory::getDbo();
        $rootId=$this->getRootId();

        if($rootId==false)
        {
            return false;
        }
        if(!$this->parent_id)
        {
            $this->parent_id=$rootId;
        }
        // Verify that the alias is unique
        if (!parent::store($updateNulls))
        {
            return false;
        }


        // Get the new path in case the node was moved
        $pathNodes = $this->getPath();
        $segments = array();

        foreach ($pathNodes as $node)
        {
            // Don't include root in path
            if ($node->alias != 'root')
            {
                $segments[] = $node->alias;
            }
        }

        $newPath = trim(implode('/', $segments), ' /\\');
        // Use new path for partial rebuild of table
        // Rebuild will return positive integer on success, false on failure
        return ($this->rebuild($this->{$this->_tbl_key}, $this->lft, $this->level, $newPath) > 0);
    }



    #region JTablePositionNested Members

    /**
     * Sets the debug level on or off
     *
     * @param integer $level 0 = off, 1 = on
     *
     * @return void
     */
    function debug($level)
    {
        return parent::debug($level);
    }

    /**
     * Method to get an array of nodes from a given node to its root.
     *
     * @param integer $pk Primary key of the node for which to get the path.
     * @param boolean $diagnostic Only select diagnostic data for the nested sets.
     *
     * @return mixed An array of node objects including the start node.
     */
    function getPath($pk = null, $diagnostic = false)
    {
        return parent::getPath($pk, $diagnostic);
    }

    /**
     * Method to get a node and all its child nodes.
     *
     * @param integer $pk Primary key of the node for which to get the tree.
     * @param boolean $diagnostic Only select diagnostic data for the nested sets.
     *
     * @return mixed Boolean false on failure or array of node objects on success.
     */
    function getTree($pk = null, $diagnostic = false)
    {
        return parent::getTree($pk, $diagnostic);
    }

    /**
     * Method to determine if a node is a leaf node in the tree (has no children).
     *
     * @param integer $pk Primary key of the node to check.
     *
     * @return boolean True if a leaf node, false if not or null if the node does not exist.
     */
    function isLeaf($pk = null)
    {
        return parent::isLeaf($pk);
    }

    /**
     * Method to set the location of a node in the tree object.  This method does not
     * save the new location to the database, but will set it in the object so
     * that when the node is stored it will be stored in the new location.
     *
     * @param integer $referenceId The primary key of the node to reference new location by.
     * @param string $position Location type string. ['before', 'after', 'first-child', 'last-child']
     *
     * @return void
     */
    function setLocation($referenceId, $position = 'after')
    {
        return parent::setLocation($referenceId, $position);
    }

    /**
     * Method to move a row in the ordering sequence of a group of rows defined by an SQL WHERE clause.
     * Negative numbers move the row up in the sequence and positive numbers move it down.
     *
     * @param integer $delta The direction and magnitude to move the row in the ordering sequence.
     * @param string $where WHERE clause to use for limiting the selection of rows to compact the
     *                      ordering values.
     *
     * @return mixed Boolean true on success.
     */
    function move($delta, $where = '')
    {
        return parent::move($delta, $where);
    }

    /**
     * Method to move a node and its children to a new location in the tree.
     *
     * @param integer $referenceId The primary key of the node to reference new location by.
     * @param string $position Location type string. ['before', 'after', 'first-child', 'last-child']
     * @param integer $pk The primary key of the node to move.
     *
     * @return boolean True on success.
     */
    function moveByReference($referenceId, $position = 'after', $pk = null)
    {
        return parent::moveByReference($referenceId, $position, $pk);
    }

    /**
     * Method to delete a node and, optionally, its child nodes from the table.
     *
     * @param integer $pk The primary key of the node to delete.
     * @param boolean $children True to delete child nodes, false to move them up a level.
     *
     * @return boolean True on success.
     */
    function delete($pk = null, $children = true)
    {
        return parent::delete($pk, $children);
    }

    /**
     * Method to set the publishing state for a node or list of nodes in the database
     * table.  The method respects rows checked out by other users and will attempt
     * to checkin rows that it can after adjustments are made. The method will not
     * allow you to set a publishing state higher than any ancestor node and will
     * not allow you to set a publishing state on a node with a checked out child.
     *
     * @param mixed $pks An optional array of primary key values to update.  If not
     *                   set the instance property value is used.
     * @param integer $state The publishing state. eg. [0 = unpublished, 1 = published]
     * @param integer $userId The user id of the user performing the operation.
     *
     * @return boolean True on success.
     */
    function publish($pks = null, $state = 1, $userId = 0)
    {
        return parent::publish($pks, $state, $userId);
    }

    /**
     * Method to move a node one position to the left in the same level.
     *
     * @param integer $pk Primary key of the node to move.
     *
     * @return boolean True on success.
     */
    function orderUp($pk)
    {
        return parent::orderUp($pk);
    }

    /**
     * Method to move a node one position to the right in the same level.
     *
     * @param integer $pk Primary key of the node to move.
     *
     * @return boolean True on success.
     */
    function orderDown($pk)
    {
        return parent::orderDown($pk);
    }



    /**
     * Method to recursively rebuild the whole nested set tree.
     *
     * @param  $menu_type_id 
     * @param integer $parentId The root of the tree to rebuild.
     * @param integer $leftId The left id to start with in building the tree.
     * @param integer $level The level to assign to the current nodes.
     * @param string $path The path to the current nodes.
     *
     * @return integer 1 + value of root rgt on success, false on failure
     */
    function rebuild($parentId = null, $leftId = 0, $level = 0, $path = '')
    {
        return parent::rebuild( $parentId, $leftId, $level, $path);
    }

    /**
     * Method to rebuild the node's path field from the alias values of the
     * nodes from the current node to the root node of the tree.
     *
     * @param integer $pk Primary key of the node for which to get the path.
     *
     * @return boolean True on success.
     */
    function rebuildPath($pk = null)
    {
        return parent::rebuildPath($pk);
    }

    /**
     * Method to reset class properties to the defaults set in the class
     * definition. It will ignore the primary key as well as any private class
     * properties (except $_errors).
     *
     * @return void
     */
    function reset()
    {
        return parent::reset();
    }

    /**
     * Method to update order of table rows
     *
     * @param array $idArray id numbers of rows to be reordered.
     * @param array $lft_array lft values of rows to be reordered.
     *
     * @return integer 1 + value of root rgt on success, false on failure.
     */
    function saveorder($idArray = null, $lft_array = null)
    {
        return parent::saveorder($idArray, $lft_array);
    }

    /**
     * Method to get nested set properties for a node in the tree.
     *
     * @param integer $id Value to look up the node by.
     * @param string $key An optional key to look up the node by (parent | left | right).
     *                    If omitted, the primary key of the table is used.
     *
     * @return mixed Boolean false on failure or node object on success.
     */
    function _getNode($id, $key = null)
    {
        return parent::_getNode($id, $key);
    }

    /**
     * Method to get various data necessary to make room in the tree at a location
     * for a node and its children.  The returned data object includes conditions
     * for SQL WHERE clauses for updating left and right id values to make room for
     * the node as well as the new left and right ids for the node.
     *
     * @param object $referenceNode A node object with at least a 'lft' and 'rgt' with
     *                              which to make room in the tree around for a new node.
     * @param integer $nodeWidth The width of the node for which to make room in the tree.
     * @param string $position The position relative to the reference node where the room
     *                         should be made.
     *
     * @return mixed Boolean false on failure or data object on success.
     */
    function _getTreeRepositionData($referenceNode, $nodeWidth, $position = 'before')
    {
        return parent::_getTreeRepositionData($referenceNode, $nodeWidth, $position);
    }

    /**
     * Method to create a log table in the buffer optionally showing the query and/or data.
     *
     * @param boolean $showData True to show data
     * @param boolean $showQuery True to show query
     *
     * @return void
     */
    function _logtable($showData = true, $showQuery = true)
    {
        return parent::_logtable($showData, $showQuery);
    }

    /**
     * Runs a query and unlocks the database on an error.
     *
     * @param mixed $query A string or JDatabaseQuery object.
     * @param string $errorMessage Unused.
     *
     * @return boolean void
     */
    function _runQuery($query, $errorMessage)
    {
        return parent::_runQuery($query, $errorMessage);
    }

    #endregion
}
