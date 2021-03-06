<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

JFormHelper::loadFieldClass('list');

/**
 * Supports an HTML select list of files
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldCssTextAlign extends JFormFieldList
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'csstextalign';




    /**
     * Method to get the list of files for the field options.
     * Specify the target directory with a directory attribute
     * Attributes allow an exclude mask and stripping of extensions from file name.
     * Default attribute may optionally be set to null (no file) or -1 (use a default).
     *
     * @return  array  The field option objects.
     *
     * @since   11.1
     */
    protected function getInput()
    {
        $options = array();
        $options[] = JHtml::_('select.option', '-1','None');
        $csstextalign=array(
            'left',
            'right',
            'center',
            'justify',
            'initial',
            'inherit',
        );
        $class=' class="'.$this->class.'" ';
        $attr='';
        // Initialize JavaScript field attributes.
        $attr .= $this->onchange ? ' onchange="' . $this->onchange . '"' : '';

        foreach($csstextalign as $item)
        {
            $options[] = JHtml::_('select.option', $item, $item);
        }
        $options = array_merge(parent::getOptions(), $options);
        $html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr).$class , 'value', 'text', $this->value, $this->id);

        return implode('',$html);
    }
}
