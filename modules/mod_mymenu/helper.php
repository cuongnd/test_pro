<?php

defined('_JEXEC') or die;

/**
 * Helper for mod_menu
 *
 * @package     Joomla.Administrator
 * @subpackage  mod_menu
 * @since       1.5
 */
abstract class ModMymenuHelper
{

    public static function getData()
    {

        return 1;
    }
    public static function getModuleById($id){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select("a.*");
        $query->from("#__modules as a");
        $query->where("a.id=".$id);
        $db->setQuery($query);
        return $data=$db->loadObject();
    }


}
