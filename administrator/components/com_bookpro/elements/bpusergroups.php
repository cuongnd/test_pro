<?php

/**
 * Popup element to select destination.
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: destination.php 44 2012-07-12 08:05:38Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');
   
jimport('joomla.html.parameter.element');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

class JFormFielDbpusergroups extends JFormFieldList
{
	
	protected function getInput() {
		
        $config = JComponentHelper::getParams('com_bookpro');
		$arrayGroup[]   =     JText::_('COM_BOOKPRO_GROUP_SELECT');
		$arrayGroup[$config->get('supplier_usergroup')] =  JText::_('COM_BOOKPRO_SUPPLIER');
		$arrayGroup[$config->get('customers_usergroup')] =  JText::_('COM_BOOKPRO_CUSTOMER');
		return JHTML::_('select.genericlist', $arrayGroup, $this->name, ' class="inputbox" ', 'value', 'text', $this->value, $this->id) ;
	
	}

	

}

?>