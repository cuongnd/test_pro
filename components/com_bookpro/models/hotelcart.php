<?php
    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id$
    **/
    defined('_JEXEC') or die('Restricted access');
    include_once 'cart.php';
    class BookProHotelCart extends BookproCart{

        var $sum = 0;
        var $service_fee=0;
        var $products;
        var $tax=0;
        var $room;
        var $checkin_date;
        var $checkout_date;
        var $no_room;
        var $total_room;
        var $room_type;

        var $array_adult;
        var $array_child;


        var $hotel_id;
        var $facility_id;
        var $facilities;
        var $notes;



        function load($type_cart = "hotelcart"){
            $this->type_cart = $type_cart;
            $session =& JFactory::getSession();
            $objcart = $session->get($this->type_cart);

            if (isset($objcart) && $objcart!='') {
                $temp_cart = unserialize($objcart);
                $this->checkin_date = $temp_cart->checkin_date;
                $this->checkout_date = $temp_cart->checkout_date;
                $this->room_type=$temp_cart->room_type;
                $this->facility_id=$temp_cart->facility_id;
                $this->facilities=$temp_cart->facilities;
                $this->total_room=$temp_cart->total_room;
                $this->adult=$temp_cart->adult;
                $this->children=$temp_cart->children;

                $this->array_adult=$temp_cart->array_adult;
                $this->array_child=$temp_cart->array_child;



                $this->no_room=$temp_cart->no_room;
                $this->room = $temp_cart->room;
                $this->sum=$temp_cart->sum;
                $this->service_fee=$temp_cart->service_fee;
                $this->tax=$temp_cart->tax;
                $this->total=$temp_cart->total;
                $this->notes=$temp_cart->notes;
                $this->customer=$temp_cart->customer;
                $this->hotel_id=$temp_cart->hotel_id;
                $this->adult=$temp_cart->adult;
                $this->children=$temp_cart->children;
                $this->products=$temp_cart->products;
                $this->orderinfo=$temp_cart->orderinfo;
            }


        }
        function clear(){
            $session =& JFactory::getSession();
            $session->set($this->type_cart, "");
        }

}