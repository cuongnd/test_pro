<?php

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class BookproViewMessages extends JViewLegacy {

    var $items;

    function display($tpl = null) {
        $user = JFactory::getUser();
        $userId = $user->id;

        AImporter::model('messages','orders','customer');

        $modelCustomer = new BookProModelCustomer();
        $modelCustomer->setIdByUserId();
        $customer = $modelCustomer->getObject();

        $cid = $customer->id;

        $orderModel = new BookProModelOrders();

        $lists = array("user_id" => $cid);
        $orderModel->init($lists);
        $orders = $orderModel->getData();
        $model = new BookProModelMessages();
        $this->items = $model->getItems();
        parent::display($tpl);
    }

}

?>
