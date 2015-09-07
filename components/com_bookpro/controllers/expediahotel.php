<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
AImporter::model('hotels', 'rooms', 'hotel', 'application', 'customer', 'facilities');
AImporter::helper('request', 'date', 'hotel', 'expediaemail');

class BookProControllerExpediahotel extends JControllerLegacy
{

    public function __construct()
    {
        parent::__construct();
    }

    function display()
    {

        $vName = JRequest::getCmd('view', 'expediahotel');

        switch ($vName) {
            case 'expediahotels':
                $this->displayhotels();
                return;
            case 'expediahotel':
                $this->displayhotel();
                return;
            case 'hotelzzz':
                $this->displayhotel();
                return;
        }
        JRequest::setVar('view', $vName);
        parent::display();
    }
    public function ajaxGetListHotel()
    {
        $input=JFactory::getApplication()->input;

        //set filter start
        $minStart=$input->get('minStart',0,'int');
        $maxStart=$input->get('maxStart',0,'int');
        $input->set('minStart',$minStart);
        $input->set('maxStart',$maxStart);
        //------------

        //set filter price
        $minRate=$input->get('minRate',0,'int');
        $maxRate=$input->get('maxRate',0,'int');
        $input->set('minRate',$minRate);
        $input->set('maxRate',$maxRate);
        //------------
        $input->set('callFromAjax',1);

        $view = &$this->getView('expediahotels', 'html', 'BookProView');
        $respone_array = array();
        ob_start();
        $view->display('hotel');
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.list-hotel',
            'contents' => $contents
        );
        echo json_encode($respone_array);

