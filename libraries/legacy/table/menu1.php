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
class JTableMenu extends JTableNested
{
    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__menu', 'id', $db);

    }
    /**
     * Gets the ID of the root item in the tree
     *
     * @return  mixed  The primary id of the root row, or false if not found and the internal error is set.
     *
     * @since   11.1
     */
    public function getRootId($menu_type_id=0)
    {
        if ((int) self::$root_id > 0)
        {
            return self::$root_id;
        }
        $menuitemmenutype_table=JTable::getInstance('menuitemmenutype');
        $menuitemmenutype_table->load(array('menu_type_id'=>$menu_type_id));
        if($menuitemmenutype_table->id)
        {
            self::$root_id=$menuitemmenutype_table->menu_id;
            return self::$root_id;
        }else{
            $query=$this->_db->getQuery(true);
            $query->insert('#__menu')
                ->set('title='.$query->q('Menu_Item_Root'))
                ->set('alias='.$query->q('root'))
                ;
            $ok=$this->_db->setQuery($query)->execute();
            if(!$ok)
            {
                throw  new Exception($this->_db->getErrorMsg());
            }
            $new_menu_item_id=$this->_db->insertid();
            $menuitemmenutype_table->menu_id=$new_menu_item_id;
            $menuitemmenutype_table->menu_type_id=$menu_type_id;
            $ok=$menuitemmenutype_table->store();
            if(!$ok)
            {
                throw new Exception($menuitemmenutype_table->getErrorMsg());
            }
            return $new_menu_item_id;
        }
        self::$root_id = false;
        return false;
    }
    /**
     * Method to recursively rebuild the whole nested set tree.
     *
     * @param   integer  $parentId  The root of the tree to rebuild.
     * @param   integer  $leftId    The left id to start with in building the tree.
     * @param   integer  $level     The level to assign to the current nodes.
     * @param   string   $path      The path to the current nodes.
     *
     * @return  integer  1 + value of root rgt on success, false on failure
     *
     * @link    http://docs.joomla.org/JTableNested/rebuild
     * @since   11.1
     * @throws  RuntimeException on database error.
     */
    public function rebuild($menu_type_id=0,$parentId=0,$leftId = 0, $level = 0, $path = '')
    {
        if($menu_type_id==0)
        {
            throw new Exception('menu type id must not empty');
        }
        if($parentId===null)
        {
            $parentId = $this->getRootId($menu_type_id);
        }

        $query = $this->_db->getQuery(true);

        // Build the structure of the recursive query.
        if (!isset($this->_cache['rebuild.sql']))
        {
            $query->clear()
                ->select($this->_tbl_key . ', alias')
                ->from($this->_tbl)
                ->where('parent_id = %d');

            // If the table has an ordering field, use that for ordering.
            if (property_exists($this, 'ordering'))
            {
                $query->order('parent_id, ordering, lft');
            }
            else
            {
                $query->order('parent_id, lft');
            }
            $this->_cache['rebuild.sql'] = (string) $query;
        }

        // Make a shortcut to database object.

        // Assemble the query to find all children of this node.
        $this->_db->setQuery(sprintf($this->_cache['rebuild.sql'], (int) $parentId));

        $children = $this->_db->loadObjectList();

        // The right value of this node is the left value + 1
        $rightId = $leftId + 1;

        // Execute this function recursively over all children
        foreach ($children as $node)
        {
            /*
             * $rightId is the current right value, which is incremented on recursion return.
             * Increment the level for the children.
             * Add this item's alias to the path (but avoid a leading /)
             */
            $rightId = $this->rebuild($menu_type_id,$node->{$this->_tbl_key}, $rightId, $level + 1, $path . (empty($path) ? '' : '/') . $node->alias);

            // If there is an update failure, return false to break out of the recursion.
            if ($rightId === false)
            {
                return false;
            }
        }

        // We've got the left value, and now that we've processed
        // the children of this node we also know the right value.
        $query->clear()
            ->update($this->_tbl)
            ->set('lft = ' . (int) $leftId)
            ->set('rgt = ' . (int) $rightId)
            ->set('level = ' . (int) $level)
            ->set('path = ' . $this->_db->quote($path))
            ->where($this->_tbl_key . ' = ' . (int) $parentId);
        $this->_db->setQuery($query)->execute();

        // Return the right value of this node + 1.
        return $rightId + 1;
    }

}
