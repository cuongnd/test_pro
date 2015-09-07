<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
AImporter::model('countries','application');
AImporter::helper('image');
JHtml::_('jquery.framework');

class BookProViewHotelBook extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		$app=&JFactory::getApplication();
		$this->config=AFactory::getConfig();
		//$this->assign('countries',$this->getCountrySelect($this->customer->country_id));
		parent::display($tpl);
	}
	
	function getCountrySelect($select){
		$model = new BookProModelCountries();
		$lists = array('order'=>'id','order_dir'=>'ASC');
		$model->init($lists);
		$list=$model->getData();
		return AHtmlFrontEnd::getFilterSelect('country_id', JText::_("COM_BOOKPRO_SELECT_COUNTRY"), $list, $select, false, '', 'id', 'country_name');
		 
	}
}
