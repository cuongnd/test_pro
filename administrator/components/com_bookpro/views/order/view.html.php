<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 62 2012-07-29 01:18:34Z quannv $
 * */
defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');

//import needed models
AImporter::model('customer', 'order', 'passengers', 'orders', 'orderinfos');
//import needed JoomLIB helpers
AImporter::helper('bookpro', 'request', 'paystatus', 'ordertype', 'orderstatus');

//import needed assets


class BookProViewOrder extends BookproJViewLegacy {

    /**
     * Prepare to display page.
     *
     * @param string $tpl name of used template
     */
    function display($tpl = null) {

        $mainframe = &JFactory::getApplication();
        /* @var $mainframe JApplication */
        $document = &JFactory::getDocument();

        /* @var $document JDocument */
        $model = new BookProModelOrder();
        $model->setId(ARequest::getCid());
        $order = &$model->getObject();
        $this->listChildrenOrderIds=array();
        $model->getListChildrenOrder($this->listChildrenOrderIds,$order->id);
        $listOrderIdLevel1=$this->listChildrenOrderIds[$order->id];
        $this->listOrderLevel1=array();
        foreach($listOrderIdLevel1 as $orderId)
        {
            $model = new BookProModelOrder();
            $model->setId($orderId);
            $obj=new stdClass();

            //order info
            $order= $model->getObject();
            $obj->order=$order;
            //end order

            //get assigned user
            $order->user=JFactory::getUser($order->assigned_id);
            //end get assigned user

            //customer
            $cModel = new BookProModelCustomer();
            $cModel->setId($order->user_id);
            $customer = $cModel->getObject();
            $obj->customer=$customer;
            //end customer
            //get receipt
            AImporter::model('paylogs');
            $modelPayLogs = new BookProModelPayLogs();
            $modelPayLogs->setState('order_id',$orderId);
            $paymentLogs = $modelPayLogs->getData();
            $receipt=0;
            foreach($paymentLogs as $payment)
            {
                $receipt+=$payment->amount;
            }
            $obj->receipt=$receipt;
            //end get receipt

            //get balance
            $obj->balance=$order->total-$receipt;
            //end get balance

            //check has sub order
            if(count($this->listChildrenOrderIds[$orderId]))
            {
                $obj->hassuborder=true;
            }
            //end check has sub order
            //get orderinfo
            $infomodel = new BookProModelOrderinfos();
            $param = array('order_id' => $order->id);
            $infomodel->init($param);
            $orderinfos = $infomodel->getData();
            $listOrderInfo=array();
            foreach($orderinfos as $orderinfo)
            {
                switch ($order->type) {
                    case 'TOUR':
                        AImporter::helper('tour');
                        $objectInfo=TourHelper::getBookedTour($orderId);
                        AImporter::model('PackageRate');
                        $modelPackageRate = new BookProModelPackageRate();
                        $modelPackageRate = new BookProModelPackageRate();
                        $modelPackageRate->setId($orderinfo->obj_id);
                        $packageRate=$modelPackageRate->getObject();
                        $serviceDate=$packageRate->date;
                        $orderinfo->serviceDate=$serviceDate;
                        $orderinfo->objectInfo= $objectInfo;
                        $orderinfo->title=$objectInfo->title;
                        $orderinfo->link="index.php?option=com_bookpro&view=tour&cid=$objectInfo->id";
                        $orderinfo->code=$objectInfo->code;
                        $listOrderInfo[]=$orderinfo;




                        break;
                    case 'FLIGHT':
                        $modelFlight = new BookProModelFlight();
                        $modelFlight->setId($orderinfo->obj_id);
                        $objectInfo=$modelFlight->getObject();
                        $orderinfo->objectInfo= $objectInfo;
                        $orderinfo->tite=$objectInfo->title;
                        $listOrderInfo[]=$orderinfo;
                        break;
                    default:
                        $orderinfo->tite='Error';
                        $listOrderInfo[]=$orderinfo;
                }
            }
            $obj->listOrderInfo=$listOrderInfo;

            //end get order info
            $this->listOrderLevel1[]=$obj;
        }


        $cModel = new BookProModelCustomer();
        $cModel->setId($order->user_id);
        $customer = $cModel->getObject();
        $layout = JRequest::getVar('layout');
        $tpl = JRequest::getVar('tpl');
        $this->setLayout($layout);


        //$countryselectbox = $this->getCountrySelectBox($customer->country_id);



        if ($this->getLayout() == 'form') {
            $this->_displayForm($tpl, $order);
            return;
        }
        $document->setTitle(BookProHelper::formatName($customer));
        $params = JComponentHelper::getParams(OPTION);
        /* @var $params JParameter */

        $infomodel = new BookProModelOrderinfos();
        $param = array('order_id' => $order->id);
        $infomodel->init($param);
        $orderinfo = $infomodel->getData();

        $this->assignRef('customer', $customer);
        $this->assignRef('payment', $payment);
        $this->assignRef('order', $order);
        $this->assignRef('orderinfo', $orderinfo);
        $this->assignRef('params', $params);
//        echo "<pre>";
//        print_r($orderinfo);
//        echo "</pre>";
////customer
//        echo $customer->lastname.$customer->firstname;
////depart date and end_date
//        echo $orderinfo['0']->start;
//        echo $orderinfo['0']->end;
////created
//        echo $orderinfo['0']->created;
////adult and child and infant(tre sơ sinh) and enfant(trẻ con)
//        echo $orderinfo['0']->adult;
//        echo $orderinfo['0']->child;
//        echo $orderinfo['0']->enfant;
//        echo $orderinfo['0']->infant;


        parent::display($tpl);
        return;
    }

