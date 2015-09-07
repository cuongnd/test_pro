<?php
/**
 * @package    Bookpro
 * @author        Nguyen Dinh Cuong
 * @link        http://ibookingonline.com
 * @copyright    Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license    GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version    $Id$
 * */
defined('_JEXEC') or die ('Restricted access');
AImporter::model('order', 'orders', 'customer', 'application', 'customtrip', 'passenger', 'passengers');
AImporter::helper('currency', 'date', 'request');
class EmailHelper
{

    /**
     *
     * @param String $input
     * @param CustomerTable $customer
     */
    var $config;
    var $app;
    var $order;
    var $customer;
    var $orderinfo;
    var $agents = array();

    public function EmailHelper($order_id = null)
    {
        $this->config = AFactory::getConfig();
        if ($order_id) {

            AImporter::model('order', 'customer', 'orderinfos');
            $orderModel = new BookProModelOrder ();
            $orderModel->setId($order_id);
            $this->order = $orderModel->getObject();

            $omodel = new BookProModelOrderInfos ();
            $omodel->init(array(
                'order_id' => $this->order->id
            ));
            $this->orderinfo = $omodel->getData();

            $customerModel = new BookProModelCustomer ();
            $customerModel->setId($this->order->user_id);
            $this->customer = $customerModel->getObject();
        }
        $applicationModel = new BookProModelApplication ();
        $this->app = $applicationModel->getObjectByCode($this->order->type);
    }

    /*
     * Send email message: From client to user
     * 1. Load model
     * 2. Filldata
     * 3. Send email
     */

