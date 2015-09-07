<?php
defined ( '_JEXEC' ) or die ( 'Restricted access' );

// import needed Joomla! libraries
jimport ( 'joomla.application.component.view' );
AImporter::helper('route', 'bookpro', 'request', 'hotel');
// import needed JoomLIB helpers
AImporter::model ( 'customer', 'hotels','hotel','registerhotels' );


class BookProViewScriptHotel extends JViewLegacy 
{
	var $lists;
	var $items;
	var $pagination;
	var $selectable;
	var $params;
	function display($tpl = null)
	{
		$mainframe = &JFactory::getApplication();
		$user = &JFactory::getUser();
		parent::display($tpl);
		
	}
	function getHotelSelect(){
		$model = new BookProModelRegisterHotels();
		
		$param=array('userid'=>HotelHelper::getCustomerIdByUserLogin(),'state'=>1);
		$model->init($param);
		$lists = $model->getData();
		
        
		return AHtmlFrontEnd::getFilterSelect('hotel', JText::_('COM_BOOKPRO_SELECT_HOTEL'), $lists, '', '', '', 'id', 'title');
		
	}
   
}
?>