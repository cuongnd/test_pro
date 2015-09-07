<?php
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
AImporter::helper('paystatus', 'orderstatus');
class BookProControllerPayment extends JControllerLegacy
{


    function BookProControllerPayment()
    {

        parent::__construct();

    }

    function process()
    {
    	
        
        $input = JFactory::getApplication()->input;
        $payment_plugin = $input->getString('payment_plugin', '', 'bookpro');
        $element = explode('_', $payment_plugin);
        $order_id = $input->getInt('order_id');

        $deposit = $input->get('deposit');

        JTable::addIncludePath(JPATH_COMPONENT_FRONT_END . '/tables');
        $order = JTable::getInstance('orders', 'table');
        $customer = JTable::getInstance('customer', 'table');

        $order->load($order_id);
        $order->pay_method = $element[1];
        $order->store();

        $customer->load($order->user_id);

        $values['payment_plugin'] = $payment_plugin;
        $values['firstname'] = $customer->firstname;
        $values['lastname'] = $customer->lastname;
        $values['states'] = $customer->states;
        $values['email'] = $customer->email;
        $values['city'] = $customer->city;
        $values['country'] = $customer->country;
        $values['address'] = $customer->address;
        $values['desc'] = $order->order_number;
        if ($deposit) {
            $values['total'] = $deposit;
        } else {
            $values['total'] = $this->order->total;
        }
        $values['order_number'] = $order->order_number;

        //cal payment plugin
        $dispatcher = JDispatcher::getInstance();
        JPluginHelper::importPlugin('bookpro');
        
        $results = $dispatcher->trigger("onBookproPrePayment", array($payment_plugin, $values));
       // var_dump($results);
       
        //echo $results;
        exit;

    }

    function getPaymentForm($element = '')
    {
        $app = JFactory::getApplication();
        $values = JRequest::get('post');
        $html = '';
        $text = "";
        $user = JFactory::getUser();
        if (empty($element)) {
            $element = JRequest::getVar('payment_element');
        }
        $results = array();
        $dispatcher = JDispatcher::getInstance();
        JPluginHelper::importPlugin('bookpro');

        $results = $dispatcher->trigger("onBookproGetPaymentForm", array($element, $values));
        for ($i = 0; $i < count($results); $i++) {
            $result = $results[$i];
            $text .= $result;
        }
        $html = $text;
        // set response array
        $response = array();
        $response['msg'] = $html;
        // encode and echo (need to echo to send back to browser)
        echo json_encode($response);
        //$app->close();
        return;
    }

    function postpayment()
    {


        $app = JFactory::getApplication();
        $plugin = $app->input->getString('method');
        $paction = $app->input->getString('paction');
        $dispatcher = JDispatcher::getInstance();
        JPluginHelper::importPlugin('bookpro');
        $results = $dispatcher->trigger("onBookproPostPayment", array($plugin, $values));
        /// Send email

        if ($results) {
            AImporter::helper('email');
            if (!$results[0]->sendemail) {

                $mail = new EmailHelper($results[0]->id);
                $mail->sendMail();
            }
        }
        if ($results[0]->remote_url != '') {
            $app->redirect(base64_decode($results[0]->remote_url));
            exit();
        }
        $view = $this->getView('postpayment', 'html', 'Bookproview');
        $view->assign('order', $results[0]);
        $view->display();
    }


}
