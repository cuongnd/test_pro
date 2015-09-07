<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: order.php 56 2012-07-21 07:53:28Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.model');
//import needed JoomLIB helpers

AImporter::helper('bookpro', 'model');
AImporter::model('passenger','orderinfo');

class BookProModelExpediaOrder extends AModelFrontEnd
{

	var $_table;
	var $_ids;

	function __construct()
	{
		parent::__construct();
		if (! class_exists('TableOrders')) {
			AImporter::table('orders');
		}
		$this->_table = $this->getTable('orders');
	}
	function getObject()
	{
			
		$query = 'SELECT `obj`.*, c.mobile,c.firstname,c.email FROM `' . $this->_table->getTableName() . '` AS `obj` ';
		$query .= 'LEFT JOIN `#__bookpro_customer` AS `c` ON `c`.`id` = `obj`.`user_id` ';
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
	
	function getObjectByID($id)
	{
		AImporter::model('orderinfos','customer');
			
		$query = 'SELECT `obj`.*, c.mobile,c.firstname,c.email FROM `' . $this->_table->getTableName() . '` AS `obj` ';
		$query .= 'LEFT JOIN `#__bookpro_customer` AS `c` ON `c`.`id` = `obj`.`user_id` ';
		$query .= 'WHERE `obj`.`id` = ' . (int) $id;
		$this->_db->setQuery($query);
		$obj= &$this->_db->loadObject();
		
		//load orderinfo
		
		$infosmode = new BookProModelOrderinfos();
		$infolists = array('order_id'=>$id);
		$infosmode->init($infolists);
		$infos = $infosmode->getData();
		$obj->infos= $infos;
	//load customer
		$customerModel = new BookProModelCustomer();
		$customerModel->setId($obj->user_id);
		$customer = $customerModel->getObject();
	   	$obj->customer=$customer;
	
	  return $obj;
		
	}	

	
	

	function store($data)
	{
		
		$id = (int) $data['id'];
		$this->_table->init();
		$this->_table->load($id);

		if (! $this->_table->bind($data)) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		unset($data['id']);

		if (! $this->_table->check()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if (! $this->_table->store()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}

		return $this->_table->id;
	}
	function batchSave($order,$infos){
		$order_id = $this->store($order);
		$orderModelInfo = new BookProModelOrderInfo();
		
		for ($i = 0; $i < count($infos); $i++) {
			$info = $infos[$i];
			$info['order_id'] = $order_id;
			$orderModelInfo->store($info);
			if ($err = $orderModelInfo->getError()){
				$app -> enqueueMessage($err, 'error');
				return false;
			}
		}
		return $order_id;
		
	}
	function getByOrderNumber($number){

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

	
	function processDiscount($order_id){
		$this->setId($order_id);
		$order=$this->getObject();
		AImporter::model('customer','cgroup');
		$cModel=new BookProModelCustomer();
		$cModel->setId($order->user_id);
		$customer=$cModel->getObject();
		if($customer->cgroup_id){
			$gModel=new BookProModelCGroup();
			$gModel->setId($customer->cgroup_id);
			$cgroup=$gModel->getObject();
			if($cgroup->discount){
				
				$discount=$order->total*$cgroup->discount;
				$newTotal=$order->total-$discount;
				$order->total=$newTotal;
				$order->discount=$discount;
				$order->store();
				
			}
		}

	}
	
	
	


	function trash($cids)
	{

		foreach ($cids as $id){

			if( !$this->_table->delete($id))
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			//delete orderinfo

			$passModel = new BookProModelPassenger();
			if(!$passModel->deleteByOrderID($id))
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			$passModel = new BookProModelOrderInfo();
			if(!$passModel->deleteByOrderID($id))
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

		}
		return true;
	}

	function publish($cids)
	{
		return $this->state('state', $cids, 1, 0);
	}

	/**
	 * Unublish selected subjects
	 *
	 * @param $cids subjects IDs
	 * @return boolean success sign
	 */
	function unpublish($cids)
	{
		return $this->state('state', $cids, 0, 1);
	}
}
?>