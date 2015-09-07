<?php

defined('_JEXEC') or die;

class BookproViewPoints extends BookproJViewLegacy {

    protected $items;
    protected $pagination;
    protected $state;

    /**
     * (non-PHPdoc)
     * @see JViewLegacy::display()
     */
    public function display($tpl = null) {
       $this->getModel('');
        $orderModel = new BookProModelOrders();
        $lists = array("request_point" => 1);
        $orderModel->init($lists);
        $orders = $orderModel->getData();
        
        //$model->init(array('title'=>'111'));
        $this->items = $orders;
        
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since	1.6
     */
}
