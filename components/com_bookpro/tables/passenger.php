<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: passenger.php 26 2012-07-08 16:07:54Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

class TablePassenger extends JTable
{
  
    var $id;
    var $title;
    var $firstname;
    var $lastname;
    var $gender;
    var $age;
    var $passport;
    var $ppvalid;
    var $issueby;
    var $country_id;
    var $birthday;
    var $customer_id;
    var $order_id;
    var $orderinfo_id;
    var $group_id;
    
    
   
    /**
     * Construct object.
     * 
     * @param JDatabaseMySQL $db database connector
     */
    function __construct(& $db)
    {
        parent::__construct('#__' . PREFIX . '_passenger', 'id', $db);
    }

    /**
     * Init empty object.
     */
    function init()
    {
        $this->id = 0;
        $this->title='';
        $this->firstname = '';
        $this->lastname = '';
        $this->gender = '';
        $this->age = NULL;
        $this->passport = '';
        $this->birthday=null;
        $this->country_id=0;
        $this->ppvalid=null;
        $this->customer_id=0;
        $this->order_id=null;
        $this->orderinfo_id=0;
       
    }
}

?>