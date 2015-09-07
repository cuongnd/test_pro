<?php 
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.view');

class BookProViewLogin extends JViewLegacy{
	function display($tpl=null){
		$document=JFactory::getDocument();
		$document->setTitle(JText::_('COM_BOOKPRO_LOGIN_PAGE_TITLE'));
		parent::display($tpl);
	}
}
?>