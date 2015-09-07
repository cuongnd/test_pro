<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php 84 2012-08-17 07:16:08Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

//import needed Joomla! libraries
jimport('joomla.application.component.view');
//import needed models
//import needed JoomLIB helpers

AImporter::helper('bookpro', 'request','image','document','currency','bus');
AImporter::model('currencys');
if (! defined('SESSION_PREFIX')) {
	define('SESSION_PREFIX', 'bookpro_ticket_list_');
}

class BookProViewBussearch extends BookproJViewLegacy
{
	var $currency_id;
	function display($tpl = null)
	{
		$mainframe = JFactory::getApplication();

		$this->lists['limitstart'] = ARequest::getUserStateFromRequest('limitstart', 0, 'int');
		$this->lists['roundtrip'] = ARequest::getUserStateFromRequest('roundtrip', 0, 'int');
		
		$this->lists['bustrip-from'] = ARequest::getUserStateFromRequest('desfrom', null, 'int');
		$this->lists['bustrip-to'] = ARequest::getUserStateFromRequest('desto', null, 'int');
			
		$this->lists['adult'] = ARequest::getUserStateFromRequest('adult', null, 'int');
		$this->lists['start'] = ARequest::getUserStateFromRequest('start', null,'string');
		$this->lists['end'] = ARequest::getUserStateFromRequest('end', null,'string');
			
		AImporter::model('bustrips','customer','orderinfo','bustripsearch');
		$now=JFactory::getDate();
		$now->format('d-m-Y', true) ;
		
		$searchcurrent=JFactory::getDate($this->lists['start'].' '.$now->format('H:i:s'));
			
		$today = JFactory::getDate('now');
		if($this->lists['start']){
			$start=JFactory::getDate($this->lists['start'])->format(str_replace('%','','%Y-%m-%d'),true);
		}else {

			$today->add(new DateInterval('P1D'));
			$start= $today->format(str_replace('%','','%Y-%m-%d'),true);
		}
		if($$this->lists['end']){
			$end=JFactory::getDate($this->lists['end'])->format(str_replace('%','','%Y-%m-%d'),true);
		}else {
			$today->add(new DateInterval('P2D'));
			$end= $today->format(str_replace('%','','%Y-%m-%d'),true);
		}
			
		$this->lists['start'] = $start;
			
		$this->lists['end'] = $end;
			
			
		if ($this->lists['bustrip-from'] && $this->lists['bustrip-to'] && $this->lists['adult'] && ($this->lists['start'] || $this->lists['end'])) {


			$lists = array();
			$lists['from'] = $this->lists['bustrip-from'];
			$lists['to'] = $this->lists['bustrip-to'];
			$lists['adult'] = $this->lists['adult'];
			$lists['depart_date']=$this->lists['start'];
			
			$infomodel=new BookProModelOrderInfo();

			$bustrips = BusHelper::getBustripSearch($lists,$this->lists['roundtrip']);
			
			$this->assignRef('going_trips',$bustrips);
			if ($this->lists['roundtrip'] == 1) {
				$lists = array();
				$lists['from'] = $this->lists['bustrip-to'];
				$lists['to'] = $this->lists['bustrip-from'];
				$lists['adult'] = $this->lists['adult'];
				
				$lists['depart_date']=$this->lists['end'];
				
				$model=new BookProModelBustripSearch();
					
				$end=$this->lists['end'];
				if(is_null($end)){
					$end=JFactory::getDate()->format('Y-m-d');
				}
				$timestamp = strtotime($this->lists['end']);
				//$lists['depart_date']=$end;
				
				$return_trips = BusHelper::getBustripSearch($lists,$this->lists['roundtrip'],true);
				
				$this->assignRef('return_trips',$return_trips);
					
			}
		}else{
			$bustrips = array();
			$return_trips = array();
			$this->assignRef('going_trips',$bustrips);
			$this->assignRef('return_trips',$return_trips);
		}
			
		$select_from = AHtml::getFilterSelect('desfrom','From' , BusHelper::getDepartLocation() , $this->lists['bustrip-from'],false,'','id','title');
		$select_to = AHtml::getFilterSelect('desto','To' , BusHelper::getArrivalLocation() , $this->lists['bustrip-to'],false,'','id','title');

		if($this->lists['bustrip-from'] && $this->lists['bustrip-to']){
			$from_to=BusHelper::getRoutePair($this->lists['bustrip-from'], $this->lists['bustrip-to']);
			$this->assignRef('from_to', $from_to);
		}

		$this->assignRef('select_from', $select_from);
		$this->assignRef('select_to', $select_to);
			

		parent::display($tpl);
	}



	function getAgentSelectBox($select,$field = 'agent_id'){
		$model = new BookProModelAgents();
			
		$lists = array('state'=>null,'order'=>'id','order_Dir'=>'ASC');
		$model->init($lists);
			
		$fullList = $model->getData();
		return AHtml::getFilterSelect($field, 'Select Agent', $fullList, $select, false, '', 'id', 'company');
			
	}
	function getCurrencies(){
			
		$model = new BookProModelCurrencys();
		$lists = array('state'=>1);
		$model->init($lists);
		$fullList = $model->getData();
		return AHtml::getFilterSelect('currency_id', 'Currency', $fullList, $this->currency_id, false, '', 'id', 'currency_name');
			
	}


}

?>