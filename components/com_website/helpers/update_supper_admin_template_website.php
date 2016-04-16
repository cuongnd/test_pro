<?php

/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 15/04/2016
 * Time: 4:38 CH
 */
class update_supper_admin_template_website
{
    public static function update_current_website_from_supper_admin_template_website($website_id)
    {
        $table_website=JTable::getInstance('website');
        $table_website->load(array('is_template_supper_admin'=>1));
        $template_supper_admin_website_id=$table_website->id;
        if($template_supper_admin_website_id==$website_id){
            return false;
        }
        if($template_supper_admin_website_id)
        {
            update_supper_admin_template_website::action_step_by_step($website_id,$template_supper_admin_website_id);
        }

    }

    /**
     * @param string $currentStep
     * @throws Exception
     */
    private function action_step_by_step($website_id,$template_supper_admin_website_id)
    {
        $steps=update_supper_admin_template_website::getListStep();
        foreach($steps as $key=>$step)
        {

            $ok= call_user_func_array(array('update_supper_admin_template_website', $step), array($website_id,$template_supper_admin_website_id));
            if(!$ok)
            {

            }
        }


    }
    private function formBase($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function createBasicInfoWebsite($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function createConfiguration($website_id,$template_supper_admin_website_id){

        return true;

    }
    private function createGroupUser($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function createViewAccessLevels($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function createSupperAdmin($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function createComponents($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function createModules($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function createPlugins($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function createStyles($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function create_menu_type($website_id,$template_supper_admin_website_id){
        //copy menu type
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $sql='
          DELETE menu_type_id_menu_id.* FROM #__menu_type_id_menu_id AS menu_type_id_menu_id
          LEFT JOIN #__menu_types AS menu_types ON menu_types.id=menu_type_id_menu_id.menu_type_id
          LEFT JOIN #__menu_types AS menu_types2 ON menu_types2.id=menu_types.copy_from
           WHERE  menu_types2.website_id='.(int)$template_supper_admin_website_id.' AND menu_types.website_id='.(int)$website_id.'
          ';
        $query->setQuery($sql);
        $ok=$db->setQuery($query)->execute();
        if (!$ok) {
            throw new Exception($db->getErrorMsg());
        }

        $query = $db->getQuery(true);
        $query->delete('#__menu_types')
            ->where('copy_from='.(int)$template_supper_admin_website_id)
            ->where('website_id='.(int)$website_id)
            ;
        $ok=$db->setQuery($query)->execute();
        if (!$ok) {
            throw new Exception($db->getErrorMsg());
        }
        $query=$db->getQuery(true);
        //get template menu type
        $query->clear()
            ->select('menu_types.*')
            ->from('#__menu_types AS menu_types')
            ->where('menu_types.website_id=' . (int)$template_supper_admin_website_id);
        $list_older_menu_type = array();
        $list_menu_type = $db->setQuery($query)->loadObjectList();
        require_once JPATH_ROOT . '/libraries/legacy/table/menu/type.php';
        $table_menu_type = JTable::getInstance('menutype', 'JTable');
        foreach ($list_menu_type AS $menu_type) {
            $table_menu_type->bind((array)$menu_type);
            $table_menu_type->id = 0;
            $table_menu_type->copy_from = $menu_type->id;
            $table_menu_type->website_id = $website_id;
            $ok = $table_menu_type->store();
            if (!$ok) {
                throw new Exception($table_menu_type->getError());
            }
            $list_older_menu_type[$menu_type->id] = $table_menu_type->id;
        }
        //end copy menu type
        return true;

    }
    private function createMenus($website_id,$template_supper_admin_website_id){


        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        //get template menu type
        $query->clear()
            ->select('menu_types.*')
            ->from('#__menu_types AS menu_types')
            ->where('menu_types.website_id=' . (int)$website_id)
            ->leftJoin('#__menu_types AS menu_types2 ON menu_types2.id=menu_types.copy_from')
            ->where('menu_types2.website_id='.(int)$template_supper_admin_website_id)
        ;
        $db->setQuery($query);
        $list_supper_admin_menu_type=$db->loadObjectList('copy_from');
        //get root menu item from template
        $list_root_menu=MenusHelperFrontEnd::get_list_root_menu_item_by_website_id($template_supper_admin_website_id);
        if(!count($list_root_menu))
        {
            throw new Exception('there are no menu item');
        }
        $list_root_menu=JArrayHelper::pivot($list_root_menu,'id');
        $table_menu = JTable::getInstance('menu');
        $list_older_root_menu_item=array();
        $table_menu_item_menu_type = JTable::getInstance('menuitemmenutype');
        foreach ($list_root_menu AS $root_menu_id) {
            //create root menu item
            $table_menu->bind((array)$root_menu_id);
            $table_menu->id = 0;
            $table_menu->copy_from = $root_menu_id->id;
            $ok = $table_menu->check();
            $ok = $table_menu->parent_store();
            if (!$ok) {
                throw new Exception($table_menu->getError());
            }
            $new_menu_item=$table_menu->id;
            //end create root memnu item
            //store old  menu item
            $list_older_root_menu_item[$root_menu_id->id] = $new_menu_item;
            //create link root menu item width menu type
            $table_menu_item_menu_type->id=0;
            $table_menu_item_menu_type->menu_type_id = $list_supper_admin_menu_type[$root_menu_id->menu_type_id]->id;
            $table_menu_item_menu_type->menu_id =  $new_menu_item;
            $ok = $table_menu_item_menu_type->store();
            if (!$ok) {
                throw new Exception($table_menu_item_menu_type->getError());
            }
            //end get link root menu item width menu type
        }


        require_once JPATH_ROOT . '/components/com_menus/helpers/menus.php';
        $a_list_older_menu_item1 = array();
        foreach ($list_older_root_menu_item AS $old_root_menu_item_id => $new_root_menu_item_id) {
            $list_older_menu_item1 = array();
            $list_older_menu_item1[$old_root_menu_item_id] = $new_root_menu_item_id;
            $list_children_menu_item_of_root_menu_item = MenusHelperFrontEnd::get_children_menu_item_by_menu_item_id($old_root_menu_item_id);
            $children_menu_item_of_root_menu_item = array();
            // First pass - collect children
            foreach ($list_children_menu_item_of_root_menu_item as $v) {
                $pt = $v->parent_id;
                $pt=($pt==''||$pt==$v->id)?'list_root':$pt;
                $list = @$children_menu_item_of_root_menu_item[$pt] ? $children_menu_item_of_root_menu_item[$pt] : array();
                array_push($list, $v);
                $children_menu_item_of_root_menu_item[$pt] = $list;
            }
            if (!function_exists('sub_execute_copy_rows_table_menu')) {
                function sub_execute_copy_rows_table_menu(JTable $table_menu, &$list_old_menu_item_id = array(), $old_menu_item_id = 0, $new_menu_item_id, $children_menu_item_of_root_menu_item)
                {
                    if ($children_menu_item_of_root_menu_item[$old_menu_item_id]) {
                        foreach ($children_menu_item_of_root_menu_item[$old_menu_item_id] as $v) {
                            $table_menu->bind((array)$v);
                            $table_menu->id = 0;
                            $table_menu->copy_from = $v->id;
                            $table_menu->parent_id = $new_menu_item_id;
                            $table_menu->getDbo()->rebuild_action = 1;
                            $home=$v->home;
                            if($home)
                            {
                                $table_menu->is_dashboard_menu_supper_admin=1;
                            }
                            $table_menu->home=0;
                            $ok = $table_menu->parent_store();
                            if (!$ok) {
                                throw new Exception($table_menu->getError());
                            }
                            $new_menu_item_id1 = $table_menu->id;
                            $old_menu_item_id1 = $v->id;
                            $list_old_menu_item_id[$old_menu_item_id1] = $new_menu_item_id1;
                            sub_execute_copy_rows_table_menu($table_menu, $list_old_menu_item_id, $old_menu_item_id1, $new_menu_item_id1, $children_menu_item_of_root_menu_item);
                        }
                    }
                }
            }
            unset($children_menu_item_of_root_menu_item['list_root']);
            sub_execute_copy_rows_table_menu($table_menu, $list_older_menu_item1, $old_root_menu_item_id, $new_root_menu_item_id, $children_menu_item_of_root_menu_item);
            $a_list_older_menu_item1 = $list_older_menu_item1 + $a_list_older_menu_item1;
        }
        return true;

    }
    private function create_blocks($website_id,$template_supper_admin_website_id){
        //delete old block

        $list_menu_item_supper_admin_site=MenusHelperFrontEnd::get_list_menu_item_id_by_website_id($template_supper_admin_website_id);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $sql='
          DELETE position_config.* FROM #__position_config AS position_config
          LEFT JOIN #__menu AS menu ON menu.id=position_config.menu_item_id
           WHERE  menu.copy_from IN ('.implode(',',$list_menu_item_supper_admin_site).') AND position_config.website_id='.(int)$website_id.'
          ';
        $query->setQuery($sql);
        $ok=$db->setQuery($query)->execute();
        if (!$ok) {
            throw new Exception($db->getErrorMsg());
        }
        //end delete old block
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->clear()
            ->select('position_config.id,position_config.parent_id,position_config.website_id,position_config.menu_item_id,position_config.copy_from')
            ->from('#__position_config AS position_config')
            ->leftJoin('#__menu AS menu ON menu.id=position_config.menu_item_id')
        ;
        $db->setQuery($query);
        $list_position_config = $db->loadObjectList();
        $children_position = array();
        foreach ($list_position_config as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_position[$pt] ? $children_position[$pt] : array();
            array_push($list, $v);
            $children_position[$pt] = $list;
        }

        $list_root_position = $children_position['list_root'];

        unset($children_position['list_root']);
        if (!function_exists('sub_execute_copy_rows_table_position')) {
            function sub_execute_copy_rows_table_position(JTable $table_position,$website_id=0,$list_older_menu_item, $old_position_id = 0, $new_position_id, $children,$level=0,$max_level=999)
            {
                if ($children[$old_position_id]&&$level<=$max_level) {
                    $level1=$level+1;
                    foreach ($children[$old_position_id] as $v) {
                        $table_position->load($v->id);
                        $table_position->id = 0;
                        $table_position->website_id = $website_id;
                        $table_position->copy_from = $v->id;
                        $table_position->level = $level1;
                        $table_position->menu_item_id = $list_older_menu_item[$v->menu_item_id]->id;
                        $table_position->parent_id = $new_position_id;
                        $table_position->getDbo()->rebuild_action = 1;
                        $ok = $table_position->parent_store(true);
                        if (!$ok) {
                            throw new Exception($table_position->getError());
                        }

/*                        echo "<hr/>";
                        echo "<br/>";
                        echo 'menu_item_id:'.$list_older_menu_item[$v->menu_item_id]->id;
                        echo "<br/>";
                        echo 'id:'.$table_position->id;
                        echo "<br/>";
                        echo "<hr/>";
*/
                        $new_position_id1 = $table_position->id;
                        $old_position_id1 = $v->id;
                        sub_execute_copy_rows_table_position($table_position,$website_id, $list_older_menu_item, $old_position_id1, $new_position_id1, $children,$level1,$max_level);
                    }
                }
            }
        }
        $table_position = JTable::getInstance('positionnested');
        $list_old_position_config=array();
        $list_menu_item_id=MenusHelperFrontEnd::get_list_menu_item_id_by_website_id($template_supper_admin_website_id);
        $list_menu_item_id_of_website=MenusHelperFrontEnd::get_list_menu_item_id_by_website_id($website_id);
        $query = $db->getQuery(true);
        //get template menu type
        $query->clear()
            ->select('menu.id,menu.copy_from')
            ->from('#__menu AS menu')
            ->leftJoin('#__menu AS menu2 ON menu2.id=menu.copy_from')
            ->where('menu2.id IN ('.implode(',',$list_menu_item_id).')')
            ->where('menu.id IN ('.implode(',',$list_menu_item_id_of_website).')')
        ;
        $db->setQuery($query);
        $list_menu_of_website=$db->loadObjectList('copy_from');

        $query = $db->getQuery(true);

        $query->select('position_config.id')
            ->from('#__position_config AS position_config')
            ->where('(position_config.parent_id =position_config.id || parent_id IS NULL )')
            ->where('position_config.website_id = '.(int)$website_id)
            ->group('position_config.id')
        ;
        $root_id= $db->setQuery($query)->loadResult();
        foreach ($list_root_position as $position) {


            if ($position->website_id == $template_supper_admin_website_id) {
                sub_execute_copy_rows_table_position($table_position,$website_id, $list_menu_of_website, $position->id,$root_id, $children_position);
            }
        }
        return true;
    }
    private function config_blocks_menu($website_id,$template_supper_admin_website_id){
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->clear()
            ->select('menu_item_id_position_id_ordering.*')
            ->from('#__menu_item_id_position_id_ordering AS menu_item_id_position_id_ordering')
            ->where('menu_item_id_position_id_ordering.website_id='.(int)$template_supper_admin_website_id)
        ;
        $db->setQuery($query);
        $list_menu_item_id_position_id = $db->loadObjectList();


        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->clear()
            ->select('position_config.id,position_config.parent_id,position_config.website_id,position_config.menu_item_id,position_config.copy_from')
            ->from('#__position_config AS position_config');
        $db->setQuery($query);
        $list_position_config = $db->loadObjectList();
        $children_position = array();
        foreach ($list_position_config as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_position[$pt] ? $children_position[$pt] : array();
            array_push($list, $v);
            $children_position[$pt] = $list;
        }
        $list_root_position = $children_position['list_root'];
        unset($children_position['list_root']);

        $list_position_of_website=array();
        foreach ($list_root_position as $position) {
            if ($position->website_id == $website_id) {
                $list_position_of_website[$position->id]=$position;
                $get_position_of_website=function($function_call_back,$root_position_id=0,&$list_position_of_website,$list_position=array(),$level=0, $max_level=999){
                    if ($list_position[$root_position_id]&&$level<=$max_level) {
                        $level1=$level+1;
                        foreach ($list_position[$root_position_id] as $v) {
                            $list_position_of_website[$v->id]=$v;
                            $root_position_id1=$v->id;
                            $function_call_back($function_call_back,$root_position_id1, $list_position_of_website, $list_position,$level1,$max_level);
                        }
                    }
                };
                $get_position_of_website($get_position_of_website,$position->id,$list_position_of_website,$children_position);


            }
        }
        $list_root_position_id_of_website=array();
        foreach($list_root_position as $position){
            if ($position->website_id == $website_id) {
                $list_root_position_id_of_website[]=$position->id;
            }
        }
        $list_menu_item_id=MenusHelperFrontEnd::get_list_menu_item_id_by_website_id($template_supper_admin_website_id);
        $query = $db->getQuery(true);
        //get template menu type
        $query->clear()
            ->select('menu_item_id_position_id_ordering.*')
            ->from('#__menu_item_id_position_id_ordering AS menu_item_id_position_id_ordering')
            ->where('menu_item_id_position_id_ordering.menu_item_id IN ('.implode(',',$list_menu_item_id).')')
        ;
        $db->setQuery($query);
        $list_menu_of_website_supper_admin=$db->loadObjectList();
        $list_position_copy_from_of_website=array();
        foreach($list_position_of_website as $position)
        {
            if($position->copy_from&&!array_key_exists($position->copy_from,$list_position_copy_from_of_website))
            {
                $list_position_copy_from_of_website[$position->copy_from]=$position;
            }
        }
        $table_menu_item_id_position_id_ordering = JTable::getInstance('menu_item_id_position_id_ordering');
        foreach ($list_menu_of_website_supper_admin as $menu_position) {
            $menu_item_id=$list_position_copy_from_of_website[$menu_position->position_id]->id;
            $position_id=$list_position_of_website[$menu_position->position_id]->id;
            if($menu_item_id&&$position_id&&$website_id) {
                $table_menu_item_id_position_id_ordering->menu_item_id = $menu_item_id;
                $table_menu_item_id_position_id_ordering->position_id = $position_id;
                $table_menu_item_id_position_id_ordering->website_id = $website_id;
                $ok = $table_menu_item_id_position_id_ordering->store();
                if (!$ok) {
                    throw new Exception($table_menu_item_id_position_id_ordering->getError());
                }
            }
        }
        return true;

    }
    private function copy_module($website_id,$template_supper_admin_website_id){
        //delete old module
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $sql='
          DELETE module.* FROM #__modules AS module
          LEFT JOIN #__modules AS modules2 ON modules2.id=module.copy_from
          LEFT JOIN #__extensions AS extension ON extension.id=modules2.extension_id
          LEFT JOIN #__extensions AS extension2 ON extension2.id=modules.extension_id
           WHERE  extension.website_id = ('.(int)$template_supper_admin_website_id.') AND extension2.website_id='.(int)$website_id.'
          ';
        $query->setQuery($sql);
        $ok=$db->setQuery($query)->execute();
        if (!$ok) {
            throw new Exception($db->getErrorMsg());
        }

    }

    private function getListStep()
    {
        $steps = array();
        $steps[] = 'formBase';
        $steps[] = 'createBasicInfoWebsite';
        $steps[] = 'insertDomainToWebsite';
        $steps[] = 'createConfiguration';
        $steps[] = 'createGroupUser';
        $steps[] = 'createViewAccessLevels';
        $steps[] = 'createSupperAdmin';
        $steps[] = 'createComponents';
        $steps[] = 'createModules';
        $steps[] = 'createPlugins';
/*        $steps[] = 'createStyles';
        $steps[] = 'create_menu_type';
        $steps[] = 'createMenus';
        $steps[] = 'create_blocks';
        $steps[] = 'config_blocks_menu';*/
        $steps[] = 'copy_module';
        $steps[] = 'createControl';
        $steps[] = 'changeParams';
        $steps[] = 'createContentCategory';
        $steps[] = 'finish';
        return $steps;
    }



}