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
class JFormFieldcsstextshadow extends JFormFieldList
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'csstextshadow';




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
        $attr .= !empty($this->class) ? ' class="' . $this->class . '"' : '';
        $attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
        $attr .= $this->multiple ? ' multiple' : '';
        $attr .= $this->required ? ' required aria-required="true"' : '';
        $attr .= $this->autofocus ? ' autofocus' : '';
        $attr .= $this->onchange ? ' onchange="'.$this->onchange.'"' : '';
        $options[] = JHtml::_('select.option', '-1','None');
        $border_style='auto|hidden|dotted|dashed|solid|double|groove|ridge|inset|outset|initial|inherit';
        $border_style=explode('|',$border_style);
        foreach($border_style as $item)
        {
            $options[] = JHtml::_('select.option', $item, $item);
        }
        $options = array_merge(parent::getOptions(), $options);
        $html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);

        return implode('',$html);
    }
}
