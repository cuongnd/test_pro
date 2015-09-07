<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 4/6/2015
 * Time: 3:42 PM
 */
class BookProModelCurency extends JModelList{

    function getListQuery(){
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select("a.*");
        $query->from("#__bookpro_expedia_curency_support as a");
        $db->setQuery($query);
        return $query;
    }
}