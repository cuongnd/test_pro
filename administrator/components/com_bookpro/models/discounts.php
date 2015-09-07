<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 **/
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelDiscounts extends AModel
{
    
    var $_table;

    function __construct()
    {
        parent::__construct();
        $this->_table = $this->getTable('discount');
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
            $query = 'SELECT `discount`.* ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `discount` ';
            $query .= $this->buildContentWhere();
            //$query .= $this->buildContentOrderBy();
        }
        return $query;
    }

    function buildContentWhere()
    {
    	$where = array();
    	$this->addIntProperty($where, 'app');
    	return $this->getWhere($where);
    }
  }

?>