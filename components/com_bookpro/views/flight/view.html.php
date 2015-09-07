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
AImporter::model('flights','countries','airlines','airports','customer');



class BookProViewFlight extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{

		
		$this->assign('airlines',$this->createAirlineBox());
		// Check for errors.
		parent::display($tpl);
	}
	private function createAirlineBox(){
		$amodel=new BookProModelAirlines();
		$state = $amodel->getState();
		
		$state->set('filter.state', 1);
		
		$airlines=$amodel->getItems();
		return AHtmlFrontEnd::getFilterSelect('airline_id', 'Select Airline', $airlines, $this->airline_id, false, 'onchange="applyfilter()"', 'id', 'title');
	}
	
}
