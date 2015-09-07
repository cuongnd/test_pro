<?php

defined('_JEXEC') or die;
AImporter::helper('bookpro', 'request', 'image');
AImporter::model('messages');

class BookproViewMessage extends JViewLegacy {

    protected $form;
    protected $item;
    protected $state;

    public function display($tpl = null) {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        print_r($this->item);

        $this->state = $this->get('State');

        $this->parent_id = JFactory::getApplication()->input->get('parent_id');
        //$this->addToolbar();
        AImporter::model('messages','orders','customer');

        $modelCustomer = new BookProModelCustomer();
        $modelCustomer->setIdByUserId();
        $customer = $modelCustomer->getObject();

        $cid = $customer->id;

        $orderModel = new BookProModelOrders();
        $lists = array("user_id" => $cid);
        $orderModel->init($lists);
        $orders = $orderModel->getData();
        $this->orders=$orders;

        parent::display($tpl);
    }

    protected function addToolbar() {
        JRequest::setVar('hidemainmenu', true);
        JToolBarHelper::title(JText::_('COM_BOOKPRO_MESSAGE_EDIT'), 'message');
        JToolBarHelper::apply('message.apply');
        JToolBarHelper::save('message.save');
        JToolBarHelper::cancel('message.cancel');
    }

}