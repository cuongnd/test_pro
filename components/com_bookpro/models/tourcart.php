<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: flightcart.php  23-06-2012 23:33:14
 * */
defined('_JEXEC') or die('Restricted access');
include_once 'cart.php';

class BookProTourCart extends BookproCart {

    var $obj_id;
    var $count_product = 0;
    var $sum = 0;
    var $total = 0;
    var $total_discount = 0;
    var $service_fee = 0;
    var $tax = 0;
    var $checkin_date;
    var $checkout_date;
    var $no_room;
    var $children;
    var $adult;
    var $stype;
    var $package_id;
    var $leader;
    var $notes;
    var $orderinfo;
    var $tour_id;
    var $depart;
    var $filter;
    var $person;
    var $setroom;
    var $packagetype_id;
    var $setchildrenacommodation;
    var $pre_trip_acommodaton;
    var $post_trip_acommodaton;
    var $pre_airport_transfer;
    var $post_airport_transfer;
    var $additionnaltrip;
    var $activity;
    var $needasignchildrenforspecialroom;
    var $private;
    var $packagerate_id;

    function saveToSession() {
        $session = & JFactory::getSession();
        $session->set($this->type_cart, serialize($this));
    }

    function load($type_cart = "tourcart") {
        $this->type_cart = $type_cart;
        $session = & JFactory::getSession();
        $objcart = $session->get($this->type_cart);

        if (isset($objcart) && $objcart != '') {
            $temp_cart = unserialize($objcart);

            $this->obj_id = $temp_cart->obj_id;
            $this->package_id = $temp_cart->package_id;
            $this->packagerate_id = $temp_cart->packagerate_id;
            $this->leader = $temp_cart->leader;
            $this->checkin_date = $temp_cart->checkin_date;
            $this->checkout_date = $temp_cart->checkout_date;
            $this->no_room = $temp_cart->no_room;
            $this->setroom = $temp_cart->setroom;
            $this->needasignchildrenforspecialroom = $temp_cart->needasignchildrenforspecialroom;
            $this->total_discount = $temp_cart->total_discount;
            $this->stype = $temp_cart->stype;

            $this->setchildrenacommodation = $temp_cart->setchildrenacommodation;
            $this->pre_trip_acommodaton = $temp_cart->pre_trip_acommodaton;
            $this->post_trip_acommodaton = $temp_cart->post_trip_acommodaton;
            $this->packagetype_id = $temp_cart->packagetype_id;

            $this->pre_airport_transfer = $temp_cart->pre_airport_transfer;

            $this->post_airport_transfer = $temp_cart->post_airport_transfer;
            $this->additionnaltrip = $temp_cart->additionnaltrip;

            $this->sum = $temp_cart->sum;
            $this->service_fee = $temp_cart->service_fee;
            $this->tax = $temp_cart->tax;

            $this->filter = $temp_cart->filter;
            $this->activity = $temp_cart->activity;
            $this->private = $temp_cart->private;
            $this->total = $temp_cart->total;
            $this->products = $temp_cart->products;
            $this->notes = $temp_cart->notes;
            $this->customer = $temp_cart->customer;
            $this->orderinfo = $temp_cart->orderinfo;
            $this->adult = $temp_cart->adult;
            $this->children = $temp_cart->children;
            $this->tour_id = $temp_cart->tour_id;
            $this->depart = $temp_cart->depart;
            $this->order_id = $temp_cart->order_id;
            $this->person = $temp_cart->person;
        }
    }

    function clear() {
        $session = & JFactory::getSession();
        $this->order_id = 0;
        $session->set($this->type_cart, "");
    }

}