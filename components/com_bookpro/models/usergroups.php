<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: airlines.php 102 2012-08-29 17:33:02Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelUsergroups extends AModelFrontEnd
{
    
    var $_table;

    function __construct()
    {
        parent::__construct();
        //$this->_table = $this->getTable('company');
    }

    /**
     * Get MySQL loading query for customers list
     * 
     * @return string complet MySQL query
     */
    function buildQuery()
    {
            $query = 'SELECT `usergroups`.* ';
            $query .= 'FROM `#__usergroups` AS `usergroups` ';
            $query .= $this->buildContentWhere();
             //$query .= $this->buildContentOrderBy();
      
        return $query;
    }
    function buildContentWhere()
    {
    	$where = array();
        //$this->addStringProperty($where, 'name');
    	//$this->addIntProperty($where, 'state');
    	return $this->getWhere($where);
    }

  }

?>