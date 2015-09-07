<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

JFormHelper::loadFieldClass('text');

/**
 * Form Field class for the Joomla Platform.
 * Provides and input field for e-mail addresses
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.email.html#input.email
 * @see         JFormRuleEmail
 * @since       11.1
 */
class JFormFieldClient extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'client';

	/**
	 * Method to get the field input markup for e-mail addresses.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{


        $options[] = JHtml::_('select.option', '0', JText::_('JSITE'));
        $options[] = JHtml::_('select.option', '1', JText::_('JADMINISTRATOR'));
        $attribute=array();
        $attribute[]=$this->onchange?'onchange="'.$this->onchange.'"':'';
        $attribute=implode(' ',$attribute);

        $html = JHtml::_('select.genericlist', $options, $this->name,$attribute, 'value', 'text', $this->value, $this->id);

        return $html;
	}
}
