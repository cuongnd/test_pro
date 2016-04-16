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

        return true;

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
        $steps[] = 'createStyles';
        $steps[] = 'create_menu_type';
        $steps[] = 'createMenus';
        $steps[] = 'createControl';
        $steps[] = 'changeParams';
        $steps[] = 'createContentCategory';
        $steps[] = 'finish';
        return $steps;
    }



}