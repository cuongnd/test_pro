<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: airports.php 100 2012-08-29 14:55:21Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelPayLogs extends AModel {

    var $_table;

    function __construct() {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'l.id',
                'l.created',
            );
        }
        parent::__construct();
        $this->_table = $this->getTable('paylog');
    }

    function buildQuery() {

        $order_id= $this->getState('order_id', 'order_id', 0);
        $query = null;
        if (is_null($query)) {
            $query = 'SELECT `paylog`.*,`orders`.`order_number` AS `ordernumber`,`orders`.`total` AS `orderstotal`,`users`.`name` AS `username` ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `paylog` ';
            $query .='LEFT JOIN `#__bookpro_orders` AS `orders` ON `paylog`.`order_id` = `orders`.`id` ';
            $query .='LEFT JOIN `#__users` AS `users` ON `users`.`id` =`paylog`.`created_by` ';
            $query .='LEFT JOIN `#__bookpro_customer` AS `customer` ON `customer`.`id` =`paylog`.`created_by` ';
            $query .='WHERE `paylog`.`order_id` =' . $order_id;
        }
        return $query;
    }

}

?>