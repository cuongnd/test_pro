<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: bookpro.php 80 2012-08-10 09:25:35Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

class ReportHelper
{

	
	static function getSummary($start,$end){
		$db = JFactory::getDbo();
	
		$query = "
		SELECT sum(total) AS total_sum FROM #__bookpro_orders WHERE created >= '$start' AND created <= '$end'";
	
		$db->setQuery($query);
		$row = $db->loadObject();
	
		return $row->total_sum;
	
	}
	function getCategory(){
		$db= JFactory::getDbo();
		$query = "
					SELECT cat.* FROM #__bookpro_category AS cat,#__bookpro_customer AS cus 
				WHERE cat.id = cus.referral_id
				 
				";
		$db->setQuery($query);
		$cats = $db->loadObjectList();
		
		return $cats;
	}
	function getReferralCustomer($start,$end,$ref_id){
		$db = JFactory::getDbo();
		$query = "
					SELECT count(id) FROM #__bookpro_customer WHERE referral_id = $ref_id AND created >= '$start' AND created < '$end'
				";
		$db->setQuery($query);
		
		$total = $db->loadResult();
		return $total;
	}
	public static function getTotalCustomer($start,$end,$country_id){
		
		$db = JFactory::getDbo();
		$query = "SELECT count(id) FROM #__bookpro_customer WHERE country_id = $country_id AND created >= '$start' AND created < '$end'";
		$db->setQuery($query);
		
		$total = $db->loadResult();
		return $total;
	}
	public static function getCountry(){
		$db = JFactory::getDbo();
		$query = "
					SELECT ct.* FROM #__bookpro_country As ct,#__bookpro_customer AS cus 
				WHERE ct.id = cus.country_id 
				 
				  
				";
		$db->setQuery($query);
		
		$country = $db->loadObjectList();
		
		return $country;
	}
	public static function getTourInfo($price_id){
		$db= JFactory::getDbo();
		$query = "SELECT t.* FROM #__bookpro_tour AS t where  t.id = (SELECT tour_id FROM #__bookpro_tour_package AS p WHERE p.id = (SELECT package_id FROM #__bookpro_packageprice AS r WHERE r.id =".$price_id." ))";
		$db->setQuery($query);
		return $db->loadObject();
		
	}

	public static function buildAdminReport($datefrom,$dateto){
	
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('info.*, o.user_id AS cus_id, o.total AS total,o.created AS receiveDate, o.notes AS notes, o.order_number AS ordNo,o.order_status AS order_status,o.pay_status AS pay_status,cat.title AS pickup');
		$query->from('#__bookpro_orderinfo AS info');
		$query->leftJoin('#__bookpro_orders AS o ON info.order_id=o.id');
		$query->leftJoin('#__bookpro_category AS cat ON info.location=cat.id');
		$query->where("o.created >= " . $db->quote($datefrom) . " AND o.created<=". $db->quote($dateto));
		$query->order('info.start');
	
		$sql = (string)$query;
	
		$db->setQuery($sql);
		$infos = $db->loadObjectList();
	
		if (! class_exists('BookProModelCustomer')) {
			AImporter::model('customer');
		}
		$cusModel=new BookProModelCustomer();
		foreach ($infos as $info) {
			$tourobj=ReportHelper::getTourInfo($info->obj_id);
			$info->tour_code=$tourobj->code;
			$info->depart_time=$tourobj->start_time;
			$cusModel=new BookProModelCustomer();
			$cusModel->setId($info->cus_id);
			$customer=$cusModel->getObject();
			$info->gender=$customer->gender;
			$info->fullname=$customer->firstname.' '.$customer->lastname;
			$info->telephone=$customer->telephone;
		}
	
		return $infos;
	
	}
	
