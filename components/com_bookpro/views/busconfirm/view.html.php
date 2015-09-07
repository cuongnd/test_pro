<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: view.html.php  23-06-2012 23:33:14
 **/
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');
AImporter::helper('bookpro');
JHTML::_('behavior.modal');
/**
 * HTML View class for the BookPro Component
 */
class BookProViewBusConfirm extends JViewLegacy
{
	// Overwriting JView display method

	function display($tpl = null)
	{
		$app=JFactory::getApplication();
		$this->config=&AFactory::getConfig();
		$this->_prepare();
		$cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
		$cart->load();
		$passengers = $cart->passengers;
		
		$user = JFactory::getUser();
		

		$this->assignRef('passengers', $passengers);
		parent::display($tpl);
	}
	
	protected function _prepare(){
		JFactory::getDocument()->setTitle(JText::_('COM_BOOKPRO_SELECT_BUS_ROUTE'));
	}
	function getListSeat($field = 'pSeat[]',$id = 'pSeat'){
		$cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
		$cart->load();
		
		$seat = str_replace(array('[', ']','"'), '', $cart->listseat);
		
		$seats = explode(",", $seat);
		
		$options = array();
		$options[] = JHtmlSelect::option(0,JText::_('COM_BOOKPRO_PASSENGER_SEAT'));
		for($i =0;$i < count($seats);$i++){
			$options[] = JHtmlSelect::option($seats[$i],$seats[$i]);
		}
		
		
		return JHtmlSelect::genericlist($options, $field,'class="input-small"');
		
	}
	
	function getReturnListSeat($field = 'pReturnSeat[]',$id = 'pReturnSeat'){
		$cart = &JModelLegacy::getInstance('BusCart', 'bookpro');
		$cart->load();
	
		$seat = str_replace(array('[', ']','"'), '', $cart->returnlistseat);
		$seats = explode(",", $seat);
		$options = array();
		$options[] = JHtmlSelect::option(0,JText::_('COM_BOOKPRO_PASSENGER_RETURNSEAT'));
		for($i =0;$i < count($seats);$i++){
			$options[] = JHtmlSelect::option($seats[$i],$seats[$i]);
		}
		return JHtmlSelect::genericlist($options, $field,'class="input-small"');
	
	}


}
