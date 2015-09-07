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

class JFormFieldDestinationFlight extends JFormFieldList
{
	
	protected function getInput() {
		JHtml::date();
		$db =& JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('id,title');
		$query->from('#__bookpro_dest');
		$query->where('air=1 AND state = 1 AND parent_id=1');
		$query->order('lft ASC');
		$db->setQuery($query);
		$options 	= array();
		$options[] 	= JHTML::_('select.option',  '', JText::_('COM_BOOKPRO_SELECT_DESTINATION'), 'id', 'title');
		$options = array_merge($options, $db->loadObjectList()) ;
		return JHTML::_('select.genericlist', $options, $this->name, ' class="inputbox" ', 'id', 'title', $this->value,$this->id) ;
	
	}

	

}

?>