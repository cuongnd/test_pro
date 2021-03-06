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
	const ALIAS = "alias";
	const LIST_MENU_ITEM_TYPE = array(
		alias=>"alias",
		none=>"none"
	);

	public static function get_active_menu_item()
    {
        $app=JFactory::getApplication();
        $menu=$app->getMenu();
        return $menu->getActive();
    }

    public static function change_param_module_by_fields($website_id, $params)
    {
        /**
         * @param int $level
         * @param int $max_level
         */
        $change_param_menu_by_fields =function ($function_call_back,$list_type=array(),$website_id, $field, &$params,$path='',&$level=0, $max_level=999) {
            $list_field=$field->children;
            unset($field->children);
            if($level==0)
            {
                $path1=$path;
            }else{
                $path1=$path!=""?"$path.$field->name":$field->name;
            }
            if(count($list_field) && $level<$max_level) {

                $level1=$level+1;
                foreach($list_field AS $field1)
                {
                    $function_call_back($function_call_back,$list_type,$website_id,$field1,$params,$path1,$level1,$max_level);
                }
            }else{
                $type=$field->type;
                if(in_array($type,$list_type))
                {
                    $class_field_path=$field->addfieldpath;
                    if(file_exists(JPATH_ROOT.DS.$class_field_path))
                    {
                        require_once JPATH_ROOT.DS.$class_field_path;
                    }
                    $class_field='JFormField'.$type;
                    if(method_exists($class_field,'get_new_value_by_old_value'))
                    {
                        $new_value=call_user_func(array($class_field, 'get_new_value_by_old_value'),$website_id,$params,$path1);
                        $params->set($path1,$new_value);
                    }
                }
            }

        };
        $tmp = new JRegistry;
        $tmp->loadString($params);
        $params=$tmp;
        echo "<pre>";
        print_r($params);
        echo "</pre>";
        die;
        $list_type=JFormField::get_list_type_must_change_params_when_create_website();
        $change_param_menu_by_fields($change_param_menu_by_fields,$list_type,$website_id,$fields[0],$params);
        return $params->toString();
    }


	public function getMenuLogin()
	{
		foreach($this->_items as $item){
			$link=$item->link;
			$link=strtolower($link);
			if (strpos($link, 'index.php?option=com_users&view=login') !== false) {
				return $item;
			}
		}
		return 0;
	}
    /**
	 * Loads the entire menu table into memory.
	 *
	 * @return  array
	 */
	public function load()
	{

		if(!empty($this->_items))
		{
			return $this->_items;
		}
		$db    = JFactory::getDbo();
		$website=JFactory::getWebsite();
		require_once JPATH_ROOT.'/components/com_menus/helpers/menus.php';
		$items=MenusHelperFrontEnd::get_list_menu_item_by_website_id($website->website_id);
		$items=JArrayHelper::pivot($items,'id');
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
			//parse configviewlayout
			$temp = new JRegistry;
			$temp->loadString(base64_decode($item->configviewlayout));
			$item->configviewlayout=$temp;
			//end parse configviewlayout
		}


	}
	public function get_menu_item_by_menu_type_id($menu_type_id,$recurse=false)
	{
		$children = array();
		// First pass - collect children
		foreach ($this->_items as $v) {
			$pt = $v->parent_id;
			$pt=($pt==''||$pt==$v->id)?'list_root':$pt;
			$list = @$children[$pt] ? $children[$pt] : array();
			if ($v->id != $v->parent_id || $v->parent_id!=null) {
				array_push($list, $v);
			}
			$children[$pt] = $list;
		}
		$list_root_menu_item=$children['list_root'];
		unset($children['list_root']);
		$list_menu_item=array();
		foreach($list_root_menu_item as $root_menu_item)
		{
			if($root_menu_item->menu_type_id==$menu_type_id)
			{
				if($recurse)
				{
					$list_menu_item[]=$root_menu_item;
				}
				MenusHelperFrontEnd::get_list_children_menu_item_by_root_menu_item_id($root_menu_item->id,$list_menu_item,$children);
				break;
			}
		}
		return $list_menu_item;
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
        $list_menu=MenusHelperFrontEnd::get_list_menu_item_by_website_id($website_id);
        foreach($list_menu as $menu)
        {
            if($menu->home==1)
            {
                return $menu;
            }
        }
	    return 0;
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
       foreach($this->_items as $item)
       {
           if($item->home==1)
           {
               return $item;
           }
       }
        return 0;
	}

}
