<?php

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
AImporter::model('order');

class BookproViewOrderDetail extends JViewLegacy
{
	function display($tpl = null)
	{
		$this->config=AFactory::getConfig();
		$this->_prepareDocument();
		parent::display($tpl);
	}
	protected function _prepareDocument(){
		$this->document=JFactory::getDocument();
		$this->document->setTitle(JText::_('Booking detail for order number:'.$this->order->id));
	}

}

?>
