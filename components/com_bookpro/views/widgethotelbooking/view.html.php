<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
AImporter::helper('bookpro');
AImporter::model('facilities' );
class BookProViewWidgethotelbooking extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		
		parent::display($tpl);
	}
	
	
	
}
