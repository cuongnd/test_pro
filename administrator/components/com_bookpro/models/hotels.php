<?php
    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id: hotels.php 22 2012-07-07 07:56:02Z quannv $
    **/

    defined('_JEXEC') or die('Restricted access');

    //import needed JoomLIB helpers
    AImporter::helper('request', 'model');

    class BookProModelHotels extends AModel
    {
        /**
        * Main table
        *
        * @var Table
        */
        var $_table;

        function __construct()
        {
            parent::__construct();
            $this->_table = $this->getTable('hotel');
        }

        /**
        * Get MySQL loading query for hotel list
        *
        * @return string complet MySQL query
        */
       
        function buildQuery()
        {
            $query=null;
            if(IS_ADMIN) {
                if (is_null($query)) {
                    $query = "SELECT `obj`.*,`city`.`title` AS `city_title`, CONCAT(`customer`.`firstname`,' ',`customer`.`lastname`) AS `fullname` ";
                    $query .= 'FROM `' . $this->_table->getTableName() . '` AS `obj` '; 
                    $query .= 'LEFT JOIN `#__bookpro_dest` AS `city` ON `city`.`id`=`obj`.`city_id` ';
                    $query .= 'LEFT JOIN `#__bookpro_customer` AS `customer` ON `customer`.`id` = `obj`.`userid` ';
                    if ($this->_lists['group_id']) {            
                        $query .=' INNER JOIN #__user_usergroup_map AS map ON map.user_id = customer.user ';                    
                    }
                    $query .= $this->buildContentWhere();
                    //$query .= ' GROUP BY `obj`.`userid` ';
                    $query .= $this->buildContentOrderBy();                
                }
            }else
            {
                $query=$this->buildSearchQuery();
                $query .= $this->buildContentOrderBy();
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
            $this->addIntProperty($where, 'state','city_id','rank','userid','group_id');
            $this->addStringProperty($where, 'obj.title');
            $where= $this->getWhere($where);
            return $where;        
        }

        function buildSearchQuery(){
        	$query2 = "SELECT MIN(`rate`) AS `minrate`,room_id FROM `#__bookpro_roomrate`";
        	$where2 = array();
        	$where2[] = 'rate > 0';
        	
        	if ($this->_lists['start']) {
        		$where2[] = 'DATE_FORMAT(`date`,"%d-%m-%Y") >='.$this->_db->Quote($this->_lists['start']);
        	}
        	if ($this->_lists['end']) {
        		$where2[] = 'DATE_FORMAT(`date`,"%d-%m-%Y") <='.$this->_db->Quote($this->_lists['end']);
        	}
        	$query2 .= ' WHERE ' . implode(' AND ', $where2);
        	$query2 .=' GROUP BY room_id ';
        	
			$query1 = "SELECT room.hotel_id,MIN(r.minrate) AS rateprice FROM `#__bookpro_room` AS room 
						    LEFT JOIN ($query2) AS r ON r.room_id = room.id GROUP BY room.hotel_id ";
			
            $query = 'SELECT h.*,hroom.rateprice AS price ';
            $query .= 'FROM ' . $this->_table->getTableName() . ' AS h ';
            $query .= 'LEFT JOIN #__bookpro_dest AS d ON d.id =h.city_id ';
            $query .= "
            			LEFT JOIN ($query1) AS hroom ON h.id = hroom.hotel_id 
            		";
            if ($this->_lists['city_id']){

                $where[]='h.city_id='.$this->_lists['city_id'];
            }
            if ($this->_lists['rank']){
                $where[]='`h`.`rank`='.$this->_lists['rank'];
            }
            if ($this->_lists['featured']) {
            	$where[] ='`h`.`rank` = '.$this->_lists['featured'];
            }
            $search = isset($this->_lists['search']) ? $this->_lists['search'] : '';
            if ($this->_lists['search']){
                //$where[]='LOWER(h.title) LIKE ' . $this->_db->Quote('%' . JString::strtolower($search) . '%');
                $where1='LOWER(`h`.`title`) LIKE ' . $this->_db->Quote('%' . JString::strtolower($search) . '%');
                $where1 .= ' OR LOWER(`h`.`address1`) LIKE '.$this->_db->Quote('%' . JString::strtolower($search) . '%');
                $where1 .=' OR LOWER(`h`.`desc`) LIKE '.$this->_db->Quote('%' . JString::strtolower($search) . '%');
                
                //$where1 .=' OR h.cancel_policy LIKE '.$this->_db->Quote('%' . $search . '%');
                $where1 .=' OR LOWER(`d`.`title`) LIKE '.$this->_db->Quote('%' . JString::strtolower($search) . '%');
                $where[] = $where1;  
            }
            $this->addMultipleProperty($where, 'h-id');
            $where[]='h.state=1 ';
            $where[] = 'rateprice > 0';
            $query .= ' WHERE ' . implode(' AND ', $where);
			$query .=' GROUP BY h.id';
			
			
            return $query;
        }

    }

?>