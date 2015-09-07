<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
AImporter::model('buses','customer');
AImporter::helper('html','orderstatus');
class BookProControllerBus extends JControllerLegacy{

	var $appObj=null;

	public function BookProControllerBus(){
		parent::__construct();
		if (! class_exists('BookProModelApplication')) {
			AImporter::model('application');
		}
		$aModel=new BookProModelApplication();
		$this->appObj=$aModel->getObjectByCode('BUS');

	}
	public function display($cachable = false, $urlparams = false){
		$document	= JFactory::getDocument();
		$vName	 = JRequest::getCmd('view', 'login');
		$user=JFactory::getUser();
		switch ($vName) {
			case 'bustrips':
				$this->displaySearch();
				return;

		}
		JRequest::setVar('view', $vName);
		parent::display();


	}
	
	function booking()
    {
        $this->setRedirect('index.php?option=com_bookpro&view=bustrips&layout=booking');
        return;
    }
	function ticket(){
	
	
		AImporter::model('bustrips','bustrip','passengers','orderinfos','order');
		$order_number = JRequest::getVar('order_number','');
		$order = new BookProModelOrder();
		$this->order = $order->getByOrderNumber($order_number);
		if ($this->order->id) {
			$link = 'index.php?option=com_bookpro&view=ticket&layout=ticket&order_number='.$order_number.'&Itemid='.JRequest::getVar('Itemid');
			$this->setRedirect($link,false);
		}else {
			$link = 'index.php?option=com_bookpro&view=ticket&Itemid='.JRequest::getVar('Itemid');
			$msg = JText::_('COM_BOOKPRO_TICKET_INVALID');
			$this->setRedirect($link,$msg);
		}
	
	}
	function smscron(){
		$log=JLog::getInstance('cron.txt');
		$log->addEntry(array('comment'=>'SMS cron start'));
	}
	
	function listdestination()
	{
		$from=JRequest::getVar('from',0);
		$db = JFactory::getDBO();
		$query =$db->getQuery(true);
		$query->select('f.to AS `key` ,`d2`.`title` AS `title`,`d2`.`ordering` AS `t_order`');
		$query->from('#__bookpro_bustrip AS f');
		$query->leftJoin('#__bookpro_dest AS d2 ON f.to =d2.id');
		$query->where(array('f.from='.$from,'f.state=1'));
		$query->group('f.to');
		$query->order('t_order');
		$sql = (string)$query;
		$db->setQuery($sql);
		$dests = $db->loadObjectList();
			
		$return = "<?xml version=\"1.0\" encoding=\"utf8\" ?>";
		$return .= "<options>";
		$return .= "<option id='0'>".JText::_( 'TO' )."</option>";
		if(is_array($dests)) {
			foreach ($dests as $dest) {
				$return .="<option id='".$dest->key."'>".JText::_($dest->title)."</option>";
			}
		}
		$return .= "</options>";
		echo $return;
	}

