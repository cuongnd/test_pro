<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

JFormHelper::loadFieldClass('list');

// Import the com_menus helper.
require_once realpath(JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');

/**
 * Supports an HTML select list of menus
 *
 * @package     Joomla.Libraries
 * @subpackage  Form
 * @since       1.6
 */
class JFormFieldMenu extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'Menu';

	/**
	 * Method to get the list of menus for the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.6
	 */
	protected function getInput()
	{
        $website=JFactory::getWebsite();
        $list_menu_type=MenusHelperFrontEnd::get_list_menu_type_by_website_id($website->website_id);
        $option=array(
            'id'=>'',
            'title'=>'please select menu type'
        );
        array_unshift($list_menu_type,$option);
        $attr='';
        $attr .= !empty($this->class) ? ' class="' . $this->class . '"' : '';
        $attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
        $attr .= $this->multiple ? ' multiple' : '';
        $attr .= $this->required ? ' required aria-required="true"' : '';
        $attr .= $this->autofocus ? ' autofocus' : '';
        return JHtml::_('select.genericlist',$list_menu_type,$this->name,$attr,'id','title',$this->value);

	}
    public static function get_new_value_by_old_value($website_id,$params,$path){
        $menu_item_id=$params->get($path,0);
        $db=JFactory::getDbo();
        $query=$db->getQuery(true);
        $query->select('id')
            ->from('#__menu_types')
            ->where('copy_from='.(int)$menu_item_id)
            ->where('website_id='.(int)$website_id)
            ;
        $db->setQuery($query);

        $menu_item_id=$db->loadResult();
        return $menu_item_id;
    }
}
