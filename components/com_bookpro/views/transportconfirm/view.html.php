<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
AImporter::model('categories');

class BookProViewTransportConfirm extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		$this->config=AFactory::getConfig();
		$this->cart = &JModelLegacy::getInstance('TransportCart', 'bookpro');
		$this->cart->load();
		$this->_prepareDocument();
		parent::display($tpl);
	}
	protected function _prepareDocument(){
		$document=JFactory::getDocument();
		$document->setTitle('Transportation' );

	}
	function createTimeSelectBox(){

		$start = "6:00";
		$end = "19:30";
		$option=array();
		$tStart = strtotime($start);
		$tEnd = strtotime($end);
		$tNow = $tStart;
		while($tNow <= $tEnd){
			$option[]=JHTML::_('select.option',date("H:i",$tNow),date("H:i",$tNow));
			//JHtmlSelect::option(date("H:i",$tNow),date("H:i",$tNow));
			$tNow = strtotime('+15 minutes',$tNow);
		}
		return JHtml::_('select.genericlist',$option,'depart_time','','value','text');

	}
	function createPickupSelect($name){
		$menu = &JSite::getMenu();
		$pickup_id=JRequest::getVar('pickup_location');
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select("d.*");
		$query->from('#__bookpro_dest AS d');
		$query->where("d.id IN (".$pickup_id.")");
		$query->order("d.ordering");
		$sql = (string)$query;
		$db->setQuery($sql);
		$dest = $db->loadObjectList();
		return AHtmlFrontEnd::getFilterSelect($name, JText::_('COM_BOOKPRO_TRANSPORT_PICKUP'),$dest, $selected,false,'','id','title');
	}
	function createDropLocation($name,$id,$noselected) {
		$cmodel = new BookProModelCategories();
		$lists=array('type'=>$id);
		$cmodel->init($lists);
		$items = $cmodel->getData();
		return AHtmlFrontEnd::getFilterSelect($name, $noselected, $items, $select, false, '', 'id', 'title');
	}
}
