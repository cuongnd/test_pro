<?php

/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 12/25/2015
 * Time: 2:15 PM
 */
class block_helper
{

    const ELEMENT_TYPE_NAME = 'element';
    const ROOT_ELEMENT_NAME = 'root_element';

    public static function get_html_by_block_id($block_id, $enableEditWebsite=false)
    {
        $tablePosition=JTable::getInstance('positionnested');
        $tablePosition->load($block_id);
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('poscon.*')
            ->from('#__position_config AS poscon')
            ->where('lft>'.(int)$tablePosition->lft.' AND  rgt<'.(int)$tablePosition->rgt)

            ->order('poscon.ordering')
        ;
        $listPositionsSetting=$db->setQuery($query)->loadObjectList();

        $children = array();
        if (!empty($listPositionsSetting)) {

            $children = array();

            // First pass - collect children
            foreach ($listPositionsSetting as $v) {
                $pt = $v->parent_id;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }

        }
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        $html='';
        websiteHelperFrontEnd::treeRecurse($block_id, $html, $children, 99, 0, $enableEditWebsite);
        $html=websiteHelperFrontEnd::getHeaderHtml($tablePosition,$enableEditWebsite).$html.websiteHelperFrontEnd::getFooterHtml($tablePosition,$enableEditWebsite);
        return $html;


    }

    public static function remove_all_block_not_exists_menu_item()
    {
        JTable::addIncludePath(JPATH_ROOT . '/components/com_utility/tables');
        $tablePosition = JTable::getInstance('positionnested');
        $website = JFactory::getWebsite();
        $tablePosition->webisite_id = $website->website_id;
        $list_menu_item_id=MenusHelperFrontEnd::get_list_menu_item_id_by_website_id($website->website_id);
        $root_position_id = $tablePosition->get_root_id();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('position_config.id,position_config.parent_id,position_config.menu_item_id')
            ->from('#__position_config AS position_config')
            ->where('position_config.parent_id=' . (int)$root_position_id)
            ->where('position_config.parent_id!=position_config.id')
        ;
        $db->setQuery($query);
        $db->rebuild_action=1;
        $list_position = $db->loadObjectList();
        $list_delete=array();
        $list_delete[]=0;
        foreach($list_position as $position)
        {
            if(!in_array($position->menu_item_id,$list_menu_item_id))
            {
                array_push($list_delete,$position->id);
            }
        }
        foreach($list_delete as $item)
        {
            $action_delete=function($function_call_back, $parent_position_id=0){
                $db=JFactory::getDbo();
                $query=$db->getQuery(true);
                $query->select('id')
                    ->from('#__position_config')
                    ->where('parent_id='.(int)$parent_position_id)
                ;
                $list_position_id=$db->setQuery($query)->loadColumn();
                if(count($list_position_id))
                {
                    foreach($list_position_id as $position_id)
                    {
                        $function_call_back($function_call_back,$position_id);

                    }
                }else{
                    $query=$db->getQuery(true);
                    $query->delete('#__position_config')
                        ->where('id='.(int)$parent_position_id)
                    ;
                    $db->setQuery($query);
                    $ok=$db->execute();
                    if(!$ok)
                    {
                        throw new Exception($db->getErrorMsg());
                    }

                }
            };
            $action_delete($action_delete,$item);

        }
       /* $query = $db->getQuery(true);
        $query->delete('#__position_config')
            ->where('id IN('.implode(',',$list_delete).')')
            ;
        $db->setQuery($query);
        if(!$db->execute())
        {
            throw new Exception($db->getErrorMsg());
        }*/
    }
    public static function remove_all_block_not_exists_menu_item_by_website_id($website_id=0)
    {
        JTable::addIncludePath(JPATH_ROOT . '/components/com_utility/tables');
        $tablePosition = JTable::getInstance('positionnested');
        $list_menu_item_id=MenusHelperFrontEnd::get_list_menu_item_id_by_website_id($website_id);
        $root_position_id = $tablePosition->get_root_id_by_website_id($website_id);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('position_config.id,position_config.parent_id,position_config.menu_item_id')
            ->from('#__position_config AS position_config')
            ->where('position_config.parent_id=' . (int)$root_position_id)
            ->where('position_config.parent_id!=position_config.id')
        ;
        $db->setQuery($query);
        $db->rebuild_action=1;
        $list_position = $db->loadObjectList();
        $list_delete=array();
        $list_delete[]=0;
        foreach($list_position as $position)
        {
            if(!in_array($position->menu_item_id,$list_menu_item_id))
            {
                array_push($list_delete,$position->id);
            }
        }
        foreach($list_delete as $item)
        {
            $action_delete=function($function_call_back, $parent_position_id=0){
                $db=JFactory::getDbo();
                $query=$db->getQuery(true);
                $query->select('id')
                    ->from('#__position_config')
                    ->where('parent_id='.(int)$parent_position_id)
                ;
                $list_position_id=$db->setQuery($query)->loadColumn();
                if(count($list_position_id))
                {
                    foreach($list_position_id as $position_id)
                    {
                        $function_call_back($function_call_back,$position_id);

                    }
                }else{
                    $query=$db->getQuery(true);
                    $query->delete('#__position_config')
                        ->where('id='.(int)$parent_position_id)
                    ;
                    $db->setQuery($query);
                    $ok=$db->execute();
                    if(!$ok)
                    {
                        throw new Exception($db->getErrorMsg());
                    }

                }
            };
            $action_delete($action_delete,$item);

        }
       /* $query = $db->getQuery(true);
        $query->delete('#__position_config')
            ->where('id IN('.implode(',',$list_delete).')')
            ;
        $db->setQuery($query);
        if(!$db->execute())
        {
            throw new Exception($db->getErrorMsg());
        }*/
    }
    public static function fix_block()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('root_position_id_website_id.position_id AS id')
            ->from('#__root_position_id_website_id AS root_position_id_website_id')
        ;
        $db->setQuery($query);
        $list_root_block_id=$db->loadColumn();
        $query->clear()
            ->select('position_config.id,position_config.parent_id')
            ->from('#__position_config AS position_config')
        ;
        $list_block=$db->setQuery($query)->loadObjectList();

