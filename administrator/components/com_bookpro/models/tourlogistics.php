<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: tours.php 21 2012-07-06 04:06:17Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'model');

class BookProModelTourLogistics extends AModel
{

    var $_table;

    function __construct()
    {
        parent::__construct();
        $this->_table = $this->getTable('tourlogistic');

    }

    /**
     * Get MySQL loading query for customers list
     *
     * @return string complet MySQL query
     */
    function buildQuery()
    {
        $query=null;
        if(IS_ADMIN) {
            $query = 'SELECT `c`.* ';
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `c` ';
        }
        else
        	$query=$this->buildSearchQuery();

        return $query;
    }



    function buildContentWhere()
    {
        $where = array();
        $this->addIntProperty($where, 'state');
        $this->addIntProperty($where, 'duration');
        $this->addStringProperty($where, 'title');
        $where = $this->getWhere($where);
        if (isset($this->_lists['tcat_id']))
        	//$where .= ' AND `tour_cat`.`cat_id`= ' . $this->_lists['tcat_id'];
        return $where;

    }
    function buildSearchQuery(){
    	 $db = $this->getDbo();
    	 $query = $db->getQuery(true);
    	 $query->select("t.*");
    	 $query->from('#__bookpro_tour AS t');
         if ($this->_lists['keyword']){
         	$query->where('t.title LIKE "%'.$this->_lists['keyword'] .'%"');
         }
         if ($this->_lists['days']){
         	$query->where('t.days='.$this->_lists['days']);
         }
         if ($this->_lists['cat_id']){
          	$query->leftJoin("#__bookpro_tourcategory AS tour_cat ON tour_cat.tour_id = t.id ");
          	$query->where("tour_cat.cat_id=".$this->_lists['cat_id']);
         }
         if ($this->_lists['period']){
        	$params=explode(';', $this->_lists['period']);
        	$query->where('t.publish_date >= "'.$params[0].'"');
        	$query->where('t.unpublish_date >= "'.$params[1].'"');
         }
         if ($this->_lists['activity']) {
         	$query->leftJoin('#__bookpro_touractivity AS activity ON activity.tour_id = t.id');
         	$query->where('activity.activity_id='.$this->_lists['activity']);
         }
         if ($this->_lists['private'] != 'both' && $this->_lists['private'] ) {
         	$query->where('t.stype='.$db->quote($this->_lists['private']));
         }
         if ($this->_lists['private']=='both' && $this->_lists['private']) {
         	$query->where('t.private=0');
         }
         if ($this->_lists['stype'] && !$this->_lists['daytrip']) {
         	$query->where('t.stype='.$db->quote($this->_lists['stype']));
         	$query->where('t.daytrip=0');
         }
         if ($this->_lists['daytrip']) {
         	$query->where('t.daytrip='.$db->quote($this->_lists['daytrip']));
         }
         if ($this->_lists['featured']) {
         	$query->where('t.featured='.$this->_lists['featured']);
         }
    	if ($this->_lists['dest_id']){
    		$query->select('dest.title as citytitle');
          	$query->leftJoin('#__bookpro_itinerary AS itinerary ON itinerary.tour_id = t.id');
          	$query->leftJoin('#__bookpro_dest AS dest ON dest.id = itinerary.dest_id');
          	$query->where('itinerary.dest_id='.$this->_lists['dest_id']);
         } else {
         	if ($this->_lists['country_id']){

         		$query->leftJoin('#__bookpro_itinerary AS itinerary ON itinerary.tour_id = t.id');
         		$query->leftJoin('#__bookpro_dest AS dest ON dest.id = itinerary.dest_id');

         		$query->where('dest.country_id='.$this->_lists['country_id']);
         	}
         }
         $query->where('t.state=1');
         $query->group('t.id');
         $query.= $this->buildContentOrderBy();
         return $query;
    }



}

?>