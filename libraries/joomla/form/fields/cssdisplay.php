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
class JFormFieldCssDisPlay extends JFormFieldList
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'cssdisplay';




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
        $class=' class="'.$this->class.'" ';
        $options = array();
        $options[] = JHtml::_('select.option', '-1','None');
        $cssdisplay=array(
            'auto',
            'inline',
            'block',
            'inline-block',
            'inline-flex',
            'inline-table',
            'list-item',
            'run-in',
            'table',
            'table-caption',
            'table-column-group',
            'table-header-group',
            'table-footer-group',
            'table-row-group',
            'table-cell',
            'table-column',
            'table-row',
            'none',
            'initial',
            'inherit'
        );
        foreach($cssdisplay as $item)
        {
            $options[] = JHtml::_('select.option', $item, $item);
        }
        $options = array_merge(parent::getOptions(), $options);
        $html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr).$class, 'value', 'text', $this->value, $this->id);

        return implode('',$html);
    }

}
