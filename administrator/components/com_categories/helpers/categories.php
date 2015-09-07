<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Categories helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_categories
 * @since       1.6
 */
class CategoriesHelper
{
	/**
	 * Configure the Submenu links.
	 *
	 * @param   string  $extension  The extension being used for the categories.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public static function addSubmenu($extension)
	{
		// Avoid nonsense situation.
		if ($extension == 'com_categories')
		{
			return;
		}

		$parts = explode('.', $extension);
		$component = $parts[0];

		if (count($parts) > 1)
		{
			$section = $parts[1];
		}

		// Try to find the component helper.
		$eName = str_replace('com_', '', $component);
		$file = JPath::clean(JPATH_ADMINISTRATOR . '/components/' . $component . '/helpers/' . $eName . '.php');

		if (file_exists($file))
		{
			require_once $file;

			$prefix = ucfirst(str_replace('com_', '', $component));
			$cName = $prefix . 'Helper';

			if (class_exists($cName))
			{
				if (is_callable(array($cName, 'addSubmenu')))
				{
					$lang = JFactory::getLanguage();

					// Loading language file from the administrator/language directory then
					// loading language file from the administrator/components/*extension*/language directory
					$lang->load($component, JPATH_BASE, null, false, true)
					|| $lang->load($component, JPath::clean(JPATH_ADMINISTRATOR . '/components/' . $component), null, false, true);

					call_user_func(array($cName, 'addSubmenu'), 'categories' . (isset($section) ? '.' . $section : ''));
				}
			}
		}
	}
    public function getListCategoryByWebsiteId($website_id=0)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__categories')
            ->where('website_id='.(int)$website_id)
            ->where('extension!='.$db->quote('system'));
        $db->setQuery($query);
        return $db->loadObjectList();
    }
    public function  createRootCategory($website_id)
    {

        $db=JFactory::getDbo();

        //check created root
        $query=$db->getQuery(true);
        $query->select('id')
            ->from('#__categories')
            ->where('id=parent_id')
            ->where('website_id='.(int)$website_id);
        $db->setQuery($query);

        $rootId=$db->loadResult();
        if(!$rootId)
        {

            $query->clear();
            $query->select('MAX(id)')
                ->from('#__categories');
            $parent_id=$db->setQuery($query)->loadResult();

            if($parent_id)
                $parent_id++;
            else
                $parent_id=1;
            $tableCategory=new stdClass();
            $tableCategory->id=$parent_id;
            $tableCategory->website_id=$website_id;
            $tableCategory->title=$query->q('ROOT');
            $tableCategory->alias=$query->q('root');
            $tableCategory->extension=$query->q('system');
            $tableCategory->published=1;
            $tableCategory->parent_id=$parent_id;
            $tableCategory->lft=0;
            $tableCategory->rgt=0;
            $listKeyOfObjectRoot=array();
            $listValueOfObjectRoot=array();
            foreach($tableCategory as $key=>$value)
            {
                $listKeyOfObjectRoot[]=$key;
                $listValueOfObjectRoot[]=$value;

            }
            $query->clear();
            //	 * $query->insert('#__a')->columns('id, title')->values(array('1,2', '3,4'));
            $query->insert('#__categories')
                ->columns(implode(',',$listKeyOfObjectRoot))
                ->values(implode(',',$listValueOfObjectRoot));
            $db->setQuery($query);

            if(!$db->execute())
            {
                return false;
            }
            $rootId=$db->insertid();
        }
        return $rootId;
    }

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param   string   $extension   The extension.
	 * @param   integer  $categoryId  The category ID.
	 *
	 * @return  JObject
	 *
	 * @since   1.6
	 * @deprecated  3.2  Use JHelperContent::getActions() instead
	 */
	public static function getActions($extension, $categoryId = 0)
	{
		// Log usage of deprecated function
		JLog::add(__METHOD__ . '() is deprecated, use JHelperContent::getActions() with new arguments order instead.', JLog::WARNING, 'deprecated');

		// Get list of actions
		$result = JHelperContent::getActions($extension, 'category', $categoryId);

		return $result;
	}

	public static function getAssociations($pk, $extension = 'com_content')
	{
		$associations = array();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->from('#__categories as c')
			->join('INNER', '#__associations as a ON a.id = c.id AND a.context=' . $db->quote('com_categories.item'))
			->join('INNER', '#__associations as a2 ON a.key = a2.key')
			->join('INNER', '#__categories as c2 ON a2.id = c2.id AND c2.extension = ' . $db->quote($extension))
			->where('c.id =' . (int) $pk)
			->where('c.extension = ' . $db->quote($extension));
		$select = array(
			'c2.language',
			$query->concatenate(array('c2.id', 'c2.alias'), ':') . ' AS id'
		);
		$query->select($select);
		$db->setQuery($query);
		$contentitems = $db->loadObjectList('language');

		// Check for a database error.
		if ($error = $db->getErrorMsg())
		{
			JError::raiseWarning(500, $error);

			return false;
		}

		foreach ($contentitems as $tag => $item)
		{
			// Do not return itself as result
			if ((int) $item->id != $pk)
			{
				$associations[$tag] = $item->id;
			}
		}

		return $associations;
	}
}
