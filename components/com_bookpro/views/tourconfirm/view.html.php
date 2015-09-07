<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
AImporter::model('countries','application');
AImporter::helper('image');
class BookProViewTourConfirm extends JViewLegacy
{
	// Overwriting JViewLegacy display method
	var $_document;
	function display($tpl = null)
	{
		JHtml::_('stylesheet','components/com_bookpro/assets/css/tour.css'); 
		$app=&JFactory::getApplication();
		$this->_document = &JFactory::getDocument();
		$this->config=AFactory::getConfig();
		$this->cart = &JModel::getInstance('TourCart', 'Bookpro');
		$this->cart->load();
		$this->_prepare();
		parent::display($tpl);
	}
	protected  function _prepare(){
		$this->_document->setTitle($this->tour->title);
	}

}