        $children_block_item = array();
        foreach ($list_block as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_block_item[$pt] ? $children_block_item[$pt] : array();
            array_push($list, $v);
            $children_block_item[$pt] = $list;
        }

        unset($children_block_item['list_root']);
        $list_all_block_id_has_website=array();
        foreach($list_root_block_id as $root_block_id)
        {
            $list_all_block_id_has_website[]=$root_block_id;
            $get_list_all_block_of_website=function($function_call_back, &$list_all_block_of_website=array(), $block_id=0, $children_block_item){

                if(count($children_block_item[$block_id]))
                {
                    foreach($children_block_item[$block_id] as $block_item)
                    {
                        $list_all_block_of_website[]=$block_item->id;
                        $function_call_back($function_call_back,$list_all_block_of_website,$block_item->id,$children_block_item);

                    }
                }
            };
            $get_list_all_block_of_website($get_list_all_block_of_website,$list_all_block_id_has_website,$root_block_id,$children_block_item);

        }
        $list_block_id_not_of_website=array();
        foreach($list_block as $block)
        {
            if(!in_array($block->id,$list_all_block_id_has_website))
            {
                $list_block_id_not_of_website[]=$block->id;
            }
        }
        if(count($list_block_id_not_of_website))
        {
            $query->clear();
            $query->delete('#__position_config')
                ->where('id IN ('.implode(',',$list_block_id_not_of_website).')')
            ;
            $db->setQuery($query);
            $ok=$db->execute();
            if(!$ok)
            {
                throw new Exception($db->getErrorMsg());
            }
        }
    }
    public static function change_property_position_by_fields($website_id, &$form, $str_params_base64, $str_main_property_base64)
    {
        /**
         * @param int $level
         * @param int $max_level
         */
        $change_property_position_by_fields =function ($function_call_back, $list_type=array(), $website_id, $fields, $type_fields_position, &$form, $path='', &$level=0, $max_level=999) {
            if($type_fields_position=='params'){
                $path='params';
            }
            if(is_array($fields)&&count($fields))
            {
                foreach($fields as $field) {
                    $list_field = $field->children;
                    unset($field->children);
                    if ($level == 0) {
                        $path1 = $path;
                    } else {
                        $path1 = $path != "" ? "$path.$field->name" : $field->name;
                    }
                    if (count($list_field) && $level < $max_level) {


                        $level1 = $level + 1;
                        foreach ($list_field AS $field1) {
                            $function_call_back($function_call_back,  $list_type, $website_id, $field1,$type_fields_position, $form, $path1, $level1, $max_level);
                        }
                    } else {

                        $type = $field->type;
                        $type=$type?$type:'text';

                        if (in_array($type, $list_type)) {
                            $class_field_path = $field->addfieldpath;
                            if (file_exists(JPATH_ROOT . DS . $class_field_path)) {
                                require_once JPATH_ROOT . DS . $class_field_path;
                            }

                            $field_object=$form->getField($field->name,$path);
                            if (method_exists($field_object, 'get_new_value_by_old_value')) {
                                $new_value = call_user_func(array($field_object, 'get_new_value_by_old_value'), $website_id);
                                $form->setValue($field->name,$path,$new_value);
                            }
                        }
                    }
                }
            }else{

                $field=$fields;
                if ($level == 0) {
                    $path1 = $path;
                } else {
                    $path1 = $path != "" ? "$path.$field->name" : $field->name;
                }
                $type = $field->type;
                $type=$type?$type:'text';

                if (in_array($type, $list_type)) {
                    $class_field_path = $field->addfieldpath;
                    if (file_exists(JPATH_ROOT . DS . $class_field_path) && $class_field_path!= JPATH_ROOT && $class_field_path!='') {
                        require_once JPATH_ROOT . DS . $class_field_path;
                    }

                    $field_object=$form->getField($field->name,$path);

                    if (method_exists($field_object, 'get_new_value_by_old_value')) {
                        $new_value=call_user_func(array($field_object, 'get_new_value_by_old_value'), $website_id);
                        $form->setValue($field->name,$path,$new_value);
                    }
                }
            }

        };

        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';

        $list_type=JFormField::get_list_type_must_change_params_when_create_website();

        $main_property = base64_decode($str_main_property_base64);
        $main_property = (array)up_json_decode($main_property, false, 512, JSON_PARSE_JAVASCRIPT);
        $change_property_position_by_fields($change_property_position_by_fields,$list_type,$website_id,$main_property,'property',$form);
        $params_property = base64_decode($str_params_base64);
        $params_property = (array)up_json_decode($params_property, false, 512, JSON_PARSE_JAVASCRIPT);
        $change_property_position_by_fields($change_property_position_by_fields,$list_type,$website_id,$params_property,'params',$form);
    }





    private static function get_list_root_block()
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('root_position_id_website_id.*')
            ->from('#__root_position_id_website_id AS root_position_id_website_id')
        ;
        $db->setQuery($query);
        return $db->loadObjectList();
    }
}