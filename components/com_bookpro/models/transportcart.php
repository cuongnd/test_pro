<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: flightcart.php  23-06-2012 23:33:14
 **/
defined('_JEXEC') or die('Restricted access');
include_once 'cart.php';
class BookProTransportCart extends BookproCart{

	var $type_cart = "transportcart"; //cart,wishlist
	var $count_product = 0;
	var $sum = 0;
	var $total=0;
	var $service_fee=0;
	var $tax=0;
	var $orderinfos;
	var $from;
	var $to;
	var $start;
	var $end;
	var $roundtrip;
	var $trips;
	

	function saveToSession() {
		$session =& JFactory::getSession();
		$session->set($this->type_cart, serialize($this));
	}
 
	function load($type_cart = "transportcart"){
		$this->type_cart = $type_cart;
		$session =& JFactory::getSession();
		$objcart = $session->get($this->type_cart);

		if (isset($objcart) && $objcart!='') {
			$temp_cart = unserialize($objcart);
			$this->sum=$temp_cart->sum;
			$this->service_fee=$temp_cart->service_fee;
			$this->tax=$temp_cart->tax;
			$this->total=$temp_cart->total;
			$this->roundtrip=$temp_cart->roundtrip;
			$this->notes=$temp_cart->notes;
			$this->customer=$temp_cart->customer;
			$this->orderinfos=$temp_cart->orderinfos;
			$this->adult=$temp_cart->adult;
			$this->children=$temp_cart->children;
			$this->passengers=$temp_cart->passengers;
			$this->trips=$temp_cart->trips;
			$this->from=$temp_cart->from;
			$this->to=$temp_cart->to;
			$this->start=$temp_cart->start;
			$this->end=$temp_cart->end;
			$this->bustrip_id=$temp_cart->bustrip_id;
			$this->return_bustrip_id=$temp_cart->return_bustrip_id;
			$this->price=$temp_cart->price;
			$this->return_price=$temp_cart->return_price;
			$this->order_id=$temp_cart->order_id;
		}

	}
	

}