	public static function buildDriverReport($datefrom,$dateto){
		
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('info.*, o.user_id AS cus_id, o.total AS total, o.notes AS notes, o.order_number AS ordNo,o.order_status AS order_status,o.pay_status AS pay_status,cat.title AS pickup');
		$query->from('#__bookpro_orderinfo AS info');
		$query->leftJoin('#__bookpro_orders AS o ON info.order_id=o.id');
		$query->leftJoin('#__bookpro_category AS cat ON info.location=cat.id');
		$query->where("info.start >= " . $db->quote($datefrom) . " AND info.start<=". $db->quote($dateto));
		$query->order('info.start');
		 
		
		$sql = (string)$query;
		
	
		
		$db->setQuery($sql);
		$infos = $db->loadObjectList();
		
		if (! class_exists('BookProModelCustomer')) {
			AImporter::model('customer');
		}
		$cusModel=new BookProModelCustomer();
		foreach ($infos as $info) {
			$tourobj=ReportHelper::getTourInfo($info->obj_id);
			$info->tour_code=$tourobj->code;
			$info->depart_time=$tourobj->start_time;
			$cusModel=new BookProModelCustomer();
			$cusModel->setId($info->cus_id);
			$customer=$cusModel->getObject();
			$info->gender=$customer->gender;
			$info->fullname=$customer->firstname.' '.$customer->lastname;
			$info->telephone=$customer->telephone;
		}
		
		return $infos;
		
	}
	public static function getCategoryTour(){
		$tours = ReportHelper::getTours();
		
		$tourId = ReportHelper::getTourIds($tours);
		
		$tourId = implode($tourId, ",");
		$db = JFactory::getDbo();
		$query = "
					SELECT c.* FROM #__bookpro_category AS c WHERE c.id IN(
				SELECT cat_id FROM #__bookpro_tourcategory WHERE tour_id IN ($tourId) GROUP BY cat_id
				) 
				";
		
		$db->setQuery($query);
		$cats = $db->loadObjectList();
		return $cats;
	}
	
	public static function getTourIds($tours){
		$tourids = array();
		foreach ($tours as $v){
			$tourids[] = $v->id;
		}
		
		return $tourids;
	}
	public static function getTourCatIds($cat_id){
		$db = JFactory::getDbo();
		$query = "
				SELECT tour_id FROM #__bookpro_tourcategory WHERE cat_id = $cat_id
				";
		$db->setQuery($query);
		$tids = $db->loadResultArray();
		//$tid = implode($tids, ",");
		return $tids;
	}
	public static function getToursCat($start,$end,$tour_ids){
		$tourId = implode($tour_ids, ",");
		
		$total = ReportHelper::getOrderTour($start, $end, $tourId);
		return $total;
	}
	public static function getCountToursCat($start,$end,$tour_ids){
		$tourId = implode($tour_ids, ",");
	
		$total = ReportHelper::getOrderCountTour($start, $end, $tourId);
		return $total;
	}
	public static function getTours($start = '',$end = '',$tour_id = array()){
	
		$db = JFactory::getDbo();
		$where = "";
		if (!empty($tour_id)){
			$id = implode(",",$tour_id);
			$where .= " AND t.id IN ($id)";
		}
		if ($start != ''){
			$where .= " AND o.created >='$start'";
		}
		if ($end != ''){
			$where .= " AND o.created <= '$end'";
		}
	
		$query = "
		SELECT t.id,t.title,o.created FROM `#__bookpro_tour` AS t,
		`#__bookpro_tour_package` AS pt,
		`#__bookpro_packageprice` AS p,
		`#__bookpro_orderinfo` AS oi,
		`#__bookpro_orders` AS o
		WHERE t.id = pt.tour_id AND pt.id = p.package_id AND p.id = oi.obj_id AND oi.order_id = o.id
	
		";
		$query = $query.$where." GROUP BY t.id";
	
		$db->setQuery($query);
		$tours = $db->loadObjectList();
	
		return $tours;
	
	}
	public static function getOrderTour($start,$end,$tour_id){
		$db = JFactory::getDbo();
		$query = "
		SELECT SUM(o.total) FROM `#__bookpro_orders` AS o,
		`#__bookpro_orderinfo` AS oi,
		`#__bookpro_packageprice` AS p,
		`#__bookpro_tour_package` AS pt,
		`#__bookpro_tour` AS t
			
		WHERE o.id = oi.order_id AND oi.obj_id = p.id AND p.package_id = pt.id AND pt.tour_id = t.id AND o.created >= '$start' AND o.created <= '$end' AND t.id IN ($tour_id)
		";
	
		$db->setQuery($query);
		$rows = $db->loadResult();
		return $rows;
	
	}
	public static function getOrderCountTour($start,$end,$tour_id){		
		$db = JFactory::getDbo();		
		$query = "		SELECT count(o.id) FROM `#__bookpro_orders` AS o,		
					`#__bookpro_orderinfo` AS oi,		
					`#__bookpro_packageprice` AS p,		
					`#__bookpro_tour_package` AS pt,		
					`#__bookpro_tour` AS t					
		WHERE o.id = oi.order_id AND oi.obj_id = p.id AND p.package_id = pt.id AND pt.tour_id = t.id AND o.created >= '$start' AND o.created <= '$end' AND t.id IN ($tour_id)		";			$db->setQuery($query);		$rows = $db->loadResult();		return $rows;		}
	
}