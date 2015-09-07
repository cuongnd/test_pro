<?php
    defined('_JEXEC') or die('Restricted access');
    jimport('joomla.application.component.controller');
    AImporter::model('hotels','rooms','hotel','application','customer','facilities');
    AImporter::helper('request','date','hotel');
    class BookProControllerHotel extends JControllerLegacy{

        public function __construct(){
            parent::__construct();
        }
        function display(){

            $vName	 = JRequest::getCmd('view', 'hotel');
            switch ($vName) {
                case 'hotels':
                    $this->search();
                    return ;
                case 'hotel':
                    $this->displayhotel();
                    return;
                case 'hotelzzz':
                    $this->displayhotel();
                    return;
            }
            JRequest::setVar('view', $vName);
            parent::display();

        }
        function guestform(){

            JSession::checkToken() or jexit('Invalid Token');
            AImporter::model('room');
            $app=JFactory::getApplication();
            $user=JFactory::getUser();
            $config=AFactory::getConfig();
            AImporter::helper('currency','hotel');
            $model = new BookProModelRoom();
            $cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
            $cart->load();
            $input=JFactory::getApplication()->input;
            $roomtype = $input->get('room_type',array(),'array');

            $cart->hotel_id=$input->get('hotel_id','','int');
            $cart->room_type=$roomtype;
            $checkin =$input->get('checkin_date');
            $checkout =$input->get('checkout_date');


            $cart->checkin_date = $checkin;
            $cart->checkout_date = $checkout;
            $no_room=$input->get('no_room',array(),'array');

            $adult=$input->get('adult',array(),'array');
            $child=$input->get('child',array(),'array');
            $hotel_id=$input->get('hotel_id');
            $facility_id = $input->get('facility_id',array(),'array');
            $cart->facility_id = $facility_id;
            $cart->array_adult=$adult;
            $cart->array_child=$child;
            $cart->no_room=$no_room;
            $cart->saveToSession();
            $this->check($step);



            //save  facilities
            //code here



        }
        private function check($step){
            $mainf=JFactory::getApplication();
            $user=JFactory::getUser();
            $config=AFactory::getConfig();
            if($config->anonymous){
                $this->display_booking_form();
            }else {
                if($user->id==0){
                    $return = base64_encode(JURI::root().'index.php?option=com_bookpro&controller=hotel&task=display_booking_form');
                    $return= JURI::root().'index.php?option=com_bookpro&view=login&return='.$return;
                    $this->setRedirect($return);
                    return;
                }else{
                    $this->display_booking_form();
                }

            }
        }
        function display_booking_form(){
            $appModel=new BookProModelApplication();
            $input = JFactory::getApplication()->input;
            $app=$appModel->getObjectByCode('HOTEL');
            $cart = &JModelLegacy::getInstance('HotelCart', 'bookpro');
            $cart->load();
            $model=new BookProModelHotel();
            $model->setId($cart->hotel_id);
            $hotel=$model->getObject();
            $roomtype=$cart->room_type;
            $no_room=$cart->no_room;

            $facility_id = $cart->facility_id;
            /*
            for ($i=0;$i<count($roomtype);$i++){
            if($no_room[$roomtype]=='0'){
            array_splice($no_room,$roomtype,1);
            array_splice($roomtype,$i,1);
            }
            }
            */
            $roomModel=new BookProModelRooms();

            $selectedRooms=array();
            for ($i=0;$i<count($roomtype);$i++){
                if($no_room[$roomtype[$i]]>0){
                    $selectedRooms[]=$roomtype[$i];
                }
            }

            $numberofdays=DateHelper::getCountDay($cart->checkin_date, $cart->checkout_date);

            $rooms=$roomModel->getRoomsByIDs($selectedRooms);

            $faciModel = new BookProModelFacilities();
            $facilities = $faciModel->getFacilitiesByIds($facility_id);
            $sum=0;
            $total_room=0;
            $totaladult=0;
            $totalchild=0;

            for ($i=0;$i<count($rooms);$i++){
                $room_price = 0;
                $room_total_price = 0;
                $rooms[$i]->no_room=(int) $no_room[$rooms[$i]->id];

                $price = HotelHelper::getRoomRateTotalPrice($rooms[$i]->id, $cart->checkin_date, $cart->checkout_date);

                //$rooms[$i]->price = $price;

                $sum +=$price*$rooms[$i]->no_room;
                $room_price += $price*$rooms[$i]->no_room;

                //$sum += $price;
                //$price = HotelHelper::getRoomRateTotalPrice($rooms[$i]->id, $cart->checkin_date, $cart->checkout_date);

                //$rooms[$i]->price = $price;


                $total_adult_price = 0;
                if (!empty($cart->array_adult[$rooms[$i]->id])) {
                    foreach ($cart->array_adult[$rooms[$i]->id] as $adult){

                        if ((int)$adult > $rooms[$i]->adult) {
                            $iadults = $adult - $rooms[$i]->adult;
                            $adult_prices = explode(",", $rooms[$i]->adult_price);

                            for ($k = 0;$k < $iadults;$k++){

                                $sum += (int) $adult_prices[$k]*$numberofdays;
                                $room_price += (int) $adult_prices[$k]*$numberofdays;
                                $total_adult_price += (int) $adult_prices[$k];
                            }

                        }
                    }
                }
                $total_child_price = 0;
                if (!empty($cart->array_child[$rooms[$i]->id])) {
                    foreach ($cart->array_child[$rooms[$i]->id] as $child){
                        $total_adult += (int) $child;
                        $child_prices = explode(",", $rooms[$i]->child_price);

                        for($ck = 0;$ck < (int) $child;$ck++){

                            $sum += (int) $child_prices[$ck]*$numberofdays;
                            $room_price += (int) $child_prices[$ck]*$numberofdays;
                            $total_child_price += (int) $child_prices[$ck];
                        }

                    }
                }



                $rooms[$i]->total_adult = array_sum($cart->array_adult[$rooms[$i]->id]);
                $rooms[$i]->total_child = array_sum($cart->array_child[$rooms[$i]->id]);
                $rooms[$i]->total_adult_price = $total_adult_price;
                $rooms[$i]->total_child_price = $total_child_price;
                $rooms[$i]->price = $room_price;
                //$rooms[$i]->total_price = $room_total_price;
                //$sum +=$price*$no_room[$i];
                $total_room+=$no_room[$rooms[$i]->id];
            }

            if (count($facilities)) {
                foreach ($facilities as $fac){
                    $sum += $fac->price;
                }
            }


            $cart->sum=$sum;
            $cart->total_room=$total_room;
            $cart->total=$sum;
            $cart->products=$rooms;
            $cart->facilities = $facilities;

            $cart->saveToSession();
            $user = JFactory::getUser();
            $groups = $user->getAuthorisedGroups();
            $config = AFactory::getConfig();
            
            $cmodel=new BookProModelCustomer();
            $cmodel->setIdByUserId();
            $customer=$cmodel->getObject();
           
            if (in_array( $config->agentUsergroup,$groups)) {
            	$customer = null;
            	
            }
         
            $view=&$this->getView('Hotelbook','html','BookProView');
            $view->assign('hotel',$hotel);
            $view->assign('rooms',$rooms);
            $view->assign('facilities',$facilities);
            $view->assign('customer',$customer);
            $view->assign('app',$app);
            $view->assign('cart',$cart);
            $view->display();

        }
        function getprice($room,$array_adult,$array_child)
        {

            $total=0;
            for($i=0;$i<count($array_adult);$i++)
            {

                $total+=HotelHelper::CaculatePriceRoom($room,$array_adult[$i],$array_child[$i]);
            }

            return $total;

        }
        function step2(){
			
            JSession::checkToken() or jexit('Invalid Token');
            $cart = &JModelLegacy::getInstance('HotelCart', 'bookpro');
            $cart->load();
            $config=AFactory::getConfig();
            $jconfig = JFactory::getConfig();
            $app = JFactory::getApplication();
            $post=JRequest::get('post');
            $post['id']=$post['customer_id'];

            $user = JFactory::getUser();
            //$config->anonymous
            if($user->get('guest')){

                JTable::addIncludePath(JPATH_COMPONENT_FRONT_END.'/tables');
                $customer = JTable::getInstance('customer', 'table');
                $customer->bind($post);
                $customer->id = null;
                $customer->state = 1;
                $customer->created=JFactory::getDate()->toSql();
                $customer->store();
                $cid=$customer->id;


            }else {

            	$groups = $user->getAuthorisedGroups();
            	if (in_array($config->agentUsergroup, $groups)) {
            		$cmodel=new BookProModelCustomer();
		            $cmodel->setIdByUserId();
		            $customer=$cmodel->getObject();
            		
            		
            		JTable::addIncludePath(JPATH_COMPONENT_FRONT_END.'/tables');
            		$customer = JTable::getInstance('customer', 'table');
            		$customer->bind($post);
            		$customer->id = null;
            		$customer->state = 1;
            		$customer->created=JFactory::getDate()->toSql();
            		$customer->created_by = $customer->id;
            		$customer->store();
            		$cid=$customer->id;
            		
            	}else{
            		$cmodel=new BookProModelCustomer();
            		$cid = $cmodel->store($post);
            		
            		if($err= $cmodel->getError()){
            		
            			$app->enqueueMessage($err,'Save customer error');
            		}
            		
            	}
               

                /*
                if ($cid && !$post['id']) {
                AImporter::helper('email');
                $config = AFactory::getConfig();
                $cmodel->setId($cid);
                $customer = $cmodel->getObject();
                $user = new JUser($customer->user);
                $data = $user->getProperties();
                $data['password_clear'] = $post['password'];
                $body_admin=$config->sendRegistrationsBodyAdmin;
                $body_customer=$config->sendRegistrationsBodyCustomer;

                $body_customer=$this->fillCustomer($body_customer,$customer,$data);
                $body_admin=$this->fillCustomer($body_admin,$customer,$data);

                if($config->sendRegistrationsEmails=1 || $config->sendRegistrationsEmails=3 )
                BookProHelper::sendMail($config->sendRegistrationsEmailsFrom,
                $config->sendRegistrationsEmailsFromname,
                $customer->email,
                $config->sendRegistrationsEmailsSubjectCustomer ,
                $body_customer,true);
                if($config->sendRegistrationsEmails=1 || $config->sendRegistrationsEmails=2 )
                BookProHelper::sendMail($config->sendRegistrationsEmailsFrom,
                $config->sendRegistrationsEmailsFromname,
                $config->sendRegistrationsEmailsFrom,
                $config->sendRegistrationsEmailsSubjectAdmin,
                $body_admin, $htmlMode);
                }
                */
            }

            //$person=JRequest::getVar('pfirstname',array());
            $room=JRequest::getVar('room_id',array());
            $room_person=array();

            $rooms=$cart->products;

            /*
            $notes=JRequest::getVar('notes',"");
            $cart->passenger=$passenger;
            $cart->notes=$notes;
            $order=array('notes'=>$notes);
            */
            $cart->saveToSession();
            if ($cid) {

                $order_id=$this->_storeOrder($cid);

                //apply discount for user group

                $hotel = JTable::getInstance('hotel', 'table');
                $hotel->load($cart->hotel_id);


                if($hotel->agent_comission >0){

                    $this->processHotelDiscount($order_id, $hotel->agent_comission);

                }
                //cart

                $this->setRedirect(JURI::base().'index.php?option=com_bookpro&view=formpayment&order_id='.$order_id.'&'.JSession::getFormToken().'=1');
            }else{
                $this->setRedirect('index.php?option=com_bookpro&controller=hotel&task=display_booking_form&'.JSession::getFormToken().'=1'.'&Itemid='.JRequest::getVar('Itemid'));
            }

            return;

        }

        private function fillCustomer($input,$customer,$data){
            $input = str_replace('{email}', $customer->email, $input);
            $input = str_replace('{name}', $data['name'], $input);
            $input = str_replace('{firstname}', $customer->firstname, $input);

            $input = str_replace('{lastname}', $customer->lastname, $input);
            $input = str_replace('{username}', $data['username'], $input);
            $input = str_replace('{password}', $data['password_clear'], $input);
            $input = str_replace('{mobile}', $customer->mobile, $input);
            $input = str_replace('{address}', $customer->address, $input);
            $input = str_replace('{city}', $customer->city, $input);
            $input = str_replace('{gender}', BookProHelper::formatGender($customer->gender), $input);
            $input = str_replace('{telephone}', $customer->telephone, $input);
            $input = str_replace('{states}', $customer->states, $input);
            $input = str_replace('{zip}', $customer->zip?'N/A':$customer->zip, $input);
            $input = str_replace('{country}', $customer->country_name, $input);
            return $input;
        }

        function getRoomIdSearch(){
            $model = new BookProModelHotels();
            $lists = array('state'=>1);
            $model->init($lists);
            $hotels = $model->getData();
            $hotelIds = array();
            foreach ($hotels as $hotel){

            }
        }

        function search(){

            AImporter::helper('hotel');
            $app = JFactory::getApplication();


            $menu = &JSite::getMenu();
            $active = $menu->getActive();
            if($active) {
                $this->products_per_row=$active->params->get('products_per_row',2);
                $this->count=$active->params->get('count',8);
            }else{
                $this->products_per_row=1;
                $this->count= $mainframe -> getCfg('list_limit');
            }



            $input = JFactory::getApplication()->input;

            $cart = &JModelLegacy::getInstance('HotelCart', 'bookpro');
            $cart->load();
            $checkin_date=$input->get('checkin');
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
            $lists['featured'] = $input->get('featured', $this->count, 'int');
            $lists['state'] = 1;
            $lists['start'] = $startMonth;
            $lists['end'] = $endMonth;
            $lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart',  0, 'int');
            $this->lists['order'] = ARequest::getUserStateFromRequest('order', 'title', 'cmd');
            $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('order_Dir', 'DESC', 'word');
            $lists['searchmode']=$searchmode;

            $hids = HotelHelper::getHotelAvailable( $checkin_date, $checkout_date);

            $lists['id'] =$hids;


            $model->init($lists);
            $data=$model->getData();
            $pagination=&$model->getPagination();

            $view=$this->getView('Hotels','html','BookProView');
            $view->assign('hotels',$data);
            $view->assign('pagination',$pagination);
            $view->display();

        }


        /*
        * au
        */
        private function _storeOrder($user_id){

            $cart = &JModelLegacy::getInstance('HotelCart', 'bookpro');
            $cart->load();
            if (! class_exists('BookProModelPassenger')) {
                AImporter::model('passenger');
            }
            if (! class_exists('BookProModelOrderInfo')) {
                AImporter::model('orderinfo');
            }
            if (! class_exists('BookProModelOrder')) {
                AImporter::model('order');
            }
            $orderModel=new BookProModelOrder();
            $orderinfoModel=new BookProModelOrderInfo();

            //store Order
            $order=array('id'=>0,
                'type'=>'HOTEL',
                'user_id'=>$user_id,
                'total'=>$cart->total,
                'subtotal'=>$cart->sum,
                'order_status'=>'NEW',
                'pay_status'=>'PENDING',
                'notes'=>$cart->notes,
                'tax'=>$cart->tax,
                'service_fee'=>$cart->service_fee

            );
            $user = JFactory::getUser();
            $config=AFactory::getConfig();
            $groups = $user->getAuthorisedGroups();
            if (in_array($config->agentUsergroup, $groups)) {
            	$cmodel=new BookProModelCustomer();
            	$cmodel->setIdByUserId();
            	$customer=$cmodel->getObject();
            	$order['created_by'] = $customer->id;
            }

            $order_id=$orderModel->store($order);
            if($err= $orderModel->getError()){

                $app->enqueueMessage($err,'Save order error');
                return false;
            }
            //save order info
            $rooms = $cart->products;


            foreach($rooms as $room)
            {
                $checkout_date = new JDate($cart->checkout_date);
                $orderinfo = array('id'=>0,
                    'type' => 'HOTEL_ROOM',
                    'order_id'=>$order_id,
                    'adult'=>array_sum($cart->array_adult[$room->id]),
                    'child'=>array_sum($cart->array_child[$room->id]),
                    'obj_id'=>$room->id,
                    'start'=>JFactory::getDate($cart->checkin_date)->toSql(),
                    'end'=>JFactory::getDate($checkout_date->modify('-1 day'))->toSql(),
                    'qty'=>$room->no_room,
                    'price'=>$room->price
                );

                $orderinfo_id = $orderinfoModel->store($orderinfo);
                if($err= $orderinfoModel->getError()){
                    $app->enqueueMessage($err,'Save order error');
                    return false;
                }
            }
            $cart->saveToSession();
            return  $order_id;
        }
        /**
        *
        * @param int $hotel_id
        * @param date $date_from
        * @param date $date_to
        * @return array of room
        */
        function getRoomAvailable($hotel_id,$date_from,$date_to){


        }
        function checkAvailable(){
            $app = JFactory::getApplication();
            $input = $app->input;
            $hotel_id = $input->get('hotel_id');
            $checkin = $input->get('checkin');
            $checkout = $input->get('checkout');
            AImporter::helper('hotel');
            $rooms = HotelHelper::getRoomAvailable($hotel_id, $checkin, $checkout);

            $cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
            $cart->load();
            $cart->checkin_date = $checkin;
            $cart->checkout_date = $checkout;
            $cart->saveToSession();
            $view=&$this->getView('Ajaxroom','html','BookProView');

            $view->assign('cart',$cart);

            $view->assign('rooms',$rooms);
            $view->display();
            return;
        }


        function displayhotel(){
            AImporter::helper('hotel');
            $cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
            $cart->load();


            if($cart->checkin_date){
                $checkin=$cart->checkin_date;
            }else {

                $checkin=JFactory::getDate();
                $checkin->add(new DateInterval('P1D'));
                $checkin = JFactory::getDate($checkin)->format('d-m-Y',true);
            }

            if($cart->checkout_date){
                $checkout=$cart->checkout_date;
            }else {
                $checkout=JFactory::getDate();
                $checkout->add(new DateInterval('P2D'));
                $checkout = JFactory::getDate($checkout)->format('d-m-Y',true);
            }
            $cart->checkin_date = $checkin;
            $cart->checkout_date = $checkout;

            $model=new BookProModelHotel();
            $id=JRequest::getInt('id');
            $cart->hotel_id=$id;

            $model->setId($id);
            $subject=$model->getObject();

            $db=JFactory::getDbo();
            $query=$db->getQuery(true);
            $query->update('#__bookpro_hotel as hotel');
            $query->set('hotel.hits=hotel.hits+1');
            $query->where('hotel.id='.$id);
            $db->setQuery($query);
           
            $db->execute();


            $cart->saveToSession();
            //
            //$roommodel=new BookProModelRooms();
            //$params=array('hotel_id'=>$id,'state'=>1);

            //$roommodel->init($params);
            //$rooms=$roommodel->getData();
            $rooms = HotelHelper::getRoomAvailable($id, $cart->checkin_date, $cart->checkout_date);


            $city=BookProHelper::getObjectAddress($subject->city_id);

            $view=&$this->getView('Hotel','html','BookProView');

            $view->assign('hotel',$subject);
            $view->assign('cart',$cart);
            $view->assign('city',$city);
            $view->assign('rooms',$rooms);

            $view->display();
            return;
        }

        private function processHotelDiscount($order_id,$agent_comission){
            JTable::addIncludePath(JPATH_COMPONENT_FRONT_END.'/tables');

            $order = JTable::getInstance('orders', 'table');
            $order->load($order_id);

            $customer = JTable::getInstance('customer', 'table');
            $customer->load($order->user_id);

            //var_dump($order);

            if($customer->cgroup_id==1){
                $discount=($order->total*$agent_comission)/100;
                $newTotal=$order->total-$discount;
                $order->total=$newTotal;
                $order->discount=$discount;
                $order->store();

            }
            else if($customer->cgroup_id==4){
                $discount=($order->total*$agent_comission)/100;
                $newTotal=$order->total-$discount;
                $order->total=$newTotal;
                $order->discount=$discount;
                $order->store();
            }

        }



}