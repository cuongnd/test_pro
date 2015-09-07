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
JFormHelper::loadFieldClass('checkboxes');

class JFormFieldFrequency extends JFormFieldCheckboxes
{
	
	protected function getOptions(){
		AImporter::helper('date');
		$days=DateHelper::dayofweek();
		$daysweek=array();
		$options = array();
		foreach ($days as $key => $value)
		{
				
			$object=new stdClass();
			$object->key=$key;
			$object->value=$value->text;
			$options[] = JHtml::_('select.option', $key, $value->text, 'value', 'text');
			
		}
		
		return $options;
		
	}

	

}

?>