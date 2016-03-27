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
    public static function  copy_categories($from_website_id=0,$to_website_id=0)
    {
        $db=JFactory::getDbo();
		$table_category=JTable::getInstance('category');
        //check created root
        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__categories')
            ->where('(id=parent_id OR parent_id IS NULL)')
            ->where('website_id='.(int)$from_website_id)
		;
        $db->setQuery($query);
        $list_root_category=$db->loadObjectList();
        if(count($list_root_category)==0)
        {
            return true;
        }
        $list_old_category_id=array();
        foreach($list_root_category as $category)
        {
            $table_category->bind((array)$category);
            $table_category->id=0;
            $table_category->parent_id=NULL;
            $table_category->copy_from=$category->id;
            $table_category->website_id=$to_website_id;
            $ok=$table_category->parent_store();
            if(!$ok)
            {
                throw new Exception($table_category->getError());
            }
            $list_old_category_id[$category->id]=$table_category->id;
        }

        $query=$db->getQuery(true);
        $query->select('*')
            ->from('#__categories')
        ;
        $db->setQuery($query);
        $list_category=$db->loadObjectList('id');
        $children = array();

        // First pass - collect children
        foreach ($list_category as $v) {
            $pt = $v->parent_id;
            $pt=($pt=='' || $pt==$v->id)?'list_root':$pt;
            $list = @$children[$pt] ? $children[$pt] : array();
            array_push($list, $v);
            $children[$pt] = $list;
        }
        if(!function_exists('sub_execute_copy_rows_table_category')) {
            function sub_execute_copy_rows_table_category(JTable $table_category, &$list_old_category_id=array(), $old_category_id = 0, $new_category_id, $children)
            {
                if ($children[$old_category_id]) {
                    foreach ($children[$old_category_id] as $v) {
                        $table_category->bind((array)$v);
                        $table_category->id = 0;
                        $table_category->copy_from = $v->id;
                        $table_category->parent_id = $new_category_id;
                        $table_category->getDbo()->rebuild_action=1;
                        $ok = $table_category->parent_store();
                        if (!$ok) {
                            throw new Exception($table_category->getError());
                        }

                        $new_category_id1 = $table_category->id;
                        $old_category_id1 = $v->id;
                        $list_old_category_id[$old_category_id1]=$new_category_id1;
                        sub_execute_copy_rows_table_category($table_category, $list_old_category_id,$old_category_id1, $new_category_id1, $children);
                    }
                }
            }
        }





        $a_list_old_category_id=array();
        foreach($list_old_category_id AS $old_category_id=>$new_category_id)
        {
            $list_old_category_id1=array();
            $list_old_category_id1[$old_category_id]=$new_category_id;
            sub_execute_copy_rows_table_category($table_category,$list_old_category_id1,$old_category_id, $new_category_id,$children);
            $a_list_old_category_id=$list_old_category_id1+$a_list_old_category_id;
        }
        $query->clear()
            ->select('content.*')
            ->from('#__content AS content')
            ->where('content.catid IN ('.implode(',',array_keys($a_list_old_category_id)).')')
        ;
        $db->setQuery($query);
        $list_content=$db->loadObjectList();
        $table_content=JTable::getInstance('content');
        foreach($list_content as $content)
        {
            $table_content->bind((array)$content);
            $table_content->id=0;
            $category_id=$content->catid;
            $table_content->catid=$a_list_old_category_id[$category_id];
            $ok = $table_content->store();
            if (!$ok) {
                throw new Exception($table_content->getError());
            }
        }
        return true;
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
