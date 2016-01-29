<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Menu
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die(__FILE__);

/**
 * JMenu class
 *
 * @package     Joomla.Libraries
 * @subpackage  Menu
 * @since       1.5
 */
class JMenuSite extends JMenu
{
	/**
	 * Loads the entire menu table into memory.
	 *
	 * @return  array
	 */
	public function load()
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('m.id,m.menu_type_id,m.hidden, m.menutype,m.binding_source_key,m.binding_source,m.binding_source_value,m.icon,m.lesscontent, m.title, m.alias, m.note, m.path AS route, m.link, m.type, m.level, m.language');
		$query->select($db->quoteName('m.browserNav') . ', m.access, m.params, m.home, m.img, m.template_style_id, m.component_id, m.parent_id');
		$query->select('e.element as component');
		$query->from('#__menu AS m');
		$query->leftJoin('#__extensions AS e ON m.component_id = e.id');
		$query->where('m.published = 1');
		$query->where('m.parent_id > 0');
		$query->where('m.client_id = 0');
		$query->where('m.alias != '.$query->quote('root'));
		$query->order('m.lft');
        $website=JFactory::getWebsite();
        $query->leftJoin('#__menu_types AS mt ON mt.id=m.menu_type_id');
        $query->where('mt.website_id='.$website->website_id);


		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// OSE Added - OSE and Open Source Excellence is the registered trade mark of the Open Source Excellence PTE LTD.
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$where = null;
		if (!defined('DS'))
		{
			define('DS', DIRECTORY_SEPARATOR);
		}		
		if (file_exists(JPATH_SITE.DS.'components'.DS.'com_osemsc'.DS.'init.php') && file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_ose_cpu'.DS.'define.php') && !file_exists(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_osemsc'.DS.'installer.dummy.ini'))
		{
			require_once(JPATH_SITE.DS.'components'.DS.'com_osemsc'.DS.'init.php');
		
			$content_ids = oseRegistry::call('content')->getRestrictedContent('joomla','menu');
		
			$where = (COUNT($content_ids) > 0)?$query->where(' m.id NOT IN ('.implode(',',$content_ids).')'):null;
		}
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// OSE Added - OSE and Open Source Excellence is the registered trade mark of the Open Source Excellence PTE LTD.
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		// Set the query
        $cache = JFactory::getCache('_system', 'callback');
		$items1=array();
        try
        {
            $db->setQuery($query);
			$items1 = $db->loadObjectList();// $cache->get(array($db, 'loadObjectList'), null, md5($query), false);
			$items1=JArrayHelper::pivot($items1,'id');
        }

        catch (RuntimeException $e)
        {
            // Fatal error.
            JLog::add(JText::sprintf('JERROR_LOADING_MENUS', md5($query), $e->getMessage()), JLog::WARNING, 'jerror');
            return false;
        }

		JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/models');
		$modalDataSources=JModelLegacy::getInstance('DataSources','phpMyAdminModel');
		$items=array();
		if(count($items1))
		{
			foreach ($items1 as $i => $item)
			{
				$query=array();
				$url = str_replace('index.php?', '', $item->link);
				$url = str_replace('&amp;', '&', $url);
				parse_str($url, $query);
				foreach($query as $key_query=>$value_key_query)
				{
					if(trim($value_key_query)=='')
					{
						unset($query[$key_query]);
					}
				}
				if(empty($query))
				{
					$item->link='index.php?option=com_utility&view=blank';
					$item->type="component";
					$table_menu=JTable::getInstance('Menu');
					JTable::addIncludePath(JPATH_ROOT.'/components/com_menus/tables');
					if(!$table_menu->load($item->id))
					{
						throw new Exception($table_menu->getError());
					}
					if(!$table_menu->bind((array)$item))
					{
						throw new Exception($table_menu->getError());
					}
					if(!$table_menu->store())
					{
						throw new Exception($table_menu->getError());
					}
				}

				$items[$i]=$item;
				$item1=$item;
				if(trim($item->binding_source)!='')
				{
					$list_item_binding_source=$modalDataSources->getListDataSource($item->binding_source);


					if(count($list_item_binding_source))
					{
						$children = array();

						// First pass - collect children
						foreach ($list_item_binding_source as $v)
						{
							$pt = $v->parent_id;
							$list = @$children[$pt] ? $children[$pt] : array();
							array_push($list, $v);
							$children[$pt] = $list;
						}

						$id=key($children);
						JMenuSite::treerecurse_menu_item($id, $items,$item, $children);


					}
				}
			}
		}

		$this->_items=$items;
		foreach ($this->_items as &$item)
		{
			// Get parent information.
			$parent_tree = array();
			if (isset($this->_items[$item->parent_id]))
			{
				$parent_tree  = $this->_items[$item->parent_id]->tree;
			}

			// Create tree.
			$parent_tree[] = $item->id;
			$item->tree = $parent_tree;

			// Create the query array.
			$url = str_replace('index.php?', '', $item->link);
			$url = str_replace('&amp;', '&', $url);
			$http_build_query=http_build_query($item->query);
			if($http_build_query)
			{
				$url.=$http_build_query;
			}
			$item->link='index.php?'.$url;
			parse_str($url, $item->query);
		}

	}
	function treerecurse_menu_item($id, &$items,$item, &$children, $maxlevel = 9999, $level = 0)
	{

		if (@$children[$id] && $level <= $maxlevel) {
			foreach ($children[$id] as $key=> $v) {
				$item1= clone $item;
				$id = $v->id;
				$level1=$level + 1;
				$item1->id_of_item=$v->id;
				$item1->parent_id_of_item=$v->parent_id;
				$item1->title=$v->title;
				$item1->alias=$v->alias;
				$item1->level+=$level1;
				$item1->type="component";
				$item1->link='';
				$item1->json_of_item=json_encode($v);
				$item1->query['option']='com_utility';
				$item1->query['view']='blank';
				$item1->query[$item1->binding_source_key]=$v->{$item1->binding_source_value};
				$item1->query['Itemid']=$item1->id;
				$key=$item1->id.'-'.$id;
				$items[$key]=$item1;
				//unset($children[$id]);
				JMenuSite::treerecurse_menu_item($id, $items,$item, $children, $maxlevel,$level1 );
			}
		}
	}

	public function get_menu_default_by_website_id($website_id){
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('menu.*')
			->from('#__menu AS menu')
			->where('menu.home=1')
			->leftJoin('#__menu_types AS menu_type ON menu_type.id=menu.menu_type_id')
			->where('menu_type.website_id='.(int)$website_id)
			;
		$item=$db->setQuery($query)->loadObject();
		return $item;
	}
	/**
	 * Gets menu items by attribute
	 *
	 * @param   string   $attributes  The field name
	 * @param   string   $values      The value of the field
	 * @param   boolean  $firstonly   If true, only returns the first item found
	 *
	 * @return	array
	 */
	public function getItems($attributes, $values, $firstonly = false)
	{

		$attributes = (array) $attributes;
		$values 	= (array) $values;
		$app		= JApplication::getInstance('site');
		if ($app->isSite())
		{
			// Filter by language if not set
			if (($key = array_search('language', $attributes)) === false)
			{
				if ($app->getLanguageFilter())
				{
					$attributes[] 	= 'language';
					$values[] 		= array(JFactory::getLanguage()->getTag(), '*');
				}
			}
			elseif ($values[$key] === null)
			{
				unset($attributes[$key]);
				unset($values[$key]);
			}
			// Filter by access level if not set
			if (($key = array_search('access', $attributes)) === false)
			{
				$attributes[] = 'access';
				$user=JFactory::getUser();
				$values[] = $user->getAuthorisedViewLevels();


			}
			elseif ($values[$key] === null)
			{
				unset($attributes[$key]);
				unset($values[$key]);
			}
		}

		return parent::getItems($attributes, $values, $firstonly);
	}

	/**
	 * Get menu item by id
	 *
	 * @param   string  $language  The language code.
	 *
	 * @return  object  The item object
	 *
	 * @since   1.5
	 */
	public function getDefault($language = '*')
	{
		if (array_key_exists($language, $this->_default) && JApplication::getInstance('site')->getLanguageFilter())
		{
			return $this->_items[$this->_default[$language]];
		}
		elseif (array_key_exists('*', $this->_default))
		{
			return $this->_items[$this->_default['*']];
		}
		else
		{

			return 0;
		}
	}

}
