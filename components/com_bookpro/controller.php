<?php
    /**
    * @package 	Bookpro
    * @author 		Nguyen Dinh Cuong
    * @link 		http://ibookingonline.com
    * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
    * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
    * @version 	$Id: controller.php 129 2012-09-10 04:34:01Z quannv $
    **/
    // No direct access to this file
    defined('_JEXEC') or die('Restricted access');

    // import Joomla controller library
    jimport('joomla.application.component.controller');
    AImporter::helper('bookpro', 'controller', 'parameter', 'request');
    /**
    * Hello World Component Controller
    */
    AImporter::model('hotels');
    class BookProController extends JControllerLegacy
    {
        var $model;

        public function BookProController(){
            parent::__construct();
        }

        public function display($cachable = false, $urlparams = false)
        {     
            // Set the default view name and format from the Request.
			
            $vName     = JRequest::getCmd('view', 'login');
         	$this->setDirectByUserGroup($vName);
            switch ($vName) {
            	case 'hotels':
            		$this->search();
            		
            		return; 
                case 'formpayment':
                    $this->processOrder();
                    return;  
                case 'points':
                    $this->addModelPath(JPATH_SITE.'/components/com_bookpro/models');
                    $this->getModel('orders'); 
                    return;
            }
			
            JRequest::setVar('view', $vName);
            parent::display();

        }
        /**
        * Filter before display: apply discount, coupon
        */
		
        private function setDirectByUserGroup($vName)
        {
        	$mainframe = &JFactory::getApplication();
        	$config = &AFactory::getConfig();
        	$user = JFactory::getUser();
        	$totalview = array();
        
        	$views[$config->supplierUsergroup]  = array('supplierpage','registerhotel', 'registerhotels', 'room', 'rooms', 'roomrate', 'roomratedetail','scripthotel' );
        	$views[$config->customersUsergroup] = array('mypage','booking');
        	$views[$config->agentUsergroup] = array('agentpage','booking');
        
        	$totalviews = array_merge($views[$config->supplierUsergroup], $views[$config->customersUsergroup],$views[$config->agentUsergroup]);
        
        	if((in_array($vName, $totalviews)) && $user->guest ){
        		$return = 'index.php?option=com_bookpro&view='.$vName;
        		$url = 'index.php?option=com_bookpro&view=login';
        		$url .= '&return='.urlencode(base64_encode($return));
        		$mainframe->redirect($url, JText::_('COM_BOOKPRO_LOGIN_REQUIRED'));
        		return;
        	}
        	else if((in_array($vName, $totalviews)) && $user->id ){
        
        		if(!$this->_checkUserRight($vName, $views, $user))
        		{
        			$mainframe->redirect(JUri::base() , JText::_('COM_BOOKPRO_PERMISSION_REQUIRED'));
        		}
        	}
        }
        
        private function _checkUserRight($vName,$views,$user){
        
        	$config = &AFactory::getConfig();
        	$checked = false;
        
        	if(in_array($vName, $views[$config->supplierUsergroup]) && in_array($config->supplierUsergroup, $user->groups)){
        		$checked = true;
        	}
        	if(in_array($vName, $views[$config->customersUsergroup]) && in_array($config->customersUsergroup, $user->groups)){
        		$checked = true;
        	}
        	if(in_array($vName, $views[$config->agentUsergroup]) && in_array($config->agentUsergroup, $user->groups)){
        		$checked = true;
        	}
        	return $checked;
        }
        
        
        
        function search(){
        	 
        	AImporter::helper('hotel');
        	$app = JFactory::getApplication();
        	AImporter::model('airport');
        
        	$menu = &JSite::getMenu();
        	$active = $menu->getActive();
        	if($active) {
        		$this->products_per_row=$active->params->get('products_per_row',2);
        		$this->count=$active->params->get('count',8);
        	}else{
        		$this->products_per_row=1;
        		$this->count= $app -> getCfg('list_limit');
        	}
        	 
        
        
        	$input = JFactory::getApplication()->input;
        
        	$cart = &JModelLegacy::getInstance('HotelCart', 'bookpro');
        	$cart->load();
        	$checkin_date=$input->get('checkin');
        	if (!$checkin_date) {

        		$checkin_date=JFactory::getDate();
        		$checkin_date->add(new DateInterval('P1D'));
        		$checkin_date = JFactory::getDate($checkin)->format('d-m-Y',true);
        	}
        	if (!$checkout_date) {
        		$checkout_date=JFactory::getDate();
        		$checkout_date->add(new DateInterval('P2D'));
        		$checkout_date = JFactory::getDate($checkout)->format('d-m-Y',true);
        	}
        	$checkout_date = $input->get('checkout');
        	
        	$keyword = $input->get('keyword','','string');
        	$keyword = ltrim($keyword);
        	$keyword = rtrim($keyword);
        	$adult = $input->getInt('adult',1);
        	$room = $input->getInt('room',0);
        	$searchmode = $input->getBool('searchmode',true);
        	$children = $input->getInt('child',0);
        
        
        	$cart->adult=$adult;
        	$cart->children=$children;
        	$cart->room = $room;
        	$cart->checkin_date=$checkin_date;
        
        	$cart->checkout_date=$checkout_date;
        	$cart->saveToSession();
        
        	$model=new BookProModelHotels();
        	$lists=array();
        
        	$date = new JDate();
        	$end = $date->format('t-m-Y',true);
        	$startMonth = $date->setDate($date->year, $date->month, 01);
        	$startMonth = $date->format('d-m-Y');
        	$endMonth = new JDate($end);
        	$endMonth = $endMonth->format('d-m-Y');
        	
        	$lists['search']=$keyword;
        	$lists['limit'] = $input->get('limit', $this->count, 'int');
        	$lists['featured'] = $input->get('featured', 0, 'int');
        	$lists['city_id'] = $input->get('city_id',0,'int');
        	
        	$lists['state'] = 1;
        	$lists['start'] = $checkin_date;
        	$lists['end'] = $checkout_date;
        	$lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart',  0, 'int');
        	$this->lists['order'] = ARequest::getUserStateFromRequest('order', 'title', 'cmd');
        	$this->lists['order_Dir'] = ARequest::getUserStateFromRequest('order_Dir', 'DESC', 'word');
        	$lists['searchmode']=$searchmode;
        	
        	$airModel = new BookProModelAirport();
        	$airModel->setId($lists['city_id']);
        	$dest = $airModel->getObject();
        	if ($dest->id == NULL) {
        		
        		$dest->title = JText::_('COM_BOOKPRO_HOTELS_ALL_DEST');
        		
        	}
        	//$hids = HotelHelper::getHotelAvailable( $checkin_date, $checkout_date);
        		
        	//$lists['h-id'] =$hids;
        
        	 
        	$model->init($lists);
        	$data=$model->getData();
        	$pagination=&$model->getPagination();
        	 
        	$view=$this->getView('Hotels','html','BookProView');
        	$view->assign('hotels',$data);
        	$view->assign('dest',$dest);
        	$view->assign('pagination',$pagination);
        	$view->display();
        
        }
        private function  processOrder(){
            JSession::checkToken('get') or jexit('Invalid Token');
            AImporter::model('customer','cgroup','order');
            $oModel=new BookProModelOrder();
            $order_id=JRequest::getVar('order_id');
            $oModel->setId($order_id);
            $order=$oModel->getObject();

            if($order->discount==0){

                $cModel=new BookProModelCustomer();
                $cModel->setId($order->user_id);
                $customer=$cModel->getObject();
                if($customer->cgroup_id){
                    $gModel=new BookProModelCGroup();
                    $gModel->setId($customer->cgroup_id);
                    $cgroup=$gModel->getObject();
                    if($cgroup->discount){
                        $discount=($order->total*$cgroup->discount)/100;
                        $newTotal=$order->total-$discount;
                        $order->total=$newTotal;
                        $order->discount=$discount;
                        $order->store();

                    }
                }
            }
            $view=&$this->getView('formpayment','html','BookProView');
            $view->assign('order_id',$order_id);
            $view->display();

        }



        public function displaymap(){

            $hotel_id=JRequest::getInt('hotel_id',null);
            $dest_id=JRequest::getInt('dest_id',null);
            $obj=new stdClass();
            if($hotel_id){
                if (! class_exists('BookProModeHotel')) {
                    AImporter::model('hotel');
                }
                $hotelModel=new BookProModelHotel();
                $hotelModel->setId($hotel_id);
                $hotel=$hotelModel->getObject();
                $obj->longitude=$hotel->longitude;
                $obj->latitude=$hotel->latitude;
                $obj->address=$hotel->address;
                $obj->title=$hotel->title;
                $obj->desc=$hotel->desc;
            }
            if($dest_id){
                if (! class_exists('BookProModeAirport')) {
                    AImporter::model('airport');
                }
                $destModel	=new BookProModelAirport();
                $destModel->setId($dest_id);
                $dest=$destModel->getObject();
                $obj->longitude=$dest->longitude;
                $obj->latitude=$dest->latitude;
                $obj->title=$dest->title;
                $obj->desc=$dest->desc;
            }
            $view=&$this->getView('googlemap','html','BookProView');
            $view->assign('obj',$obj);
            $view->display();
        }



        function listdestination()
        {

            $desfrom=JRequest::getVar( 'desfrom',0);
            $model = $this->getModel('BookPro');
            $dests = $model->getToAirportByFrom($desfrom);
            $return = "<?xml version=\"1.0\" encoding=\"utf8\" ?>";
            $return .= "<options>";
            $return .= "<option id='0'>".JText::_( 'TO' )."</option>";
            if(is_array($dests)) {
                foreach ($dests as $dest) {
                    $return .="<option id='".$dest->key."'>".JText::_($dest->value)."</option>";
                }
            }
            $return .= "</options>";
            echo $return;
            //$mainframe->close();
        }







    }
