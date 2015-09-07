<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');

class BookProViewAjaxroom extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		$this->config=AFactory::getConfig();
		$this->_prepareDocument();
		parent::display($tpl);
	}
	protected function _prepareDocument(){
		$document=JFactory::getDocument();
		$document->setTitle($this->hotel->title );
		$document->setDescription($this->hotel->metadesc);
		$document->setMetaData('keywords',$this->hotel->metakey);

	}
}
