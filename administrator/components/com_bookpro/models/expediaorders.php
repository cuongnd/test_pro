<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: orders.php 56 2012-07-21 07:53:28Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.model');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'model');
//import needed tables

class BookProModelExpediaOrders extends AModel
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
	function buildQuery(){
		$query=null;

		$customerTable = &$this->getTable('customer');
		
		if (is_null($query)) {
			$query = "SELECT `orders`.*, CONCAT(`c`.`firstname`,' ',`c`.`lastname`) AS `ufirstname` ,`c`.`id` AS `cid` ";
			$query .= 'FROM `' . $this->_table->getTableName() . '` AS `orders` ';
			$query .= 'LEFT JOIN `' . $customerTable->getTableName() . '` AS `c` ON `c`.`id` = `orders`.`user_id` ';
			$query .= $this->buildContentWhere();
			$query .= $this->buildContentOrderBy();      
		}
		return $query;
	}
	function buildContentWhere(){
		
		$where=array();
		$this->addIntProperty($where,'user_id','orders-created_by');
		$this->addStringProperty($where, 'order_status','type','pay_method','pay_status');
		$this->addTimeRange($where, 'orders.created','from','to');
		if ($this->_lists['created']) {
			$where[] = 'DATE_FORMAT(`orders`.`created`,"%Y-%m-%d")='. $this->_db->quote($this->_lists['created']);
		}
        if ($this->_lists['orders-id']) {
            $where[] = ' `orders`.`id` IN  ('.implode(",", $this->_lists['orders-id']).')';
        }
        $where[]='orders.itineraryid!=""';
		$where= $this->getWhere($where);
		return $where;
	
	}
	function getFullObject(){
		AImporter::model('orderinfos','customer');
		$orders = $this->getData();
		
		//load orderinfo
		foreach ($orders as $order){
			$infomodel = new BookProModelOrderinfos();
			$lists = array('order_id'=>$order->id);
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
	function getByOrderNumber($number){

		$query = 'SELECT `obj`.* FROM `' . $this->_table->getTableName() . '` AS `obj` ';
		$query .= 'WHERE `obj`.`order_number` = ' . (int) $number;
		$this->_db->setQuery($query);

		if (($object = &$this->_db->loadObject())) {
			$this->_table->bind($object);
			return $this->_table;
		}

		return parent::getObject();
	}
	


	function trash($cids)
	{

		foreach ($cids as $id){

			if( !$this->_table->delete($id))
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

			$passModel = new BookProModelPassenger();
			if(!$passModel->deleteByOrderID($id))
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
			$infoModel = new BookProModelOrderInfo();
			if(!$infoModel->deleteByOrderID($id))
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

		}
		return true;
	}
	function deleteByCustomerID($customerID){

		$query='DELETE o.*, info.*,p.* ';
		$query.='FROM #__bookpro_orders as  o, #__bookpro_orderinfo as  info, #__bookpro_passenger as p where o.id=info.order_id and o.id=p.order_id ';
		$query.='and o.user_id='.$customerID;
		$this->_db->setQuery($query);
		if (!$this->_db->query()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
	
		return true;
	}

	function unpublish($cids){
		return $this->state('state', $cids, 0, 1);
	}
	
	function saveorder($cids, $order)
	{
		$branches = array();
		for ($i = 0; $i < count($cids); $i ++) {
			$this->_table->load((int) $cids[$i]);
			$branches[] = $this->_table->parent;
			if ($this->_table->ordering != $order[$i]) {
				$this->_table->ordering = $order[$i];
				if (! $this->_table->store()) {
					$this->setError($this->_db->getErrorMsg());
					return false;
				}
			}
		}
		return true;
	}


}

?>