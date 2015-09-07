<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: payments.php 48 2012-07-13 14:13:31Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelPayments extends AModel
{
    /**
     * Main table
     * 
     * @var TableCustomer
     */
    var $_table;

    function __construct()
    {
        parent::__construct();
        $this->_table = $this->getTable('payment');
    }

    /**
     * Get MySQL loading query for customers list
     * 
     * @return string complet MySQL query
     */
    function buildQuery()
    {
        static $query;
        
        if (is_null($query)) {
            $query = 'SELECT `obj`.* ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
            $query .= $this->buildContentWhere();
        }
        return $query;
    }

                
    /**
     * Get MySQL filter criteria for customers list
     * 
     * @return string filter criteria in MySQL format
     */
    function buildContentWhere()
    {
        $where = array();
        $this->addIntProperty($where,'state');
        return $this->getWhere($where);
    }
}

?>