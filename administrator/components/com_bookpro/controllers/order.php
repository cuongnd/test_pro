<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: order.php 66 2012-07-31 23:46:01Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');

//import needed JoomLIB helpers
AImporter::helper('request', 'controller');

class BookProControllerOrder extends AController {

    var $_model;

    function __construct($config = array()) {
        parent::__construct($config);
        $this->_model = $this->getModel('order');
        $this->_controllerName = CONTROLLER_ORDER;

    }

    /**
     * Display default view - Airport list
     */
    function display() {


        switch ($this->getTask()) {
            case 'publish':
            case 'sendemail':
                $this->sendemail();
                break;
            case 'unpublish':
            case 'bookings':
                JRequest::setVar('view', 'bookings');
                break;
            case 'detail':
                JRequest::setVar('view', 'order');
                break;
            case 'trash':
                $this->state($this->getTask());
                break;
            case 'export_passenger':
                $this->export_passenger();
                break;
            default:
                JRequest::setVar('view', 'orders');
        }

        parent::display();
    }

    /**
     * Open editing form page
     */
    function editing() {
        parent::editing('order');
    }

    /**
     * Cancel edit operation. Check in subject and redirect to subjects list.
     */
    function cancel() {
        parent::cancel('Subject editing canceled');
    }

    /**
     * Save items ordering
     */
    function saveorder() {
        JRequest::checkToken() or jexit('Invalid Token');

        $cids = ARequest::getCids();
        $order = ARequest::getIntArray('order');
        if (ARequest::controlCids($cids, 'save order')) {
            $mainframe = &JFactory::getApplication();
            if ($this->_model->saveorder($cids, $order)) {
                $mainframe->enqueueMessage(JText::_('Successfully saved order'), 'message');
            } else {
                $mainframe->enqueueMessage(JText::_('Order save failed'), 'error');
            }
        }
        ARequest::redirectList(CONTROLLER_ORDER);
    }

    function batchupdate() {

        JRequest::checkToken() or jexit('Invalid Token');
        $mainframe = &JFactory::getApplication();
        $order_id = JRequest::getInt('order_id');

        if (!class_exists('BookProModelOrderInfo')) {
            AImporter::model('orderinfo');
        }
        $modelInfo = new BookProModelOrderInfo();

        $depart = JRequest::getVar('depart', array());
        $orderinfo_id = JRequest::getVar('info_id', array());
        $obj_id = JRequest::getVar('obj_id', array());
        $start = JRequest::getVar('start', array());
        $adult = JRequest::getVar('adult', array());
        $children = JRequest::getVar('child', array());
        $package = JRequest::getVar('package', array());
        $location = JRequest::getVar('location', array());
        $purpose = JRequest::getVar('purpose', array());
        $qty = JRequest::getVar('qty', array());
        for ($i = 0; $i < count($orderinfo_id); $i++) {
            $tdate = $start[$i];
            if (count($depart) > 0) {
                $tdate = JFactory::getDate($start[$i] . ' ' . $depart[$i])->toSql();
            } else {
                $tdate = $tdate = JFactory::getDate($start[$i])->toSql();
            }
            $data = array('id' => $orderinfo_id[$i],
                'obj_id' => $obj_id[$i],
                'adult' => $adult[$i],
                'child' => $children[$i],
                'package' => $package[$i],
                'location' => $location[$i],
                'purpose' => $purpose[$i],
                'start' => $tdate);
            $modelInfo->store($data);
        }
        $this->updateOrder($order_id);
        $this->setRedirect(JURI::base() . 'index.php?option=com_bookpro&controller=order&task=detail&cid[]=' . $order_id);
    }

