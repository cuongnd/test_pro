<?php
/**
 * Created by PhpStorm.
 * User: Son
 * Date: 4/6/2015
 * Time: 9:28 AM
 */

class BookProModelCpayorderstatus extends JModelList
{
    var $_table;

    function __construct()
    {
        parent::__construct();
        $this->_table = $this->getTable('cpayorderstatus');
    }

    function getListPaystatus() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select("a.*");
        $query->from("#__bookpro_cpayorderstatus as a");
        $query->where("pay_status=1");
        $db->setQuery($query);
        return $db->loadObjectList();
        //$db->execute();

    }



    function getListOrderstatus() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select("a.*");
        $query->from("#__bookpro_cpayorderstatus as a");
        $query->where("order_status=1");
        $db->setQuery($query);
        return $db->loadObjectList();
    }

}