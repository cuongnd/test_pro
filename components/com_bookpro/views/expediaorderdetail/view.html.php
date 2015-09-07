<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
AImporter::model('order', 'customer');
require_once JPATH_COMPONENT_FRONT_END . DS . 'classes/Expedia/API.php';

class BookproViewExpediaOrderDetail extends JViewLegacy {

    function display($tpl = null) {
        $this->config = AFactory::getConfig();
        $this->_prepareDocument();
        $app = JFactory::getApplication();
        $input = $app->input;
        $orderModel = new BookProModelOrder();
        $order_id = $input->get('order_id', '', 'string');
        $customerModel = new BookProModelCustomer();

        $orderModel->setId($order_id);

        $order = $orderModel->getObject();

        $customerModel->setId($order->user_id);
        $customer = $customerModel->getObject();
        $config = AFactory::getConfig();
        $expedia = new API('55505', $config->api_key, $config->currency_code, $config->minor_rev,$config->locale);
        $params = array(
            'itineraryId' => $order->itineraryid,
            'email' => $customer->email
        );

        $this->itin = $expedia->itin($params);




        //$this->setLayout(JRequest::getVar('layout',''));
        parent::display($tpl);
    }

    protected function _prepareDocument() {
        $this->document = JFactory::getDocument();
        $this->document->setTitle(JText::_('Booking detail for order number:' . $this->order->id));
    }

}

?>
