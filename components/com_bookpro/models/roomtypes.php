<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: hotels.php 22 2012-07-07 07:56:02Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelRoomTypes extends AModelFrontEnd {

    /**
     * Main table
     *
     * @var Table
     */
    var $_table;

    function __construct() {
        parent::__construct();
        $this->_table = $this->getTable('roomtype');
    }

    function buildQuery() {
        $query = null;
        $query = 'SELECT `obj`.* ';
        $query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` ';
        $query .= $this->buildContentWhere();
        $query .= $this->buildContentOrderBy();
        return $query;
    }

    /**
     * Get MySQL filter criteria for customers list
     *
     * @return string filter criteria in MySQL format
     */
    function buildContentWhere() {
        $where = array();
        $this->addStringProperty($where, 'title');
        $where = $this->getWhere($where);
        return $where;
    }

    function buildSearchQuery() {

        $query = 'SELECT `t`.* ';
        $query .= 'FROM `' . $this->_table->getTableName() . '` AS `t` ';
        $where[] = '`t`.`state`=1';
        $query .= ' WHERE ' . implode(' AND ', $where);
        $query.= $this->buildContentOrderBy();
        return $query;
    }

    function getRoomTypesByPakage($package_id) {
        $db = & JFactory::getDBO();
        $sql = 'SELECT rtpk.roomtype_id  FROM #__bookpro_roomtype_tourpackage AS rtpk ';
        $sql.=' WHERE rtpk.tourpackage_id=' . $package_id;
        $db->setQuery($sql);
        return $db->loadColumn();
    }

     function getRoomTypesDataByPakageId($package_id) {
        $db = & JFactory::getDBO();                                                     
        $sql = 'SELECT roomtype.*  FROM #__bookpro_roomtype AS roomtype ';
        $sql .= 'LEFT JOIN #__bookpro_roomtype_tourpackage AS rtpk ON rtpk.roomtype_id = roomtype.id ';
        $sql .= ' WHERE rtpk.tourpackage_id=' . $package_id;
        $db->setQuery($sql);
        return $db->loadObjectList();
    }
    
    function getListRoomTypesByPakage($package_id) {
        $db = & JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('roomtype.*,CONCAT(\'{\',\'"id":\',roomtype.id,\',\',\'"max_person":\',roomtype.max_person,\'}\') AS id_max_person');
        $query->from('#__bookpro_roomtype AS roomtype');
        $db->setQuery($query);
        return $db->loadObjectList();
    }

}

?>