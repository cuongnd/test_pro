<?php

defined('_JEXEC') or die;
AImporter::model('order', 'paylogs');

class BookproViewPayLog extends BookproJViewLegacy {

    protected $form;
    protected $item;
    protected $state;

    public function display($tpl = null) {

        
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');
        $this->addToolbar();

        $this->order_id = JFactory::getApplication()->getUserStateFromRequest('order_id', 'order_id', 0);
        $model = new BookProModelOrder();
        $model->setId($this->order_id);
        $this->order = $model->getObject();
       parent::display($tpl);
    }

    protected function addToolbar() {
        JRequest::setVar('hidemainmenu', true);
        JToolBarHelper::title(JText::_('COM_BOOKPRO_PAYMENT_LOG_EDIT'), 'paylog');
        JToolBarHelper::apply('paylog.apply');
        JToolBarHelper::save('paylog.save');
        JToolBarHelper::cancel('paylog.cancel');
    }

}