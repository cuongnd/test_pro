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
    AImporter::model('countries', 'application', 'categories', "addons");
    
    jimport('joomla.application.component.view');
    
    class BookProViewCustomtripinfor extends JViewLegacy
    {
    	var $order_id;
    	var $_modelCustomer;
    	var $_modelOrder;
    	var $_modelCustomtrip;
    	var $_modePassenger;
    	var $countries;
    	
    	var $total;//total passenger
	    function display($tpl = null) {
		AImporter::model('customer','order');
		AImporter::model('customtrip','passenger');
		$this -> _modelCustomer = new BookProModelCustomer();
		$this -> _modelOrder = new BookProModelOrder();
		$this -> _modelCustomtrip = new BookProModelCustomTrip();
		$this ->_modePassenger = new BookProModelPassenger();
		$jinput = JFactory::getApplication ()->input;
		$this->order_id = $jinput->get ( "order_id", 0 );
		
		
		
		if (!$this ->checkValidOrder($this ->order_id)){
			$app =JFactory::getApplication();
			$app->redirect("index.php?option=com_bookpro&view=mypage");
				
		}
		
		
		parent::display ( $tpl );   
		
        }
        /*
         * Load order by order id
         */
        function  loadOrder($id){
        	$order;
        	$order = $this ->_modelOrder ->getObjectByID($id);
        	return $order;
        }
        /*
         * Load customer by customer id
        */
        function  loadCustomer($id){
        	$customer;
        	$customer = $this ->_modelCustomer ->getCustomerByID($id);
        	return $customer;
        }
        
        function loadPassengers($id){
        	$passengers;
        	$passengers= $this ->_modelCustomtrip ->loadPassengerByOrderID($id);
        	return $passengers;
        }
        
        function loadPassengerItem($id){
        	$passenger;
        	$passenger= $this ->_modelCustomtrip ->loadPassengerByID($id);
        	return $passenger;
        }
        /*
         * 
         */
        function loadCountryName($id){
        	return $this->_modelCustomtrip ->getCountryNameById($id);
        }
        function loadCountry($id){
        	AImporter::model('cgroups');
        	$model = new BookProModelCGroups();
        }
        function  loadTypeGroup($name = 'passenger',$id = 'passenger'){
        	AImporter::model('cgroups');
        	$model = new BookProModelCGroups();
        	$lists = array('state'=>1);
        	$model->init($lists);
        	$lists = $model->getData();
        	
        	return JHtmlSelect::genericlist($lists, $name,'class=input-small','id','title',$id);
        }
        function  checkValidOrder($order_id){
        	//load customer
        	//load user
        	//compare with current user
        	$customer = $this ->loadOrder($order_id)->customer;
        	$order_user =$this -> _modelCustomer ->getUserSystemByEmail($customer->email);
        	$user = JFactory::getUser();
        	return $user->id ==$order_user ->id;
        }
}

