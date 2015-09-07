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
JHTML::_('behavior.modal');
AImporter::model('countries');
/**
 * HTML View class for the BookPro Component
 */
class BookProViewFlightConfirm extends JViewLegacy
{
	// Overwriting JView display method

	function display($tpl = null)
	{
		$cart = &JModelLegacy::getInstance('FlightCart', 'bookpro');
		$cart->load();
		$passengers = $cart->passengers;
		$cart->load();
		$passengers = $cart->passengers;
		
		$user = JFactory::getUser();
		
		
		
		parent::display($tpl);
	}
	
	


}
