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
        //delete all extension supper admin
        $query = $db->getQuery(true);
        $query->delete_all('#__components AS components','components.*')
            ->where('components.supper_admin_component_id IS NOT NULL')
            ->leftJoin('#__extensions AS extensions ON extensions.id=components.extension_id')
            ->where('extensions.website_id='.(int)$website_id)
        ;
        $db->setQuery($query);
        $ok = $db->execute();
        if (!$ok) {
            throw new Exception($db->getErrorMsg());
        }

        $query = $db->getQuery(true);
        $query->clear()
            ->select('
                    component_supper_admin.id AS component_supper_admin_component_id,
                    component_supper_admin.name AS component_supper_admin_component_name,
                    extension_supper_admin.id AS supper_admin_extension_id,
                    current_website_extension.id AS current_website_extension_extension_id,
                    component_of_current_website.id as component_current_website_component_id,
                    component_of_current_website.name AS component_of_current_website_component_name
                ')
            ->from('#__components AS component_supper_admin')
            ->leftJoin('#__extensions AS extension_supper_admin ON extension_supper_admin.id=component_supper_admin.extension_id')
            ->where('extension_supper_admin.website_id=' . (int)$template_supper_admin_website_id)
            ->where('extension_supper_admin.type=' . $query->q('component'))
            ->leftJoin('#__components AS component_of_current_website ON component_of_current_website.name=component_supper_admin.name
             AND component_of_current_website.extension_id IN(
                    SELECT id FROM #__extensions WHERE website_id='.(int)$website_id.'
            )')
            ->innerJoin('#__extensions AS current_website_extension ON current_website_extension.type=extension_supper_admin.type
                        AND current_website_extension.element=extension_supper_admin.element AND current_website_extension.folder=extension_supper_admin.folder
                        AND current_website_extension.website_id='.(int)$website_id)
            ->where('component_of_current_website.name IS NULL')
            ->where('current_website_extension.id IS NOT NULL')
            ->group('component_supper_admin.name')
        ;
        $list_components = $db->setQuery($query)->loadObjectList();
        $table_component=JTable::getInstance('component');


        foreach ($list_components AS $component) {

            $table_component->load($component->component_supper_admin_component_id);
            $table_component->id=0;
            $table_component->extension_id=$component->current_website_extension_extension_id;
            $table_component->copy_from=$component->component_supper_admin_component_id;
            $table_component->supper_admin_component_id=$component->component_supper_admin_component_id;
            $ok = $table_component->store();
            if (!$ok) {
                throw new Exception($table_component->getError());
            }
        }
        return true;

    }
    private function copy_plugins($website_id,$template_supper_admin_website_id){
        $db = JFactory::getDbo();
        //delete all extension supper admin
        $query = $db->getQuery(true);
        $query->delete_all('#__plugins AS plugins','plugins.*')
            ->where('plugins.supper_admin_plugin_id IS NOT NULL')
            ->leftJoin('#__extensions AS extensions ON extensions.id=plugins.extension_id')
            ->where('extensions.website_id='.(int)$website_id)
        ;
        $db->setQuery($query);
        $ok = $db->execute();
        if (!$ok) {
            throw new Exception($db->getErrorMsg());
        }

        $query = $db->getQuery(true);
        $query->clear()
            ->select('
                    plugin_supper_admin.id AS plugin_supper_admin_plugin_id,
                    plugin_supper_admin.name AS plugin_supper_admin_plugin_name,
                    extension_supper_admin.id AS supper_admin_extension_id,
                    current_website_extension.id AS current_website_extension_extension_id,
                    plugin_of_current_website.id as plugin_current_website_plugin_id,
                    plugin_of_current_website.name AS plugin_of_current_website_plugin_name
                ')
            ->from('#__plugins AS plugin_supper_admin')
            ->leftJoin('#__extensions AS extension_supper_admin ON extension_supper_admin.id=plugin_supper_admin.extension_id')
            ->where('extension_supper_admin.website_id=' . (int)$template_supper_admin_website_id)
            ->where('extension_supper_admin.type=' . $query->q('plugin'))
            ->leftJoin('#__plugins AS plugin_of_current_website ON plugin_of_current_website.name=plugin_supper_admin.name AND plugin_of_current_website.element=plugin_supper_admin.element AND plugin_of_current_website.folder=plugin_supper_admin.folder
             AND plugin_of_current_website.extension_id IN(
                    SELECT id FROM #__extensions WHERE website_id='.(int)$website_id.'
            )')
            ->innerJoin('#__extensions AS current_website_extension ON current_website_extension.type=extension_supper_admin.type
                        AND current_website_extension.element=extension_supper_admin.element AND current_website_extension.folder=extension_supper_admin.folder
                        AND current_website_extension.website_id='.(int)$website_id)
            ->where('plugin_of_current_website.name IS NULL')
            ->where('current_website_extension.id IS NOT NULL')
            ->order('plugin_supper_admin.folder,plugin_supper_admin.element,plugin_supper_admin.name')
        ;
        $list_plugins = $db->setQuery($query)->loadObjectList();
        $table_plugin=JTable::getInstance('plugin');


        foreach ($list_plugins AS $plugin) {

            $table_plugin->load($plugin->plugin_supper_admin_plugin_id);
            $table_plugin->id=0;
            $table_plugin->extension_id=$plugin->current_website_extension_extension_id;
            $table_plugin->copy_from=$plugin->plugin_supper_admin_plugin_id;
            $table_plugin->supper_admin_plugin_id=$plugin->plugin_supper_admin_plugin_id;
            $ok = $table_plugin->store();
            if (!$ok) {
                throw new Exception($table_plugin->getError());
            }
        }
        return true;

    }
    private function copy_Styles($website_id,$template_supper_admin_website_id){
        return true;

    }
    private function copy_menu_type($website_id,$template_supper_admin_website_id){
        //copy menu type
        $db = JFactory::getDbo();

        $query = $db->getQuery(true);
        $query->delete_all('#__menu AS menu','menu.*')
            ->where('menu.supper_admin_menu_item_id IS NOT NULL')
            ->leftJoin('#__menu_type_id_menu_id AS menu_type_id_menu_id ON menu_type_id_menu_id.menu_id=menu.id')
            ->leftJoin('#__menu_types AS menu_types ON menu_types.id=menu_type_id_menu_id.menu_type_id')
            ->where('menu_types.website_id='.(int)$website_id)
        ;
        $db->setQuery($query);
        $ok = $db->execute();
        if (!$ok) {
            throw new Exception($db->getErrorMsg());
        }




        //delete all extension supper admin
        $query = $db->getQuery(true);
        $query->delete('ueb3c_menu_types')
            ->where('supper_admin_menu_type_id IS NOT NULL')
            ->where('website_id='.(int)$website_id)
        ;
        $db->setQuery($query);
        $ok = $db->execute();
        if (!$ok) {
            throw new Exception($db->getErrorMsg());
        }


        $query=$db->getQuery(true);
        //get template menu type
        $query->clear()
            ->select('menu_types.id ')
            ->from('#__menu_types AS menu_types')
            ->where('menu_types.website_id=' . (int)$template_supper_admin_website_id)
        ;
        $list_menu_type_id = $db->setQuery($query)->loadColumn();
        require_once JPATH_ROOT . '/libraries/legacy/table/menu/type.php';
        $table_menu_type = JTable::getInstance('menutype', 'JTable');
        foreach ($list_menu_type_id AS $menu_type_id) {
            $table_menu_type->load($menu_type_id);
            $table_menu_type->id = 0;
            $table_menu_type->supper_admin_menu_type_id = $menu_type_id;
            $table_menu_type->copy_from = $menu_type_id;
            $table_menu_type->website_id = $website_id;

            $ok = $table_menu_type->store();
            if (!$ok) {
                throw new Exception($table_menu_type->getError());
            }
        }
        //end copy menu type
        return true;

    }
    static $list_menu_item=null;
    private function copy_menus($website_id,$template_supper_admin_website_id){

        $db = JFactory::getDbo();


        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        //get template menu type
        $query->clear()
            ->select('menu_type.*')
            ->from('#__menu_types AS menu_type')
            ->where('menu_type.website_id=' . (int)$website_id)
            ->where('menu_type.supper_admin_menu_type_id IS NOT NULL')
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
            $table_menu->menu_type_id = $menu_type->id;
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
            $table_menu_item_menu_type->menu_type_id = $menu_type->id;
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
            ->select('current_website.id AS current_website_menu_item_id,menu_type_id_menu_id2.menu_id AS supper_admin_website_menu_item_id')
            ->from('#__menu AS current_website')
            ->innerJoin('#__menu_type_id_menu_id AS menu_type_id_menu_id ON menu_type_id_menu_id.menu_id=current_website.id')
            ->innerJoin('#__menu_types AS current_website_menu_type ON current_website_menu_type.id=menu_type_id_menu_id.menu_type_id')
            ->where('current_website_menu_type.supper_admin_menu_type_id IS NOT NULL')
            ->where('current_website_menu_type.website_id ='.(int)$website_id)
            ->innerJoin('#__menu_types AS supper_admin_menu_types ON supper_admin_menu_types.id=current_website_menu_type.supper_admin_menu_type_id')
            ->leftJoin('#__menu_type_id_menu_id AS menu_type_id_menu_id2 ON menu_type_id_menu_id2.menu_type_id=supper_admin_menu_types.id')
        ;
        $db->setQuery($query);
        $list_current_website_root_menu_item=$db->loadObjectList();
        $query->clear()
            ->select('menu.id,menu.parent_id')
            ->from('#__menu AS menu')
        ;
        $list_menu_item=$db->setQuery($query)->loadObjectList();

        $children_menu_item = array();
        foreach ($list_menu_item as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_menu_item[$pt] ? $children_menu_item[$pt] : array();
            array_push($list, $v);
            $children_menu_item[$pt] = $list;
        }
        $list_root_menu_item = $children_menu_item['list_root'];
        unset($children_menu_item['list_root']);
        $db->rebuild_action=1;
        require_once JPATH_ROOT . '/components/com_menus/helpers/menus.php';
        foreach ($list_current_website_root_menu_item AS $menu_item) {

            $supper_admin_menu_item_id=$menu_item->supper_admin_website_menu_item_id;
            $current_website_menu_item_id=$menu_item->current_website_menu_item_id;
            $update_supper_menu_item=function($function_call_back,$supper_admin_parent_menu_item_id=0, $current_website_parent_menu_item_id,$children_menu_item, $level=0, $max_level=999){
                foreach($children_menu_item[$supper_admin_parent_menu_item_id] as $menu_item)
                {
                    $table_menu = JTable::getInstance('menu');
                    $table_menu->load($menu_item->id);
                    $table_menu->id=0;
                    $home = $table_menu->home;
                    if ($home) {
                        $table_menu->is_dashboard_menu_supper_admin = 1;
                    }
                    $table_menu->home = 0;
                    $table_menu->parent_id = $current_website_parent_menu_item_id;
                    $table_menu->supper_admin_menu_item_id = $menu_item->id;
                    $table_menu->copy_from= $menu_item->id;
                    $ok = $table_menu->parent_store();
                    if (!$ok) {
                        throw new Exception($table_menu->getError());
                    }

                    $supper_admin_parent_menu_item_id1 = $menu_item->id;
                    $current_website_parent_menu_item_id1 = $table_menu->id;
                    $function_call_back($function_call_back,$supper_admin_parent_menu_item_id1, $current_website_parent_menu_item_id1,$children_menu_item);
                }
            };
            $update_supper_menu_item($update_supper_menu_item,$supper_admin_menu_item_id,$current_website_menu_item_id,$children_menu_item);
        }
        return true;

    }
    static $list_position=null;
    private function copy_blocks($website_id,$template_supper_admin_website_id){

        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->delete_all('#__position_config AS position_config','position_config.*')
            ->leftJoin('#__root_position_id_website_id AS root_position_id_website_id ON root_position_id_website_id.position_id=position_config.id')
            ->where('root_position_id_website_id.website_id='.(int)$website_id)
            ->where('position_config.supper_admin_block_id IS NOT NULL')
            ;
        $db->setQuery($query);
        $ok=$db->execute();
        if(!$ok)
        {
            throw new Exception($db->getErrorMsg());
        }


        $query->clear()
            ->select('root_position_id_website_id.position_id as current_website_root_position_id,root_position_id_website_id2.position_id AS supper_admin_website_root_position_id')
            ->from('#__root_position_id_website_id AS root_position_id_website_id')
            ->where('root_position_id_website_id.website_id='.(int)$website_id)
            ->innerJoin('#__root_position_id_website_id AS root_position_id_website_id2 ON root_position_id_website_id2.website_id='.(int)$template_supper_admin_website_id)

        ;
        $db->setQuery($query);
        $root_position=$db->loadObject();
        $query->clear()
            ->select('menu.id,menu.parent_id,menu.supper_admin_menu_item_id')
            ->from('#__menu AS menu')
        ;
        $list_menu_item=$db->setQuery($query)->loadObjectList('id');

        $children_menu_item = array();
        foreach ($list_menu_item as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_menu_item[$pt] ? $children_menu_item[$pt] : array();
            array_push($list, $v);
            $children_menu_item[$pt] = $list;
        }



        $query = $db->getQuery(true);
        //get template menu type
        $query->clear()
            ->select('current_website.id AS current_website_menu_item_id')
            ->from('#__menu AS current_website')
            ->innerJoin('#__menu_type_id_menu_id AS menu_type_id_menu_id ON menu_type_id_menu_id.menu_id=current_website.id')
            ->innerJoin('#__menu_types AS current_website_menu_type ON current_website_menu_type.id=menu_type_id_menu_id.menu_type_id')
            ->where('current_website_menu_type.supper_admin_menu_type_id IS NOT NULL')
            ->where('current_website_menu_type.website_id ='.(int)$website_id)
        ;
        $db->setQuery($query);
        $list_current_website_root_menu_item_id=$db->loadColumn();

        $list_menu_item_of_website=array();
        foreach($list_current_website_root_menu_item_id as $root_menu_item_id)
        {
            $menu_item=$list_menu_item[$root_menu_item_id];
            if($menu_item->supper_admin_menu_item_id)
            {
                $list_menu_item_of_website[$menu_item->supper_admin_menu_item_id]=$menu_item;
            }
            $get_list_menu_item_of_website=function($function_call_back,&$list_menu_item_of_website,$root_menu_item,$children_menu_item,$level=0, $max_level=999){
                foreach($children_menu_item[$root_menu_item] as $sub_menu_item)
                {
                    if($sub_menu_item->supper_admin_menu_item_id)
                    {
                        $list_menu_item_of_website[$sub_menu_item->supper_admin_menu_item_id]=$sub_menu_item;
                    }
                    $function_call_back($function_call_back,$list_menu_item_of_website,$children_menu_item);

                }
            };
            $get_list_menu_item_of_website($get_list_menu_item_of_website,$list_menu_item_of_website,$root_menu_item_id,$children_menu_item);
        }
        $query->clear()
            ->select('position_config.id,position_config.parent_id')
            ->from('#__position_config AS position_config')
        ;
        $list_position_config=$db->setQuery($query)->loadObjectList();

        $children_position_config = array();
        foreach ($list_position_config as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_position_config[$pt] ? $children_position_config[$pt] : array();
            array_push($list, $v);
            $children_position_config[$pt] = $list;
        }

        $insert_position_current_website=function($function_call_back, $website_id, $list_menu_item_of_website, $root_supper_admin_website_position_id=0, $root_current_website_position_id, $children_position_config, $level=0, $max_level=999){
            $table_position=JTable::getInstance('positionnested');
            foreach($children_position_config[$root_supper_admin_website_position_id] as $position)
            {
                $table_position->load($position->id);
                $table_position->id = 0;
                $table_position->parent_id = $root_current_website_position_id;
                $table_position->website_id = $website_id;

                $table_position->supper_admin_block_id = $position->id;
                $table_position->copy_from = $position->id;
                $menu_item_id=$table_position->menu_item_id;
                $table_position->menu_item_id = $list_menu_item_of_website[$menu_item_id]->id;
                $ok = $table_position->parent_store();
                if (!$ok) {
                    throw new Exception($table_position->getError());
                }
                $root_supper_admin_website_position_id1 = $position->id;
                $root_current_website_position_id1 = $table_position->id;
                $function_call_back($function_call_back,$website_id, $list_menu_item_of_website, $root_supper_admin_website_position_id1, $root_current_website_position_id1,$children_position_config);
            }
        };
        $insert_position_current_website($insert_position_current_website,$website_id,$list_menu_item_of_website,$root_position->supper_admin_website_root_position_id,$root_position->current_website_root_position_id,$children_position_config);
        return true;
    }
    private function config_blocks_menu($website_id,$template_supper_admin_website_id){
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->delete_all('#__menu_item_id_position_id_ordering AS menu_item_id_position_id_ordering','menu_item_id_position_id_ordering.*')
            ->innerJoin('#__menu AS menu ON menu.id=menu_item_id_position_id_ordering.menu_item_id')
            ->where('menu.supper_admin_menu_item_id IS NOT NULL')
            ->innerJoin('#__position_config AS position_config ON position_config.id=menu_item_id_position_id_ordering.position_id')
            ->where('position_config.supper_admin_block_id IS NOT NULL')
            ;
        $db->setQuery($query);
        $ok=$db->execute();
        if(!$ok)
        {
            throw new Exception($db->getErrorMsg());
        }



        $query=$db->getQuery(true);
        $query->clear()
            ->select('position_config.id,position_config.parent_id,position_config.supper_admin_block_id')
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
        //get root position of current website
        $query=$db->getQuery(true);
        $query->clear()
            ->select('root_position_id_website_id.position_id')
            ->from('#__root_position_id_website_id AS root_position_id_website_id')
            ->where('root_position_id_website_id.website_id='.(int)$website_id)
        ;
        $db->setQuery($query);
        $position_id = $db->loadResult();
        $list_position_of_website=array();
        $get_position_of_website=function($function_call_back,$root_position_id=0,&$list_position_of_website,$list_position=array(),$level=0, $max_level=999){
            if ($list_position[$root_position_id]) {
                $level1=$level+1;
                foreach ($list_position[$root_position_id] as $v) {
                    if($v->supper_admin_block_id) {
                        $list_position_of_website[$v->supper_admin_block_id] = $v;
                        $root_position_id1 = $v->id;
                        $function_call_back($function_call_back, $root_position_id1, $list_position_of_website, $list_position, $level1, $max_level);
                    }
                }
            }
        };
        $get_position_of_website($get_position_of_website,$position_id,$list_position_of_website,$children_position);




        $query=$db->getQuery(true);
        $query->clear()
            ->select('menu.id,menu.parent_id,menu.supper_admin_menu_item_id')
            ->from('#__menu AS menu');
        $db->setQuery($query);
        $list_menu_item = $db->loadObjectList();
        $children_menu_item = array();
        foreach ($list_menu_item as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_menu_item[$pt] ? $children_menu_item[$pt] : array();
            array_push($list, $v);
            $children_menu_item[$pt] = $list;
        }
        $list_root_menu_item = $children_menu_item['list_root'];
        unset($children_menu_item['list_root']);
        //get root position of current website
        $query=$db->getQuery(true);
        $query->clear()
            ->select('menu_type_id_menu_id.menu_id')
            ->from('#__menu_type_id_menu_id AS menu_type_id_menu_id')
            ->innerJoin('#__menu_types AS menu_types ON menu_types.id=menu_type_id_menu_id.menu_type_id')
            ->where('menu_types.website_id='.(int)$website_id)
        ;
        $db->setQuery($query);
        $list_root_menu_item_id = $db->loadColumn();

        $get_menu_item_of_website=function($function_call_back, $menu_item_id=0, &$list_menu_item_of_website, $children_menu_item=array(), $level=0, $max_level=999){
            if ($children_menu_item[$menu_item_id]) {
                $level1=$level+1;
                foreach ($children_menu_item[$menu_item_id] as $menu_item) {
                    if($menu_item->supper_admin_menu_item_id) {
                        $list_menu_item_of_website[$menu_item->supper_admin_menu_item_id] = $menu_item;
                        $menu_item_id_1 = $menu_item->id;
                        $function_call_back($function_call_back, $menu_item_id_1, $list_menu_item_of_website, $children_menu_item, $level1, $max_level);
                    }
                }
            }
        };
        $list_menu_item_of_website=array();
        foreach($list_root_menu_item_id AS $root_menu_item_id)
        {
            $get_menu_item_of_website($get_menu_item_of_website,$root_menu_item_id,$list_menu_item_of_website,$children_menu_item);
        }

        $query->clear()
            ->select('menu_item_id_position_id_ordering.*')
            ->from('#__menu_item_id_position_id_ordering AS menu_item_id_position_id_ordering')
            ->where('menu_item_id_position_id_ordering.website_id='.(int)$template_supper_admin_website_id)
        ;
        $db->setQuery($query);
        $list_menu_item_id_position_id_ordering = $db->loadObjectList();
        $table_menu_item_id_position_id_ordering = JTable::getInstance('menu_item_id_position_id_ordering');
        foreach($list_menu_item_id_position_id_ordering AS $item)
        {
            $table_menu_item_id_position_id_ordering->id=0;
            $table_menu_item_id_position_id_ordering->menu_item_id=$list_menu_item_of_website[$item->menu_item_id]->id;
            $table_menu_item_id_position_id_ordering->position_id=$list_position_of_website[$item->position_id]->id;
            $table_menu_item_id_position_id_ordering->website_id=$website_id;
            $table_menu_item_id_position_id_ordering->ordering=$item->ordering;
            $ok = $table_menu_item_id_position_id_ordering->store();
            if (!$ok) {
                throw new Exception($table_menu_item_id_position_id_ordering->getError());
            }

        }
        return true;

    }
    private function config_modules($website_id,$template_supper_admin_website_id){
        $db=JFactory::getDbo();



        $query=$db->getQuery(true);
        $query->clear()
            ->select('modules.id,modules.supper_admin_module_id')
            ->from('#__modules AS modules')
            ->innerJoin('#__extensions AS extension ON extension.id=modules.extension_id')
            ->where('extension.website_id='.(int)$website_id)
            ->where('modules.supper_admin_module_id IS NOT NULL')
        ;
        $db->setQuery($query);
        $list_module_of_current_website = $db->loadObjectList('supper_admin_module_id');

        $query=$db->getQuery(true);
        $query->clear()
            ->select('menu.id,menu.parent_id,menu.supper_admin_menu_item_id')
            ->from('#__menu AS menu');
        $db->setQuery($query);
        $list_menu_item = $db->loadObjectList();
        $children_menu_item = array();
        foreach ($list_menu_item as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_menu_item[$pt] ? $children_menu_item[$pt] : array();
            array_push($list, $v);
            $children_menu_item[$pt] = $list;
        }
        $list_root_menu_item = $children_menu_item['list_root'];
        unset($children_menu_item['list_root']);
        //get root position of current website
        $query=$db->getQuery(true);
        $query->clear()
            ->select('menu_type_id_menu_id.menu_id')
            ->from('#__menu_type_id_menu_id AS menu_type_id_menu_id')
            ->innerJoin('#__menu_types AS menu_types ON menu_types.id=menu_type_id_menu_id.menu_type_id')
            ->where('menu_types.website_id='.(int)$website_id)
        ;
        $db->setQuery($query);
        $list_root_menu_item_id = $db->loadColumn();

        $get_menu_item_of_website=function($function_call_back, $menu_item_id=0, &$list_menu_item_of_website, $children_menu_item=array(), $level=0, $max_level=999){
            if ($children_menu_item[$menu_item_id]) {
                $level1=$level+1;
                foreach ($children_menu_item[$menu_item_id] as $menu_item) {
                    if($menu_item->supper_admin_menu_item_id) {
                        $list_menu_item_of_website[$menu_item->supper_admin_menu_item_id] = $menu_item;
                        $menu_item_id_1 = $menu_item->id;
                        $function_call_back($function_call_back, $menu_item_id_1, $list_menu_item_of_website, $children_menu_item, $level1, $max_level);
                    }
                }
            }
        };
        $list_menu_item_of_website=array();
        foreach($list_root_menu_item_id AS $root_menu_item_id)
        {
            $get_menu_item_of_website($get_menu_item_of_website,$root_menu_item_id,$list_menu_item_of_website,$children_menu_item);
        }

        $query->clear()
            ->select('modules_menu.*')
            ->from('#__modules_menu AS modules_menu')
            ->innerJoin('#__modules AS modules ON modules.id=modules_menu.moduleid')
            ->innerJoin('#__extensions AS extension ON extension.id=modules.extension_id')
            ->where('extension.website_id='.(int)$template_supper_admin_website_id)
        ;

        $db->setQuery($query);
        $list_modules_menu = $db->loadObjectList();
        $table_module_menu = JTable::getInstance('modulemenu');
        foreach($list_module_of_current_website AS $item)
        {
            $table_module_menu->id=0;
            $table_module_menu->moduleid=$list_module_of_current_website[$item->moduleid]->id;
            if($item->menuid)
            {
                $table_module_menu->menuid=$list_menu_item_of_website[$item->menuid]->id;
            }
            $ok = $table_module_menu->store();
            if (!$ok) {
                throw new Exception($table_module_menu->getError());
            }

        }
        return true;

    }
    private function change_params_menus($website_id,$template_supper_admin_website_id){
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->clear()
            ->select('menu.id,menu.parent_id,menu.supper_admin_menu_item_id')
            ->from('#__menu AS menu');
        $db->setQuery($query);
        $list_menu_item = $db->loadObjectList();
        $children_menu_item = array();
        foreach ($list_menu_item as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_menu_item[$pt] ? $children_menu_item[$pt] : array();
            array_push($list, $v);
            $children_menu_item[$pt] = $list;
        }
        $list_root_menu_item = $children_menu_item['list_root'];
        unset($children_menu_item['list_root']);
        //get root position of current website
        $query=$db->getQuery(true);
        $query->clear()
            ->select('menu_type_id_menu_id.menu_id')
            ->from('#__menu_type_id_menu_id AS menu_type_id_menu_id')
            ->innerJoin('#__menu_types AS menu_types ON menu_types.id=menu_type_id_menu_id.menu_type_id')
            ->where('menu_types.website_id='.(int)$website_id)
        ;
        $db->setQuery($query);
        $list_root_menu_item_id = $db->loadColumn();

        $get_menu_item_of_website=function($function_call_back, $menu_item_id=0, &$list_menu_item_of_website, $children_menu_item=array(), $level=0, $max_level=999){
            if ($children_menu_item[$menu_item_id]) {
                $level1=$level+1;
                foreach ($children_menu_item[$menu_item_id] as $menu_item) {
                    if($menu_item->supper_admin_menu_item_id) {
                        $list_menu_item_of_website[$menu_item->supper_admin_menu_item_id] = $menu_item;
                        $menu_item_id_1 = $menu_item->id;
                        $function_call_back($function_call_back, $menu_item_id_1, $list_menu_item_of_website, $children_menu_item, $level1, $max_level);
                    }
                }
            }
        };
        $list_menu_item_of_website=array();
        foreach($list_root_menu_item_id AS $root_menu_item_id)
        {
            $get_menu_item_of_website($get_menu_item_of_website,$root_menu_item_id,$list_menu_item_of_website,$children_menu_item);
        }
        $table_menu = JTable::getInstance('menu');
        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_menus/models');
        $menu_model = JModelLegacy::getInstance('item','MenusModel');
        JForm::addFormPath(JPATH_ROOT.'/components/com_menus/models/forms');
        foreach($list_menu_item_of_website AS $menu_item)
        {
            $item= $menu_model->getItem($menu_item->id);
            $params = new JRegistry;
            $params->loadString($item->params);
            $form=$menu_model->getForm((array)$item);
            $field_sets= $form->getFieldset();
            foreach($field_sets as $field)
            {
                $function_name='get_new_value_by_old_value';
                if(method_exists ( $field ,$function_name))
                {
                    $field_name=$field->__get('fieldname');

                    $item->$field_name= call_user_func(array($field, $function_name), $website_id,$item->$field_name,$field_name);
                }
            }
            echo "<pre>";
            print_r($item);
            echo "</pre>";
            die;
            $params=$table_menu->params;
            if($params)
            {
                $params=JMenuSite::change_param_module_by_fields($website_id,$params);
            }
            $table_menu->params=$params;
            $ok=$table_menu->store();
            if (!$ok) {
                throw new Exception($table_menu->getError());
            }
        }
        echo "<pre>";
        print_r($list_menu_item_of_website);
        echo "</pre>";
        die;
    }
    private function change_params_modules($website_id,$template_supper_admin_website_id){
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->clear()
            ->select('modules.id,modules.supper_admin_module_id,modules.params')
            ->from('#__modules AS modules')
            ->innerJoin('#__extensions AS extension ON extension.id=modules.extension_id')
            ->where('extension.website_id='.(int)$website_id)
            ->where('modules.supper_admin_module_id IS NOT NULL')
        ;
        $db->setQuery($query);
        $list_module_of_current_website = $db->loadObjectList();




        $query=$db->getQuery(true);
        $query->clear()
            ->select('position_config.id,position_config.parent_id,position_config.supper_admin_block_id')
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
        //get root position of current website
        $query=$db->getQuery(true);
        $query->clear()
            ->select('root_position_id_website_id.position_id')
            ->from('#__root_position_id_website_id AS root_position_id_website_id')
            ->where('root_position_id_website_id.website_id='.(int)$website_id)
        ;
        $db->setQuery($query);
        $position_id = $db->loadResult();
        $list_position_of_website=array();
        $get_position_of_website=function($function_call_back,$root_position_id=0,&$list_position_of_website,$list_position=array(),$level=0, $max_level=999){
            if ($list_position[$root_position_id]) {
                $level1=$level+1;
                foreach ($list_position[$root_position_id] as $v) {
                    if($v->supper_admin_block_id) {
                        $list_position_of_website[$v->supper_admin_block_id] = $v;
                        $root_position_id1 = $v->id;
                        $function_call_back($function_call_back, $root_position_id1, $list_position_of_website, $list_position, $level1, $max_level);
                    }
                }
            }
        };
        $get_position_of_website($get_position_of_website,$position_id,$list_position_of_website,$children_position);






        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_modules/models');
        $module_model = JModelLegacy::getInstance('module','ModulesModel');
        JForm::addFormPath(JPATH_ROOT.'/components/com_modules/models/forms');

        foreach($list_module_of_current_website AS $module)
        {
            $module_model->setState('module.id',$module->id);
            $item=$module_model->getItem($module->id);
            $form=$module_model->getForm((array)$item);
            echo "<pre>";
            print_r($form);
            echo "</pre>";
            die;

            $control=JControlHelper::get_control_module_by_module_id($module->id);
            if($control)
            {
                JModuleHelper::change_param_module_by_fields($website_id,$form,$control->fields);
            }
            die;

            $field_sets= $form->getFieldset();
            foreach($field_sets as $field)
            {
                $function_name='get_new_value_by_old_value';
                if(method_exists ( $field ,$function_name))
                {
                    $field_name=$field->__get('fieldname');

                    $item->$field_name= call_user_func(array($field, $function_name), $website_id,$item->$field_name,$field_name);
                }
            }



            $params=$module->params;
            $control=JControlHelper::get_control_module_by_module_id($module->id);
            if($control)
            {
                $params=JModuleHelper::change_param_module_by_fields($website_id,$params,$control->fields);
            }
            $table_module->load($module->id);
            $position_id=$table_module->position_id;
            $position_id=$list_position_of_website[$position_id]->id;
            $table_module->position_id=$position_id;
            $position="position-$position_id";
            $table_module->position=$position;
            $table_module->params=$params;
            $ok=$table_module->store();
            if (!$ok) {
                throw new Exception($table_module->getError());
            }
        }
        //update params blocks
        //update params component
        //update params plugins

    }
    private function copy_modules($website_id,$template_supper_admin_website_id){
        $db = JFactory::getDbo();
        //delete all extension supper admin
        $query = $db->getQuery(true);
        $query->delete_all('#__modules AS modules','modules.*')
            ->where('modules.supper_admin_module_id IS NOT NULL')
            ->leftJoin('#__extensions AS extensions ON extensions.id=modules.extension_id')
            ->where('extensions.website_id='.(int)$website_id)
        ;
        $db->setQuery($query);
        $ok = $db->execute();
        if (!$ok) {
            throw new Exception($db->getErrorMsg());
        }

        $query = $db->getQuery(true);
        $query->clear()
            ->select('
                    module_supper_admin.id AS module_supper_admin_module_id,
                    module_supper_admin.module AS module_supper_admin_module_name,
                    extension_supper_admin.id AS supper_admin_extension_id,
                    current_website_extension.id AS current_website_extension_extension_id
                ')
            ->from('#__modules AS module_supper_admin')
            ->leftJoin('#__extensions AS extension_supper_admin ON extension_supper_admin.id=module_supper_admin.extension_id')
            ->where('extension_supper_admin.website_id=' . (int)$template_supper_admin_website_id)
            ->where('extension_supper_admin.type=' . $query->q('module'))

            ->innerJoin('#__extensions AS current_website_extension ON current_website_extension.type=extension_supper_admin.type
                        AND current_website_extension.element=extension_supper_admin.element AND current_website_extension.folder=extension_supper_admin.folder
                        AND current_website_extension.website_id='.(int)$website_id)
            ->where('current_website_extension.id IS NOT NULL')
            ->group('module_supper_admin.module')
        ;
        $list_modules = $db->setQuery($query)->loadObjectList();
        $table_module=JTable::getInstance('module');


        foreach ($list_modules AS $module) {

            $table_module->load($module->module_supper_admin_module_id);
            $table_module->id=0;
            $table_module->extension_id=$module->current_website_extension_extension_id;
            $table_module->asset_id=null;
            $table_module->copy_from=$module->module_supper_admin_module_id;
            $table_module->supper_admin_module_id=$module->module_supper_admin_module_id;
            $ok = $table_module->store(true);
            if (!$ok) {
                throw new Exception($table_module->getError());
            }
        }
        return true;


    }
    private function copy_controls($website_id,$template_supper_admin_website_id){
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->delete('#__control')
            ->where('website_id='.(int)$website_id)
            ->where('supper_admin_control_id IS NOT NULL')
            ;
        $db->setQuery($query);
        $ok=$db->execute();
        if(!$ok)
        {
            throw new Exception($db->getErrorMsg());
        }
        $query = $db->getQuery(true);
        $query->clear()
            ->select('control.*')
            ->from('#__control AS control')
            ->where('control.website_id=' . (int)$template_supper_admin_website_id);
        $list_control = $db->setQuery($query)->loadObjectList();
        $website_name=websiteHelperFrontEnd::get_website_name_by_website_id($website_id);
        $website_name_template=websiteHelperFrontEnd::get_website_name_by_website_id($template_supper_admin_website_id);
        $table_control=JTable::getInstance('control');
        foreach ($list_control AS $control) {
            $table_control->bind($control);
            $table_control->id=0;
            $element_path=$control->element_path;
            $type=$table_control->type;
            if($type=='module')
            {
                $element_path=str_replace("website_$website_name_template","website_$website_name",$element_path);
            }
            $table_control->element_path= $element_path;
            $table_control->copy_from= $control->id;
            $table_control->supper_admin_control_id= $control->id;
            $table_control->website_id=$website_id;
            $ok = $table_control->store();
            if (!$ok) {
                throw new Exception($table_control->getError());
            }
        }
        return true;


    }
    private function copy_extensions($website_id,$template_supper_admin_website_id){

        $db = JFactory::getDbo();
        //delete all extension supper admin
        $query = $db->getQuery(true);
        $query->delete('#__extensions')
            ->where('supper_admin_extension_id IS NOT NULL')
            ->where('website_id='.(int)$website_id)
            ;
        $db->setQuery($query);
        $ok = $db->execute();
        if (!$ok) {
            throw new Exception($db->getErrorMsg());
        }
        $query = $db->getQuery(true);
        $query->clear()
            ->select('extension.type,extension.id,extension.name,extension.element,extension.folder,extension2.id AS extension_id')
            ->from('#__extensions AS extension')
            ->where('extension.website_id=' . (int)$template_supper_admin_website_id)
            ->leftJoin('#__extensions AS extension2 ON extension2.type=extension.type AND extension2.element=extension.element AND extension2.folder=extension.folder AND extension2.website_id='.(int)$website_id)
            ->where('(extension2.type IS NULL AND extension2.element IS NULL AND extension2.folder IS NULL)')
            ->group("extension.type,extension.element,extension.folder")
        ;
        $list_extension = $db->setQuery($query)->loadObjectList();
        $table_extension=JTable::getInstance('extension');

        foreach ($list_extension AS $extension) {
            $table_extension->load($extension->id);
            $table_extension->id=0;
            $table_extension->website_id=$website_id;
            $table_extension->supper_admin_extension_id=$extension->id;
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
        $steps[] = 'config_modules';
        $steps[] = 'copy_controls';
        $steps[] = 'change_params_modules';
        $steps[] = 'change_params_menus';
        $steps[] = 'change_params_components';
        $steps[] = 'change_params_plugins';
        $steps[] = 'change_params_blocks';
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