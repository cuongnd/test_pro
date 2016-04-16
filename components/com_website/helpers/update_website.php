<?php

/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 15/04/2016
 * Time: 4:38 CH
 */
class update_website
{
    public static function update_current_website_from_parent_website($website_id)
    {
        $table_website=JTable::getInstance('website');
        $table_website->load($website_id);
        $template_website_id=$table_website->copy_from;
        if($template_website_id)
        {
            update_website::action_step_by_step($website_id,$template_website_id);
        }

    }

    /**
     * @param string $currentStep
     * @throws Exception
     */
    private function action_step_by_step($website_id,$template_website_id)
    {
        $steps=update_website::getListStep();
        foreach($steps as $key=>$step)
        {

            $ok= call_user_func_array(array('update_website', $step), array($website_id,$template_website_id));
            if(!$ok)
            {

            }
        }


    }
    private function formBase($website_id,$template_website_id){
        return true;

    }
    private function createBasicInfoWebsite($website_id,$template_website_id){
        return true;

    }
    private function createConfiguration($website_id,$template_website_id){

        return true;

    }
    private function createGroupUser($website_id,$template_website_id){
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
        $steps[] = 'createMenus';
        $steps[] = 'createControl';
        $steps[] = 'changeParams';
        $steps[] = 'createContentCategory';
        $steps[] = 'finish';
        return $steps;
    }



}