    private function updateOrder($order_id) {
        $order = JTable::getInstance('orders', 'table');
        $order->load($order_id);
        if (!class_exists('BookProModelOrderInfos')) {
            AImporter::model('orderinfos');
        }
        $modelInfo = new BookProModelOrderinfos();
        $lists = array('order_id' => $order_id);
        $modelInfo->init($lists);
        $datas = $modelInfo->getData();

        $total = 0;
        switch ($order->type) {
            case 'TOUR':

                if (!class_exists('BookProModelTourPackagece')) {
                    AImporter::model('tourpackage');
                }
                foreach ($datas as $row) {
                    $modelpackprice = new BookProModelTourPackage();
                    $modelpackprice->setId($row->obj_id);
                    $price = $modelpackprice->getObject();
                    $total+=$row->adult * $price->price + $row->child * $price->child_price;
                }
                $order->total = $total;
                break;
            case 'TRANSPORT':
                if (!class_exists('BookProModelTransport')) {
                    AImporter::model('transport');
                }
                foreach ($datas as $row) {
                    $modelTransport = new BookProModelTransport();
                    $modelTransport->setId($row->obj_id);
                    $trans = $modelTransport->getObject();
                    $total+=$row->adult * $trans->price;
                }
                $order->total = $total;
                break;

            default:
                ;
                break;
        }

        $order->notes = JRequest::getString('notes');
        $order->order_status = JRequest::getString('order_status');
        $order->pay_status = JRequest::getString('pay_status');

        $order->store();
    }

    function saveorderinfo() {

        JRequest::checkToken() or jexit('Invalid Token');
        $mainframe = &JFactory::getApplication();
        $post = JRequest::get('post');
        $post['id'] = ARequest::getCid();

        if (!class_exists('BookProModelOrderInfo')) {
            AImporter::model('orderinfo');
        }
        $modelInfo = new BookProModelOrderInfo();
        $id = $modelInfo->store($post);


        if (!class_exists('BookProModelPackagePrice')) {
            AImporter::model('packageprice');
        }
        $modelpackprice = new BookProModelPackagePrice();
        $modelpackprice->setId(JRequest::getInt('price_id'));
        $price = $modelpackprice->getObject();




        $order_id = JRequest::getVar('order_id');
        $order = array('id' => $order_id, 'total' => $total);
        $this->_model->store($order);


        if ($id !== false) {
            $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
        } else {
            $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
        }

        $this->setRedirect(JURI::base() . 'index.php?option=com_bookpro&controller=order&task=detail&cid[]=' . $order_id);
    }

    /**
     * Move item up in ordered list
     */
    function orderup() {
        $this->setOrder(- 1);
    }

    /**
     * Move item down in ordered list
     */
    function orderdown() {
        $this->setOrder(1);
    }

    /**
     * Set item order
     *
     * @param int $direct move direction
     */
    function setOrder($direct) {
        JRequest::checkToken() or jexit('Invalid Token');
        $cid = ARequest::getCid();
        $mainframe = &JFactory::getApplication();
        if ($this->_model->move($cid, $direct)) {
            $mainframe->enqueueMessage(JText::_('Successfully moved item'), 'message');
        } else {
            $mainframe->enqueueMessage(JText::_('Item move failed'), 'error');
        }
        ARequest::redirectList(CONTROLLER_ORDER);
    }

    /**
     * Save subject and state on edit page.
     */
    function apply() {
        $this->save(true);
    }

    /**
     * Save subject.
     *
     * @param boolean $apply true state on edit page, false return to browse list
     */
    function sendemail() {


        AImporter::model('customer', 'order', 'application');
        $amount = JRequest::getVar('amount');
        $order_id = JRequest::getVar('order_id');

        $applicationModel = new BookProModelApplication();
        $app = $applicationModel->getObjectByCode($order->type);
        //get order
        $orderModel = new BookProModelOrder();
        $orderModel->setId($order_id);
        $order = $orderModel->getObject();
        //get customer
        $customerModel = new BookProModelCustomer();
        $customerModel->setId($order->user_id);
        $customer = $customerModel->getObject();

        //get list email
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('sendemail.*');
        $query->from('#__bookpro_sendemail AS sendemail');
        $query->where('sendemail.code=' . $db->q($order->type));
        $query->where('sendemail.payment_status=' . $db->q($order->pay_status));
        $query->where('sendemail.order_status=' . $db->q($order->order_status));
        $db->setQuery($query);

        $listsendemail = $db->loadObjectList();
        //send email
        AImporter::helper('email');
        $helperemail = new EmailHelper($order_id);
        if ($listsendemail) {

            foreach ($listsendemail as $email) {

                $body_customer = $email->email_body;

                $body_customer = $helperemail->fillCustomer($body_customer);

                $body_customer = $helperemail->fillOrder($body_customer);
                $body_customer = $helperemail->fillviewdetailtour($body_customer);

                $payment_link = JURI::root() . 'index.php?option=com_bookpro&task=paymentredirect&controller=payment&order_id=' . $order->id;
                $body_customer = str_replace('{payment_link}', $payment_link, $body_customer);

                BookProHelper::sendMail($email->email_send_from, $email->email_send_from_name, $customer->email, $email->email_subject, $body_customer, true);
            }
        }



        $this->setRedirect(JURI::root() . '/administrator/index.php?option=com_bookpro&view=orders');
        return;
    }

