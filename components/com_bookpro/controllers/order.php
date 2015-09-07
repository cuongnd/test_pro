<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id$
 * */
AImporter::helper('request');

class BookProControllerOrder extends JControllerLegacy {

    var $_model;

    function __construct($config = array()) {
        parent::__construct($config);
        if (!class_exists('BookProModelOrder')) {
            AImporter::model('order');
        }
        $this->_model = new BookProModelOrder();
    }

    public function display($cachable = false, $urlparams = false) {
        // Get the document object.
        $document = JFactory::getDocument();

        // Set the default view name and format from the Request.
        $vName = JRequest::getCmd('view', 'login');

        $user = JFactory::getUser();
        switch ($vName) {
            case 'profile':

                // If the user is a guest, redirect to the login page.

                if ($user->get('guest') == 1) {
                    $return = 'index.php?option=com_bookpro&controller=customer&view=profile';
                    $url = 'index.php?option=com_users&view=login';
                    $url .= '&return=' . urlencode(base64_encode($return));
                    $this->setRedirect(JRoute::_($url), false);
                    return;
                }
                break;

            case 'mypage':

                // If the user is a guest, redirect to the login page.
                if ($user->get('guest') == 1) {
                    $return = 'index.php?option=com_bookpro&controller=customer&view=mypage';
                    $url = 'index.php?option=com_users&view=login';
                    $url .= '&return=' . urlencode(base64_encode($return));
                    $this->setRedirect(JRoute::_($url), false);
                    return;
                }
                break;
        }
        JRequest::setVar('view', $vName);
        parent::display();
    }
	function cancel_order()
	{

		$input=JFactory::getApplication()->input;
		$order_id=$input->get('order_id');
		JTable::addIncludePath(JPATH_COMPONENT_FRONT_END.'/tables');
		$order = JTable::getInstance('orders', 'table');
		$order->load($order_id);
		$order->order_status='CANCELLED';
		$order->store();
		$url ='index.php?option=com_bookpro&view=mypage';
		$this->setRedirect($url,JText::_('COM_BOOKPRO_ORDER_CANCELLATION'),'info');
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
        $this->setRedirect('index.php?option=com_bookpro&controller=order&task=detail&order_id=' . $order_id);
    }

    function applycoupon() {
        $input = JFactory::getApplication()->input;
        $code = $input->getString('coupon');
        $order_id = $input->getInt('order_id');

        JTable::addIncludePath(JPATH_COMPONENT_FRONT_END . DS . 'tables');
        $order = JTable::getInstance('orders', 'table');
        $order->load($order_id);

        //check if user login is agent, they can not redeem coupon
        $user = JFactory::getUser();
        if (!$user->guest) {
            $config = AFactory::getConfig();
            if (in_array($config->agentUsergroup, $user->groups)) {
                $msg = JText::_('COM_BOOKPRO_COUPON_AGENT_INVALID');
                $this->setRedirect(JURI::base() . 'index.php?option=com_bookpro&view=formpayment&order_id=' . $order_id . '&' . JSession::getFormToken() . '=1', $msg);
                return;
            }
        }

        AImporter::model('coupon');
        $couponModel = new BookProModelCoupon();
        $coupon = $couponModel->getObjectByCode($code);

        //end check agent
        if ($coupon) {
            //check hotel coupon, coupon of other hotel can not use for this order
            if ($coupon->hotel_id) {
                AImporter::helper('hotel');
                $hotel = HotelHelper::getObjectHotelByOrder($order_id);
                if ($hotel->id != $coupon->hotel_id) {
                    $msg = JText::_('COM_BOOKPRO_COUPON_HOTEL_INVALID');
                    $this->setRedirect(JURI::base() . 'index.php?option=com_bookpro&view=formpayment&order_id=' . $order_id . '&' . JSession::getFormToken() . '=1', $msg);
                    return;
                }
            }
            //end check coupon

            $check = true;

            if ((int) $coupon->total == 0) {
                $check = false;
                $msg = JText::_('COM_BOOKPRO_COUPON_INVALID');
            } else {

                if ($order->discount > 0) {
                    $check = false;
                    $msg = JText::_('COM_BOOKPRO_COUPON_APPLY_ERROR');
                } else {

                    if ($coupon->subtract_type == 1) {
                        $discount = ($order->total * $coupon->amount) / 100;
                        $order->total = $order->total - $discount;
                        $order->discount = $discount;
                    } else {
                        $order->total = $order->total - $coupon->amount;
                        $order->discount = $coupon->amount;
                    }
                    $order->coupon_id = $coupon->id;
                    $coupon->total = $coupon->total - 1;
                    $coupon->store();
                    $order->store();
                    $msg = JText::_('COM_BOOKPRO_COUPON_VALID');
                }
            }
        } else {
            $check = false;
            $msg = JText::_('COM_BOOKPRO_COUPON_INVALID');
        }
        $this->setRedirect(JURI::base() . 'index.php?option=com_bookpro&view=formpayment&order_id=' . $order_id . '&' . JSession::getFormToken() . '=1', $msg);
        return;
    }

