<?php

defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
class FormHelper {

	public static function bookproHiddenField($param){
		
		$fields='<input type="hidden" name="option" value="com_bookpro" />';
		$fields.=JHtml::_('form.token');
		foreach ($param as $key=>$value) {
			$fields.='<input id="'.$key.'" type="hidden" name="'.$key.'" value="'.$value.'" />';
		}
		return $fields;

	}
}