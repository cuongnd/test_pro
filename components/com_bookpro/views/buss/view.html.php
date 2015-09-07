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

class BookProViewBusTrips extends JViewLegacy
{
	
	function display($tpl = null)
	{
		$this->_prepare();
		parent::display($tpl);
		
	}
	protected function _prepare(){ 
		$document=JFactory::getDocument();
		$document->setTitle(JText::_('COM_BOOKPRO_SELECT_TRIP'));
	}
}
