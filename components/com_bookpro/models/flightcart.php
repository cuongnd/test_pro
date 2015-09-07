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
class BookProFlightCart extends BookproCart{

	var $type_cart = "flightcart"; //cart,wishlist
	var $sum = 0;
	var $total=0;
	var $service_fee=0;
	var $tax=0;
	var $start;
	var $end;
	var $children;
	var $adult;
	var $infant;
	var $notes;
	var $orderinfos;
	var $roundtrip;
	var $passengers=array();
	var $baggage = array();
	var $price;
	var $return_price;
	var $package;
	var $return_package;
	var $flight_id;
	var $return_flight_id;
	var $rate_id;
	var $return_rate_id;
	var $min_price;
	var $max_price;
	var $min_time;
	var $max_time;
	var $airline;
	var $adult_tax;
	var $child_tax;
	var $infant_tax;

	function saveToSession() {
		$session =& JFactory::getSession();
		$session->set($this->type_cart, serialize($this));
	}
 
	function load($type_cart = "flightcart"){
		$this->type_cart = $type_cart;
		$session =& JFactory::getSession();
		$objcart = $session->get($this->type_cart);

		if (isset($objcart) && $objcart!='') {
			$temp_cart = unserialize($objcart);
			
			$this->from = $temp_cart->from;
			$this->to = $temp_cart->to;
			$this->start = $temp_cart->start;
			$this->end = $temp_cart->end;
			$this->sum=$temp_cart->sum;
			$this->service_fee=$temp_cart->service_fee;
			$this->tax=$temp_cart->tax;
			$this->total=$temp_cart->total;
			$this->notes=$temp_cart->notes;
			$this->customer=$temp_cart->customer;
			$this->orderinfos=$temp_cart->orderinfos;
			$this->adult=$temp_cart->adult;
			$this->adult_tax=$temp_cart->adult_tax;
			$this->children=$temp_cart->children;
			$this->child_tax=$temp_cart->child_tax;
			$this->infant=$temp_cart->infant;
			$this->infant_tax=$temp_cart->infant_tax;
			$this->roundtrip=$temp_cart->roundtrip;
			$this->passengers=$temp_cart->passengers;
			$this->baggage=$temp_cart->baggage;
			$this->price=$temp_cart->price;
			$this->return_price=$temp_cart->return_price;
			$this->package = $temp_cart->package;
			$this->return_package = $temp_cart->return_package;
			$this->flight_id = $temp_cart->flight_id;
			$this->rate_id = $temp_cart->rate_id;
			$this->return_rate_id = $temp_cart->return_rate_id;
			
			$this->return_flight_id = $temp_cart->return_flight_id;
			$this->min_price = $temp_cart->min_price;
			$this->max_price = $temp_cart->max_price;
			$this->min_time = $temp_cart->min_time;
			$this->max_time = $temp_cart->max_time;
			$this->airline = $temp_cart->airline;
		}


	}
	function clear(){
		$session =& JFactory::getSession();
        $this->products = null;
        $this->passengers = array();
        $this->baggage = array();
        $this->orderinfo = null;
        $this->customer=null;
        $this->sum = 0;
        $this->no_room = 0;
        $this->notes = "";        
        $this->total = 0; 
        $this->adult=0;
        $this->children=0;
        $this->enfant=0;
        $this->rate_id = 0;
        $this->return_rate_id = 0;
        $this->min_price = 0;
        $this->max_price = 0;
        $this->min_time = 0;
        $this->max_time = 24;
        $this->adult_tax = 0;
        $this->child_tax = 0;
        $this->infant_tax = 0;
        $this->airline = array();
        $this->start = null;
        $this->end = null;
	}

}