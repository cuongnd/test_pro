<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

/**
 * Form Field class for the Joomla Platform.
 * Supports a generic list of options.
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldSelect2 extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Select2';

	/**
	 * Method to get the field input markup for a generic list.
	 * Use the multiple attribute to enable multiselect.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$html = array();
		$attr = '';
		$app=JFactory::getApplication();
		// Initialize some field attributes.
		$attr .= !empty($this->class) ? ' class="' . $this->class . '"' : '';
		$attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
		$maximumSelectionLength=$this->element['maximumSelectionLength'] ? (string) $this->element['maximumSelectionLength'] : 10;
		$maximumSelectionSize=$this->element['maximumSelectionSize'] ? (string) $this->element['maximumSelectionSize'] : 10;
		$tags=$this->element['tags'] ? (boolean) $this->element['tags'] : false;

		// To avoid user's confusion, readonly="true" should imply disabled="true".
		if ((string) $this->readonly == '1' || (string) $this->readonly == 'true' || (string) $this->disabled == '1'|| (string) $this->disabled == 'true')
		{
			$attr .= ' disabled="disabled"';
		}

		// Initialize JavaScript field attributes.
		$attr .= $this->onchange ? ' onchange="' . $this->onchange . '"' : '';
		$options=array();

		// Get the field options.
		foreach ($this->element->children() as $option)
		{
			$options[] = (string) $option['value'];
		}
		$selectOPtion=array(
			maximumSelectionLength=>$maximumSelectionLength,
			maximumSelectionSize=>$maximumSelectionSize
		);
		if($tags)
		{
			$selectOPtion['tags']=$options;
		}
		$selectOPtion['onremoveitem']=$this->element['onremoveitem'];
		$selectOPtion['onselecting']=$this->element['onselecting'];
		JHtml::_('formbehavior.select2','.select2[name="'.$this->name.'"]',null,$selectOPtion,JUserHelper::genRandomPassword());
		$html[]='<input value="'.htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8').'" '.$attr.' type="hidden" name="'.$this->name.'" class="select2"   />';
		return implode($html);
	}

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
}
