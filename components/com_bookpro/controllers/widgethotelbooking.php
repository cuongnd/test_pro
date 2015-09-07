<?php
    defined('_JEXEC') or die('Restricted access');
    jimport('joomla.application.component.controller');
    AImporter::model('hotels','rooms','hotel','application','customer','facilities');
    AImporter::helper('request','date','hotel');
    class BookProControllerWidgethotelbooking extends JControllerLegacy{

        public function BookProControllerHotel(){
            parent::__construct();
        }

        function showmodulehotelsearch()
        {
            $cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
            $cart->load();
            $view=$this->getView('widgethotelbooking','html','BookProView');

            $view->assign('cart',$cart);
            ob_start();
            $view->display('modulehotelsearch');
            $contents=ob_get_contents();
            ob_end_clean(); // get the callback function
            $callback=JRequest::getVar( 'callback');
            if ($callback) {
                $callback=filter_var($callback,FILTER_SANITIZE_STRING);
            }
            echo $callback . '('.json_encode($contents). ');';
            exit();  
        }
        function showwidgetform()
        {


            $app = JFactory::getApplication();
            $input = $app->input;
            $id=$input->get('id');
            $curent_url=$input->get('curent_url');
            $checkin = $input->get('checkin');
            $checkout = $input->get('checkout');
            AImporter::helper('hotel');
            $rooms = HotelHelper::getRoomAvailable($id, $checkin, $checkout);
            $cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
            $cart->load();
            $cart->hotel_id=$id;

            $cart->checkin_date = $checkin;
            $cart->checkout_date = $checkout;
            $cart->saveToSession();
            AImporter::helper('hotel');
            $model=new BookProModelHotel();
            $model->setId($cart->hotel_id);
            $subject=$model->getObject();
            $view=&$this->getView('widgethotelbooking','html','BookProView');
            $view->assign('cart',$cart);
            $view->assign('rooms',$rooms);
            ob_start();
            $view->display('widgetform');
            $contents=ob_get_contents();
            ob_end_clean(); // get the callback function
            $callback=JRequest::getVar( 'callback');
            if ($callback) {
                $callback=filter_var($callback,FILTER_SANITIZE_STRING);
            }
            echo $callback . '('.json_encode($contents). ');';
            exit();
        }
        function showrooms()
        {
            $app = JFactory::getApplication();
            $input = $app->input;
            $id = $input->get('id');
            $checkin = $input->get('checkin');
            $checkout = $input->get('checkout');
            AImporter::helper('hotel');
            $rooms = HotelHelper::getRoomAvailable($id, $checkin, $checkout);

            $cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
            $cart->load();
            $cart->hotel_id=$id;
            $cart->checkin_date = $checkin;
            $cart->checkout_date = $checkout;
            $cart->saveToSession();

            $view=&$this->getView('widgethotelbooking','html','BookProView');

            $view->assign('cart',$cart);

            $view->assign('rooms',$rooms);
            ob_start();
            $view->display('rooms');
            $contents=ob_get_contents();
            ob_end_clean(); // get the callback function
            $callback=JRequest::getVar( 'callback');
            if ($callback) {
                $callback=filter_var($callback,FILTER_SANITIZE_STRING);
            }
            echo $callback . '('.json_encode($contents). ');';
            exit();
        }
        function checkexistsroom()
        {
            $app = JFactory::getApplication();
            $input = $app->input;
            $hotel_id=$input->get('hotel_id');
            AImporter::model('rooms');
            $model = new BookProModelRooms();

            $lists = array('state'=>1,'hotel_id'=>$hotel_id);
            $model->init($lists);
            $rooms = $model->getData();
            echo count($rooms);
            exit();
        }
        function showroombookingdetail()
        {

            $view=&$this->getView('widgethotelbooking','html','BookProView');
            AImporter::model('room');
            AImporter::helper('currency','hotel');
            $model = new BookProModelRoom();
            $input=JFactory::getApplication()->input;

            $checkin =$input->get('checkin_date');
            $checkout =$input->get('checkout_date');
            $cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
            $hotel_id=$input->get('hotel_id');
            $cart->load();
            $cart->hotel_id=$hotel_id;
            $cart->checkin_date = $checkin;
            $cart->checkout_date = $checkout;
            $cart->saveToSession();

            $no_room=$input->get('no_room',array(),'ARRAY');

            $adult=$input->get('adult',array(),'ARRAY');
            $child=$input->get('child',array(),'ARRAY');
            $hotel_id=$input->get('hotel_id');
            $numberofnight=DateHelper::getCountDay($cart->checkin_date,$cart->checkout_date);
            $dStart = new JDate($checkin);
            $view->assign('numberofnight',$numberofnight);
            for ($i = 0;$i < $numberofnight;$i++)
            {
                $bookingroom=new stdClass();
                $dDate = clone $dStart;
                $date = $dDate->add(new DateInterval('P'.$i.'D')); 
                $date = JFactory::getDate($date)->format('d-m-Y',true);
                $bookingroom->date=$date;
                $totalperday=0;
                $totalroom=0;
                foreach($no_room as $id=>$a_no_room)
                {
                    if(!$a_no_room)
                        continue;
                    $model = new BookProModelRoom();

                    $model->setId($id);
                    $room=$model->getObject();
                    $totalroom+=$a_no_room;
                    $bookingroom->rooms[$id]->id=$room->id;
                    $bookingroom->rooms[$id]->title=$room->title;

                    $bookingroom->rooms[$id]->totalroom=$a_no_room;

                    $bookingroom->rooms[$id]->totaladult=array_sum($adult[$id]);
                    $bookingroom->rooms[$id]->totalchild=array_sum($child[$id]);
                    $roomRatepricedate=HotelHelper::getRoomRatePriceDate($id, $date);
                    $bookingroom->rooms[$id]->price=CurrencyHelper::formatprice($roomRatepricedate);
                    $bookingroom->rooms[$id]->totalprice=CurrencyHelper::formatprice($a_no_room*$roomRatepricedate);
                    $totalperday+=$a_no_room*$roomRatepricedate;


                    $totalperday+=$this->getprice($room,$adult[$id],$child[$id]);
                }
                $bookingroom->totalperday=CurrencyHelper::formatprice($totalperday);
                $array_rooms[]=$bookingroom;
                $totalallday+=$totalperday;

            }
            $totalallday=CurrencyHelper::formatprice($totalallday);
            $view->assign('totalallday',$totalallday);
            $view->assign('array_rooms',$array_rooms);
            $view->assign('cart',$cart);
            $totaladult=0;
            $totalchild=0;
            foreach($no_room as $id=>$a_no_room)
            {
                $totaladult+=array_sum($adult[$id]);
                $totalchild+=array_sum($child[$id]);
            }
            $view->assign('totaladult',$totaladult);
            $view->assign('totalchild',$totalchild);

            $view->assign('no_room', $totalroom);

            ob_start();
            $view->display('roombookingdetail');
            $contents=ob_get_contents();
            ob_end_clean(); // get the callback function
            $callback=JRequest::getVar( 'callback');
            if ($callback) {
                $callback=filter_var($callback,FILTER_SANITIZE_STRING);
            }
            echo $callback . '('.json_encode($contents). ');';
            exit();
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
        function savehotelbooking()
        {
            AImporter::model('room');
            AImporter::helper('currency','hotel');
            $input=JFactory::getApplication()->input;
            $checkin =$input->get('checkin_date');
            $checkout =$input->get('checkout_date');
            $no_room=$input->get('no_room',array(),'ARRAY');
            $adult=$input->get('adult',array(),'ARRAY');
            $child=$input->get('child',array(),'ARRAY');
            $hotel_id=$input->get('hotel_id');
            $customer=$input->get('customer',array(),'ARRAY');

            $curent_url=$input->get('curent_url');
            //load cart
            $cart = JModelLegacy::getInstance('HotelCart', 'bookpro');
            $cart->load();
            //set cart
            $cart->hotel_id=$hotel_id;
            $cart->checkin_date = $checkin;
            $cart->checkout_date = $checkout;


            //get class Passenger,OrderInfo,Order
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
            $passModel=new BookProModelPassenger();
            $numberofnight=DateHelper::getCountDay($cart->checkin_date,$cart->checkout_date);
            //get price per room from checkin_date to checkout_date
            $sum=0;
            $array_room_total_price=array();
            foreach($no_room as $id=>$a_no_room)
            {
                if(!$a_no_room)
                    continue; 
                $model = new BookProModelRoom();

                $model->setId($id);
                $room=$model->getObject(); 

                $price = $a_no_room*HotelHelper::getRoomRateTotalPrice($id, $cart->checkin_date, $cart->checkout_date);  
                $price+=$numberofnight*$this->getprice($room,$adult[$id],$child[$id]);
                $array_room_total_price[$id]=$price;
                $sum+=$price;

            }

            //save  facilities
            //code here

            $app=JFactory::getApplication();
            $table_customer=JTable::getInstance('customer','Table');
            $customer['state']=1;
             $customer['createby']=-1;
            $table_customer->bind($customer);
            $table_customer->store();
            $cid=JFactory::getDbo()->insertid();

            $totaladult=0;
            $totalchild=0;
            foreach($no_room as $id=>$a_no_room)
            {
                $totaladult+=array_sum($adult[$id]);
                $totalchild+=array_sum($child[$id]);
            }
            $cart->adult=$totaladult;
            $cart->children=$totalchild;
            $cart->sum=$sum;
            $cart->no_room=array_sum($no_room);
            $cart->tax=$sum*$app->vat/100;
            $cart->service_fee=$app->service_fee*$sum/100;
            $cart->total=$sum+$cart->tax+$cart->service_fee;

            $cart->products=$no_room;

            //save cart
            $cart->saveToSession();

            //store Order
            $order=array('id'=>0,
                'type'=>'HOTEL',
                'user_id'=>$cid,
                'total'=>$cart->total,
                'subtotal'=>$cart->sum,
                'order_status'=>'NEW',
                'pay_status'=>'PENDING',
                'notes'=>$cart->notes,
                'tax'=>$cart->tax,
                'remote_url'=>$curent_url,
                'service_fee'=>$cart->service_fee

            );

            $order_id=$orderModel->store($order);
            if($err= $orderModel->getError()){
                $app->enqueueMessage($err,'Save order error');
            }
            //save order info
            foreach($cart->products as $id=>$a_no_room)
            {
                if(!$a_no_room)
                    continue;

                $orderinfo = array('id'=>0,
                    'type' => 'HOTEL_ROOM',
                    'order_id'=>$order_id,
                    'adult'=>$cart->adult,
                    'child'=>$cart->children,
                    'obj_id'=>$id,
                    'start'=>JFactory::getDate($cart->checkin_date)->toSql(),
                    'end'=>JFactory::getDate($cart->checkout_date)->toSql(),
                    'qty'=>$a_no_room,
                    'price'=>$array_room_total_price[$id]
                );
                $orderinfo_id = $orderinfoModel->store($orderinfo);
            }
            //save oderinfo facility
            //code here
            AImporter::helper('email');
            $mail=new EmailHelper();
            $mail->sendMail($order_id->id);
            $param=new stdClass();
            $param->session=JSession::getFormToken();
            $param->order_id=$order_id;
            $callback=JRequest::getVar( 'callback');
            if ($callback) {
                $callback=filter_var($callback,FILTER_SANITIZE_STRING);
            }
            echo $callback . '('.json_encode($param). ');';
            exit();
        }



}