        exit();
    }
    static function getRemoteDatabaseExpedia()
    {
        $config=JComponentHelper::getParams('com_bookpro');
        $option = array(); //prevent problems

        $option['driver']   = $config->get('driver');            // Database driver name
        $option['host']     = $config->get('host');    // Database host name
        $option['user']     = $config->get('user');      // User for database authentication
        $option['password'] = $config->get('password');   // Password for database authentication
        $option['database'] = $config->get('database');      // Database name
        $option['prefix']   = $config->get('prefix');             // Database prefix (may be empty)
        $db = JDatabase::getInstance( $option );
        return $db;
    }
    function ajax_getJsonListString()
    {


        $db=$this->getRemoteDatabaseExpedia();
        $query=$db->getQuery(true);
        $input=JFactory::getApplication()->input;
        $keyword=$input->get('keyword','','string');

        $query->from('citycoordinateslist AS citycoordinateslist');
        $query->select('CONCAT("Cities/Areas:",citycoordinateslist.RegionID,":",citycoordinateslist.RegionName) as id,citycoordinateslist.RegionName as text');
        $query->where('citycoordinateslist.RegionName LIKE '.$db->quote($keyword.'%'));
        $db->setQuery($query);
        $listRegions['Cities/Areas']=$db->loadObjectList();


        $query=$db->getQuery(true);
        $query->from('airportcoordinateslist AS airportcoordinateslist');
        $query->select('CONCAT("Airports:",airportcoordinateslist.AirportID,":",airportcoordinateslist.AirportName) as id,airportcoordinateslist.AirportName as text');
        $query->where('airportcoordinateslist.AirportName LIKE '.$db->quote($keyword.'%'));
        $db->setQuery($query);
        $listRegions[' Airports']=$db->loadObjectList();
        $query=$db->getQuery(true);
        $query->from('activepropertylist AS activepropertylist');
        $query->select('CONCAT("Hotels:",activepropertylist.EANHotelID,":",activepropertylist.Name) AS id,activepropertylist.Name AS text');
        $query->where('activepropertylist.Name LIKE '.$db->quote($keyword.'%'));
        $db->setQuery($query);
        $listRegions['Hotels']=$db->loadObjectList();
        echo json_encode($listRegions);
        exit();

    }

    function guestform()
    {

        AImporter::model('room');
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $config = AFactory::getConfig();
        AImporter::helper('currency', 'hotel');
        $model = new BookProModelRoom();
        $cart = JModelLegacy::getInstance('ExpediaHotelCart', 'bookpro');
        $cart->load();

        $input = JFactory::getApplication()->input;
        $roomtypecode = $input->get('roomtypecode', '', 'string');

        $cart->hotel_id = $input->get('hotel_id', '', 'int');
        $cart->room_type = $roomtypecode;


        $no_room = $input->get('no_room', array(), 'array');

        $adult = $input->get('adult', array(), 'array');
        $child = $input->get('child', array(), 'array');
        $hotel_id = $input->get('hotel_id');

        $cart->array_adult = $adult;

        $cart->array_child = $child;
        $cart->no_room = $no_room;
        $cart->saveToSession();
        $this->check($step);


        //save  facilities
        //code here
    }


    public function displaymap()
    {
        $app = JFactory::getApplication();
        $input = $app->input;

        $obj = new stdClass();
        $obj->longitude = $input->get('longitude', '', 'string');
        $obj->latitude = $input->get('latitude', '', 'string');
        $obj->address = $input->get('address', '', 'string');
        $obj->title = $input->get('title', '', 'string');
        $obj->desc = $input->get('desc', '', 'string');
        $view = & $this->getView('googlemap', 'html', 'BookProView');
        $view->assign('obj', $obj);
        $view->display();
    }

    private function check($step)
    {
        $mainf = JFactory::getApplication();
        $user = JFactory::getUser();
        $config = AFactory::getConfig();
        if ($config->anonymous) {
            $this->display_booking_form();
        } else {
            if ($user->id == 0) {
                $return = base64_encode(JURI::root() . 'index.php?option=com_bookpro&controller=expediahotel&task=display_booking_form');
                $return = JURI::root() . 'index.php?option=com_bookpro&view=login&return=' . $return;
                $this->setRedirect($return);
                return;
            } else {
                $this->display_booking_form();
            }
        }
    }

    function display_booking_form()
    {

        $appModel = new BookProModelApplication();
        $input = JFactory::getApplication()->input;
        $app = $appModel->getObjectByCode('HOTEL');
        $cart = & JModelLegacy::getInstance('ExpediaHotelCart', 'bookpro');
        $cart->load();

        $expedia = $this->getProfileExpedia();


        $model = new BookProModelHotel();


        $model->setId($cart->hotel_id);
        $hotel = $model->getObject();
        $roomtypecode = $cart->room_type;
        $no_room = $cart->no_room;

        $numberofdays = DateHelper::getCountDay($cart->checkin_date, $cart->checkout_date);

        $cart->saveToSession();
        $user = JFactory::getUser();
        $groups = $user->getAuthorisedGroups();
        $config = AFactory::getConfig();

        $cmodel = new BookProModelCustomer();
        $cmodel->setIdByUserId();
        $customer = $cmodel->getObject();

        if (in_array($config->agentUsergroup, $groups)) {
            $customer = null;
        }
        $expedia = $this->getProfileExpedia();
        $payment_params = array(
            'locale' => en_US
        );

        $payment_methor = $expedia->paymentInfo($payment_params);

        $hotel = $expedia->info(array(
            'hotelId' => $cart->hotel['hotelId']
        ));


        $payment_methor = $expedia->paymentInfo($payment_params);


        $view = & $this->getView('ExpediaHotelbook', 'html', 'BookProView');
        $view->assign('payment_methor', $payment_methor);
        $view->assign('hotel', $hotel);
        $view->assign('customer', $customer);
        $view->assign('app', $app);
        $view->assign('cart', $cart);
        $view->display();
    }

    function getprice($room, $array_adult, $array_child)
    {

        $total = 0;
        for ($i = 0; $i < count($array_adult); $i++) {

            $total += HotelHelper::CaculatePriceRoom($room, $array_adult[$i], $array_child[$i]);
        }

        return $total;
    }

    function step2()
    {


        JSession::checkToken() or jexit('Invalid Token');
        $cart = & JModelLegacy::getInstance('ExpediaHotelCart', 'bookpro');
        $cart->load();
        $cmodel = new BookProModelCustomer();
        $config = AFactory::getConfig();
        $jconfig = JFactory::getConfig();
        $app = JFactory::getApplication();
        $app=JFactory::getApplication();
        $input=$app->input;

        $post = JRequest::get('post');

        $post['firstName']=$post['infobooking']['firstName'];
        $post['lastName']=$post['infobooking']['lastName'];
        $post['homePhone']=$post['infobooking']['homePhone'];
        $post['address1']=$post['infobooking']['address1'];
        $post['city']=$post['infobooking']['city'];
        $post['postalCode']=$post['infobooking']['postalCode'];
        $post['id'] = $post['customer_id'];
        $user_system = JFactory::getUser();

        $model_customer=new BookProModelCustomer();
        if (! $user_system->id) {
            $config=AFactory::getConfig();
            $data ['id']=0;
            $data ['email'] = $post['email'];
            $data ['username'] = $post['email'];
            $data ['name'] =  $post['firstName'].'-'.$post['lastName'];
            $data ['password'] = 123456;
            $data ['block']=0;
            $data ['groups']=array($config->customersUsergroup);
            $user_system = $model_customer->createUserSystem ( $data );
            $model_customer->autoLoginByUserIdSystem ( $user_system->id );

        }
        $customer = $model_customer->getCustomerByUserIdSystem ( $user_system->id );
        if (! $customer->id) {
            $extend_data=array();
            $extend_data['firstname']=$post['firstName'];
            $extend_data['lastname']=$post['lastName'];


            $customer = $model_customer->createCustomerByUserIdSystem ( $user_system->id ,$extend_data);
        }


        $cid = $customer->id;

        //$person=JRequest::getVar('pfirstname',array());
        $room = JRequest::getVar('room_id', array());
        $room_person = array();




        $cart->saveToSession();
        if ($cid) {

            $order_id = $this->_storeOrder($cid);

            //apply discount for user group

            $hotel = JTable::getInstance('hotel', 'table');
            $hotel->load($cart->hotel_id);


            if ($hotel->agent_comission > 0) {

                $this->processHotelDiscount($order_id, $hotel->agent_comission);
            }
            $HotelRoomResponse = $cart->hotel['HotelRoomResponse'];
            $HotelRoomResponse = $HotelRoomResponse[0] ? $HotelRoomResponse : array($HotelRoomResponse);
            $HotelRoomResponse = JArrayHelper::pivot($HotelRoomResponse, 'roomTypeCode');
            $roomavail = $HotelRoomResponse[$cart->room_type];
            $rooms=$roomavail['RateInfos']['RateInfo']['RoomGroup']['Room'];
            $rooms=$rooms[0]?$rooms:array($rooms);
            $rateInfo=$roomavail['RateInfos']['RateInfo'];



            $expedia = $this->getProfileExpedia();
            $expedia->set_method('POST');
            $expedia->set_protocol('https://');


            $post_array = $input->getArray($_POST);
            $infoBooking_array=$post_array['infobooking'];
            $params = array(
                'hotelId' => $cart->hotel['hotelId'],
                "arrivalDate" => JFactory::getDate($cart->checkin_date)->format('m/d/Y'),
                "departureDate" => JFactory::getDate($cart->checkout_date)->format('m/d/Y'),
                'supplierType' => $roomavail['supplierType'],
                'rateKey' => $rooms[0]['rateKey'],
                'roomTypeCode' => $roomavail['roomTypeCode'],
                'rateCode' => $roomavail['rateCode'],
                'chargeableRate' => $rateInfo['ChargeableRateInfo']['@total'],
                'room1SmokingPreference' => 'no',
                'email' => $infoBooking_array['email'],
                'firstName' => $infoBooking_array['firstName'],
                'lastName' => $infoBooking_array['lastName'],
                'homePhone' =>$infoBooking_array['homePhone'],
                'workPhone' => $customer->telephone,
                'creditCardType' => $infoBooking_array['creditCardType'],
                'creditCardNumber' => $infoBooking_array['creditCardNumber'],
                'creditCardIdentifier' => $infoBooking_array['creditCardIdentifier'],
                'creditCardExpirationMonth' =>$infoBooking_array['creditCardExpirationMonth'],
                'creditCardExpirationYear' => $infoBooking_array['creditCardExpirationYear'],
                'address1' => $infoBooking_array['address1'],
                'city' => $infoBooking_array['city']?$infoBooking_array['city']:'Moscow',
                'stateProvinceCode' =>null,//$infoBooking_array['stateProvinceCode']
                'countryCode' =>$infoBooking_array['countryCode']?$infoBooking_array['countryCode']:'VN',
                'postalCode' =>$infoBooking_array['postalCode']

            );

            $HotelRoomResponse = $cart->hotel['HotelRoomResponse'];
            $HotelRoomResponse = $HotelRoomResponse[0] ? $HotelRoomResponse : array($HotelRoomResponse);
            $HotelRoomResponse = JArrayHelper::pivot($HotelRoomResponse, 'roomTypeCode');
            $roomavail = $HotelRoomResponse[$cart->room_type];
            $a_room = $roomavail['RateInfos']['RateInfo']['RoomGroup']['Room'];
            $a_room = $a_room[0] ? $a_room : array($a_room);
            $params['creditCardType']='5401999999999999';
            $params['creditCardType']='CA';
            $params['creditCardIdentifier']='123';
            $params['creditCardExpirationMonth']='05';
            $params['creditCardExpirationYear']='2017';


            $params['workPhone']='1234567';
            $params['countryCode']='VI';

            $params['postalCode']='10000';
            for($i=0;$i<count($a_room);$i++)
            {
                $childAges= $a_room[$i][childAges];
                $childAges = $childAges[0] ? $childAges : array($childAges);
                $childAges=implode(',',$childAges);
                $params['room'.($i+1)] = "{$a_room[$i][numberOfAdults]}".($childAges?",$childAges":'');
                $params['room'.($i+1).'FirstName'] = $infoBooking_array['room'.($i+1).'FirstName'];
                $params['room'.($i+1).'LastName'] = $infoBooking_array['room'.($i+1).'LastName'];
                $params['room'.($i+1).'BedTypeId'] =$infoBooking_array['room'.($i+1).'BedTypeId'];
            }

            unset($params['stateProvinceCode']);
            unset($params['room1SmokingPreference']);
            $cart->infoBooking=$infoBooking_array;
            $cart->saveToSession();


            $result = $expedia->res($params);
            $itineraryid = $result['itineraryId'];
            $orderModel = new BookProModelOrder();
            foreach($params as $key=> $param)
            {
                if(!$param)
                    unset($params[$key]);
            }
            $order = array('id' => 0, 'type' => 'EXPEDIAHOTEL', 'itineraryid' => $itineraryid, 'user_id' => $cid, 'pay_method' => 'UNDEFINED');
            $orderid = $orderModel->store($order);
            if($itineraryid!=-1)
            {
                //ExpediaEmailHelper::sendMail($order_number);
                $this->setRedirect('https://www.travelnow.com/selfService/55505/searchByIdAndEmail?itineraryId='.$itineraryid.'&email='.$infoBooking_array['email']);
            }
            else
            {
                $this->setRedirect('index.php?option=com_bookpro&controller=expediahotel&task=display_booking_form&' . JSession::getFormToken() . '=1' . '&Itemid=' . JRequest::getVar('Itemid'),$result['presentationMessage']);
            }
            //$this->setRedirect(JURI::base() . 'index.php?option=com_bookpro&view=expediaorderdetail&order_id=' . $orderid . '&' . JSession::getFormToken() . '=1');
        } else {
            $this->setRedirect('index.php?option=com_bookpro&controller=expediahotel&task=display_booking_form&' . JSession::getFormToken() . '=1' . '&Itemid=' . JRequest::getVar('Itemid'));
        }

        return;
    }

    private function fillCustomer($input, $customer, $data)
    {
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
        $input = str_replace('{zip}', $customer->zip ? 'N/A' : $customer->zip, $input);
        $input = str_replace('{country}', $customer->country_name, $input);
        return $input;
    }

    function getRoomIdSearch()
    {
        $model = new BookProModelHotels();
        $lists = array('state' => 1);
        $model->init($lists);
        $hotels = $model->getData();
        $hotelIds = array();
        foreach ($hotels as $hotel) {

        }
    }

    function search()
    {

        AImporter::helper('hotel');
        $app = JFactory::getApplication();


        $menu = & JSite::getMenu();
        $active = $menu->getActive();
        if ($active) {
            $this->products_per_row = $active->params->get('products_per_row', 2);
            $this->count = $active->params->get('count', 8);
        } else {
            $this->products_per_row = 1;
            $this->count = $mainframe->getCfg('list_limit');
        }


        $input = JFactory::getApplication()->input;

        $cart = & JModelLegacy::getInstance('ExpediaHotelCart', 'bookpro');
        $cart->load();
        $checkin_date = $input->get('checkin');
        $checkout_date = $input->get('checkout');
        $keyword = $input->get('keyword', '', 'string');
        $keyword = ltrim($keyword);
        $keyword = rtrim($keyword);
        $adult = $input->getInt('adult', 1);
        $room = $input->getInt('room', 0);
        $searchmode = $input->getBool('searchmode', true);
        $children = $input->getInt('child', 0);


        $cart->adult = $adult;
        $cart->children = $children;
        $cart->room = $room;
        $cart->checkin_date = $checkin_date;

        $cart->checkout_date = $checkout_date;
        $cart->saveToSession();

        $model = new BookProModelHotels();
        $lists = array();

        $date = new JDate();
        $end = $date->format('t-m-Y', true);
        $startMonth = $date->setDate($date->year, $date->month, 01);
        $startMonth = $date->format('d-m-Y');
        $endMonth = new JDate($end);
        $endMonth = $endMonth->format('d-m-Y');

        $lists['search'] = $keyword;
        $lists['limit'] = $input->get('limit', $this->count, 'int');
        $lists['featured'] = $input->get('featured', $this->count, 'int');
        $lists['state'] = 1;
        $lists['start'] = $startMonth;
        $lists['end'] = $endMonth;
        $lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
        $this->lists['order'] = ARequest::getUserStateFromRequest('order', 'title', 'cmd');
        $this->lists['order_Dir'] = ARequest::getUserStateFromRequest('order_Dir', 'DESC', 'word');
        $lists['searchmode'] = $searchmode;

        $hids = HotelHelper::getHotelAvailable($checkin_date, $checkout_date);

        $lists['id'] = $hids;


        $model->init($lists);
        $data = $model->getData();
        $pagination = & $model->getPagination();

        $view = $this->getView('Hotels', 'html', 'BookProView');
        $view->assign('hotels', $data);
        $view->assign('pagination', $pagination);
        $view->display();
    }

    /*
     * au
     */

    private function _storeOrder($user_id)
    {

        $cart = & JModelLegacy::getInstance('ExpediaHotelCart', 'bookpro');
        $cart->load();
        if (!class_exists('BookProModelPassenger')) {
            AImporter::model('passenger');
        }
        if (!class_exists('BookProModelOrderInfo')) {
            AImporter::model('orderinfo');
        }
        if (!class_exists('BookProModelOrder')) {
            AImporter::model('order');
        }
        $orderModel = new BookProModelOrder();
        $orderinfoModel = new BookProModelOrderInfo();

        //store Order
        $order = array('id' => 0,
            'type' => 'HOTEL',
            'user_id' => $user_id,
            'total' => $cart->total,
            'subtotal' => $cart->sum,
            'order_status' => 'NEW',
            'pay_status' => 'PENDING',
            'notes' => $cart->notes,
            'tax' => $cart->tax,
            'service_fee' => $cart->service_fee
        );
        $user = JFactory::getUser();
        $config = AFactory::getConfig();
        $groups = $user->getAuthorisedGroups();
        if (in_array($config->agentUsergroup, $groups)) {
            $cmodel = new BookProModelCustomer();
            $cmodel->setIdByUserId();
            $customer = $cmodel->getObject();
            $order['created_by'] = $customer->id;
        }

        $order_id = $orderModel->store($order);
        if ($err = $orderModel->getError()) {

            $app->enqueueMessage($err, 'Save order error');
            return false;
        }
        //save order info
        $rooms = $cart->products;


        foreach ($rooms as $room) {
            $checkout_date = new JDate($cart->checkout_date);
            $orderinfo = array('id' => 0,
                'type' => 'HOTEL_ROOM',
                'order_id' => $order_id,
                'adult' => array_sum($cart->array_adult[$room->id]),
                'child' => array_sum($cart->array_child[$room->id]),
                'obj_id' => $room->id,
                'start' => JFactory::getDate($cart->checkin_date)->toSql(),
                'end' => JFactory::getDate($checkout_date->modify('-1 day'))->toSql(),
                'qty' => $room->no_room,
                'price' => $room->price
            );

            $orderinfo_id = $orderinfoModel->store($orderinfo);
            if ($err = $orderinfoModel->getError()) {
                $app->enqueueMessage($err, 'Save order error');
                return false;
            }
        }
        $cart->saveToSession();
        return $order_id;
    }

    /**
     *
     * @param int $hotel_id
     * @param date $date_from
     * @param date $date_to
     * @return array of room
     */
    function checkAvailable()
    {
        $view = & $this->getView('expediahotel', 'html', 'BookProView');
        $view->setLayout('listroom');
        $app = JFactory::getApplication();
        $cart = & JModelLegacy::getInstance('ExpediaHotelCart', 'bookpro');
        $cart->load();
        $input = $app->input;

        $age_children = $input->get('age_children', 'array()', 'array');
//        echo "<pre>";
//        print_r($age_children);
//        echo "</pre>";
        $total_room = $input->get('room');
        $childrens = $input->get('children', 'array()', 'array');
        array_splice($childrens, $total_room);
        $adults = $input->get('adult', 'array()', 'array');
        array_splice($adults, $total_room);
        $hotel_id = $input->get('hotel_id');
        $checkin = $input->get('checkin_date');
        $checkout = $input->get('checkout_date');
        $roomgroup = array();
        $age_childrens = array();


        $age_childrens = implode(',', $age_childrens);
        $params = array(
            "hotelId" => $hotel_id,
            "arrivalDate" => JFactory::getDate($checkin)->format('m/d/Y'),
            "departureDate" => JFactory::getDate($checkout)->format('m/d/Y'),
            "includeRoomImages" => "true",
            "includeDetails" => "true"
        );
        for ($i = 0; $i < count($adults); $i++) {

            if ($childrens[$i] != 0) {
                $age_childrens[$i] = implode(',', $age_children[$i]);
            }

        }

        for ($i = 0; $i < count($adults); $i++) {
            $index_room = $i + 1;
            $params['room' . $index_room] = $adults[$i] . ($age_children[$i] != 0 ? ',' . $age_childrens[$i] : '');
        }
        $expedia = $this->getProfileExpedia();
        $hotel = $expedia->getAvailableRooms($params);
        //echo $expedia->get_http_dump();
        $cart->hotel = $hotel;


        $params = array(
            "HotelRoomImageRequest" => array(
                "hotelId" => $hotel_id
            )
        );
        //$images=$expedia->getroomimages($params);
        //$view->assign('images',$images);


        $cart->checkin_date = $checkin;
        $cart->checkout_date = $checkout;
        $cart->saveToSession();

        $view->assign('cart', $cart);

        $view->assign('hotel', $hotel);
        $view->display();
        exit();
    }

    function getProfileExpedia()
    {
        $config = JComponentHelper::getParams('com_bookpro');
        require_once JPATH_COMPONENT_FRONT_END . DS . 'classes/Expedia/API.php';
        $expedia = new API($config->get('cid'), $config->get('api_key'), $config->get('currency_code'), $config->get('minor_rev'),$config->get('locale'));

        return $expedia;
    }

    function gethotelinfo()
    {
        $app = JFactory::getApplication();
        $input = $app->input;
        $hotel_id = $input->get('hotel_id', 'string');
        $expedia = $this->getProfileExpedia();
        $this->hotel = $expedia->info(array(
            'hotelId' => $hotel_id
        ));
        $view = & $this->getView('expediahotels', 'html', 'BookProView');
        ob_start();
        $view->setLayout('default:item');
        $view->assign('cart', $cart);
        $view->display();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.show-grid.content_details_hotels',
            'contents' => $contents
        );
        echo json_encode($respone_array);

        exit();


        $view->setLayout('item');
    }

    function displayhotel()
    {
        $input=JFactory::getApplication()->input;
        $view = & $this->getView('ExpediaHotel', 'html', 'BookProView');
        $cart = JModelLegacy::getInstance('ExpediaHotelCart', 'bookpro');
        $cart->load();

        $currency_code=$input->get('currency_code','','string');
        if($currency_code)
        {
            $cart->currency_code=$currency_code;
        }
        $language_code=$input->get('language_code','','string');
        if($language_code)
        {
            $cart->language_code=$language_code;
        }
        $cart->saveToSession();

        if ($cart->checkin_date) {
            $checkin = JFactory::getDate($cart->checkin_date);
        } else {

            $checkin = JFactory::getDate();
        }


        if ($cart->checkout_date) {
            $checkout = JFactory::getDate($cart->checkout_date);
        } else {
            $checkout = JFactory::getDate();
            $checkout->add(new DateInterval('P2D'));
        }
        $cart->checkin_date = $checkin;
        $cart->checkout_date = $checkout;

        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->from('#__bookpro_expedia_curency_support AS curency');
        $query->select('curency.*');
        $db->setQuery($query);
        $list=$db->loadObjectList();
        $list_currency=JHtmlSelect::genericlist($list, 'currency_code',' onchange="this.form.submit()" ','code','title',$cart->currency_code);


        $query=$db->getQuery(true);
        $query->from('#__bookpro_expedia_language_support AS language');
        $query->select('language.*');
        $db->setQuery($query);
        $list=$db->loadObjectList();
        $list_language=JHtmlSelect::genericlist($list, 'language_code',' onchange="this.form.submit()" ','code','title',$cart->language_code);



        $expedia = $this->getProfileExpedia();
        $hotel_id = JRequest::getVar('hotel_id');
        $hotel = $expedia->getHotelInfo($hotel_id);

        $objmap = new stdClass();
        $objmap->longitude = $hotel['HotelSummary']['longitude'];
        $objmap->latitude = $hotel['HotelSummary']['latitude'];
        $objmap->address = $hotel['HotelSummary']['address1'];
        $objmap->title = $hotel['HotelSummary']['name'];
        $objmap->desc = $hotel['HotelSummary']['locationDescription'];
        $view->assign('obj', $objmap);
        $view->assign('cart', $cart);
        $view->assign('list_currency', $list_currency);
        $view->assign('list_language', $list_language);
        $view->assign('hotel', $hotel);
        $view->display();
        return;
    }

    private function processHotelDiscount($order_id, $agent_comission)
    {
        JTable::addIncludePath(JPATH_COMPONENT_FRONT_END . '/tables');

        $order = JTable::getInstance('orders', 'table');
        $order->load($order_id);

        $customer = JTable::getInstance('customer', 'table');
        $customer->load($order->user_id);

        //var_dump($order);

        if ($customer->cgroup_id == 1) {
            $discount = ($order->total * $agent_comission) / 100;
            $newTotal = $order->total - $discount;
            $order->total = $newTotal;
            $order->discount = $discount;
            $order->store();
        } else if ($customer->cgroup_id == 4) {
            $discount = ($order->total * $agent_comission) / 100;
            $newTotal = $order->total - $discount;
            $order->total = $newTotal;
            $order->discount = $discount;
            $order->store();
        }
    }

}
