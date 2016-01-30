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
}