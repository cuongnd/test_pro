<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';
require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';

/**
 * Registration controller class for Users.
 *
 * @package     Joomla.Site
 * @subpackage  com_users
 * @since       1.6
 */
class UtilityControllerBlocks extends JControllerForm
{
    public function ajax_copy_block_from_orther_website()
    {
        $app=JFactory::getApplication();
        $website=$app->input->get('website','','string');
        $menu_active_id=$app->input->get('menu_active_id',0,'int');
        $uri_website=JFactory::getURI($website);
        $website=JFactory::getWebsite($website);
        $menu_item_id=$uri_website->getVar('Itemid');
        if(!$menu_item_id)
        {
            $menu=$app->getMenu();
            $item=$menu->get_menu_default_by_website_id($website->website_id);
            $menu_item_id=$item->id;

        }
        JTable::addIncludePath(JPATH_ROOT.'/components/com_utility/tables');
        $tablePosition=JTable::getInstance('Position','JTable');

        $db=JFactory::getDbo();

        $query = $db->getQuery(true)
            ->select('id')
            ->from('#__position_config')
            ->where('parent_id =id ')
            ->where('website_id = '.(int)$website->website_id)
        ;
        $block_parent_id=$db->setQuery($query)->loadResult();
        $query=$db->getQuery(true);
        $query->select('position_config.id,position_config.menu_item_id')
            ->from('#__position_config AS position_config')
            ->where('position_config.parent_id='.(int)$block_parent_id)
            ->where('position_config.menu_item_id='.(int)$menu_item_id)
        ;
        $db->setQuery($query);
        $list_position=$db->loadObjectList();
        $a_listId=array();
        $modelPosition=$this->getModel();
        $curent_website=JFactory::getWebsite();
        $tablePosition->webisite_id=$curent_website->website_id;
        $parentId = $tablePosition->getRootId();
        foreach($list_position as $position)
        {
            $modelPosition->duplicateBlock($position->id,$a_listId,$parentId,$curent_website->website_id,$menu_active_id);
        }
        $tablePosition->rebuild();
        $getDuplicateBlockId=reset($a_listId);
        $result = new stdClass();
        $result->e=0;
        $result->m=JText::_('copy success');
        echo json_encode($result);
        die;

    }
    public function getModel($name = 'position', $prefix = 'UtilityModel', $config = array())
    {
        return parent::getModel($name, $prefix, array('ignore_request' => true));
    }
    public function ajax_get_list_block(){
        $app=JFactory::getApplication('site');
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        $screenSize=$app->input->get('screenSize','','string');
        $isAdminSite=UtilityHelper::isAdminSite();
        if($isAdminSite)
            UtilityHelper::setCurrentScreenSizeEditing($screenSize);
        else
        {
            UtilityHelper::setScreenSize($screenSize);
        }
        echo 1;
        die;
    }

}