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
 * Users component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_users
 * @since       1.6
 */
class GroupsHelper
{
	/**
	 * @var    JObject  A cache for the available actions.
	 * @since  1.6
	 */
	protected static $actions;
    public function  createRootGroup($website_id)
    {

        $db=JFactory::getDbo();

        //check created root
        $query=$db->getQuery(true);
        $query->select('id')
            ->from('#__usergroups')
            ->where('id=parent_id')
            ->where('website_id='.(int)$website_id);
        $db->setQuery($query);

        $rootId=$db->loadResult();
        if(!$rootId)
        {

            $query->clear();
            $query->select('MAX(id)')
                ->from('#__usergroups');
            $parent_id=$db->setQuery($query)->loadResult();

            if($parent_id)
                $parent_id++;
            else
                $parent_id=1;
            $tableUserGroup=new stdClass();
            $tableUserGroup->id=$parent_id;
            $tableUserGroup->website_id=$website_id;
            $tableUserGroup->title=$query->q('Public');
            $tableUserGroup->parent_id=$parent_id;
            $tableUserGroup->lft=0;
            $tableUserGroup->rgt=0;
            $listKeyOfObjectRoot=array();
            $listValueOfObjectRoot=array();
            foreach($tableUserGroup as $key=>$value)
            {
                $listKeyOfObjectRoot[]=$key;
                $listValueOfObjectRoot[]=$value;

            }
            $query->clear();
            //	 * $query->insert('#__a')->columns('id, title')->values(array('1,2', '3,4'));
            $query->insert('#__usergroups')
                ->columns(implode(',',$listKeyOfObjectRoot))
                ->values(implode(',',$listValueOfObjectRoot));
            $db->setQuery($query);

            if(!$db->execute())
            {
                throw new Exception($db->getErrorMsg());
            }
            $rootId=$db->insertid();
        }
        return $rootId;
    }
    public function getChildrenGroupUserByParentGroupId($parent_group_id=0)
    {

        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('ug.*');
        $query->from('#__usergroups AS ug');
        $query->leftJoin('#__usergroups AS ug1 ON ug1.parent_id='.(int)$parent_group_id);
        $query->where('ug.website_id=ug1.website_id');
        $query->where('ug.parent_id!='.(int)$parent_group_id);
        $query->group('ug.id');
        echo $query->dump();
        $db->setQuery($query);
        try
        {
            $groups = $db->loadObjectList();
        }
        catch (RuntimeException $e)
        {
            JError::raiseWarning(500, $e->getMessage());
        }

        return $groups;
    }
    public function getRootUserGroupByWebsiteId($website_id=0)
    {

        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__usergroups')
            ->where(
                array(
                    'website_id='.(int)$website_id,
                    'title!='.$db->q('Public')
                )
            );

        $db->setQuery($query);
        $listGroup=$db->loadObjectList();
        return $listGroup;
    }
    public function  getAllGroupByWebsiteId($website_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__usergroups')
            ->where(
                array(
                    'website_id='.(int)$website_id
                )
            );

        $db->setQuery($query);
        $listGroup=$db->loadObjectList();
        return $listGroup;
    }
}
