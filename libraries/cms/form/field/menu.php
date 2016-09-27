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
    private static $list_menu_item_id;
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
    public  function get_new_value_by_old_value($website_id){
        if($this->value) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->select('id')
                ->from('#__menu_types')
                ->where('copy_from=' . (int)$this->value)
                ->where('website_id=' . (int)$website_id);
            $query_md5=md5($query->dump());
            $menu_type_id=static::$list_menu_item_id[$query_md5];
            if(!$menu_type_id){
                $db->setQuery($query);
                $menu_type_id = $db->loadResult();
                static::$list_menu_item_id[$query_md5]=$menu_type_id;
            }
            return $menu_type_id;
        }else{
            return $this->value;
        }
    }
}
