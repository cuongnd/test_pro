<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
AImporter::model('customer');

class BookproViewSupplierPage extends JViewLegacy
{

	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();
		$document = JFactory::getDocument();
		$customer=AFactory::getCustomer();
		if ($customer) {
			$customerUser = new JUser($customer->user);
			$this->assignRef('customer', $customer);
			$this->assignRef('user', $customerUser);
		}
		$document->setTitle(JText::_('COM_BOOKPRO_SUPPLIER_PAGE'));         
        $this->setLayout(JRequest::getVar('layout','default'));
		parent::display($tpl);
		

	}
	
	
	
}

?>