	/**
	 * Find destination for ajax call
	 */
	function findDestination()
	{
		$from=JRequest::getVar('desfrom',0);
		$db = JFactory::getDBO();
		$query =$db->getQuery(true);
		$query->select('d2.id AS `key` ,`d2`.`title` AS `title`,`d2`.`ordering` AS `t_order`');
		$query->from('#__bookpro_bustrip AS f');
		$query->leftJoin('#__bookpro_dest AS d2 ON f.to =d2.id');
		$query->where(array('f.from='.$from,'f.state=1'));
		$query->group('f.to');
		$query->order('t_order');
		$sql = (string)$query;
		$db->setQuery($sql);
		$dests = $db->loadObjectList();
			
		$return = '<option value="">'.JText::_('COM_BOOKPRO_BUSTRIP_TO').'</option>';
		if(is_array($dests)) {
			foreach ($dests as $dest) {
				$return .="<option value='".$dest->key."'>".$dest->title."</option>";
			}
		}
		echo trim($return);
		die();

	}
	function findPickup()
	{
		$from=JRequest::getVar('desfrom',0);
		$db = JFactory::getDBO();
		$query =$db->getQuery(true);
		$query->select('dest.*');
		$query->from('#__bookpro_dest AS dest');
        $query->where('dest.bus=1');
	    $db->setQuery($query);
        $options=$db->loadObjectList();


        $children = array();
        if(!empty($options)){

            $children = array();

            // First pass - collect children
            foreach ($options as $v)
            {
                $pt = $v->parent_id;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }

        }

        $options=static::treeReCurseCategories($from,'' , array(),$children,99,0,0);


        $return = '<option value="">'.JText::_('COM_BOOKPRO_SELECT_PICKUP').'</option>';
        if(is_array($options)) {
            foreach ($options as $pickup) {
                $return .="<option value='".$pickup->id."'>".$pickup->treename."</option>";
            }
        }
        echo trim($return);
		die();

	}
	function findTo()
	{
        $from=JRequest::getVar('desfrom',0);
        AImporter::model('bustrips');
        $model_bustrips=JModelLegacy::getInstance('Bustrips','BookproModel');
        $app=JFactory::getApplication();
        $app->setUserState('bustrip_filter_bustrip_id',null);
        $app->setUserState('bustrip_filter_from',$from);
        $app->setUserState('bustrip_filter_to',null);
        $listBusTrip=$model_bustrips->getItems();


        $options=array();
        foreach($listBusTrip as $busTrip)
        {
            $options[$busTrip->dest_to_parent_id]="<option value='".$busTrip->dest_to_parent_id."'>-".$busTrip->dest_to_parent_title."</option>";
        }
        $options=implode(' ',$options);
        echo $options;
		die();

	}
	function findDropoff()
	{
        $input=JFactory::getApplication()->input;
        $title=$input->get('title','','string');
		$from=JRequest::getVar('desto',0);
		$db = JFactory::getDBO();
		$query =$db->getQuery(true);
		$query->select('dest.*');
		$query->from('#__bookpro_dest AS dest');
        $query->where('dest.bus=1');
	    $db->setQuery($query);
        $options=$db->loadObjectList();


        $children = array();
        if(!empty($options)){

            $children = array();

            // First pass - collect children
            foreach ($options as $v)
            {
                $pt = $v->parent_id;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }

        }

        $options=static::treeReCurseCategories($from,'' , array(),$children,99,0,0);


        $return = '<option value="">'.$title.'</option>';
        if(is_array($options)) {
            foreach ($options as $pickup) {
                $return .="<option value='".$pickup->id."'>".$pickup->treename."</option>";
            }
        }
        echo trim($return);
		die();

	}
    public static function treeReCurseCategories($id, $indent, $list, &$children, $maxlevel = 9999, $level = 0, $type = 1)
    {
        if (@$children[$id] && $level <= $maxlevel)
        {
            foreach ($children[$id] as $v)
            {
                $id = $v->id;

                if ($type)
                {
                    $pre = '<sup>|_</sup>&#160;';
                    $spacer = '.&#160;&#160;&#160;&#160;&#160;&#160;';
                }
                else
                {
                    $pre = '- ';
                    $spacer = '&#160;&#160;';
                }

                if ($v->parent_id == 0)
                {
                    $txt = $v->title;
                }
                else
                {
                    $txt = $pre . $v->title;
                }

                $list[$id] = $v;
                $list[$id]->treename = $indent . $txt;
                $list[$id]->children = count(@$children[$id]);
                $list = static::treeReCurseCategories($id, $indent . $spacer, $list, $children, $maxlevel, $level + 1, $type);
            }
        }

        return $list;
    }






