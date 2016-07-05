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
class JFormFieldWebsites extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'Websites';

    /**
     * Method to get the field input markup for e-mail addresses.
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    protected function getInput()
    {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $query = $db->getQuery(true);
        $query->from('#__website')
            ->select('id,name')
            ->where('created_by='.(int)$user->id)
        ;
        $db->setQuery($query);
        $list_website = $db->loadObjectList();
        $option = array('id' => '', 'name' => 'select website');
        array_unshift($list_website, (object)$option);
        $attribute = array();
        $attribute[] = $this->onchange ? 'onchange="' . $this->onchange . '"' : '';
        $attribute = implode(' ', $attribute);
        $html = JHtml::_('select.genericlist', $list_website, $this->name, $attribute, 'id', 'name', $this->value);

        return $html;
    }
}
