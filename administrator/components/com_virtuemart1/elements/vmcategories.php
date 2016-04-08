<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @package	VirtueMart
 * @subpackage Plugins  - Elements
 * @author Valérie Isaksen
 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2011 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: $
 */
if (!class_exists('VmConfig'))
    require(JPATH_ROOT . DS . 'administrator' . DS . 'components' . DS . 'com_virtuemart' . DS . 'helpers' . DS . 'config.php');

if (!class_exists('ShopFunctions'))
    require(JPATH_VM_ADMINISTRATOR . DS . 'helpers' . DS . 'shopfunctions.php');
if (!class_exists('TableCategories'))
    require(JPATH_VM_ADMINISTRATOR . DS . 'tables' . DS . 'categories.php');


if (!class_exists('VmElements'))
    require(JPATH_VM_ADMINISTRATOR . DS . 'elements' . DS . 'vmelements.php');
/*
 * This element is used by the menu manager
 * Should be that way
 */
class JFormFieldVMcategories extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'vmcategories';


    /**
     * Method to get the field input markup.
     * The checked element sets the field to selected.
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    protected function getInput()
    {

        $key = ($this->element['key_field'] ? $this->element['key_field'] : 'value');
        $val     = !empty($this->value) ? $this->value : '0';
        $lang = JFactory::getLanguage();
        $lang->load('com_virtuemart',JPATH_ADMINISTRATOR);
        ShopFunctions::$categoryTree=0;
        $categorylist = ShopFunctions::categoryListTree(array($this->value));
        $option=JHTML::_('select.option', 0, JText::_('COM_VIRTUEMART_CATEGORY_FORM_TOP_LEVEL'));
        array_unshift($categorylist, $option);
        $dropdown = JHTML::_('select.genericlist', $categorylist, $this->name, 'class="inputbox"', 'value', 'text', $this->value);
        return $dropdown;
    }

    function fetchElement($name, $value, &$node, $control_name) {
        JPlugin::loadLanguage('com_virtuemart', JPATH_ADMINISTRATOR);
        $categorylist = ShopFunctions::categoryListTree(array($value));

        $class = '';
        $html = '<select class="inputbox"   name="' . $control_name . '[' . $name . ']' . '" >';
        $html .= '<option value="0">' . JText::_('COM_VIRTUEMART_CATEGORY_FORM_TOP_LEVEL') . '</option>';
        $html .= $categorylist;
        $html .="</select>";
        return $html;
    }

}

