<?php

    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id: view.html.php 84 2012-08-17 07:16:08Z quannv $
    **/

    defined('_JEXEC') or die('Restricted access');
    AImporter::model('countries');
    
    jimport('joomla.application.component.view');
    
    class BookProViewCustomtrip extends JViewLegacy
    {
    	var $model;
    	var $countries;
	    function display($tpl = null) {
		AImporter::model('customtrip');
		$this -> model = new BookProModelCustomTrip();
		parent::display ( $tpl );   
        }
        
        function loadCustomer (){
        	$user = JFactory::getUser();
        	AImporter::model('customer');
        	$cModel = new BookProModelCustomer();
        	
        	$cutomer =$cModel ->getCustomerByUserIdSystem($user ->id);
        	
        	return $cutomer;
        	//Load customer
        }
        function getGender($id){
        	if ($id==1)     return "Mr";
        	else if ($id==2)   return "Mrs";
        	return "Ms";
        }
        function checkIsLogin(){
        	return JFactory::getUser ()->id;
        }
}

