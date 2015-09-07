<?php 
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: airticket.php  23-06-2012 23:33:14
 **/
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
AImporter::helper('bookpro', 'controller');


class BookProControllerFlight extends JControllerLegacy{

	var $_app;
	public function display($cachable = false, $urlparams = false){
		$document	= JFactory::getDocument();
		$vName	 = JRequest::getCmd('view', 'flight');
		switch ($vName) {
			case 'flight':
				$this->displaySearch();
				return;

		}
		JRequest::setVar('view', $vName);
		parent::display();
	}
	function displaySearch(){

		$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
		$cart->load();
		if (! class_exists('BookProModelAirport')) {
			AImporter::model('airport');
		}
		$from_to=array();

		$dmodel=new BookProModelAirport();
		
		$from_to[]=$dmodel->getObject($cart->from);

		$dmodel=new BookProModelAirport();
		
		$from_to[]=$dmodel->getObject($cart->to);

		$view=&$this->getView('flight','html','BookProView');
		$view->assign('from_to',$from_to);
		$view->assign('cart',$cart);
		//die;
		$view->display();
	}
   
	public function search(){
		$app=JFactory::getApplication();
		$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
		$cart->load();
		$app=JFactory::getApplication();
		$input = JFactory::getApplication()->input;
		
		$from= JRequest::getInt('desfrom',null);
		$to= JRequest::getInt('desto',null);
		$start=JRequest::getVar('start',null);
		$end=JRequest::getVar('end',null);
		$adult=JRequest::getInt('adult',0);
		$children=JRequest::getInt('children',0);
		$infant=JRequest::getInt('infant',0);
		$roundtrip=JRequest::getVar('roundtrip','0');
		
		$cart->roundtrip=$roundtrip;
		if($start)
			$cart->start=$start;

		if($roundtrip=='0'){
			$cart->end=null;
		}else{
			$cart->end=$end;
		}

		if($from){
			$cart->from=$from;
		}
		if($to)
			$cart->to=$to;
		
		
		$min_price = $input->get('min_price',0);
		
		if (!$cart->min_price) {
			$cart->min_price = 10;
		}
		
		if ($min_price) {
			$cart->min_price = $min_price;
		}
		
		$max_price = $input->get('max_price',0);
		if (!$cart->max_price) {
			$cart->max_price = 2000;
		}
		
		if ($max_price) {
			$cart->max_price = $max_price;
		}
		
		$min_time = $input->get('min_time',0,'int');
		if (!$cart->min_time) {
			$cart->min_time = 0;
		}
		
		
		
		if ($min_time) {
			$cart->min_time = $min_time;
		}
		
		$max_time = $input->get('max_time',0,'int');
		
		if (!$cart->max_time) {
			$cart->max_time = 24;
		}
		
		if ($max_time) {
			$cart->max_time = $max_time;
		}
		
		$cart->children=$children;
		$cart->adult=$adult;
		$cart->infant=$infant;
		$cart->saveToSession();
		
		
		$query=JURI::buildQuery(
				array('option'=>'com_bookpro',
						'from'=>$cart->from,'to'=>$cart->to,
						'view'=>'flight',
						'controller'=>'flight',
						'Itemid'=>JRequest::getVar('Itemid')));
		$this->setRedirect(JURI::base().'index.php?'.$query);
		return;

	}
	public function login()
	{
		//JSession::checkToken('post') or jexit(JText::_('JInvalid_Token'));
	
		$mainframe=JFactory::getApplication('site');
		$return = JRequest::getVar('return', '', 'method', 'base64');
		$return1 = JRequest::getVar('return', '', 'method', 'base64');
		$return = base64_decode($return);
		$options = array();
		$options['remember'] = JRequest::getBool('remember', false);
		$options['return'] = $return;
		$credentials = array();
		$credentials['username'] = JRequest::getVar('username', '', 'method', 'username');
		$credentials['password'] = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
		$result= $mainframe->login($credentials, $options);
	
		if ($result) {
			// Success
			$config = &AFactory::getConfig();
			$user = JFactory::getUser();
	
			$mainframe->redirect('index.php?option=com_bookpro&controller=flight&task=confirm');
		}else{
	
			$mainframe->redirect('index.php?option=com_bookpro&controller=flight&task=confirm');
		}
	}
	function ticket(){
		
	
		AImporter::model('flights','flight','passengers','orderinfos','order');
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
	function __construct($config = array())
	{
		parent::__construct($config);
		if (! class_exists('BookProModelApplication')) {
			AImporter::model('application');
		}
		$this->_app = new BookProModelApplication();
	}
	function totalBaggageAdult($adult,$baggages,$flight_id){
		$total = 0;
		foreach ($baggages as $baggage){
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select('bag.price');
			$query->from('#__bookpro_baggage AS bag');
			$query->join('LEFT', '#__bookpro_airline AS airline ON bag.airline_id = airline.id');
			$query->join('LEFT', '#__bookpro_flight AS flight ON flight.airline_id = airline.id');
			$query->where('bag.qty='.$baggage);
			if ($flight_id) {
				$query->where('flight.id='.$flight_id);
			}
			
			$db->setQuery($query);
			$bag = $db->loadResult();
			$total += (int) $bag;
		}
		
		return $total;
	}
	function addToCart(){
		/*
		 * Add cart khi chon 1 flight rate o ket qua search flight
		* */
		AImporter::helper('flight');
		$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
		$cart->load();
		$adult_tax = 0;
		$child_tax = 0;
		$infant_tax = 0;
		$total = 0;
		$data = array();
		$baggages = $cart->baggage;
		
		if ($cart->rate_id) {
				
			$flight = FlightHelper::getFlightByCart($cart->rate_id);
			
			$flight->roundtrip = 0;
			$flight->depart_date = $cart->start;
			$flight->adult_number = $cart->adult;
			$flight->adult_price = $cart->adult*$flight->adult;
			if ($cart->adult) {
				$adult_tax += $this->totalBaggageAdult($cart->adult, $baggages['adult'], $flight->flight_id);
				$adult_tax  += (int) $flight->adult_taxes+(int) $flight->adult_fees;
			}
			
				
			$flight->child_number = $cart->children;
			$flight->child_price = $cart->children*$flight->child;
			if ($cart->children) {
				$child_tax += $this->totalBaggageAdult($cart->children, $baggages['children'], $flight->flight_id);
				$child_tax += (int) $flight->child_taxes+(int) $flight->child_fees;
			}
			
			$flight->infant_number= $cart->infant;
			$flight->infant_price = $cart->infant*$flight->infant;
			if ($cart->infant) {
				$infant_tax += $this->totalBaggageAdult($cart->infant, $baggages['infant'], $flight->flight_id);
				$infant_tax +=(int) $flight->infant_taxes+(int) $flight->infant_fees;
			}	
			
				
			$total += FlightHelper::getTotalPrice($flight, $cart->adult, $cart->children, $cart->infant);
			$data[] = $flight;
		}	
		if ($cart->roundtrip = "1") {
				
				
			if ($cart->return_rate_id) {
				$return_flight = FlightHelper::getFlightByCart($cart->return_rate_id);
		
				$return_flight->roundtrip = 1;
				$return_flight->depart_date = $cart->end;
				
				$return_flight->adult_number = $cart->adult;
		
				$return_flight->adult_price = $cart->adult*$return_flight->adult_roundtrip;
				if ($cart->adult) {
					$adult_tax += $this->totalBaggageAdult($cart->adult, $baggages['adult'], $return_flight->flight_id);
					$adult_tax +=(int) $return_flight->adult_taxes+(int) $return_flight->adult_fees;
				}
				
				$return_flight->child_number = $cart->children;
				$return_flight->child_price = $cart->children*$return_flight->child_roundtrip;
				if ($cart->children) {
					$child_tax += $this->totalBaggageAdult($cart->children, $baggages['children'], $return_flight->flight_id);
					$child_tax +=(int) $return_flight->child_taxes+(int) $return_flight->child_fees;
				}
				
				$return_flight->infant_number = $cart->infant;
				$return_flight->infant_price = $cart->infant*$return_flight->infant_roundtrip;
				if ($cart->infant) {
					$infant_tax += $this->totalBaggageAdult($cart->infant, $baggages['infant'], $return_flight->flight_id);
					$infant_tax +=(int) $return_flight->infant_taxes+(int) $return_flight->infant_fees;
				}
				
				$total += FlightHelper::getTotalPrice($return_flight, $cart->adult, $cart->children, $cart->infant,(int) $cart->roundtrip);
				$data[] = $return_flight;
			}
				
		}
		$cart->adult_tax = $adult_tax;
		$cart->child_tax = $child_tax;
		$cart->infant_tax = $infant_tax;
		
		$cart->sum=$total;
		$cart->total=$total+$adult_tax+$child_tax+$infant_tax;
		
		$cart->saveToSession();
		return $data;
	}
	public function addcart(){
		/*
		 * Add cart khi chon 1 flight rate o ket qua search flight
		 * */		
		AImporter::helper('flight');
		$app = JFactory::getApplication();
		$input = $app->input;
		$config=AFactory::getConfig();
		$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
		$cart->load();
		$rate_id = $input->get('rate_id',0,'int');
		$roundtrip = $input->get('roundtrip',0,'int');
		
		if ($rate_id) {
			$cart->rate_id = $rate_id;
		}
		$return_rate_id = $input->get('return_rate_id',0,'int');
		if ($return_rate_id) {
			$cart->return_rate_id = $return_rate_id;
		}
		$cart->saveToSession();
		
		$data = $this->addToCart();
		
		$layout = new JLayoutFile('summary', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');
		$html = $layout->render($data);
		echo $html;
		die();
	}
	public function allairline(){
		/*
		 * Tim kiem tat ca flight cho truong hop one way
		 * */
		AImporter::helper('flight');
		$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
		$cart->load();
		$list=array();
		$list['desfrom']=$cart->from;
		$list['desto']=$cart->to;
		$list['adult']= $cart->adult;
		$list['children'] = $cart->children;
		$list['infant'] = $cart->infant;
		$list['order']='fdate';
		$list['ordering']='ASC';
		$list['min_price'] = $cart->min_price;
		$list['max_price'] = $cart->max_price;
		$list['airline'] = $cart->airline;
		
		$list['min_time'] = $cart->min_time;
		
		$list['max_time'] = $cart->max_time;
		$list['depart_date']=$cart->start;
		//$count = FlightHelper::getAllAirline();
		if ((int) $cart->roundtrip == 0) {
			$flights = FlightHelper::getFlightSearch($list);
		}else{
			$flights = array();
		}
		
		
		
		$layout = new JLayoutFile('all_airline', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');
		$html = $layout->render(count($flights));
		echo $html;
	}
	
	function addcartconfirm(){
		/*
		 * Add passenger vao cart khi chon passenger o form
		 * */
		$app = JFactory::getApplication();
		$input = $app->input;
		$config=AFactory::getConfig();
		$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
		$cart->load();
		$baggages = $input->get('baggage',array(),'array');
		$psform = $input->get('psform',array(),'array');
		$pasform = $this->addPsFormBaggage($psform, $baggages);
		$passengers = $this->convertArray($pasform);
		
		$cart->baggage = $baggages;
		$cart->passengers = $passengers;
		$cart->saveToSession();
		
		$data = $this->addToCart();
		
		$layout = new JLayoutFile('summary_booking', $basePath = JPATH_ROOT .'/components/com_bookpro/layouts/flight');
		$html = $layout->render($data);
		echo $html;
		die();
	}
public function reserve(){
/*Sau khi chon 1 flight va an nut continue se di vao day
 * View flight confirm
 * */	
		//JRequest::setVar('view','payment');
		$app = JFactory::getApplication();
		$input = $app->input;
		$config = JComponentHelper::getParams('com_bookpro');
		$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
		$cart->load();
		
		/*
		if($config->get('anonymous')){
			$this->display_booking_form();
				
		} else {
			$user=JFactory::getUser();
			if($user->id==0){
				$return = base64_encode(JURI::root().'index.php?option=com_bookpro&controller=flight&task=display_booking_form');
				$return= JURI::root().'index.php?option=com_bookpro&view=login&return='.$return;
				$this->setRedirect($return);
				return;
			}else{
				$this->display_booking_form();

			}
		}*/
		$this->display_booking_form();
	}

function display_booking_form(){
		if (! class_exists('BookProModelFlight')) {
			AImporter::model('flight');
		}
		if (! class_exists('BookProModelCustomer')) {
			AImporter::model('customer');
		}
		AImporter::helper('flight');
		$aaa=$this->_app->getObjectByCode('FLIGHT');

		$view=&$this->getView('flightconfirm','html','BookProView');
		$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
		$cart->load();
		
		$user = JFactory::getUser();
		if ($user->id) {
			$customerModel=new BookProModelCustomer();
			$customer = $customerModel->getObjectByUserId();
		}else{
			
		}
		
	
		
		
		$dispatcher    = JDispatcher::getInstance();
		require_once (JPATH_SITE.'/administrator/components/com_bookpro/helpers/plugin.php');
		$payment_plugins = PluginHelper::getPluginsWithEvent( 'onBookproGetPaymentPlugins' );
		$plugins = array();
		if ($payment_plugins)
		{
			foreach ($payment_plugins as $plugin)
			{
				 
				$results = $dispatcher->trigger( "onBookproGetPaymentOptions", array( $plugin->element, $order ) );
		
				if (in_array(true, $results, true))
				{
					$plugins[] = $plugin;
				}
			}
		}
		if (count($plugins) == 1)
		{
			$plugins[0]->checked = true;
			ob_start();
			$this->getPaymentForm( $plugins[0]->element );
			$html = json_decode( ob_get_contents() );
			ob_end_clean();
			$view->assign( 'payment_form_div', $html->msg );
		}
		$view->assign('plugins', $plugins);
		
		
		
		$view->assign('cart',$cart);
		$view->assign('customer',$customer);
		$view->assign('flights',$flightArr);
		$view->display();

	}
	function getPaymentForm($element='')
	{
	
		$values = JRequest::get('post');
		$html = '';
		$text = "";
		$user = JFactory::getUser();
		if (empty($element)) {
			$element = JRequest::getVar( 'payment_element' );
		}
		$results = array();
		$dispatcher	= JDispatcher::getInstance();
		JPluginHelper::importPlugin ('bookpro');
	
		$results = $dispatcher->trigger( "onBookproGetPaymentForm", array( $element, $values ) );
		for ($i=0; $i<count($results); $i++)
		{
		$result = $results[$i];
			$text .= $result;
		}
	
		$html = $text;
	
		// set response array
		$response = array();
		$response['msg'] = $html;
	
		// encode and echo (need to echo to send back to browser)
		echo json_encode($response);
	
		return;
	}
	function ajaxsearch(){
/* Ham tim kiem va hien thi flight
 * Gom ca 2 truong hop roundtrip va return
 * */ 
		
		AImporter::helper('flight','airport');
		$view=&$this->getView('ajaxflight','html','BookProView');
		$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
		$cart->load();
		if (! class_exists('BookProModelFlights')) {
			AImporter::model('flights');
		}
		if (! class_exists('BookProModelOrderInfo')) {
			AImporter::model('orderinfo');
		}
		$input = JFactory::getApplication()->input;
		
		$airline = $input->get('airline',array(),'array');
		if (!empty($airline)) {
			$cart->airline = $airline;
		}
		$start=$input->get('start',0);
		
		if($start)
			$cart->start=$start;
		
		$min_price = $input->get('min_price',0);
		
		if (!$cart->min_price) {
			$cart->min_price = 10;
		}
		if ($min_price) {
			$cart->min_price = $min_price;
		}
		
		$max_price = $input->get('max_price',0);
		if (!$cart->max_price) {
			$cart->max_price = 2000;
		}
		
		if ($max_price) {
			$cart->max_price = $max_price;
		}
		
		$min_time = $input->get('min_time',0,'int');
		if (!$cart->min_time) {
			$cart->min_time = 0;
		}
		
		
		
		if ($min_time) {
			$cart->min_time = $min_time;
		}
		
		$max_time = $input->get('max_time',0,'int');
		
		if (!$cart->max_time) {
			$cart->max_time = 24;
		}
		
		if ($max_time) {
			$cart->max_time = $max_time;
		}
		
		
		$infomodel=new BookProModelOrderInfo();
		$fmodel=new BookProModelFlights();
		$list=array();
		$list['desfrom']=$cart->from;
		$list['desto']=$cart->to;
		$list['adult']= $cart->adult;
		$list['children'] = $cart->children;
		$list['infant'] = $cart->infant;
		$list['order']='fdate';
		$list['ordering']='ASC';
		$list['min_price'] = $cart->min_price;
		$list['max_price'] = $cart->max_price;
		$list['airline'] = $cart->airline;
		
		$list['min_time'] = $cart->min_time;
		
		$list['max_time'] = $cart->max_time;
		$list['depart_date']=$cart->start;
		
		
		$flights = FlightHelper::getFlightSearch($list,(int) $cart->roundtrip);
		
		
		$view->assign('flights',$flights);

		if($cart->roundtrip=='1'){
			$end=JRequest::getVar('end',null);
			if($end)
				$cart->end=$end;
			$fmodel=new BookProModelFlights();
			$list=array();
			$list['desfrom']=$cart->to;
			$list['desto']=$cart->from;
			$list['order']='fdate';
			$list['ordering']='ASC';
			$list['adult']= $cart->adult;
			$list['children'] = $cart->children;
			$list['infant'] = $cart->infant;
			
			$list['min_price'] = $cart->min_price;
			$list['max_price'] = $cart->max_price;
			$list['airline'] = $cart->airline;
			$list['min_time'] = $cart->min_time;
			$list['max_time'] = $cart->max_time;
			
			$list['depart_date']=$cart->end;
			
			$returnflights=FlightHelper::getFlightSearch($list,(int) $cart->roundtrip,true);
			
			
			$view->assign('returnFlights',$returnflights);
		}
		
		$cart->saveToSession();
		
		
		if (! class_exists('BookProModelAirport')) {
			AImporter::model('airport');
		}
		$from_to=array();
		
		$dmodel=new BookProModelAirport();
		
		$from_to[]=$dmodel->getObject($cart->from);
		
		$dmodel=new BookProModelAirport();
		
		$from_to[]=$dmodel->getObject($cart->to);
		$view->assign('from_to',$from_to);
		$view->assign('cart',$cart);
		$view->display();
	}
	function findDestination()
	{
		$from=JRequest::getVar('desfrom',0);
		$db = JFactory::getDBO();
		$query =$db->getQuery(true);
		$query->select('f.desto AS `key` ,`d2`.`code` AS `code`,`d2`.`value` AS `value`,`d2`.`title` AS `title`,`d2`.`lft` AS `t_order`');
		$query->select('CONCAT(`d2`.`title`,'.$db->quote('-').',`d2`.`value`) AS dtitle');
		$query->from('#__bookpro_flight AS f');
		$query->leftJoin('#__bookpro_dest AS d2 ON f.desto =d2.id');
		$query->where(array('f.desfrom='.$from,'f.state=1'));
		$query->group('f.desto');
		$query->order('t_order');
		$sql = (string)$query;
		$db->setQuery($sql);
		$dests = $db->loadObjectList();
		
		$return = '<option value="">'.JText::_('COM_BOOKPRO_FLIGHT_TO').'</option>';
		if(is_array($dests)) {
			foreach ($dests as $dest) {
				$return .="<option value='".$dest->key."'>".$dest->title.'-'.$dest->value."</option>";
			}
		}
		echo trim($return);
		die();
	
	}
	private function convertArray($array) {
		$keys = array_keys ( $array );
	
		
		$values = array_values ($array );
		
		
		$results = array ();
	
		$k = count($values[0]);
		
		for($j = 0; $j < $k; $j ++) {
			$result = array ();
			for($i = 0; $i < count ( $values ); $i ++) {
				
				$result[$keys[$i]] = $values [$i][$j];
			}
			$results [] = $result;
		}
		return $results;
	}
	function addPsFormBaggage($psform,$baggages,$name='bag_qty',$return_name = 'return_bag_qty'){
		$pags = array();
		foreach ($baggages as $key=>$value){
			if (!empty($value)) {
				foreach ($value as $key1=>$value1){
					$bags[] = $value1;
				}
			}
		}
		$psform[$name] = $bags;
		$psform[$return_name] = $bags;
		return $psform;
	}
	function confirm()
	{
		$config=AFactory::getConfig();
		if (! class_exists('BookProModelPassenger')) {
			AImporter::model('passenger');
		}
		if (! class_exists('BookProModelFlight')) {
			AImporter::model('flight');
		}
		if (! class_exists('BookProModelOrderInfo')) {
			AImporter::model('orderinfo');
		}
		if (! class_exists('BookProModelOrder')) {
			AImporter::model('order');
		}
		if (! class_exists('BookProModelCustomer')) {
			AImporter::model('customer');
		}
		$app = &JFactory::getApplication();
		$cmodel=new BookProModelCustomer();
		
		$orderModel = new BookProModelOrder();
		$orderModelInfo= new BookProModelOrderInfo();
		$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
		$cart->load();
		$post=JRequest::get('post');
		$user = JFactory::getUser();
		if($user->get('guest')){
			JTable::addIncludePath(JPATH_COMPONENT_FRONT_END.DS.'tables');
			$customer = JTable::getInstance('customer', 'table');
			$customer->init();
			$customer->bind($post);
			//$customer->id = null;
			$customer->state = 1;
			$customer->store();
			$cid=$customer->id;
			
		} else {
			$cid = $cmodel->store($post);
			if($err=$cmodel->getError()){
				$app->enqueueMessage($err,'error');
			}
		}
		
		AImporter::helper('flight','ordertype');
		
		$input = $app->input;
		$baggage = $input->get('baggage',array(),'array');
		
		$passenger=$input->get('psform',array(),'array');
		$payment_plugin = $input->getString('payment_plugin', '', 'bookpro');
		$psform = $this->addPsFormBaggage($passenger, $baggage);
		
		$passengers=$this->convertArray($psform);
		
		$cart->passengers = $passengers;
		
		//$passengers = $cart->passengers;
		
		
		
		
		for ($i = 0;$i < count($passengers);$i++){
			$passengers[$i]['route_id'] = $cart->rate_id;
			$passengers[$i]['start'] = JFactory::getDate($cart->start)->toSql();
		}
		
		if($cart->roundtrip=='1'){
		
		
			
			for ($i = 0;$i < count($passengers);$i++){
					
				$passengers[$i]['return_rate_id'] = $cart->return_rate_id;
				$passengers[$i]['return_start'] = JFactory::getDate($cart->end)->toSql();
					
					
			}
		}
	
		
		
		$cart->saveToSession();
		OrderType::init();
		$order=array('id'=>0,
				'type'=>OrderType::$FLIGHT->getValue(),
				'user_id'=>$cid,
				'total'=>$cart->total,
				'subtotal'=>$cart->sum,
				'pay_status'=>'PENDING',
				'notes'=>$cart->notes,
				'tax'=>$cart->tax,
				'order_status'=>'PENDING',
				'service_fee'=>$cart->service_fee
					
		);
		$orderid = $orderModel->store($order);
		
	   //save passenger
		
		for($i=0; $i< count($passengers); $i++){
			$params = array();
			$passengers[$i]['order_id']=$orderid;
			$params['passenger'] = $passengers[$i];
			$params['order'] = $order;
			$params['order']['id'] = $orderid;
			$passengers[$i]['params'] = json_encode($params);
			
			
			$pModel = new BookProModelPassenger();
			
			$pModel->store($passengers[$i]);
			if($err=$pModel->getError()){
				$app->enqueueMessage($err,'error');
				$app->redirect(JURI::base());
				exit;
			}
		}
		
		
		$cart->clear();
		$this->setRedirect(JURI::base().'index.php?option=com_bookpro&controller=payment&task=process&payment_plugin='.$payment_plugin.'&order_id='.$orderid.'&'.JSession::getFormToken().'=1');
		return;

	}

}