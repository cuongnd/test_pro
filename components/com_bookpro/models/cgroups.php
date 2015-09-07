<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: cgroups.php 102 2012-08-29 17:33:02Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelCGroups extends AModelFrontEnd
{
    
    var $_table;

    function __construct()
    {
        parent::__construct();
        $this->_table = $this->getTable('cgroup');
    }

    /**
     * Get MySQL loading query for customers list
     * 
     * @return string complet MySQL query
     */
    function buildQuery()
    {
        $query=null;
        
        if (is_null($query)) {
            $query = 'SELECT `cgroup`.* ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `cgroup` ';
            $query .= $this->buildContentWhere();
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