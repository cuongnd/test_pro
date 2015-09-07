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
    class BookProExpediaHotelCart extends BookproCart{

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
        var $cardtype;
        var $expiration_date;
        var $card_identification_number;
        var $biiling_zip_code;
        var $cardholder_name;
        var $destination_text;
        var $destination_value;
        var $array_adult;
        var $array_child;
        var $page;
        var $currency_code;
        var $language_code;

        var $hotel;
        var $facility_id;
        var $facilities;
        var $notes;



        function load($type_cart = "expediahotelcart"){
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
                $this->destination_value=$temp_cart->destination_value;
                $this->page=$temp_cart->page;
                $this->currency_code=$temp_cart->currency_code;
                $this->language_code=$temp_cart->language_code;
                $this->children=$temp_cart->children;
                $this->destination_text=$temp_cart->destination_text;
                $this->infoBooking=$temp_cart->infoBooking;

                $this->array_adult=$temp_cart->array_adult;
                $this->array_child=$temp_cart->array_child;



                $this->cardtype=$temp_cart->cardtype;
                $this->expiration_date=$temp_cart->expiration_date;
                $this->biiling_zip_code=$temp_cart->biiling_zip_code;
                $this->cardholder_name=$temp_cart->cardholder_name;



                $this->no_room=$temp_cart->no_room;
                $this->room = $temp_cart->room;
                $this->sum=$temp_cart->sum;
                $this->service_fee=$temp_cart->service_fee;
                $this->tax=$temp_cart->tax;
                $this->total=$temp_cart->total;
                $this->notes=$temp_cart->notes;
                $this->customer=$temp_cart->customer;
                $this->hotel=$temp_cart->hotel;
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