    function _displayForm($tpl, $order) {
        $document = &JFactory::getDocument();
        /* @var $document JDocument */

        $error = JRequest::getInt('error');
        $data = JRequest::get('post');
        if ($error) {
            $order->bind($data);
        }

        if (!$order->id && !$error) {
            $order->init();
        }
        JFilterOutput::objectHTMLSafe($order);

        $params = JComponentHelper::getParams(OPTION);
        /* @var $params JParameter */
        $this->assignRef('obj', $order);
        $this->assignRef('params', $params);
        $this->assignRef('paystatus', $this->getPayStatusSelect($order->pay_status));
        $this->assignRef('orderstatus', $this->getOrderStatusSelect($order->order_status));
        $this->assignRef('ordertype', $this->getOrderTypeSelect($order->type));
        $this->assignRef('customers', $this->getCustomerSelectBox($order->user_id));
        parent::display($tpl);
    }

    function getCountrySelectBox($select, $field = 'country_id', $autoSubmit = false) {
        AImporter::model('countries');
        $model = new BookProModelCountries();
        $fullList = $model->getItems();
        return AHtml::getFilterSelect($field, JText::_('COM_BOOKPRO_SELECT_COUNTRY'), $fullList, $select, $autoSubmit, '', 'id', 'country_name');
    }
    function getHotelSelectBox($select, $field = 'hotel_id', $autoSubmit = false) {
    	AImporter::model('hotels');
    	$model = new BookProModelHotels();
    	$fullList = $model->getData();
    	return AHtml::getFilterSelect($field, JText::_('COM_BOOKPRO_SELECT_HOTEL'), $fullList, $select, $autoSubmit, '', 'id', 'title');
    }

    function getPayStatusSelect($select) {
        PayStatus::init();
        return AHtml::getFilterSelect('pay_status', 'Pay status', PayStatus::$map, $select, false, '', 'value', 'text');
    }

    function getOrderTypeSelect($select) {
        OrderType::init();
        return AHtml::getFilterSelect('type', 'Order Type', OrderType::$map, $select, false, '', 'value', 'value');
    }

    function getOrderStatusSelect($select) {
        OrderStatus::init();
        return AHtml::getFilterSelect('order_status', 'Order Status', OrderStatus::$map, $select, false, '', 'value', 'text');
    }

    function getListSale($select) {
        $config = AFactory::getConfig();
        $config->sale_group=252;
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

    function getCustomerSelectBox($select) {
        AImporter::model('customers');
        $model = new BookProModelCustomers();
        $lists = array('limit' => null, 'limitstart' => null);
        $model->init($lists);
        $fullList = $model->getData();
        return AHtml::getFilterSelect('user_id', 'Select Customer', $fullList, $select, false, 'disabled="disabled"', 'id', 'firstname');
    }

    function createTimeSelectBox($name, $selected) {
        $start = "00:00";
        $end = "23:30";
        $option = array();
        $tStart = strtotime($start);
        $tEnd = strtotime($end);
        $tNow = $tStart;
        while ($tNow <= $tEnd) {
            $option[] = JHTML::_('select.option', date("H:i", $tNow), date("H:i", $tNow));
            $tNow = strtotime('+15 minutes', $tNow);
        }
        return JHtml::_('select.genericlist', $option, $name, 'style="float:none !important;"', 'value', 'text', $selected);
    }

}
?>