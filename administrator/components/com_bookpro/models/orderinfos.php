<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: orderinfos.php 102 2012-08-29 17:33:02Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
AImporter::helper('bookpro', 'model');

class BookProModelOrderinfos extends AModel {

    var $_table;

    function __construct() {
        parent::__construct();
        $this->_table = $this->getTable('orderinfo');
    }

    function buildQuery() {
        $query = null;
        if (is_null($query)) {
            $query = 'SELECT `obj`.* ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
            $query .= $this->buildContentWhere();
        }
        return $query;
    }

    function buildContentWhere() {
        $where = array();
        $this->addIntProperty($where, 'order_id');
        $this->addStringProperty($where, 'type');
        return $this->getWhere($where);
    }

    function getList() {
        AImporter::helper('hotel');
        $rows = $this->getData();
        foreach ($rows as $row) {
            $row->checkout = HotelHelper::checkOutDate($row->end);
        }
        return $rows;
    }

    

}

?>