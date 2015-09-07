<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
AImporter::model('orders');

/**
 * HTML View class for the BookPro Component
 */
class BookProViewPostPayment extends JViewLegacy
{
	// Overwriting JViewLegacy display method
	function display($tpl = null)
	{

		$this->config=AFactory::getConfig();
		//get Payment methods
		// Display the view
		parent::display($tpl);
		
	}


}