	function displaySearch(){
		AImporter::helper('bus');
		$cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
		$cart->load();
		$app=JFactory::getApplication();
			
		$view=&$this->getView('bustrips','html','BookProView');
		$from_to=BusHelper::getRoutePair($cart->from, $cart->to);
		$view->assign('return_trips',$return_trips);
		$view->assign('countries',$countries);
		$view->assign('buses',$buses);
		$view->assign('from_to',$from_to);
		$view->assign('cart',$cart);
		$view->display();

	}
	function search(){
		//make SEF
		JSession::checkToken() or jexit('Invalid Token');
		$app=JFactory::getApplication();
		$cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
		$cart->load();
		$app=JFactory::getApplication();
		$input = $app->input;
		$from = $input->getInt('desfrom',0,'int');
		$to = $input->getInt('desto',0,'int');
		$pickup = $input->getString('pickup','','string');

		$dropoff = $input->getString('dropoff','','string');
		$start = $input->get('start',null);
		$end = $input->get('end',null);
        $adult = $input->getInt('adult',0);
        $children = $input->getInt('children',0,'int');
		$roundtrip = $input->getInt('roundtrip',0,'int');
		$cart->roundtrip=$roundtrip;
		if($start)
			$cart->start=$start;
		if($pickup)
			$cart->pickup=$pickup;

		if($dropoff)
			$cart->dropoff=$dropoff;

		if($roundtrip=='0'){
			$cart->end=null;
		}else{
			$cart->end=$end?$end:$cart->end;
		}

		if($from){
			$cart->from=$from;
		}
		if($to)
			$cart->to=$to;

		$cart->children=$children;
		$cart->adult=$adult;

		$cart->saveToSession();
		$query=JURI::buildQuery(
				array('option'=>'com_bookpro',
						'view'=>'bustrips',
						'layout'=>'search',
                        'Itemid'=>JRequest::getVar('Itemid'),
                        'from'=>$cart->from,'to'=>$cart->to
                    )
                );
		$this->setRedirect('index.php?'.$query);
		return;
	}
	function reserve(){
		$app = JFactory::getApplication();
		$input = $app->input;
		$config=AFactory::getConfig();
		$cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
		$cart->load();
		$bustrip_id=$input->get('bustrip_id');
		
		$listseat=$input->get('listseat_'.$bustrip_id);
		$cart->listseat=$listseat;
		if($cart->roundtrip==1){
			$return_bustrip_id=$input->get('return_bustrip_id');
			$returnlistseat=$input->get('returnlistseat_'.$return_bustrip_id);
			$cart->returnlistseat=$returnlistseat;
			$cart->return_bustrip_id=$return_bustrip_id;
		}
		
		$pGroup = $input->get('pGroup',array(),'array');
		$pGender= $input->get('pGender',array(),'array');
		$pFirstname = $input->get('pFirstname',array(),'array');
		$pBirthday = $input->get('pBirthday',array(),'array');
		$pMiddlename = $input->get('pMiddlename',array(),'array');
		$pPassport = $input->get('pPassport',array(),'array');
		
		$pCountry = $input->get('pCountry',array(),'array');
		$pSeat = $input->get('pSeat',array(),'array');
		$pReturnSeat = $input->get('pReturnSeat',array(),'array');
		$pBag = $input->get('pBag',array(),'array');
		$pBagReturn = $input->get('pBagReturn',array(),'array');
		$passengers = array();
		for($i=0; $i< count($pFirstname); $i++){
			
			$passenger = array( 'gender'=>$pGender[$i],
					'firstname'=>$pFirstname[$i],
					'lastname'=>$pMiddlename[$i],
					'birthday'=>$pBirthday[$i]?$pBirthday[$i]:null,
					'passport'=>$pPassport[$i],
					'group_id'=>$pGroup[$i],
					'country_id'=>$pCountry[$i],
					'seat'=>$pSeat[$i],
					'bag_qty'=>$pBag[$i],
					'return_bag_qty'=>$pBagReturn[$i]
		
			);
			if((int) $cart->roundtrip == 1){
				$passenger['return_seat'] = $pReturnSeat[$i];
			}
			$passengers[] = $passenger;
			
		}
		$cart->passengers = $passengers;
		$cart->bustrip_id=$bustrip_id;
		
		$cart->saveToSession();
		
		//display confirmation
		$user=JFactory::getUser();


        $this->display_booking_form();
	}

