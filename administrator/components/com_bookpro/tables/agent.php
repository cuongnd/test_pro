<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: customer.php 80 2012-08-10 09:25:35Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

class TableAgent extends JTable
{
  
    var $id;
    var $firstname;
    var $lastname;
    var $company;
    var $brandname;
    var $image;
    var $alias;
    var $desc;
    var $approved;
    var $address;
    var $email;
    var $telephone;
    var $mobile;
    var $fax;
    var $city;
    var $skype;
    var $website;
    var $country_id;
    var $zip;
    var $birthday;
    var $states;
    var $state;
	var $user;
	var $created;
    
    function __construct(& $db)
    {
        parent::__construct('#__' . PREFIX . '_agent', 'id', $db);
    }

    /**
     * Init empty object.
     */
    function init()
    {
        $this->id = 0;
        $this->firstname = '';
        $this->lastname = '';
        $this->brandname = '';
        $this->image = '';
        $this->email = '';
        $this->city = '';
        $this->country_id = 0;
        $this->telephone = '';
        $this->mobile = '';
        $this->address = '';
        $this->states = '';
        $this->state = '';
        $this->zip = '';
        $this->fax = '';
		$this->user = 0;
    }
   
    function check(){
    	$date = JFactory::getDate();
    	$this->created=$date->toMySQL();
    	return true;
    }
}

?>