    function ajax_sendemail() {
        $this->sendemail();
        exit();
    }

    function save($apply = false) {
        JRequest::checkToken() or jexit('Invalid Token');


        $mainframe = &JFactory::getApplication();

        $post = JRequest::get('post');


        $post['id'] = $post['order_id'];

        $post['notes'] = JRequest::getVar('notes', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $hotel_ids=$post['hotel_id'];
        $db=JFactory::getDbo();

        foreach ($hotel_ids as $roomtypepassenger_id=>$hotel_id)
        {
            $query=$db->getQuery(true);
            $query->update('#__bookpro_order_roomtypepassenger')->set('hotel_id='.$hotel_id);
            $query->where('id='.$roomtypepassenger_id);
            $db->setQuery($query);
            $db->query();
        }

        $id = $this->_model->store($post);

        // notification
        $jinput = JFactory::getApplication()->input;

        if ($id) {
            if ($jinput->getBool('notify_customer', false)) {
                AImporter::helper('email');
                $mailer = new EmailHelper();
                $mailer->changeOrderStatus($id);
            }
        }

        if ($id !== false) {
            $mainframe->enqueueMessage(JText::_('Successfully saved'), 'message');
        } else {
            $mainframe->enqueueMessage(JText::_('Save failed'), 'error');
        }
        if ($apply) {
            ARequest::redirectEdit(CONTROLLER_ORDER, $id);
        } else {
            ARequest::redirectList(CONTROLLER_ORDER);
        }
    }

    function updatehotelposttrip() {

        $app = JFactory::getApplication();
        $input = $app->input;
        $id = $input->get('id');
        $text = $input->get('text');

        $this->updatehotelpretripandposttrip($id, $text);
        exit();
    }

    function savepassenger() {

        $input = JFactory::getApplication()->input;
        $post_array = $input->getArray($_POST);
        $post_array['birthday'] = JFactory::getDate($post_array['birthday'])->toSql();
        $post_array['passport_issue'] = JFactory::getDate($post_array['passport_issue'])->toSql();
        $post_array['passport_expiry'] = JFactory::getDate($post_array['passport_expiry'])->toSql();
        AImporter::model('passenger');
        $post_array['id'] = $post_array['passenger_id'];
        $pModel = new BookProModelPassenger();
        $pModel->store($post_array);
        $order_id = $post_array['order_id'];
        $this->setRedirect('index.php?option=com_bookpro&controller=order&task=detail&cid[]=' . $order_id);
    }

    function updatehotelpretrip() {
        $app = JFactory::getApplication();
        $input = $app->input;
        $id = $input->get('id');
        $text = $input->get('text');
        $this->updatehotelpretripandposttrip($id, $text);
        exit();
    }

    function ajax_allow_convert_point_to_money() {
        $app = JFactory::getApplication();
        $input = $app->input;
        $order_id = $input->get('order_id');

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('o.*');
        $query->from('#__bookpro_orders AS o');
        $query->where('o.id=' . $order_id);
        $db->setQuery($query);
        $order = $db->loadObject();
        $total = (float) $order->total;

        $config = AFactory::getConfig();
        $point_money = $config->point_money;
        $point_money = explode('-', $point_money);

        $point = ($total * $point_money[0]) / $point_money[1];

        $user_id = $order->user_id;

        if ($user_id && $order->request_point == 1) {
            $query = $db->getQuery(true);
            $query->select('point.id');
            $query->from('#__bookpro_point AS point');
            $query->where('point.customer_id=' . $user_id);
            $db->setQuery($query);
            $point_id = $db->loadResult();

            if ($point_id) {
                $query = $db->getQuery(true);
                $query->update('#__bookpro_point AS point')
                    ->set('point.point=(point.point+' . $point . ')')
                    ->where('point.id = ' . $point_id);
                $db->setQuery($query);
                $db->execute();
            } else {
                $query = $db->getQuery(true);

                $query->insert('#__bookpro_point')->columns('customer_id, point')->values($user_id . ',' . $point);
                $db->setQuery($query);
                $db->execute();
            }
        }
        $query = $db->getQuery(true);
        $query->update('#__bookpro_orders AS o')
            ->set('o.request_point=-1')
            ->where('o.id = ' . $order_id);
        $db->setQuery($query);
        $db->execute();
        exit();
    }

    function updatehotelpretripandposttrip($id = 0, $text = '') {

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update($db->qn('#__bookpro_order_roomtypepassenger'))
            ->set($db->qn('hotel') . ' = ' . $db->q($text))
            ->where('id = ' . $id);
        $db->setQuery($query);
        $db->execute();
    }

    function updatestateorder() {
        $app = JFactory::getApplication();
        $input = $app->input;
        $id = $input->get('id');
        $order_status = $input->get('order_status');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update($db->qn('#__bookpro_orders'))
            ->set($db->qn('order_status') . ' = ' . $db->q($order_status))
            ->where('id = ' . $id);
        $db->setQuery($query);
        $db->execute();
        JRequest::setVar('order_id', $id);
        $this->sendemail();
        exit();
    }

    function updatepaymentstatus() {
        $app = JFactory::getApplication();
        $input = $app->input;
        $id = $input->get('id');
        $pay_status = $input->get('pay_status');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update($db->qn('#__bookpro_orders'))
            ->set($db->qn('pay_status') . ' = ' . $db->q($pay_status))
            ->where('id = ' . $id);
        $db->setQuery($query);
        $db->execute();
        JRequest::setVar('order_id', $id);
        $this->sendemail();
        exit();
    }

    function ajax_updatenotes(){
        $data=JRequest::get( 'post' );

//        JTable::addIncludePath(JPATH_ROOT.'/administrator/components/com_bookpro/tables');
//        $tableCountry=JTable::getInstance('Orders','Table');
//
//        if(!$tableCountry->store())
//        {
//            echo $tableCountry->getError();
//        }
//        die;

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('notes') . ' = ' ."'".$data['info_notes']."'",
        );
        $query->update("#__bookpro_orders as a");
        $query->set($fields);
        $query->where("a.id=".$data['id']);

        $db->setQuery($query);
        $db->execute ();



    }

