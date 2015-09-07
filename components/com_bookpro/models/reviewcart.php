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

class BookProReviewCart extends BookproCart {

   var $obj_id;
   var $firstname;
   var $lastname;
   var $email;
   var $date;
   var $country_id;
    
    function saveToSession() {
        $session = & JFactory::getSession();
        $session->set($this->type_cart, serialize($this));
    }

    function load($type_cart = "reviewcart") {
        $this->type_cart = $type_cart;
        $session = & JFactory::getSession();
        $objcart = $session->get($this->type_cart);

        if (isset($objcart) && $objcart != '') {
            $temp_cart = unserialize($objcart);

            $this->obj_id = $temp_cart->obj_id;
            $this->firstname = $temp_cart->firstname;
            $this->lastname = $temp_cart->lastname;
            $this->email = $temp_cart->email;
            $this->date = $temp_cart->date;
            $this->country_id = $temp_cart->country_id;
            
        }
    }

    function clear() {
        $session = & JFactory::getSession();
        
        $session->set($this->type_cart, "");
    }

}