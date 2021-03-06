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
class JFormFieldDomains extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'domains';

    /**
     * Method to get the field input markup for e-mail addresses.
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    protected function getInput()
    {
        $db=JFactory::getDbo();
        JHtml::_('formbehavior.select2', 'select');
        $query=$db->getQuery(true);
        $this->value[]=0;
        JArrayHelper::toInteger($this->value);
        $query->from('#__domain_website')
            ->select('id,domain')
            ->where('(website_id IS NULL OR id IN('.implode(',',$this->value).'))')
        ;
        $db->setQuery($query);
        $list_website=$db->loadObjectList();
        $attr = '';
        $attr .= !empty($this->class) ? ' class="' . $this->class . '"' : '';
        $attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
        $attr .= $this->multiple ? ' multiple' : '';
        $attr .= $this->required ? ' required aria-required="true"' : '';
        $attr .= $this->autofocus ? ' autofocus' : '';
        $attr.=' disableChosen="true" ';
        $attr.=' style="width:100%" ';
        $attribute[]=$this->onchange?'onchange="'.$this->onchange.'"':'';
        $attribute=implode(' ',$attribute);
        $html = JHtml::_('select.genericlist', $list_website, $this->name,$attr, 'id', 'domain', $this->value);

        return $html;
    }
    public function get_attribute_config()
    {
        return array(
            multiple=>'false',
            size=>'1',
            "class"=>''
        );
    }

}
