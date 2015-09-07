<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: orders.php 56 2012-07-21 07:53:28Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.model');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'model');

//import needed tables

class BookProModelOrdersreport extends AModelFrontEnd {

    var $_table;
    var $_ids;

    function __construct() {
        parent::__construct();
        if (!class_exists('TableOrders')) {
            AImporter::table('orders');
        }
        $this->_table = $this->getTable('orders');
    }

    function buildQuery() {
        $query = null;

        $customerTable = &$this->getTable('customer');

        if (is_null($query)) {
            
           $query = "SELECT `orders`.*,orderinfo.obj_id AS obj_id, orderinfo.start AS departdate,tour1.title AS daytrip_tour_title,tour1.id AS daytrip_tour_id,tour.title AS nonedaytriptour_title,tour.id AS nonedaytriptour_id, CONCAT(`c`.`firstname`,' ',`c`.`lastname`) AS `ufirstname` ,`c`.`id` AS `cid` ";
            $query .= 'FROM `' . $this->_table->getTableName() . '` AS `orders` ';
            $query .= 'LEFT JOIN `' . $customerTable->getTableName() . '` AS `c` ON `c`.`id` = `orders`.`user_id` ';
            $query.=' LEFT JOIN #__bookpro_orderinfo AS orderinfo ON orderinfo.order_id=orders.id';
            $query.=' LEFT JOIN #__bookpro_packagerate AS packagerate ON packagerate.id=orderinfo.obj_id';
            $query.=' LEFT JOIN #__bookpro_tour_package AS tour_package ON tour_package.id=packagerate.tourpackage_id';
            $query.=' LEFT JOIN #__bookpro_tour AS tour ON tour.id=tour_package.tour_id';
            $query.=' LEFT JOIN #__bookpro_passenger AS passenger ON passenger.order_id=orders.id';
            $query.=' LEFT JOIN #__bookpro_packageratedaytripjoingroup AS packageratedaytripjoingroup ON packageratedaytripjoingroup.id=orderinfo.obj_id';
             $query.=' LEFT JOIN #__bookpro_tour AS tour1 ON tour1.id=packageratedaytripjoingroup.tour_id';
            $query .= $this->buildContentWhere();
            $query .= $this->buildContentOrderBy();
        }
        return $query;
    }

    function getIdUserByOrder() {
        $user = &JFactory::getUser();
        if ($user->id) {
            $query = '';
            $query .='SELECT *';
            $query .='FROM `__bookpro_orders` AS order ';
            $query .='WHERE order.user=' . $user->id;
            $this->_db->setQuery($query);
        }
    }

    