    public function sendMessageEmail($message_id, $type, $reply_id)
    {
        $config = JFactory::getConfig();
        $data['fromname'] = $config->get('fromname');
        //$data['fromname'] = "mail-noreply@ibookingonline.com";
        $data['mailfrom'] = $config->get('mailfrom');
        /*
        * Get subject message
        */
        $model = new BookProModelMessage();
        $message = $model->loadMessage($message_id);

        $data['subject'] = $message->subject;
        $data['message'] = $message->message;
        //$data['messagenumber']= $message ->message_number;

        $data['email'] = $message->email;
        $data['name'] = $message->name;
        $data['cid_from'] = $message->cid_from;

        $data['emailadmin'] = $data['mailfrom']; //For test

        //get email cid_to ->assigned
        if ($message->cid_to) {
            //Load email cid_to
            $email_cid_to = $model->loadEmailcid_to($message_id)->email;
            $data['emailadmin'] = $email_cid_to;
        }
        //admin
        $admin_email_subject;
        $admin_email_body;
        /*
         * Type:0 1 2
        */
        if ($type == 0) { //New message
            $emailSubject = JText::sprintf('COM_BOOKPRO_MESSAGES_EMAIL_SUBJECT');
            $admin_email_subject = JText::sprintf('COM_BOOKPRO_MESSAGES_ADMIN_EMAIL_SUBJECT');

            $emailBody = JText::sprintf('COM_BOOKPRO_MESSAGES_EMAIL_BODY', $data['name'], $data['subject'], $data['messagenumber'], $data['email'], $data['email'], $data['message'], JUri::root(), $message->id, JUri::root());

            $admin_email_body = JText::sprintf('COM_BOOKPRO_MESSAGES_ADMIN_EMAIL_BODY', $data['subject'], $data['name'], $data['messagenumber'], $data['email'], $data['email'], $data['message'], JUri::root() . "administrator", $message->id, $data['cid_from'], JUri::root() . "administrator");
        } else {
            //Reply email -Client Admin
            //get ticket message
            $data['message'] = $model->loadMessagemsg($reply_id)->message;
            $emailSubject = JText::sprintf('COM_BOOKPRO_MESSAGES_EMAIL_SUBJECT_REPLY');
            $emailBody = JText::sprintf('COM_BOOKPRO_MESSAGES_EMAIL_BODY_REPLY', $data['subject'], $data['name'], $data['messagenumber'], $data['email'], $data['email'], $data['message'], JUri::root(), $message_id, JUri::root());
            if ($type == 1) { //type ==2 admin reply

                $admin_email_subject = JText::sprintf('COM_BOOKPRO_MESSAGES_ADMIN_EMAIL_SUBJECT_REPLY');
                $admin_email_body = JText::sprintf('COM_BOOKPRO_MESSAGES_ADMIN_EMAIL_BODY_REPLY', $data['subject'], $data['name'], $data['messagenumber'], $data['email'], $data['email'], $data['message'], JUri::root() . "administrator", $message_id, $data['cid_from'], JUri::root() . "administrator");
            }
        }
        /*
          echo $emailBody;
          echo $admin_email_body;
          die();
         */
        //send to admin

        if ($type != 2)
            JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['emailadmin'], $admin_email_subject, $admin_email_body, 1);
        //send to client
        JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody, 1);
    }

    public function sendCustomtripEmail($order_id,$customtrip='')
    {
        //load order
        //load customer

        $config = JFactory::getConfig();
        $data['fromname'] = $config->get('fromname');
        //$data['fromname'] = "mail-noreply@ibookingonline.com";
        $data['mailfrom'] = $config->get('mailfrom');

        $model_order = new  BookProModelOrder();
        $_modelCustomtrip = new BookProModelCustomTrip();

        $order = $model_order->getObjectByID($order_id);
        $customer = $order->customer;

        $data['cname'] = $customer->firstname . '' . $customer->lastname;
        $data['cfullname'] = $customer->firstname . '' . $customer->lastname;
        $data['caddress'] = $customer->address;
        $data['ccountry'] = $_modelCustomtrip->getCountryNameById($customer->country_id);
        $data['cworkphone'] = $customer->mobile;
        $data['chomephone'] = $customer->telephone;
        $data['cemail'] = $customer->email;
        $data['onotes'] = $order->notes;

        //admin email
        $data['aemail'] = $data['mailfrom'];

        //

        $emailSubject = JText::sprintf('COM_BOOKPRO_CUSTOMTRIP_EMAIL_SUBJECT');
        $admin_email_subject = JText::sprintf('COM_BOOKPRO_CUSTOMTRIP_ADMIN_EMAIL_SUBJECT');

        require_once JPATH_BASE.'/components/com_bookpro/controllers/order.php';
        $control=new BookProControllerOrder();
        $view = $control->getView('orderdetail', 'html', 'BookProView');
        $view->assign('order', $order);
        $view->sendmail=1;
        JRequest::setVar('layout','emailcustomtrip');
        $view->tmpl='component';
        ob_start();
        $view->display();
        $content=ob_get_contents();
        ob_end_flush();

    $emailBody = $content.$customtrip['notes'];
        $admin_email_body = $emailBody;
        //send to admin

        JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['cemail'], $admin_email_subject, $admin_email_body, 1);

        //send to client
        JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['cemail'], $emailSubject, $emailBody, 1);

    }

    public function getPassenger($id)
    {

    }

    public function sendCustomtripChangepassenger($order_id)
    {

        $config = JFactory::getConfig();
        $data['fromname'] = $config->get('fromname');
        //$data['fromname'] = "mail-noreply@ibookingonline.com";
        $data['mailfrom'] = $config->get('mailfrom');

        $model_order = new  BookProModelOrder();
        $_modelCustomtrip = new BookProModelCustomTrip();

        $order = $model_order->getObjectByID($order_id);
        $customer = $order->customer;


        $data['cname'] = $customer->firstname . '' . $customer->lastname;
        $data['cfullname'] = $customer->firstname . '' . $customer->lastname;
        $data['caddress'] = $customer->address;
        $data['ccountry'] = $_modelCustomtrip->getCountryNameById($customer->country_id);
        $data['cworkphone'] = $customer->mobile;
        $data['chomephone'] = $customer->telephone;
        $data['cemail'] = $customer->email;
        $data['onotes'] = $order->notes;

        //load passenger
        $model_passenger = new BookProModelPassenger();

        $passengers = $this->getPassengers($order_id);

        $passenger_str = "";
        $tprice = 0;
        foreach ($passengers as $key => $passenger) {
            $passenger_str .= "<tr>";
            $passenger_str .= "<td>" . $key . "</td>";
            $passenger_str .= "<td>" . $passenger->firstname . "</td>";
            $passenger_str .= "<td>" . $passenger->lastname . "</td>";
            if ((int)($passenger->birthday))
                $passenger_str .= "<td>" . JFactory::getDate($passenger->birthday)->format('d-m-Y') . "</td>";
            else $passenger_str .= "<td> </td>";

            $str;
            $group = $passenger->group_id;
            if ($group == 1) $str = "Adult"; else if ($group == 2) $str = "Children";
            else $str = "Infant";
            $passenger_str .= "<td>" . $str . "</td>";
            $passenger_str .= "<td>" . CurrencyHelper::displayPrice($passenger->price) . "</td>";
            $tprice += $passenger->price;
        }
        $passenger_str .= "</tr>";
        $passenger_str .= "<tr> <td colspan='5' style='text-align: center;'> Total price </td><td>" . CurrencyHelper::displayPrice($tprice) . " </td></tr>";

        $emailSubject = JText::sprintf('COM_BOOKPRO_CUSTOMTRIP_EMAIL_CHANGE_PASSENGER_SUBJECT');
        $emailBody = JText::sprintf('COM_BOOKPRO_CUSTOMTRIP_EMAIL_CHANGE_PASSENGER_BODY', $data['cname'], $data['cfullname'], $data['caddress'], $data['ccountry'], $data['ccountry'], $data['cworkphone'], $data['chomephone'], $data['cemail'], $data['cemail'], $data['onotes'], $passenger_str, JUri::root(), $order_id, JUri::root());

        //var_dump($emailBody);
        //die();


        JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['cemail'], $emailSubject, $emailBody, 1);


    }

    public function getPassengers($order_id)
    {
        $model = new BookProModelPassengers();
        $lists = array('order_id' => $order_id);
        $model->init($lists);
        $lists = $model->getData();
        return $lists;
    }

    public function sendMail()
    {
        jimport('joomla.error.log');
        JLog::addLogger(array(
            'text_file' => 'booking.log.txt',
            'text_file_path' => 'logs'
        ));

        if ($this->order->id) {

            $body_customer = $this->app->email_customer_body;
            $body_customer = $this->fillCustomer($body_customer);

            AImporter::model('order');
            $model = new BookProModelOrder();
            $order = $model->getObjectByID($this->order->id);
            require_once JPATH_BASE.'/components/com_bookpro/controllers/order.php';
            $control=new BookProControllerOrder();
            $view = $control->getView('orderdetail', 'html', 'BookProView');
            $view->assign('order', $order);
            $view->sendmail=1;
            JRequest::setVar('layout','email');
            $view->tmpl='component';
            ob_start();
            $view->display();
            $content=ob_get_contents();
            ob_end_flush();
            $body_customer = $content;

            BookProHelper::sendMail($this->app->email_send_from, $this->app->email_send_from_name, $this->customer->email, $this->app->email_customer_subject, $body_customer, true);
            $body_supplier = $this->app->email_supplier_body;
            $body_supplier = $this->fillCustomer($body_supplier);
            $body_supplier = $this->fillOrder($body_supplier);
            AImporter::helper('tour');
            AImporter::model('customer');

            $hotel = TourHelper::getObjectTourByOrder($this->order->id);
            $model = new BookProModelCustomer ();

            if ($hotel->userid) {
                $cModel = new BookProModelCustomer ();
                $supplier = $cModel->getCustomerByID($hotel->userid);

                BookProHelper::sendMail($this->app->email_send_from, $this->app->email_send_from_name, $supplier->email, $this->app->email_supplier_subject, $body_supplier, true);
            }

            $body_admin = $this->app->email_admin_body;
            $body_admin = $this->fillCustomer($body_admin);
            $body_admin = $this->fillOrder($body_admin);

            // $log->addEntry(array('status'=>$this->app->email_admin,'comment'=>'Send email to admin'));

            BookProHelper::sendMail($this->app->email_send_from, $this->app->email_send_from_name, $this->app->email_admin, $this->app->email_admin_subject, $body_admin, true);

            if ($this->order->pay_status == "SUCCESS" && $this->order->order_status == 'FINISHED') {
                // $this->sendAgentEmail();
            }
        } else {
            // $log->addEntry(array('status'=>$order_id,'comment'=>'Can not send email for this order'));
        }
    }

    /**
     *
     * @param html $input
     * @param Customer $customer
     * @return mixed
     */
    public function fillCustomer($input)
    {
        $input = str_replace('{email}', $this->customer->email, $input);

        $input = str_replace('{firstname}', $this->customer->firstname, $input);

        $input = str_replace('{lastname}', $this->customer->lastname, $input);
        $input = str_replace('{username}', $this->customer->username, $input);
        $input = str_replace('{password}', $this->customer->password, $input);
        $input = str_replace('{mobile}', $this->customer->mobile, $input);
        $input = str_replace('{address}', $this->customer->address, $input);
        $input = str_replace('{city}', $this->customer->city, $input);
        $input = str_replace('{gender}', BookProHelper::formatGender($this->customer->gender), $input);
        $input = str_replace('{telephone}', $this->customer->telephone, $input);
        $input = str_replace('{states}', $this->customer->states, $input);
        $input = str_replace('{zip}', $this->customer->zip ? 'N/A' : $this->customer->zip, $input);
        $input = str_replace('{country}', $this->customer->country_name, $input);
        return $input;
    }

    public function fillOrder($input)
    {
        if ($this->order->type == 'HOTEL') {
            AImporter::helper('hotel');
            $infos = HotelHelper::getRooms($this->order->id);
            $layout = new JLayoutFile ('rooms', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');
            $html = $layout->render($infos);
            $input = str_replace('{list_rooms}', $html, $input);
            // $input=$this->fillTourInfo($input);
        }

        AImporter::model('orderinfos');
        $infomodel = new BookProModelOrderinfos ();
        $infomodel->init(array(
            'order_id' => $this->order->id
        ));

        $a_orderinfo = $infomodel->getData();
        for ($i = 0; $i < count($a_orderinfo); $i++) {
            if ($a_orderinfo [$i]->type = "HOTEL_ROOM") {
                $room = $a_orderinfo [$i];

                // $numberday = DateHelper::getCountDay($room->start, $room->end);
                // $rooms = $room->qty;
                // $hotel = HotelHelper::getHotelbyRoomID($room->obj_id);
                // $checkin_date= DateHelper::formatDate($room->start);
                // $checkout_date=DateHelper::formatDate($room->end);
                // $totaladult=$room->adult;
                // $totalchild=$room->child;

                break;
            }
        }

        $input = str_replace('{hotel_title}', $hotel->title, $input);
        $input = str_replace('{order_number}', $this->order->order_number, $input);
        $input = str_replace('{total}', CurrencyHelper::formatprice($this->order->total), $input);
        $input = str_replace('{note}', $this->order->notes, $input);
        $input = str_replace('{payment_status}', $this->formatPaymentStatus($this->order->pay_status), $input);
        $input = str_replace('{deposit}', $this->order->deposit, $input);
        $input = str_replace('{pay_method}', $this->order->pay_method, $input);
        $input = str_replace('{note}', $this->order->notes, $input);
        $input = str_replace('{created}', DateHelper::formatDate($this->order->created), $input);
        $input = str_replace('{order_status}', $this->order->order_status, $input);
        if ($this->customer->user_id) {
            $order_link = JURI::root() . 'index.php?option=com_bookpro&controller=order&task=detail&order_id=' . $this->order->id;
        } else {
            $order_link = JURI::root() . 'index.php?option=com_bookpro&view=guest';
        }
        $input = str_replace('{order_link}', $order_link, $input);
        // $this->fillPassenger($input);
        if ($this->order->type == 'TOUR') {
            $input = $this->fillTourInfo($input);
        }

        if ($this->order->type == 'TRANSPORT') {
            $input = $this->fillTransportInfo($input);
        }
        return $input;
    }

    private function formatPaymentStatus($status)
    {
        return JText::_('COM_BOOKPRO_EMAIL_PAYMENT_STATUS_' . strtoupper($status));
    }

    private function fillTourInfo($input)
    {
        AImporter::helper('tour');
        $tour = TourHelper::getBookedTour($this->order->id);
        if (!class_exists('BookProModelOrderInfos')) {
            AImporter::model('orderinfos');
        }
        $input = str_replace('{tour_name}', $tour->title, $input);
        $input = str_replace('{traveller}', ($this->orderinfo [0]->adult + $this->orderinfo [0]->child), $input);
        $input = str_replace('{start_time}', $tour->start_time, $input);
        $input = str_replace('{depart}', DateHelper::formatDate($this->orderinfo [0]->start), $input);
        $input = str_replace('{package_name}', $tour->package_name, $input);
        $input = str_replace('{package_price}', $tour->package_price, $input);
        return $input;
    }

    function fillTransportInfo($input)
    {
        AImporter::model('transports', 'transport', 'orderinfos', 'airport');
        $infomodel = new BookProModelOrderinfos ();
        $param = array(
            'order_id' => $order->id
        );
        $infomodel->init($param);
        $orderinfos = $infomodel->getData();

        $tmodel = new BookProModelTransport ();
        for ($i = 0; $i < count($orderinfos); $i++) {
            $tmodel->setId($orderinfos [$i]->obj_id);
            $transport = $tmodel->getObject();

            $model = new BookProModelAirport ();
            $model->setId($transport->from);
            $dest = $model->getObject();

            $orderinfos [$i]->from_type = $dest->air;
            $orderinfos [$i]->tfrom = $transport->tfrom;

            $model = new BookProModelAirport ();
            $model->setId($transport->to);
            $dest = $model->getObject();

            $orderinfos [$i]->to_type = $dest->air;
            $orderinfos [$i]->tto = $transport->tto;
        }
        $infos = "<table class='transport_trip'><thead>
            <tr>
            <th>" . JText::_('COM_BOOKPRO_TRANSPORT_PICKUP_LOCATION') . "</th><th>" . JText::_('COM_BOOKPRO_TRANSPORT_DROP_LOCATION') . "</th>
            <th>" . JText::_('COM_BOOKPRO_BUSTRIP_PRICE') . "</th><th>" . JText::_('COM_BOOKPRO_TRAVELER') . "</th></tr></thead><tbody>";

        foreach ($orderinfos as $trip) {
            $infos .= '<tr><td nowrap="nowrap" valign="top">' . $trip->tfrom . '<br/>';
            if ($trip->from_type) {
                $infos .= JText::_('COM_BOOKPRO_TRANSPORT_FLIGHT_NUMBER') . ': ' . $trip->purpose . '<br/>';
                $infos .= JText::_('COM_BOOKPRO_TRANSPORT_FLIGH_TIME') . ':' . JFactory::getDate($trip->start)->format('d-m-Y H:i');
            } else {
                $infos .= $trip->location;
            }
            $infos .= "</td>";
            $infos .= '<td nowrap="nowrap" valign="top">' . $trip->tto . '<br />';
            if ($trip->to_type && !$trip->from_type) {
                $infos .= JText::_('COM_BOOKPRO_TRANSPORT_FLIGHT_NUMBER') . ': ' . $trip->purpose . '<br/>';
                $infos .= JText::_('COM_BOOKPRO_TRANSPORT_FLIGH_TIME') . ':' . JFactory::getDate($trip->start)->format('d-m-Y H:i');
            } else {
                $infos .= $trip->location;
            }
            $infos .= '</td><td>' . CurrencyHelper::formatprice($trip->price) . '</td>';
            $infos .= '<td>' . $trip->adult . '</td></tr>';
        }
        $infos .= "</tbody></table>";
        $input = str_replace('{transports}', $infos, $input);
        return $input;
    }

    public function changeOrderStatus($order_id)
    {
        $orderModel = new BookProModelOrder ();
        $applicationModel = new BookProModelApplication ();
        $customerModel = new BookProModelCustomer ();

        $orderModel->setId($order_id);
        $order = $orderModel->getObject();
        $customerModel->setId($order->user_id);
        $customer = $customerModel->getObject();
        $this->app = $applicationModel->getObjectByCode($order->type);
        $msg = 'COM_BOOKPRO_ORDER_STATUS_' . $order->order_status . '_EMAIL_BODY';
        $body_customer = JText::_($msg);
        $body_customer = $this->fillCustomer($body_customer, $customer);
        $body_customer = $this->fillOrder($body_customer, $order);
        BookProHelper::sendMail($this->app->email_send_from, $this->app->email_send_from_name, $customer->email, JText::_('COM_BOOKPRO_ORDER_STATUS_CHANGE_EMAIL_SUB'), $body_customer, true);
    }

    public function registerNotify($custtomer_id)
    {
        $customerModel = new BookProModelCustomer ();
        $customerModel->setId($custtomer_id);
        $this->customer = $customerModel->getObject();

        $body_admin = $this->config->sendRegistrationsBodyAdmin;
        $body_customer = $this->config->sendRegistrationsBodyCustomer;

        $body_customer = $this->fillCustomer($body_customer);
        $body_admin = $this->fillCustomer($body_admin);

        if ($this->config->sendRegistrationsEmails = 1 || $this->config->sendRegistrationsEmails = 3)
            BookProHelper::sendMail($this->config->sendRegistrationsEmailsFrom, $this->config->sendRegistrationsEmailsFromname, $customer->email, $this->config->sendRegistrationsEmailsSubjectCustomer, $body_customer, true);
        if ($this->config->sendRegistrationsEmails = 1 || $this->config->sendRegistrationsEmails = 2)
            BookProHelper::sendMail($config->sendRegistrationsEmailsFrom, $config->sendRegistrationsEmailsFromname, $config->sendRegistrationsEmailsFrom, $config->sendRegistrationsEmailsSubjectAdmin, $body_admin, $htmlMode);
    }

    public function fillviewdetailtour($input, $type = '')
    {
        echo "fillviewdetailtour";
        die;
        $array_tour_view = array(
            "additional_trips" => "additionnaltripprice",
            "passenger_infomation" => "listpassenger",
            "post-trip_hotel" => "posttripprice",
            "pre-trip_hotel" => "pretripprice",
            "post-trip_transfer" => "posttriptransferprice",
            "pre-trip_transfer" => "pretriptransferprice",
            "room_selected" => "roomselected"
        );
        if ($this->order->type == 'TOUR') {
            $view = & $this->getView('order', 'html', 'BookProView');
            JRequest::setVar('layout', 'tour');
            foreach ($array_tour_view as $key => $tpl) {
                ob_start();

                JRequest::setVar('layout', 'tour');
                JRequest::setVar('tpl', $tpl);
                $view->display();
                $contents = ob_get_contents();
                ob_end_clean(); // get the callback function
                $input = $contents;
                $input = str_replace("{$key}", $contents, $input);
            }

        }


        return $input;
    }

    public function fillSupplier($input)
    {
        if ($this->order->type == 'HOTEL') {
            AImporter::helper('hotel');
            $infos = HotelHelper::getRooms($this->order->id);
            $layout = new JLayoutFile ('rooms', $basePath = JPATH_ROOT . '/components/com_bookpro/layouts');
            $html = $layout->render($infos);
            $input = str_replace('{list_rooms}', $html, $input);
            // $input=$this->fillTourInfo($input);
        }

        AImporter::model('orderinfos');
        $infomodel = new BookProModelOrderinfos ();
        $infomodel->init(array(
            'order_id' => $this->order->id
        ));

        $a_orderinfo = $infomodel->getData();
        for ($i = 0; $i < count($a_orderinfo); $i++) {
            if ($a_orderinfo [$i]->type = "HOTEL_ROOM") {
                $room = $a_orderinfo [$i];

                // $numberday = DateHelper::getCountDay($room->start, $room->end);
                // $rooms = $room->qty;
                $hotel = HotelHelper::getHotelbyRoomID($room->obj_id);
                // $checkin_date= DateHelper::formatDate($room->start);
                // $checkout_date=DateHelper::formatDate($room->end);
                // $totaladult=$room->adult;
                // $totalchild=$room->child;

                break;
            }
        }

        $input = str_replace('{hotel_title}', $hotel->title, $input);
        $input = str_replace('{order_number}', $this->order->order_number, $input);
        $input = str_replace('{total}', CurrencyHelper::formatprice($this->order->total), $input);
        $input = str_replace('{note}', $this->order->notes, $input);
        $input = str_replace('{payment_status}', $this->formatPaymentStatus($this->order->pay_status), $input);
        $input = str_replace('{deposit}', $this->order->deposit, $input);
        $input = str_replace('{pay_method}', $this->order->pay_method, $input);
        $input = str_replace('{note}', $this->order->notes, $input);
        $input = str_replace('{created}', DateHelper::formatDate($this->order->created), $input);
        $input = str_replace('{order_status}', $this->order->order_status, $input);
        if ($this->customer->user_id) {
            $order_link = JURI::root() . 'index.php?option=com_bookpro&controller=order&task=detail&order_id=' . $this->order->id;
        } else {
            $order_link = JURI::root() . 'index.php?option=com_bookpro&view=guest';
        }
        $input = str_replace('{order_link}', $order_link, $input);
        // $this->fillPassenger($input);
        if ($this->order->type == 'TOUR') {
            $input = $this->fillTourInfo($input);
        }

        if ($this->order->type == 'TRANSPORT') {
            $input = $this->fillTransportInfo($input);
        }
        return $input;
    }

    private function fillPassenger($input)
    {
        AImporter::model('passengers');
        $model = new BookProModelPassengers ();
        $model->init(array(
            'order_id' => $order->id
        ));
        $items = $model->getData();
        if (count($items)) {
            ob_start();
            ?>
            <table style = "width: 100%;">
                <?php foreach ($items as $item) { ?>
                    <tr>
                        <td><?php echo $item->firsname ?></td>
                        <td><?php echo $item->firsname ?></td>
                        <td><?php echo $item->birthday ?></td>
                    </tr>
                <?php
                }
                ?>
            </table>
            <?php
            $html = ob_get_contents();
            ob_end_clean();
            $input = str_replace('{passengers}', $html, $input );
		}
	}
}

