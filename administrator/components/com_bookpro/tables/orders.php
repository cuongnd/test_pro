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

class TableOrders extends JTable
{

	var $id;
	var $order_number;
	var $user_id;
	var $type;
	/**
	 *  Total amount
	 * @var unknown_type
	 */
	var $total;
	var $state;
        var $sale_id;
        
	var $subtotal;
	/**
	 * Discounted amount
	 * @var unknown_type
	 */
	var $discount;
	var $notes;
	/**
	 * Payment method
	 * @var unknown_type
	 */
	var $pay_method;
	/**
	 * Payment status
	 * @var unknown_type
	 */
	var $pay_status;
	/**
	 * client IP address
	 * @var unknown_type
	 */
	var $ip_address;
	var $tax;
	var $service_fee;
	var $created;
	/**
	 * Deposit amount
	 */
	var $deposit;
	/**
	 * Transaction id from payment gateway
	 * @var string
	 */
	var $tx_id;
	/**
	 * Status of order
	 * @var Constant: CONFIRMED, CANCELED, NEW, NEGOTIATING, FINISHED
	 */
	var $order_status;
	/**
	 * 
	 * @var unknown_type
	 */
	var $coupon_id;


	/**
	 * Construct object.
	 *
	 * @param JDatabaseMySQL $db database connector
	 */
	function __construct(& $db)
	{
		parent::__construct('#__bookpro_orders', 'id', $db);
	}

	/**
	 * Init empty object.
	 */
	function init()
	{
		$this->order_number='';
		$this->notes='';
		$this->total = '';
		$this->subtotal = '';
		$this->state = 1;
		$this->discount=0;
		$this->pay_method='';
		$this->pay_status='';
		$this->ip_address='';
		$this->tax=0;
		$this->service_fee=0;
		$this->tx_id='';
		$this->coupon_id=0;
	}

	function check(){
		if(!$this->id) {
			$date = JFactory::getDate('now');
			$this->created=$date->toSql(true);
			$this->order_number=$this->create_unique_order_id();
			$this->ip_address=$_SERVER[REMOTE_ADDR];
		}
		return true;
	}
	function create_unique_order_id(){

		$order = '';
		$chars = "0123456789";
		srand((double)microtime()*1000000);
		$i = 0;
		while ($i <= 7) {
			$num = rand() % 10;
			$tmp = substr($chars, $num, 1);
			$order = $order . $tmp;
			$i++;
		}
		return $order;
	}
}

?>