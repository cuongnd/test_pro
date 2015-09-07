<?php
defined('_JEXEC') or die('Restricted access');
/**
 * 
 * @author sony
 *
 */
class BookproCart {
	
	
	var $type_cart ;
	var $customer;
	var $adult;
	var $children;
	var $vat;
	var $total;
	var $subtotal;
	var $order;
	var $orderinfo;
   
	var $from;
	var $to;
	var $order_id;
        var $person;
	
	function saveToSession() {
		$session =& JFactory::getSession();
		$session->set($this->type_cart, serialize($this));
	
	}
	function clear(){
		$session =& JFactory::getSession();
		$this->order_id=0;
		$session->set($this->type_cart, "");
	}

}
