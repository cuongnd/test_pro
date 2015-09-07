<?php

defined('_JEXEC') or die;
//import needed models
AImporter::model('order','customer');
class BookproViewSendemail extends BookproJViewLegacy {

	protected $form;
	protected $item;
	protected $state;

	/**
	 * (non-PHPdoc)
	 * @see JViewLegacy::display()
	 */
	public function display($tpl = null) {
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->state = $this->get('State');
		$this->addToolbar();
		$orderModel = new BookProModelOrder();
		$orderTableFields = $orderModel->getMainTable()->getFields();

		$options = array();
		foreach($orderTableFields as $key => $value) :
		$options[] = JHTML::_('select.option', $key, $key);
		endforeach;
		$field = JHTML::_('select.genericlist', $options, 'field', 'class="inputbox adddatareplate" id="field"' ,'value', 'text', '');
		$this->assign('field', $field);

		$customerModel = new BookProModelCustomer();
		$customerTableFields = $customerModel->getMainTable()->getFields();
		$options = array();
		foreach($customerTableFields as $key => $value) :
		$options[] = JHTML::_('select.option', $key, $key);
		endforeach;
		$customer = JHTML::_('select.genericlist', $options, 'customer', 'class="inputbox adddatareplate" id="customer"' ,'value', 'text', '');
		$this->assign('customer', $customer);		

		$table = JHTML::_('select.genericlist', $this->getOptionTable(), 'table', 'class="inputbox adddatareplate" id="table"' ,'value', 'text', '');
		$this->assign('table', $table);

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar() {
		JRequest::setVar('hidemainmenu', true);
		JToolBarHelper::title(JText::_('Email Detail'), 'sendemail');
		JToolBarHelper::apply('sendemail.apply');
		JToolBarHelper::save('sendemail.save');
		JToolBarHelper::cancel('sendemail.cancel');
	}

	protected function getOptionTable()
	{
		$options[] = JHTML::_('select.option', 'order', 'Order');
		$options[] = JHTML::_('select.option', 'room_selected', 'Room selected');
		$options[] = JHTML::_('select.option', 'pre-trip_hotel', 'Pre-trip hotel');
		$options[] = JHTML::_('select.option', 'post-trip_hotel', 'Post-trip hotel');
		$options[] = JHTML::_('select.option', 'pre-trip_transfer', 'Pre-trip transfer');
		$options[] = JHTML::_('select.option', 'post-trip_transfer', 'Post-trip transfer');
		$options[] = JHTML::_('select.option', 'additional_trips', 'Additional trips');
		$options[] = JHTML::_('select.option', 'passenger_infomation', 'Passenger infomation');
		return $options;
	}

}