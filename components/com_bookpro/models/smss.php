<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: smss.php 56 2012-07-21 07:53:28Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelSmss extends AModelFrontEnd
{
    /**
     * Main table
     * 
     * @var TableSms
     */
    var $_table;

    function __construct()
    {
        parent::__construct();
        $this->_table = $this->getTable('sms');
    }

    /**
     * Get MySQL loading query for smss list
     * 
     * @return string complet MySQL query
     */
    function buildQuery()
    {
        $query=null;
        if (is_null($query)) {
            $query = 'SELECT `sms`.* ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `sms` ';
            $query .= $this->buildContentWhere();
        }
        return $query;
    }

    /**
     * Get MySQL filter criteria for smss list
     * 
     * @return string filter criteria in MySQL format
     */
    function buildContentWhere()
    {
        $where = array();
        $this->addIntProperty($where, 'status');
        return $this->getWhere($where);
    }
}

?>