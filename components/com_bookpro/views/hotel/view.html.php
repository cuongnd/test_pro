<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
AImporter::helper('bookpro','currency');
AImporter::model('facilities' );
class BookProViewHotel extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		$app=JFactory::getApplication();
		$input = $app->input;
		$tpl=$input->get('layout')?$input->get('layout'):null;
		$this->config=AFactory::getConfig();
		$this->_prepareDocument();
		
		$dispatcher	= JDispatcher::getInstance();
		$this->event = new stdClass();
		JPluginHelper::importPlugin('bookpro');
		$results = $dispatcher->trigger('onBookproProductAfterTitle', array ($this->tour));
		$this->event->afterDisplayTitle=$results[0];
		
		parent::display($tpl);
	}
	protected function _prepareDocument(){
		$document=JFactory::getDocument();
		$document->setTitle($this->hotel->title );
		$document->setDescription($this->hotel->metadesc);
		$document->setMetaData('keywords',$this->hotel->metakey);
		$this->facilities=HotelHelper::getFacilitiesByHotelID($this->hotel->id);
		$facilitybox = $this->getFacilitybox($this->hotel->id);
        $lastbooking=HotelHelper::getLastbookingdate($this->hotel->id);
		$this->assignRef('facilitybox', $facilitybox);
	}
	function getFacilitybox($hotel_id){
		$items = HotelHelper::getFacilitiesBox($hotel_id);
		foreach ($items as $item){
			$factitle = $item->title.'('.CurrencyHelper::formatprice($item->price).')';
			$item->factitle = $factitle;
		}
		
		return AHtmlFrontEnd::checkBoxList($items, 'facility_id[]', '', $select,'id','factitle');
	}
	
	
}
