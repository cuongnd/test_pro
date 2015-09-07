<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: order.php 56 2012-07-21 07:53:28Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.model');
//import needed JoomLIB helpers

AImporter::helper('bookpro', 'model');
AImporter::model('passenger', 'orderinfo');

class BookProModelOrder extends AModel {

    var $_table;
    var $_ids;

    function __construct() {
        parent::__construct();
        if (!class_exists('TableOrders')) {
            AImporter::table('orders');
        }
        $this->_table = $this->getTable('orders');
    }

    function getObject() {

        $query = 'SELECT `obj`.*, c.mobile,c.firstname,c.lastname,c.email FROM `' . $this->_table->getTableName() . '` AS `obj` ';
        $query .= 'LEFT JOIN `#__bookpro_customer` AS `c` ON `c`.`user` = `obj`.`user_id` ';
        $query .= 'WHERE `obj`.`id` = ' . (int) $this->_id;
        $this->_db->setQuery($query);
        if (($object = &$this->_db->loadObject())) {
            $this->_table->bind($object);
            $this->_table->telephone = $object->telephone;
            $this->_table->mobile = $object->mobile;
            $this->_table->firstname = $object->firstname;
            $this->_table->lastname = $object->lastname;
            $this->_table->email = $object->email;
            return $this->_table;
        }

        return parent::getObject();
    }

