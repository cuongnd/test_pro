<?php

/**
 * copy_d by PhpStorm.
 * User: cuongnd
 * Date: 15/04/2016
 * Time: 4:38 CH
 */
class update_supper_admin_template_website
{

    public static function update_current_website_from_supper_admin_template_website($website_id)
    {


        //update_supper_admin_template_website::remove_duplicate_row();

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
    private function copy_BasicInfoWebsite($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function copy_Configuration($website_id,$template_supper_admin_website_id){

        return true;

    }
    private function copy_GroupUser($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function copy_ViewAccessLevels($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function copy_SupperAdmin($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function copy_components($website_id,$template_supper_admin_website_id){
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->clear()
            ->select('
                component_supper_admin.name,
                component_supper_admin.id AS component_id_supper_admin,component_of_current_website.id AS component_id_current_website,
                component_supper_admin.params AS component_params_supper_admin,
                component_of_current_website.params AS component_params_current_website,
                component_supper_admin.extension_id AS extension_id_supper_admin,
                component_of_current_website.extension_id AS extension_id_current_website,
                extension_supper_admin.website_id AS supper_admin_website_id,
                extension_current_website.website_id AS current_website_website_id
                ')
            ->from('#__components AS component_supper_admin')
            ->leftJoin('#__components AS component_of_current_website ON
             component_of_current_website.name=component_supper_admin.name
              ')
            ->leftJoin('#__extensions AS extension_supper_admin ON extension_supper_admin.id=component_supper_admin.extension_id')
            ->leftJoin('#__extensions AS extension_current_website ON extension_current_website.id=component_of_current_website.extension_id')

            ->where('extension_supper_admin.website_id=' . (int)$template_supper_admin_website_id)
            ->where('(extension_current_website.website_id=' . (int)$website_id.' OR extension_current_website.website_id IS NULL)')
        ;

        $list_components = $db->setQuery($query)->loadObjectList();
        $table_component=JTable::getInstance('component');


        foreach ($list_components AS $component) {
            if($component->component_id_current_website&&$component->website_id=$website_id)
            {
                $table_component->load($component->component_id_current_website);
                $component_params_supper_admin = new JRegistry;
                $component_params_supper_admin->loadString($component->component_params_supper_admin);
                $component_params_current_website = new JRegistry;
                $component_params_current_website->loadString($component->component_params_current_website);
                $component_params_current_website->merge($component_params_supper_admin);
                $table_component->params=$component_params_current_website->toString();

            }else{
                $table_component->load($component->component_id_supper_admin);
                $table_component->id=0;
                $extension_id_2_current_website=JComponentHelper::get_extension_id_by_component_name($website_id,$component->name);
                $table_component->extension_id=$extension_id_2_current_website;
            }
            $ok = $table_component->store();
            if (!$ok) {
                throw new Exception($table_component->getError());
            }
        }
        return true;

    }
    private function copy_Plugins($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function copy_Styles($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function copy_menu_type($website_id,$template_supper_admin_website_id){
        //copy menu type
        $db = JFactory::getDbo();

        $query=$db->getQuery(true);
        //get template menu type
        $query->clear()
            ->select('menu_types.id AS supper_admin_menu_type_id,menu_types2.id AS current_website_menu_type_id,menu_types2.website_id AS current_website_website_id')
            ->from('#__menu_types AS menu_types')
            ->where('menu_types.website_id=' . (int)$template_supper_admin_website_id)
            ->where('(menu_types2.website_id=' . (int)$website_id.' OR  menu_types2.website_id IS NULL)')
            ->leftJoin('#__menu_types AS menu_types2 ON menu_types2.menutype=menu_types.menutype')
        ;
        $list_older_menu_type = array();
        $list_menu_type = $db->setQuery($query)->loadObjectList();
        require_once JPATH_ROOT . '/libraries/legacy/table/menu/type.php';
        $table_menu_type = JTable::getInstance('menutype', 'JTable');
        foreach ($list_menu_type AS $menu_type) {
            if($menu_type->current_website_menu_type_id)
            {
                $table_menu_type->load($menu_type->current_website_menu_type_id);
            }else{
                $table_menu_type->load($menu_type->supper_admin_menu_type_id);
                $table_menu_type->id = 0;
                $table_menu_type->super_admin_menu_type_id = $menu_type->supper_admin_menu_type_id;
                $table_menu_type->website_id = $website_id;
            }

            $ok = $table_menu_type->store();
            if (!$ok) {
                throw new Exception($table_menu_type->getError());
            }
        }
        //end copy menu type
        return true;

    }
    private function copy_menus($website_id,$template_supper_admin_website_id){


        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        //get template menu type
        $query->clear()
            ->select('
                menu_types.id AS supper_admin_menu_type_id,menu_types2.id AS current_website_menu_type_id,
                menu_type_id_menu_id.menu_id AS current_website_menu_item_id
                ')
            ->from('#__menu_types AS menu_types')
            ->where('menu_types.website_id=' . (int)$template_supper_admin_website_id)
            ->leftJoin('#__menu_types AS menu_types2 ON menu_types2.menutype=menu_types.menutype')
            ->where('menu_types2.website_id='.(int)$website_id)
            ->leftJoin('#__menu_type_id_menu_id AS menu_type_id_menu_id ON menu_type_id_menu_id.menu_type_id=menu_types2.id')
            ->where('menu_type_id_menu_id.menu_id IS NULL')
        ;
        $db->setQuery($query);
        $list_menu_type=$db->loadObjectList();
        //get root menu item from template
        $table_menu = JTable::getInstance('menu');
        $table_menu_item_menu_type = JTable::getInstance('menuitemmenutype');
        foreach ($list_menu_type AS $menu_type) {
            //copy_ root menu item
            $table_menu->id = 0;
            $table_menu->copy_from = null;
            $table_menu->menu_type_id = $menu_type->current_website_menu_type_id;
            $table_menu->title = 'Menu_item_root';
            $table_menu->alias = 'root';
            $table_menu->parent_id = null;
            $ok = $table_menu->parent_store();
            if (!$ok) {
                throw new Exception($table_menu->getError());
            }
            $new_menu_item=$table_menu->id;
            //end copy_ root memnu item
            //store old  menu item
            //copy_ link root menu item width menu type
            $table_menu_item_menu_type->id=0;
            $table_menu_item_menu_type->menu_type_id = $menu_type->current_website_menu_type_id;
            $table_menu_item_menu_type->menu_id =  $new_menu_item;
            $ok = $table_menu_item_menu_type->store();
            if (!$ok) {
                throw new Exception($table_menu_item_menu_type->getError());
            }
            //end get link root menu item width menu type
        }

        $query = $db->getQuery(true);
        //get template menu type
        $query->clear()
            ->select('
                menu_types.id AS supper_admin_menu_type_id,menu_types2.id AS current_website_menu_type_id,
                menu_type_id_menu_id.menu_id AS current_website_menu_item_id,
                menu_type_id_menu_id2.menu_id AS supper_admin_menu_item_id
                ')
            ->from('#__menu_types AS menu_types')
            ->where('menu_types.website_id=' . (int)$template_supper_admin_website_id)
            ->leftJoin('#__menu_types AS menu_types2 ON menu_types2.menutype=menu_types.menutype')
            ->where('menu_types2.website_id='.(int)$website_id)
            ->leftJoin('#__menu_type_id_menu_id AS menu_type_id_menu_id ON menu_type_id_menu_id.menu_type_id=menu_types2.id')
            ->leftJoin('#__menu_type_id_menu_id AS menu_type_id_menu_id2 ON menu_type_id_menu_id2.menu_type_id=menu_types.id')
            ->where('menu_type_id_menu_id.menu_id IS NOT NULL')
        ;
        $db->setQuery($query);
        $list_older_root_menu_item=$db->loadObjectList();
        require_once JPATH_ROOT . '/components/com_menus/helpers/menus.php';
        $a_list_older_menu_item1 = array();
        foreach ($list_older_root_menu_item AS $menu_item) {

            $supper_admin_menu_item_id=$menu_item->supper_admin_menu_item_id;
            $current_website_menu_item_id=$menu_item->current_website_menu_item_id;
            $update_supper_menu_item=function($function_call_back, $supper_admin_parent_menu_item_id=0, $current_website_parent_menu_item_id, $level=0, $max_level=999){
                $db=JFactory::getDbo();
                $query=$db->getQuery(true);
                $query->select('
                    supper_admin_menu.title AS supper_admin_menu_title,
                    current_website_menu.title AS current_website_menu_title,
                    supper_admin_menu.id AS supper_admin_menu_item_id,current_website_menu.id AS current_website_menu_item_id,
                    supper_admin_menu.params AS supper_admin_menu_item_params,
                    current_website_menu.params AS current_website_menu_item_params
                    ')
                    ->from('#__menu AS supper_admin_menu')
                    ->where('supper_admin_menu.parent_id='.(int)$supper_admin_parent_menu_item_id)
                    ->leftJoin('#__menu AS current_website_menu ON current_website_menu.title=supper_admin_menu.title AND current_website_menu.parent_id='.(int)$current_website_parent_menu_item_id.' AND current_website_menu.alias!='.$query->q('root'))
                    ->where('supper_admin_menu.alias!='.$query->q('root'))
                    ->group('supper_admin_menu.id,current_website_menu.id')
                    ;
                $list_menu_item=$db->setQuery($query)->loadObjectList();
                foreach($list_menu_item as $menu_item)
                {
                    $table_menu = JTable::getInstance('menu');
                    if($menu_item->current_website_menu_item_id)
                    {
                        $table_menu->load($menu_item->current_website_menu_item_id);
                        if($menu_item->supper_admin_menu_item_params=!$menu_item->current_website_menu_item_params)
                        {
                            $supper_admin_menu_item_params = new JRegistry;
                            $supper_admin_menu_item_params->loadString($menu_item->supper_admin_menu_item_params);
                            $current_website_menu_item_params = new JRegistry;
                            $current_website_menu_item_params->loadString($menu_item->current_website_menu_item_params);
                            $current_website_menu_item_params->merge($supper_admin_menu_item_params);
                            $table_menu->params = $current_website_menu_item_params->toString();
                        }
                    }else{
                        $table_menu->load($menu_item->supper_admin_menu_item_id);
                        $table_menu->id=0;
                    }
                    $home=$table_menu->home;
                    if($home)
                    {
                        $table_menu->is_dashboard_menu_supper_admin=1;
                    }
                    $table_menu->parent_id=$current_website_parent_menu_item_id;
                    $table_menu->home=0;
                    $ok = $table_menu->parent_store();
                    if (!$ok) {
                        throw new Exception($table_menu->getError());
                    }
                    $supper_admin_parent_menu_item_id1=$menu_item->supper_admin_menu_item_id;
                    $current_website_parent_menu_item_id1=$table_menu->id;
                    $function_call_back($function_call_back,$supper_admin_parent_menu_item_id1,$current_website_parent_menu_item_id1);
                }
            };
            $update_supper_menu_item($update_supper_menu_item,$supper_admin_menu_item_id,$current_website_menu_item_id);



        }
        return true;

    }
    private function copy_blocks($website_id,$template_supper_admin_website_id){
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
    private function copy_modules($website_id,$template_supper_admin_website_id){
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->clear()
            ->select('
                module_supper_admin.module,
                module_supper_admin.id AS module_id_supper_admin,module_of_current_website.id AS module_id_current_website,
                module_supper_admin.params AS module_params_supper_admin,
                module_of_current_website.params AS module_params_current_website,
                module_supper_admin.extension_id AS extension_id_supper_admin,
                module_of_current_website.extension_id AS extension_id_current_website,
                extension_supper_admin.website_id AS supper_admin_website_id,
                extension_current_website.website_id AS current_website_website_id
                ')
            ->from('#__modules AS module_supper_admin')
            ->leftJoin('#__modules AS module_of_current_website ON
             module_of_current_website.module=module_supper_admin.module
              ')
            ->leftJoin('#__extensions AS extension_supper_admin ON extension_supper_admin.id=module_supper_admin.extension_id')
            ->leftJoin('#__extensions AS extension_current_website ON extension_current_website.id=module_of_current_website.extension_id')

            ->where('extension_supper_admin.website_id=' . (int)$template_supper_admin_website_id)
            ->where('(extension_current_website.website_id=' . (int)$website_id.' OR extension_current_website.website_id IS NULL ) ')
        ;
        $table_module=JTable::getInstance('module');
        $list_modules = $db->setQuery($query)->loadObjectList();
        foreach ($list_modules AS $module) {
            if($module->module_id_current_website)
            {
                if($module->module_params_supper_admin!=$module->module_params_current_website) {
                    $table_module->load($module->module_id_current_website);
                    $module_params_supper_admin = new JRegistry;
                    $module_params_supper_admin->loadString($module->module_params_supper_admin);
                    $module_params_current_website = new JRegistry;
                    $module_params_current_website->loadString($module->module_params_current_website);
                    $module_params_current_website->merge($module_params_supper_admin);
                    $table_module->params = $module_params_current_website->toString();
                }

            }else{
                $table_module->load($module->module_id_supper_admin);
                $table_module->id=0;
                $extension_id_2_current_website=JmoduleHelper::get_extension_id_by_module_name($website_id,$module->name);
                $table_module->extension_id=$extension_id_2_current_website;
            }
            $ok = $table_module->store();
            if (!$ok) {
                throw new Exception($table_module->getError());
            }
        }
        return true;


    }
    private function copy_extensions($website_id,$template_supper_admin_website_id){

        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->clear()
            ->select('
                extension_supper_admin.element,
                extension_supper_admin.website_id AS supper_admin_website_id,
                extension_of_current_website.website_id AS current_website_website_id,
                extension_supper_admin.id AS extension_id_supper_admin,extension_of_current_website.id AS extension_id_current_website,
                extension_supper_admin.params AS extension_params_supper_admin,
                extension_of_current_website.params AS extension_params_current_website
                ')
            ->from('#__extensions AS extension_supper_admin')
            ->leftJoin('#__extensions AS extension_of_current_website ON
             extension_of_current_website.name=extension_supper_admin.name AND extension_of_current_website.type=extension_supper_admin.type AND extension_of_current_website.website_id='.(int)$website_id.'
              AND extension_of_current_website.element=extension_supper_admin.element AND extension_of_current_website.folder=extension_supper_admin.folder
              ')
            ->where('extension_supper_admin.website_id=' . (int)$template_supper_admin_website_id)

        ;
        $list_extensions = $db->setQuery($query)->loadObjectList();
        $table_extension=JTable::getInstance('extension');



        foreach ($list_extensions AS $extensions) {
            if($extensions->extension_id_current_website)
            {
                if($extensions->extension_params_supper_admin!=$extensions->extension_params_current_website) {
                    $table_extension->load($extensions->extension_id_current_website);
                    $extension_params_supper_admin = new JRegistry;
                    $extension_params_supper_admin->loadString($extensions->extension_params_supper_admin);
                    $extension_params_current_website = new JRegistry;
                    $extension_params_current_website->loadString($extensions->extension_params_current_website);
                    $extension_params_current_website->merge($extension_params_supper_admin);
                    $table_extension->params = $extension_params_current_website->toString();
                }

            }else{
                $table_extension->load($extensions->extension_id_supper_admin);
                $table_extension->id=0;
                $table_extension->supper_admin_extension_id=$extensions->extension_id_supper_admin;
                $table_extension->website_id=$website_id;
            }
            $ok = $table_extension->store();
            if (!$ok) {
                throw new Exception($table_extension->getError());
            }
        }
    }
    private function getListStep()
    {
        $steps = array();
        $steps[] = 'formBase';
        $steps[] = 'copy_BasicInfoWebsite';
        $steps[] = 'insertDomainToWebsite';
        $steps[] = 'copy_Configuration';
        $steps[] = 'copy_GroupUser';
        $steps[] = 'copy_ViewAccessLevels';
        $steps[] = 'copy_SupperAdmin';
        $steps[] = 'copy_extensions';
        $steps[] = 'copy_components';
        $steps[] = 'copy_modules';
        $steps[] = 'copy_plugins';
        $steps[] = 'copy_Styles';
        $steps[] = 'copy_menu_type';
        $steps[] = 'copy_menus';
        $steps[] = 'copy_blocks';
        $steps[] = 'config_blocks_menu';
        $steps[] = 'config_module';
        $steps[] = 'copy_Control';
        $steps[] = 'changeParams';
        $steps[] = 'copy_ContentCategory';
        $steps[] = 'finish';
        return $steps;
    }
    public static function remove_duplicate_row(){
        $table_name='#__extensions';
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->clear()
            ->select('*')
            ->from($table_name)
        ;
        $list_row = $db->setQuery($query)->loadObjectList();
        $list_unique=array();
        $list_delete=array();
        foreach($list_row as $item)
        {
            $key="{$item->type}-{$item->website_id}-{$item->element}-{$item->folder}";
            if(!in_array($key,$list_unique))
            {
                $list_unique[$item->id]=$key;
            }else{
                array_push($list_delete,$item->id);
            }
        }
        $query->clear();
        $query->delete($table_name)
            ->where('id IN ('.implode(',',$list_delete).')');
        echo $query->dump();
        echo "<pre>";
        print_r($list_unique);
        echo "</pre>";
        die;
        die;
        $db->setQuery($query)->execute();

        die;
    }



}