    function cancel() {
        $order_id = JRequest::getVar('order_id');
        if (!class_exists('BookProModelOrder')) {
            AImporter::model('orders');
        }
        $model = new BookProModelOrder();
        $model->setId($order_id);
        $order = $model->getObject();
        $order->order_status = 'CANCELLED';
        if (!$order->store()) {
            JError::raiseError(500, $row->getError());
        }
        $this->setRedirect(JURI::root() . 'index.php?option=com_bookpro&view=mypage');
        return;
    }

    function updateorder() {
        // update orderinfo
        $adult = JRequest::getInt('adult', 1);
        $child = JRequest::getInt('children', 0);
        $orderinfo_id = JRequest::getInt('orderinfo_id');
        $order_id = JRequest::getInt('order_id');
        $start = JFactory::getDate(JRequest::getVar('depart'));

        $notes = JRequest::getString('notes');
        $location = JRequest::getInt('location');
        //save order info
        if (!class_exists('BookProModelOrderInfo')) {
            AImporter::model('orderinfo');
        }
        $modelInfo = new BookProModelOrderInfo();
        $data = array('id' => $orderinfo_id, 'adult' => $adult, 'child' => $child, 'start' => $start->toSql(true), 'location' => $location);
        $modelInfo->store($data);

        if (!class_exists('BookProModelPackagePrice')) {
            AImporter::model('packageprice');
        }

        $modelpackprice = new BookProModelPackagePrice();
        $modelpackprice->setId(JRequest::getInt('price_id'));
        $price = $modelpackprice->getObject();


        $total = $adult * $price->price + $child * $price->child_price;

        //save order
        $modelOrder = new BookProModelOrder();
        $order = array('id' => $order_id, 'total' => $total, 'notes' => $notes);
        $modelOrder->store($order);

        $this->setRedirect(JURI::base() . 'index.php?option=com_bookpro&controller=order&task=viewdetail&order_id=' . $order_id);
    }

    function ajax_requestpoint() {
        $input = JFactory::getApplication()->input;
        $order_id = $input->get('order_id');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->update($db->qn('#__bookpro_orders'))
                ->set('request_point=(1-request_point)')
                ->where('id = ' . $order_id);
        $db->setQuery($query);
        $db->execute();
        exit();
    }

    function detail() {
    	
        $order_id = JRequest::getInt('order_id');
        /*
          $user=JFactory::getUser();
          if ($user->get('guest') == 1) {
          $return = 'index.php?option=com_bookpro&controller=order&task=viewdetail&order_id='.$order_id;
          $url    = 'index.php?option=com_users&view=login';
          $url   .= '&return='.urlencode(base64_encode($return));
          $this->setRedirect($url, false);
          return;
          } else { */

        if (!class_exists('BookProModelOrder')) {
            AImporter::model('order');
        }
        $model = new BookProModelOrder();
        //$model->setId($order_id);
        $order = $model->getObjectByID($order_id);
        $view = &$this->getView('orderdetail', 'html', 'BookProView');

        $view->assign('order', $order);

        $view->setLayout(JRequest::getVar('layout', 'default'));
        $view->display();
        return;
        //}
    }

}