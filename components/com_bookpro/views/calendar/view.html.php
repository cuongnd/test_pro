<?php
defined('_JEXEC') or die( 'Restricted access' );
jimport( 'joomla.application.component.view' );
class BookProViewCalendar extends JViewLegacy
{
	function display($tpl = null)
	{
		$this->config=AFactory::getConfig();
		$this->_prepare();
		parent::display($tpl);
	}
	private function _prepare(){
		$doc=JFactory::getDocument();
		$doc->setTitle('COM_BOOKPRO_CALENDAR');
		
	}

	
}
