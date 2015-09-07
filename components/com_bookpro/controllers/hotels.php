<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_weblinks
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * @package     Joomla.Site
 * @subpackage  com_weblinks
 * @since       1.5
 */
class BookproControllerHotels extends JControllerForm
{
    var $context='com_bookpro.bustrips.search';
    function search()
    {


        JSession::checkToken() or jexit('Invalid Token');
        $app=JFactory::getApplication();
        $cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
        $cart->load();
        $app=JFactory::getApplication();
        $input = $app->input;
        $from = $input->getInt('desfrom',0,'int');
        $to = $input->getInt('desto',0,'int');
        $pickup = $input->getInt('pickup',0,'int');

        $dropoff = $input->getString('dropoff',0,'int');
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
        else
        {
            $cart->pickup=null;
        }
        if($dropoff)
            $cart->dropoff=$dropoff;
        else
        {
            $cart->dropoff=null;
        }
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

        $Itemid=$input->get('Itemid',0,'int');
        $Itemid=$Itemid?'&Itemid='.$Itemid:'';
        $this->setRedirect('index.php?option=com_bookpro&view=bustrips&layout=search'.$Itemid);
        return;
    }
    function getListBustrip()
    {
        include(JPATH_ROOT.'/administrator/components/com_bookpro/classes/dhtmlxScheduler_v4.1.0/codebase/connector/scheduler_connector.php');//includes the file
        $j_cfg = new JConfig;
        $res=mysql_connect($j_cfg->host, $j_cfg->user, $j_cfg->password);
        mysql_select_db($j_cfg->db);
        $scheduler = new JSONSchedulerConnector($res);
        $scheduler->render_table($j_cfg->dbprefix."bookpro_events_rec","id","event_start,event_end,text,rec_type,rec_pattern,event_length,bustrip_id","type");

    }
    function ajaxGetLayoutBookingSummary()
    {
        $input=JFactory::getApplication()->input;
        $cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
        $cart->load();
        $pickup_date = $input->get('pickup_date',JFactory::getDate(),'string');
        $pickup_hours=$input->get('pickup_hours',0,'int');
        $pickup_minutes=$input->get('pickup_minutes',0,'int');

        $dropoff_date = $input->get('dropoff_date',JFactory::getDate(),'string');
        $drop_off_hours=$input->get('drop_off_hours',0,'int');
        $drop_off_minutes=$input->get('drop_off_minutes',0,'int');

        $pickup_date_time=JFactory::getDate("$pickup_date $pickup_hours:$pickup_minutes:00");
        $drop_off_date_time=JFactory::getDate("$dropoff_date $drop_off_hours:$drop_off_minutes:00");
        $cart->start=$pickup_date_time->format('Y-m-d H:i');
        $cart->end=$drop_off_date_time->format('Y-m-d H:i');

        $cart->saveToSession();
        $view = &$this->getView('bustrips', 'html', 'BookProView');
        $view->setLayout('booking_booking_summary');
        $view->bookingBustrip=$view->getBookingBustrip();
        $view->ajaxGetBookingSummary();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.booking-summary',
            'contents' => $contents
        );
        echo json_encode($respone_array);
        exit();
    }
    function ajaxSetAddOne()
    {
        $input=JFactory::getApplication()->input;
        $addOneId=$input->get('addOneId',0,'int');
        $plus=$input->get('plus',0,'int');

        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->from('#__bookpro_addon');
        $query->select('*');
        $query->where('id='.$addOneId);
        $db->setQuery($query);
        $addOne= $db->loadObject();
        $cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
        $cart->load();
        if($plus)
        {
            $cart->addOne[$addOneId]=$addOneId;
            $cart->total+=$addOne->price;
        }
        else
        {
            unset($cart->addOne[$addOneId]);
            $cart->total-=$addOne->price;
        }

        $cart->saveToSession();
        $view = &$this->getView('bustrips', 'html', 'BookProView');
        $view->setLayout('booking_booking_summary');
        $view->bookingBustrip=$view->getBookingBustrip();
        $view->parentDisplay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.booking-summary',
            'contents' => $contents
        );
        echo json_encode($respone_array);
        exit();





    }
    function quick_login()
    {
        $input=JFactory::getApplication()->input;
        $username=$input->get('username','','string');
        $password=$input->get('password','','string');
        $credentials = array( 'username' => $username, 'password' => $password);
        $login_site =& JFactory::getApplication('site');
        $login=$login_site->login($credentials, $options=array());
        $returnArray=array();
        $returnArray['loginOk']= $login;
        $returnArray['msg']= $login?JText::_('Login successful'):JText::_('username or password type mismatch');
        echo json_encode($returnArray);
        die;
    }
    function booking()
    {
        AImporter::model('order','customer');
        if (! class_exists('BookProModelPassenger')) {
            AImporter::model('passenger');
        }

        if (! class_exists('BookProModelOrder')) {
            AImporter::model('order');
        }
        $orderModel = new BookProModelOrder();

        $customerModel=new BookProModelCustomer();

        $app = JFactory::getApplication();
        $input = $app->input;
        $cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
        $cart->load();
        $table_event_selected=JTable::getInstance('event','Jtable');
        $event_id=$cart->event_id;
        $table_event_selected->load($event_id);
        $app=JFactory::getApplication();
        AImporter::model('bustrips');
        $model_bustrips=&JModelLegacy::getInstance('Bustrips', 'BookproModel');
        $app->setUserState('bustrip_filter_bustrip_id',$table_event_selected->bustrip_id);
        $listBusTrip		= $model_bustrips->getItems();
        $selectBusTrip=$listBusTrip[0];
        $currentCustomer=$customerModel->getObjectByUserId();
        $cart->saveToSession();
        $order=array(
            'type'=>'BUS',
            'user_id'=>$currentCustomer->id,
            'total'=>$cart->total,
            'subtotal'=>$cart->sum,
            'pay_method'=>'',
            'pay_status'=>'PENDING',
            'order_status'=>OrderStatus::$NEW->getValue(),
            'notes'=>$cart->notes,
            'tax'=>$cart->tax,
            'service_fee'=>$cart->service_fee

        );
        $order_id = $orderModel->store($order);
        if($err=$orderModel->getError()){
            $app->enqueueMessage($err,'error');
            $app->redirect(JURI::base());
            exit;
        }
        //save passenger
        $post=$app->input->getArray($_POST);
        $params=new stdClass();
        $params->pickupplace=$post['pickUpPlace'];
        $params->pickup_time="{$post['pickup_hours']}:{$post['pickup_minutes']}";
        $params->dropoffplace=$post['DropOffPlace'];
        $params->drop_off_time="{$post['drop_off_hours']}:{$post['drop_off_minutes']}";
        $params->bustrip=$selectBusTrip;

        $passenger=array(
            'order_id'=>$order_id,
            'firstname'=>$post['firstname'],
            'lastname'=>$post['lastname'],
            'mobile'=>$post['phone'],
            'route_id'=>$selectBusTrip->id,
            'price'=>$table_event_selected->text,
            'email'=>$post['email'],
            'birthday'=>"{$post['birthday-year']}:{$post['birthday-moth']}:{$post['birthday-day']}",
            'country_id'=>$post['nationality'],
            'aditional_request'=>$post['additionalRequest'],
            'start'=>$cart->start,
            'params'=>json_encode($params)
        );
        $passModel = new BookProModelPassenger();
        $passModel->store($passenger);
        if($err=$passModel->getError()){
            $app->enqueueMessage($err,'error');
            $app->redirect(JURI::base());
            return false;
        }
        $this->setRedirect(JURI::base().'index.php?option=com_bookpro&view=formpayment&order_id='.$order_id.'&'.JSession::getFormToken().'=1');
        return true;


    }
    function ajaxGetDataBusTrip()
    {
        $cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
        $cart->load();

        $input=JFactory::getApplication()->input;
        $input->set('layout','search');
        $next_day=$input->get('next_day',0,'int');
        if($next_day)
            $start=JFactory::getDate($cart->start)->add(new DateInterval('P1D'));
        $prev_day=$input->get('prev_day',0,'int');
        if($prev_day)
            $start=JFactory::getDate($cart->start)->sub(new DateInterval('P1D'));


        $cart->start=$start->format('Y-m-d');
        $cart->saveToSession();
        $this->ajaxGetListBustrips();
        exit();

    }
    function fillInfoCustomer()
    {
        $input=JFactory::getApplication()->input;
        $view = &$this->getView('bustrips', 'html', 'BookProView');
        $view->setLayout('booking_traveller_details');
        $responeArray = array();
        $view->display();
        $contents = ob_get_contents();
        ob_end_clean();
        $responeArray[] = array(
            'key' => '#traveller_details',
            'contents' => $contents
        );
        echo json_encode($responeArray);
        exit();
    }
    function  ajaxGetListBustrips()
    {
        $input=JFactory::getApplication()->input;
        $app=JFactory::getApplication();
        $minRate=$input->get('minRate',-1,'int');
        $maxRate=$input->get('maxRate',-1,'int');
        $roundtrip=$input->get('roundtrip',2,'int');
        $vehicles=$input->get('vehicles',array(),'array');

        $view = &$this->getView('bustrips', 'html', 'BookProView');
        $responeArray = array();
        ob_start();
        $input->set('layout','search');
        $view->setLayout('search_data_car_rentals');
        if($minRate!=-1)
            $app->setUserState('bustrip_filter_minRate',$minRate);
        if($maxRate!=-1)
            $app->setUserState('bustrip_filter_maxRate',$maxRate);
        if($roundtrip==0||$roundtrip==1||$roundtrip==2)
            $app->setUserState('bustrip_filter_roundtrip',$roundtrip);
        $vehicles=implode(',',$vehicles);
        $app->setUserState('bustrip_filter_vehicles',$vehicles);
        $view->numberItemOnOnePage		= 10;
        $view->listBusTrip=$view->getListBustrip();

        $view->parentDisplay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.data-car-rentals',
            'contents' => $contents
        );
        echo json_encode($respone_array);
        exit();
    }
    function ajaxSelectThisBustrip()
    {
        $input=JFactory::getApplication()->input;
        $event_id=$input->get('event_id',0,'int');
        $cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
        $cart->load();
        $cart->event_id=$event_id;

        $view = &$this->getView('bustrips', 'html', 'BookProView');
        $view->setLayout('search_booking_summary');
        $cart->saveToSession();
        $view->bookingBustrip=$view->getBookingBustrip(true);
        $view->parentDisplay();

        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.booking-summary',
            'contents' => $contents
        );
        echo json_encode($respone_array);
        exit();


    }
    function ajaxGetDataNextDay()
    {
        $view = &$this->getView('bustrips', 'html', 'BookProView');
        $responeArray = array();
        ob_start();
        $view->setLayout('search_data_car_rentals');
        JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_bookpro/models','BookproModel');
        $model_bustrips=&JModelLegacy::getInstance('Bustrips', 'BookproModel');
        $view->listBusTrip		= $model_bustrips->getItems();
        $view->numberItemOnOnePage		= 10;
        $view->ajaxGetDataNextDay();
        $contents = ob_get_contents();
        ob_end_clean(); // get the callback function
        $respone_array[] = array(
            'key' => '.data-car-rentals',
            'contents' => $contents
        );
        echo json_encode($respone_array);
        exit();

    }
}
