<?php


    defined('_JEXEC') or die('Restricted access');

    //import needed JoomLIB helpers
    AImporter::helper('request', 'model');

    class BookProModelCoupons extends AModelFrontEnd
    {

        var $_table;

        function __construct()
        {
            parent::__construct();
            $this->_table = $this->getTable('coupon');
        }

        /**
        * Get MySQL loading query for customers list
        *
        * @return string complet MySQL query
        */
        function buildQuery()
        {
            $query=null;
            if(IS_ADMIN) {
                if (is_null($query)) {
                    $query = 'SELECT `coupon`.* ';
                    $query .= 'FROM `' . $this->_table->getTableName() . '` AS `coupon` ';
                    $query .= $this->buildContentWhere();
                    $query .= $this->buildContentOrderBy();
                }
                return $query;
            }else{
                return $this->searchQuery();
            }
        }
        function  searchQuery(){

            $hotelTable=&$this->getTable("hotel");
            $query  = null;
            $query  = 'SELECT `coupon`.*, `hotel`.`userid` as `userid`, `hotel`.`title` as `hotel_title` ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `coupon` ';
            $query .= 'LEFT JOIN `' . $hotelTable->getTableName() . '` AS `hotel` ON `hotel`.`id` = `coupon`.`hotel_id` ';
            $query .= $this->buildContentWhere();
            $query .= $this->buildContentOrderBy();

            return $query;
        }

        function buildContentWhere()
        {
            $where = array();
            $this->addIntProperty($where, 'state');
            $this->addIntProperty($where, 'hotel_id');
            $this->addStringProperty($where, 'title');
            $this->addIntProperty($where, 'userid');
            return $this->getWhere($where);
        }

    }

?>