	function display_booking_form(){
		if (! class_exists('BookProModelBustrip')) {
			AImporter::model('bustrip');
		}
		
		AImporter::helper('bus');
		
		
		$tripModel=new BookProModelBusTrip();
		$view=&$this->getView('busconfirm','html','BookProView');
		$cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
		$cart->load();
		$cmodel=new BookProModelCustomer();
		$cmodel->setIdByUserId();
		$customer=$cmodel->getObject();
		$bustrips=array();
		$passengers = $cart->passengers;
		$bustrip=BusHelper::getObjectFullById($cart->bustrip_id,$cart->start,(int) $cart->roundtrip);
		
		for ($i = 0;$i < count($passengers);$i++){
		
			$passengers[$i]['route_id'] = $cart->bustrip_id;
			$passengers[$i]['start'] = JFactory::getDate($cart->start)->toSql();
			$passengers[$i]['price'] = BusHelper::getPassengerPrice($bustrip->price, $passengers[$i]['group_id'],(int) $cart->roundtrip);
		
		}
		
		$bustrips[]=$bustrip;
		$cart->price=$bustrip->price;
		if($cart->roundtrip==1){
			$return_bustrip=BusHelper::getObjectFullById($cart->return_bustrip_id,$cart->end,(int) $cart->roundtrip);
			$cart->return_price=$return_bustrip->price;
			
			for ($i = 0;$i < count($passengers);$i++){
				$passengers[$i]['return_route_id'] = $cart->return_bustrip_id;
				$passengers[$i]['return_start'] = JFactory::getDate($cart->end)->toSql();
				$passengers[$i]['return_price'] = BusHelper::getPassengerPrice($return_bustrip->price, $passengers[$i]['group_id'],(int) $cart->roundtrip);
			}
			
			$bustrips[]=$return_bustrip;
			
			
			
		}
		
		
		for ($i = 0;$i < count($passengers);$i++){
			$agent = BusHelper::getAgentBustrip($bustrip->id);
			
			$passengers[$i]['price_bag'] = BusHelper::getPriceBag($passengers[$i]['bag_qty'], $agent->agent_id,0);
			
			if ($cart->roundtrip) {
				$agent = BusHelper::getAgentBustrip($return_bustrip->id);
				$passengers[$i]['return_price_bag'] = BusHelper::getPriceBag($passengers[$i]['return_bag_qty'], $agent->agent_id,1);
			}	
		}
		
		$subtotal=BusHelper::getPrice($cart->price,$passengers,(int) $cart->roundtrip);
		
		if ($cart->roundtrip == 1) {
			$subtotal+=BusHelper::getPrice($cart->return_price,$passengers,(int) $cart->roundtrip);
				
		}
		
		$total_bag = BusHelper::getPriceBags($passengers, $bustrip->id,0);
		
		if ($cart->roundtrip == 1) {
			$total_bag += BusHelper::getPriceBags($passengers, $return_bustrip->id,1);
		}
		
				
		$subtotal = $subtotal + $total_bag;
		$cart->passengers = $passengers;
		$cart->sum=$subtotal;
		$cart->service_fee=$this->appObj->service_fee*$subtotal/100;
		
		$cart->total=$subtotal;
		$cart->total_bag = $total_bag;
		
		$cart->saveToSession();
		$view->assign('cart',$cart);
		$view->assign('bustrips',$bustrips);
		$view->assign('customer',$customer);
		$view->display();

	}
	function confirm()
	{
		AImporter::helper('bus');
		$config=AFactory::getConfig();
		$app = JFactory::getApplication();
		$input = $app->input;
		$cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
		$cart->load();
		if (! class_exists('BookProModelPassenger')) {
			AImporter::model('passenger');
		}
		
		if (! class_exists('BookProModelOrder')) {
			AImporter::model('order');
		}
		$pModel = new BookProModelPassenger();
		$orderModel = new BookProModelOrder();

		$cmodel=new BookProModelCustomer();
		$post=JRequest::get('post');
		$post['id']=$post['customer_id'];
		$post['state'] = 1;
		$post['created'] = JFactory::getDate()->toSql();
		if($config->anonymous){
			JTable::addIncludePath(JPATH_COMPONENT_FRONT_END.DS.'tables');
			$customer = JTable::getInstance('customer', 'table');
			$customer->bind($post);
			$customer->id = null;
			$customer->store();
			$cid=$customer->id;
		}else {
			
			$cid=$cmodel->store($post);
			if($err=$cmodel->getError()){
				$app->enqueueMessage($err,'error');
				$app->redirect(JURI::base());
				exit;
			}
		}
		
		$cart->saveToSession();
		$passengers = $cart->passengers;
		OrderStatus::init();
		$order=array(
				'type'=>'BUS',
				'user_id'=>$cid,
				'total'=>$cart->total,
				'total_bag'=>$cart->total_bag,
				'subtotal'=>$cart->sum,
				'pay_method'=>'',
				'pay_status'=>'PENDING',
				'order_status'=>OrderStatus::$NEW->getValue(),
				'notes'=>$cart->notes,
				'tax'=>$cart->tax,
				'service_fee'=>$cart->service_fee
					
		);
		$orderid = $orderModel->store($order);
		if($err=$orderModel->getError()){
			$app->enqueueMessage($err,'error');
			$app->redirect(JURI::base());
			exit;
		}
		
	  //save passenger
       	
        for ($i = 0; $i < count($passengers); $i++) {
        	$passengers[$i]['order_id']=$orderid;
        	$passModel = new BookProModelPassenger();
        	$passModel->save($passengers[$i]);
        
        	if($err= $passModel->getError()){
        		$app->enqueueMessage($err,'error');
        		exit;
        	}
        }
		//
		
		$this->setRedirect(JURI::base().'index.php?option=com_bookpro&view=formpayment&order_id='.$orderid.'&'.JSession::getFormToken().'=1');
		return;
		
	
	}
	function displayPriceCalendar(){
		
		
		
	}