    function getType($selected='tour')
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('LOWER(type) AS id,LOWER(type) AS title');
        $query->from('#__bookpro_orders');
        $query->group('type');
        $db->setQuery($query);
        $types= $db->loadObjectList();
        return JHTML::_('select.genericlist', $types, 'type', ' class="inputbox" onchange="this.form.submit();" ', 'id', 'title',$selected) ;


    }
    function getObjectByID($id) {
        AImporter::model('orderinfos', 'customer');

        $query = 'SELECT `obj`.*, c.mobile,c.firstname,c.email FROM `' . $this->_table->getTableName() . '` AS `obj` ';
        $query .= 'LEFT JOIN `#__bookpro_customer` AS `c` ON `c`.`id` = `obj`.`user_id` ';
        $query .= 'WHERE `obj`.`id` = ' . (int) $id;
        $this->_db->setQuery($query);
        $obj = &$this->_db->loadObject();

        //load orderinfo

        $infosmode = new BookProModelOrderinfos();
        $infolists = array('order_id' => $id);
        $infosmode->init($infolists);
        $infos = $infosmode->getData();
        $obj->infos = $infos;
        //load customer
        $customerModel = new BookProModelCustomer();
        $customerModel->setId($obj->user_id);
        $customer = $customerModel->getObject();
        $obj->customer = $customer;

        return $obj;
    }
	function gethotelByPassengerId($roomtypepassenger_id=0)
	{
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('hotel.*');
		$query->from('#__bookpro_hotel AS hotel');
		$query->leftJoin('#__bookpro_order_roomtypepassenger AS roomtypepassenger ON roomtypepassenger.hotel_id=hotel.id');
		$query->where('roomtypepassenger.id='.$roomtypepassenger_id);
		$db->setQuery($query);

		return $db->loadObject();
	}
	function getBookingInfo($roomtypepassenger_id=0)
	{
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('roomtypepassenger.*,roomtype.title AS roomtype_title');
		$query->from('#__bookpro_order_roomtypepassenger AS roomtypepassenger');
		$query->leftJoin('#__bookpro_roomtype AS roomtype ON roomtype.id=roomtypepassenger.roomtype_id');
		$query->where('roomtypepassenger.id='.$roomtypepassenger_id);
		$db->setQuery($query);
		return $db->loadObject();
	}
	function getPassenger($roomtypepassenger_id=0)
	{
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('passenger.*,country.country_name AS country_name');
		$query->from('#__bookpro_passenger AS passenger');
		$query->leftJoin('#__bookpro_order_roomtypepassenger AS roomtypepassenger ON roomtypepassenger.passenger_id=passenger.id');
		$query->leftJoin('#__bookpro_country AS country ON country.id=passenger.country_id');
		$query->where('roomtypepassenger.id='.$roomtypepassenger_id);
		$db->setQuery($query);
		return $db->loadObject();
	}

    function getObjectTourByOrderID($orderid=0) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(TRUE);
        $query->select('tour.*');
        $query->from('#__bookpro_tour AS tour');
        $query->leftJoin('#__bookpro_tour_package AS tour_package ON tour_package.tour_id=tour.id');
        $query->leftJoin('#__bookpro_packagerate AS packagerate ON packagerate.tourpackage_id=tour_package.id');
        $query->leftJoin('#__bookpro_orderinfo AS orderinfo ON orderinfo.obj_id=packagerate.id');
        $query->where('orderinfo.order_id=' . $orderid);
        $db->setQuery($query);
        return $db->loadObject();
    }
    function gettriptransferInfo($airport_transfer_id=0) {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(TRUE);
    	$query->select('transferpassenger.*');
    	$query->from('#__bookpro_order_transferpassenger AS transferpassenger');
    	$query->where('transferpassenger.id=' . $airport_transfer_id);
    	$db->setQuery($query);
    	return $db->loadObject();
    }
    function getAdditionInfo($addition_id=0) {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(TRUE);
    	$query->select('addon.*');
    	$query->from('#__bookpro_addon AS addon');
    	$query->leftJoin('#__bookpro_order_addonpassenger AS addonpassenger ON addonpassenger.addone_id=addon.id');
    	$query->where('addonpassenger.id=' . $addition_id);
    	$db->setQuery($query);
    	return $db->loadObject();
    }

    function getPassengerByAirportStransferId($airport_transfer_id=0) {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(TRUE);
    	$query->select('passenger.*');
    	$query->from('#__bookpro_passenger AS passenger');
    	$query->leftJoin('#__bookpro_order_transferpassenger AS transferpassenger ON transferpassenger.passenger_id=passenger.id');
    	$query->where('transferpassenger.id=' . $airport_transfer_id);
    	$db->setQuery($query);
    	return $db->loadObject();
    }
    function getPassengerByadditionId($addition_id=0) {
    	$db = JFactory::getDbo();
    	$query = $db->getQuery(TRUE);
    	$query->select('passenger.*');
    	$query->from('#__bookpro_passenger AS passenger');
    	$query->leftJoin('#__bookpro_order_addonpassenger AS addonpassenger ON addonpassenger.passenger_id=passenger.id');
    	$query->where('addonpassenger.id=' . $addition_id);
    	$db->setQuery($query);
            return $db->loadObject();
    }
    function store($data) {

        $id = (int) $data['id'];
        $this->_table->init();
        $this->_table->load($id);

        if (!$this->_table->bind($data)) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        unset($data['id']);

        if (!$this->_table->check()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }
        if (!$this->_table->store()) {
            $this->setError($this->_db->getErrorMsg());
            return false;
        }

        return $this->_table->id;
    }

    function batchSave($order, $infos) {
        $order_id = $this->store($order);
        $orderModelInfo = new BookProModelOrderInfo();

        for ($i = 0; $i < count($infos); $i++) {
            $info = $infos[$i];
            $info['order_id'] = $order_id;
            $orderModelInfo->store($info);
            if ($err = $orderModelInfo->getError()) {
                $app->enqueueMessage($err, 'error');
                return false;
            }
        }
        return $order_id;
    }

    function getByOrderNumber($number) {

        $query = 'SELECT `obj`.*,c.telephone,c.mobile,c.lastname,c.firstname,c.email FROM `' . $this->_table->getTableName() . '` AS `obj` ';
        $query .= 'LEFT JOIN `#__bookpro_customer` AS `c` ON `c`.`id` = `obj`.`user_id` ';
        $query .= 'WHERE `obj`.`order_number` = ' . $number;
        $this->_db->setQuery($query);

        if (($object = &$this->_db->loadObject())) {
            $this->_table->bind($object);
            $this->_table->telephone = $object->telephone;
            $this->_table->mobile = $object->mobile;
            $this->_table->firstname = $object->firstname;
            $this->_table->lastname = $object->lastname;
            $this->_table->email = $object->email;
            return $this->_table;
        }

        return parent::getObject();
    }

    function processDiscount($order_id) {
        $this->setId($order_id);
        $order = $this->getObject();
        AImporter::model('customer', 'cgroup');
        $cModel = new BookProModelCustomer();
        $cModel->setId($order->user_id);
        $customer = $cModel->getObject();
        if ($customer->cgroup_id) {
            $gModel = new BookProModelCGroup();
            $gModel->setId($customer->cgroup_id);
            $cgroup = $gModel->getObject();
            if ($cgroup->discount) {

                $discount = $order->total * $cgroup->discount;
                $newTotal = $order->total - $discount;
                $order->total = $newTotal;
                $order->discount = $discount;
                $order->store();
            }
        }
    }

    function trash($cids) {

        foreach ($cids as $id) {

            if (!$this->_table->delete($id)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }

            //delete orderinfo

            $passModel = new BookProModelPassenger();
            if (!$passModel->deleteByOrderID($id)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
            $passModel = new BookProModelOrderInfo();
            if (!$passModel->deleteByOrderID($id)) {
                $this->setError($this->_db->getErrorMsg());
                return false;
            }
        }
        return true;
    }

    function publish($cids) {
        return $this->state('state', $cids, 1, 0);
    }

    /**
     * Unublish selected subjects
     *
     * @param $cids subjects IDs
     * @return boolean success sign
     */
    function unpublish($cids) {
        return $this->state('state', $cids, 0, 1);
    }
    function getRoomListByTourId($id){
        $db = $this->getDbo ();
        $query = $db->getQuery ( true );
        $query="SELECT a.*,b.* FROM ueb3c_bookpro_roomtype as a INNER JOIN ueb3c_bookpro_order_roomtypepassenger as b ON a.id=b.roomtype_id where b.order_id=$id";
        $db->setQuery($query);
        $result = $db->loadObjectList();
        return $result;
    }

    function getInfotourById($id){
        $db = $this->getDbo ();
        $query = $db->getQuery ( true );
        $query->select('a.order_number,a.created,a.notes,a.order_status,a.total,
        b.adult,b.start,b.end,b.adult,b.child,b.enfant,b.infant,
        c.teen,
        e.title,e.code,
        f.title as title_packagetype,
        g.firstname,g.lastname,
        h.name as assigned_name,h.id as assigned_id,
        i.title as title_pay_status,
        k.title as title_order_status
        ');
        $query->from('#__bookpro_orders as a');
        $query->where('a.id='.$id);
        $query->leftJoin('#__bookpro_orderinfo as b ON a.id=b.order_id');
        $query->leftJoin('#__bookpro_packagerate as c ON b.obj_id=c.id');
        $query->leftJoin('#__bookpro_tour_package as d ON c.tourpackage_id=d.id');

        $query->leftJoin('#__bookpro_tour as e ON d.tour_id=e.id');
        $query->leftJoin('#__bookpro_packagetype as f ON d.packagetype_id=f.id');
        $query->leftJoin('#__bookpro_customer as g ON a.user_id=g.id');
        $query->leftJoin('#__users as h ON a.assigned_id=h.id');
        $query->leftJoin('#__bookpro_cpayorderstatus as i ON a.pay_status=i.id');
        $query->leftJoin('#__bookpro_cpayorderstatus as k ON a.order_status=k.id');
//        $query->join('INNER','#__bookpro_order_roomtypepassenger as h ON a.id=h.order_id');
//        $query->join('INNER','#__bookpro_roomtype as i ON h.roomtype_id=i.id');

        $db->setQuery($query);

        $result = $db->loadObject();

        //load room
        $data_room=$this->getRoomListByTourId($id);
        $result->data_room=$data_room;
        //load passenger
        $passenger= new BookProModelPassenger();
        $data_passenger=$passenger->getPassengerByOrderid($id);
        $result->data_passenger=$data_passenger;
        return $result;
    }

    public function  getListChildrenOrder(&$listOrder=array(),$order_id)
    {
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('_order.id')
            ->from('#__bookpro_orders AS _order')
            ->where('_order.parent_id='.(int)$order_id);
        $db->setQuery($query);
        $orderIds=$db->loadColumn();
        foreach($orderIds as $id)
        {
            $listOrder[$order_id][]=$id;
            static::getListChildrenOrder($listOrder,$id);
        }
    }

}

?>