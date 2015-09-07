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

class JFormFieldDestcode extends JFormFieldList
{
	
	protected function getOptions() {
		
		$db =JFactory::getDBO();
		$query = $db->getQuery(true);
		 
		$sql = "SELECT code, title  FROM #__bookpro_dest WHERE parent_id = 1 AND air=1 AND state = 1 ORDER BY lft ASC ";
		$db->setQuery($sql);
		
		$options=$db->loadObjectList();
		
		for ($i = 0, $n = count($options); $i < $n; $i++)
		{
			$options[$i]->text = $options[$i]->title;
			$options[$i]->value = $options[$i]->code;
		}
		
		$options = array_merge(parent::getOptions(), $options);
		
		return $options;
		
		
	
	}

	

}

?>