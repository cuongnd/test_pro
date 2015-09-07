<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');
AImporter::model('categories');

class BookProViewTransport extends JViewLegacy
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
		$document->setTitle('Airport Services' );

	}
	function createTimeSelectBox($name){

		$start = "00:00";
		$end = "23:30";
		$option=array();
		$tStart = strtotime($start);
		$tEnd = strtotime($end);
		$tNow = $tStart;
		while($tNow <= $tEnd){
			$option[]=JHTML::_('select.option',date("H:i",$tNow),date("H:i",$tNow));
			//JHtmlSelect::option(date("H:i",$tNow),date("H:i",$tNow));
			$tNow = strtotime('+15 minutes',$tNow);
		}
		return JHtml::_('select.genericlist',$option,$name,'class="input-small inline"','value','text');

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
	public function createPickup($name,$params,$noSelectText){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('d.id,d.title');
		$query->from('#__bookpro_dest AS d');
		$query->innerJoin('#__bookpro_transport AS t on t.from=d.id');
		$where=array();
		foreach ($params as $key => $value) {
			$where[]=$key.'='.$value;
		}
		$where=implode(' AND ', $where);
		$query->where($where);
		
		$query->order('d.ordering ASC');
		$query->group('t.from');
		$sql = (string)$query;
		$db->setQuery($sql);
		$dest = $db->loadObjectList();
		$options = array();
		foreach($dest as $des)
		{
			$options[] = JHtml::_('select.option', $des->id, $des->title);
		}
		$option = JHtml:: _('select.option', '', $noSelectText);
		array_unshift($options, $option);
		return JHtml::_('select.genericlist',$options,$name,'class="input-medium"','value','text',$selected,false);
	}

	function createDropLocation($name,$id,$noselected) {
		$items=array();
		return AHtmlFrontEnd::getFilterSelect($name, $noselected, $items, $select, false, 'class="input-medium"', 'id', 'title');
	}
}