    function ajax_updatecustomer(){
        AImporter::model('order');

        $orders_id=$_GET['id_order'];
        $lastname=$_GET['lastname'];
        $firstname=$_GET['firstname'];


        $morder= new BookproModelOrder();
        $morder->setId($orders_id);
        $data=$morder->getObject();

        //
        $db = JFactory::getDBO();
        $query = $db->getQuery ( true );
        //get oject by id
        $query="UPDATE #__bookpro_customer SET firstname= '$firstname' , lastname='$lastname' where id=$data->user_id";
        $db->setQuery($query);
        try {
            $db->execute ();
            return true;

        } catch ( RuntimeException $e ) {
            return false;
        }

    }

    function ajax_updateassigned(){
        $data=JRequest::get('post');

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->update("#__bookpro_orders as a");
        $query->set("assigned_id=".$data['assigned']);
        $query->where("a.id=".$data['id']);
        $db->setQuery($query);
        $db->execute ();die;
    }
    function ajax_update_orderstatus(){
        $data=JRequest::get('post');

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->update("#__bookpro_orders as a");
        $query->set("order_status=".$data['newselected']);
        $query->where("a.id=".$data['id']);
        $db->setQuery($query);
        $db->execute ();die;
    }
    function ajax_add_passenger(){
        $data=JRequest::get('post');
        $model= new BookProModelPassenger();
        $model->store($data);
        die;
    }

}

?>