    function buildContentWhere() {

        $where = array();
        $this->addIntProperty($where, 'user_id', 'orders-created_by');
        $this->addStringProperty($where, 'order_status', 'type', 'pay_method', 'pay_status');
        $this->addTimeRange($where, 'orders.created', 'from', 'to');
         if ($this->_lists['tour_id']) {
            //$where[] = 'tour1.id='.$this->_lists['tour_id'].' OR tour.id='.$this->_lists['tour_id'];
             $where[] = '  CASE WHEN orders.tour_type="shared" THEN  tour1.id='.$this->_lists['tour_id'].' ELSE tour.id='.$this->_lists['tour_id'].' END';
        }
        if ($this->_lists['created']) {
            $where[] = 'DATE_FORMAT(`orders`.`created`,"%Y-%m-%d")=' . $this->_db->quote($this->_lists['created']);
        }
        if ($this->_lists['departdate']) {
            $where[] = 'DATE_FORMAT(orderinfo.start,"%Y-%m-%d")=' . $this->_db->quote($this->_lists['departdate']);
        }
        if ($this->_lists['tour_type']) {
            if ($this->_lists['tour_type'] == 'nonedaytrip')
                $where[] = 'tour_type=' . $this->_db->quote('nonedaytripprivate') . ' OR ' . 'tour_type=' . $this->_db->quote('nonedaytripshared');
            else if ($this->_lists['tour_type'] == 'daytrip') {
                $where[] = 'tour_type=' . $this->_db->quote('private') . ' OR ' . 'tour_type=' . $this->_db->quote('shared');
            } else {
                $where[] = 'tour_type=' . $this->_db->quote($this->_lists['tour_type']);
            }
        }
        if ($this->_lists['orders-id']) {
            $where[] = ' `orders`.`id` IN  (' . implode(",", $this->_lists['orders-id']) . ')';
        }
        $where = $this->getWhere($where);
        return $where;
    }
	function getTourByOrderId($order_id=0)
	{
		AImporter::model('order');
		$model_order=new BookProModelOrder();
		$model_order->init($order_id);
		$order=$model_order->getObject();
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('tour.*');
		$query->from('#__bookpro_tour AS tour');
		if($order->tour_type=='shared')
		{
			$query->leftJoin('#__bookpro_packageratedaytripjoingroup AS packageratedaytripjoingroup ON packageratedaytripjoingroup.tour_id=tour.id');
			$query->leftJoin('#__bookpro_orderinfo AS orderinfo ON orderinfo.obj_id=packageratedaytripjoingroup.id');
		}
		else 
		{
			$query->leftJoin('#__bookpro_tour_package AS tour_package ON tour_package.tour_id=tour.id');
			$query->leftJoin('#__bookpro_packagerate AS packagerate ON packagerate.tourpackage_id=tour_package.id');
			$query->leftJoin('#__bookpro_orderinfo AS orderinfo ON orderinfo.obj_id=packagerate.id');
		}
		$query->leftJoin('#__bookpro_orders AS orders ON orders.id=orderinfo.order_id');
		$query->where('orders.id='.$order_id);
		$db->setQuery($query);
		$tour=$db->loadObject();
		return $tour;
	}
    function getFullObject() {
        AImporter::model('orderinfos', 'customer');
        $orders = $this->getData();

        //load orderinfo
        foreach ($orders as $order) {
            $infomodel = new BookProModelOrderinfos();
            $lists = array('order_id' => $order->id);
            $infomodel->init($lists);
            $infos = $infomodel->getData();
            $order->infos = $infos;
            $customerModel = new BookProModelCustomer();
            $customerModel->setId($order->user_id);
            $customer = $customerModel->getObject();
            $order->customer = $customer;
        }
        //load customer/


        return $orders;
    }
    function getPassenger($cids)
    {
        $cids=  implode(',', $cids);
        $db=  JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('pass.*');
        $query->from('#__bookpro_passenger AS pass');
        $query->where('pass.order_id IN('.$cids.')');
        $db->setQuery($query);
        $list=$db->loadAssocList('id');
        
        return $list;
    }
    function listaddoneBypassenger($listpassenger=array())
    {
    	
    	$passenger_ids=implode(',', array_keys($listpassenger));
    	$db=JFactory::getDbo();
    	$query=$db->getQuery(true);
    	$query->select('addon.id,addon.title,addonpassenger.passenger_id');
    	$query->from('#__bookpro_addon AS addon');
    	$query->leftJoin('#__bookpro_order_addonpassenger AS addonpassenger ON addonpassenger.addone_id=addon.id');
    	$query->where('addonpassenger.passenger_id IN('.$passenger_ids.')');
    	$db->setQuery($query);
    	$lisaddonselected=$db->loadObjectList();
    	foreach ($lisaddonselected as $addone)
    	{
    		$listpassenger[$addone->passenger_id]['lisaddonselected'][]=$addone;
    	}
    	return $listpassenger;
    }
    function assign_roomselected($listpassenger)
    {
    	$passenger_ids=implode(',', array_keys($listpassenger));
    	$db=JFactory::getDbo();
    	$query=$db->getQuery(true);
    	$query->select('roomtype.id,roomtype.title,roomtypepassenger.*');
    	$query->from('#__bookpro_roomtype AS roomtype');
    	$query->leftJoin('#__bookpro_order_roomtypepassenger AS roomtypepassenger ON roomtypepassenger.roomtype_id=roomtype.id');
    	$query->where('roomtypepassenger.passenger_id IN('.$passenger_ids.')');
    	$db->setQuery($query);
    	$roomtypeselected=$db->loadObjectList();
    	foreach ($roomtypeselected as $roomtype)
    	{
    		
    		$listpassenger[$roomtype->passenger_id][$roomtype->type?$roomtype->type:'nonetrip'][]=$roomtype;
    	}
    	return $listpassenger;
    }
    function assign_triptransferprice($listpassenger)
    {
    	$passenger_ids=implode(',', array_keys($listpassenger));
    	$db=JFactory::getDbo();
    	$query=$db->getQuery(true);
    	$query->select('transfer.id,transfer.arrival_date_time,transfer.flightnumber,transfer.passenger_id');
    	$query->from('#__bookpro_order_transferpassenger AS transfer');
    	$query->where('transfer.passenger_id IN('.$passenger_ids.')');
    	$db->setQuery($query);
    	$transferselected=$db->loadObjectList();
    	foreach ($transferselected as $transfers)
    	{
    		$listpassenger[$transfers->passenger_id][$transfers->type?$transfers->type:'nonetransfer'][]=$transfers;
    	}
    	return $listpassenger;
    }
    
    function getByOrderNumber($number) {

        $query = 'SELECT `obj`.* FROM `' . $this->_table->getTableName() . '` AS `obj` ';
        $query .= 'WHERE `obj`.`order_number` = ' . (int) $number;
        $this->_db->setQuery($query);

        if (($object = &$this->_db->loadObject())) {
            $this->_table->bind($object);
            return $this->_table;
        }

        return parent::getObject();
    }

    function trash($cids) {

        foreach ($cids as $id) {

            if (!$this->_table->delete($id)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            $passModel = new BookProModelPassenger();
            if (!$passModel->deleteByOrderID($id)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            $infoModel = new BookProModelOrderInfo();
            if (!$infoModel->deleteByOrderID($id)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    function deleteByCustomerID($customerID) {

        $query = 'DELETE o.*, info.*,p.* ';
        $query.='FROM #__bookpro_orders as  o, #__bookpro_orderinfo as  info, #__bookpro_passenger as p where o.id=info.order_id and o.id=p.order_id ';
        $query.='and o.user_id=' . $customerID;
        $this->_db->setQuery($query);
        if (!$this->_db->query()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return true;
    }

    function unpublish($cids) {
        return $this->state('state', $cids, 0, 1);
    }

    function saveorder($cids, $order) {
        $branches = array();
        for ($i = 0; $i < count($cids); $i++) {
            $this->_table->load((int) $cids[$i]);
            $branches[] = $this->_table->parent;
            if ($this->_table->ordering != $order[$i]) {
                $this->_table->ordering = $order[$i];
                if (!$this->_table->store()) {
                    $this->setError($this->_db->getErrorMsg());
                    return false;
                }
            }
        }
        return true;
    }

}

?>