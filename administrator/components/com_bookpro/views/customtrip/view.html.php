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
    AImporter::model('customer', 'orders', 'orderinfo','order');
    AImporter::model('customtrip','passenger');

    AImporter::helper('bookpro', 'request', 'paystatus', 'ordertype', 'orderstatus');
    
    jimport('joomla.application.component.view');
    
    class BookProViewCustomtrip extends BookproJViewLegacy
    {
    	var $items;
    	
    	var $order_id;
    	var $cModel;
    	var $_modelOrder;
    	var $_modelCustomtrip;
    	var $_modePassenger;
    	var $countries;
    	var $total;//total passenger
    	var $customer;
    	var $order;
    	
	    function display($tpl = null) {
	    	
	    
	     
	    $this ->order_id =ARequest::getCid();
	    
	    
	   
		$this -> _modelCustomtrip = new BookProModelCustomTrip();
		$this ->_modePassenger = new BookProModelPassenger();
		
		/* @var $document JDocument */
		$this -> _modelOrder = new BookProModelOrder();
		$this -> _modelOrder->setId($this ->order_id);
		$this ->order = &$this ->_modelOrder->getObject();
		
		$this ->cModel = new BookProModelCustomer();
		$this ->cModel->setId($this->order->user_id);
		$this ->customer = $this ->cModel->getObject();
		
		if (!$this ->order ->id){
			$app =JFactory::getApplication();
			$app->redirect("index.php?option=com_bookpro&view=orders");
		
		}
		$this ->addToolBar();
		parent::display ( $tpl );   
		
        }
        protected function addToolBar()
        {
        	JToolBarHelper::title(JText::_('COM_BOOKPRO_CUSTOMTRIP_INFOR'));
        	if ($this ->getLayout()=='edit'){
        	JToolBarHelper::custom( 'editPassenger', 'save', '', 'Save', false, false );  
        	JToolBarHelper::custom( 'cancel', 'back', '', 'Back', false, false );
        	} else{
        		JToolBarHelper::save();
        		JToolBarHelper::cancel();
        	}
        	
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
        function  checkValidOrder(){
        	
        }
        
        //
        function getListSale($select) {
        	$config = AFactory::getConfig();
        	$list_user_id = JAccess::getUsersByGroup($config->sale_group);
        	$list_user_id = implode(',', $list_user_id);
        	$db = JFactory::getDbo();
        	$query = $db->getQuery(true);
        	$query->select('user.*');
        	$query->from('#__users AS user');
        	$query->where('user.id IN ('.$list_user_id.')');
        	$db->setQuery($query);
        	$list_user=$db->loadObjectList('id');
        	return AHtml::getFilterSelect('sale_id', 'Order Status', $list_user, $select, false, '', 'id', 'name');
        }
        function getPayStatusSelect($select) {
        	PayStatus::init();
        	return AHtml::getFilterSelect('pay_status', 'Pay status', PayStatus::$map, $select, false, '', 'value', 'text');
        }
        function getOrderStatusSelect($select) {
        	OrderStatus::init();
        	return AHtml::getFilterSelect('order_status', 'Order Status', OrderStatus::$map, $select, false, '', 'value', 'text');
        }
}

