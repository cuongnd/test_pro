<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: regions.php 48 2012-07-13 14:13:31Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelRegions extends AModelFrontEnd
{
    
    var $_table;

    function __construct()
    {
        parent::__construct();
        $this->_table = $this->getTable('region');
        
    }

    /**
     * Get MySQL loading query for customers list
     * 
     * @return string complet MySQL query
     */
    function buildQuery()
    {
         $query=null;
        
        $tourTable=&$this->getTable("region");
        if (is_null($query)) {
           $query = 'SELECT `t`.* ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `t` ';
         	$query .= $this->buildContentWhere();
            $query .= $this->buildContentOrderBy();
        }
        return $query;
    }
    
   
    	
    function buildContentWhere()
    {
        $where = array();
        $this->addIntProperty($where, 'state');
        return $this->getWhere($where);
    }
    
      	
    
   
}

?>