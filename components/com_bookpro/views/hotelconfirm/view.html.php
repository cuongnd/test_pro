<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
AImporter::model('countries','application');
AImporter::helper('image');
class BookProViewHotelConfirm extends JView
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		 
		$this->config=AFactory::getConfig();
		$this->cart = &JModel::getInstance('HotelCart', 'Bookpro');
		$this->cart->load();
		parent::display($tpl);
	}

}