	function ajaxsearch(){
		$config = JComponentHelper::getParams('com_bookpro');
		$app=JFactory::getApplication();
		$input=$app->input;
		if (! class_exists('BookProModelBustrips')) {
			AImporter::model('bustrips');
		}

		AImporter::helper('bus');

		$view=&$this->getView('ajaxbustrip','html','BookProView');
		$cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
		$cart->load();

		if(!$cart->from){
			$app->enqueueMessage(JText::_('COM_BOOKPRO_SESSION_EXPIRED'));
			$app->redirect(JUri::root());
		}else {
			$start=JRequest::getVar('start',null);
			if($start)
				$cart->start=$start;
            $agent=JRequest::getVar('agent',null);
            if($agent)
                $cart->agent=$agent;
			$lists=array();
			$lists['from']= $cart->from;
			$lists['to']= $cart->to;
			$timestamp = strtotime($cart->start);
			$lists['depart_date']=$cart->start;
			if(JFactory::getDate()->format('Y-m-d')==JFactory::getDate($cart->start)->format('Y-m-d')){
				$lists['cutofftime']=$config->get('cutofftime');
			}

			$going_trip = BusHelper::getBustripSearch($lists,(int) $cart->roundtrip);

			$view->going_trips=$going_trip;



			$cart->saveToSession();
			$view->assign('cart',$cart);
			$view->display();

		}
	}


}