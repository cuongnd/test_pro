<?php

/**
 * Created by PhpStorm.
 * User: cuongnd
 * Date: 12/25/2015
 * Time: 2:15 PM
 */
class block_helper
{

    public static function get_html_by_block_id($block_id,$enableEditWebsite=false)
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
        $root_position_id = $tablePosition->getRootId();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('position_config.id,position_config.parent_id,position_config.menu_item_id')
            ->from('#__position_config AS position_config')
            ->where('position_config.parent_id=' . (int)$root_position_id)
            ->where('position_config.parent_id!=position_config.id')
        ;
        $db->setQuery($query);
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
        $query = $db->getQuery(true);
        $query->delete('#__position_config')
            ->where('id IN('.implode(',',$list_delete).')')
            ;
        $db->setQuery($query);
        if(!$db->execute())
        {
            throw new Exception($db->getErrorMsg());
        }
    }
}