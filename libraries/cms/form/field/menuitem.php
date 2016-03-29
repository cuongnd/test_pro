<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

JFormHelper::loadFieldClass('groupedlist');

// Import the com_menus helper.
require_once realpath(JPATH_ADMINISTRATOR . '/components/com_menus/helpers/menus.php');

/**
 * Supports an HTML grouped select list of menu item grouped by menu
 *
 * @package     Joomla.Libraries
 * @subpackage  Form
 * @since       1.6
 */
class JFormFieldMenuitem extends JFormFieldGroupedList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'MenuItem';

	/**
	 * The menu type.
	 *
	 * @var    string
	 * @since  3.2
	 */
	protected $menuType;

	/**
	 * The language.
	 *
	 * @var    array
	 * @since  3.2
	 */
	protected $language;

	/**
	 * The published status.
	 *
	 * @var    array
	 * @since  3.2
	 */
	protected $published;

	/**
	 * The disabled status.
	 *
	 * @var    array
	 * @since  3.2
	 */
	protected $disable;

	/**
	 * Method to get certain otherwise inaccessible properties from the form field object.
	 *
	 * @param   string  $name  The property name for which to the the value.
	 *
	 * @return  mixed  The property value or null.
	 *
	 * @since   3.2
	 */
	public function __get($name)
	{
		switch ($name)
		{
			case 'menuType':
			case 'language':
			case 'published':
			case 'disable':
				return $this->$name;
		}

		return parent::__get($name);
	}

	/**
	 * Method to set certain otherwise inaccessible properties of the form field object.
	 *
	 * @param   string  $name   The property name for which to the the value.
	 * @param   mixed   $value  The value of the property.
	 *
	 * @return  void
	 *
	 * @since   3.2
	 */
	public function __set($name, $value)
	{
		switch ($name)
		{
			case 'menuType':
				$this->menuType = (string) $value;
				break;

			case 'language':
			case 'published':
			case 'disable':
				$value = (string) $value;
				$this->$name = $value ? explode(',', $value) : array();
				break;

			default:
				parent::__set($name, $value);
		}
	}

	/**
	 * Method to attach a JForm object to the field.
	 *
	 * @param   SimpleXMLElement  $element  The SimpleXMLElement object representing the <field /> tag for the form field object.
	 * @param   mixed             $value    The form field value to validate.
	 * @param   string            $group    The field name group control value. This acts as as an array container for the field.
	 *                                      For example if the field has name="foo" and the group value is set to "bar" then the
	 *                                      full field name would end up being "bar[foo]".
	 *
	 * @return  boolean  True on success.
	 *
	 * @see     JFormField::setup()
	 * @since   3.2
	 */
	public function setup(SimpleXMLElement $element, $value, $group = null)
	{
		$result = parent::setup($element, $value, $group);

		if ($result == true)
		{
			$this->menu_type_id  = (string) $this->element['menu_type_id'];
			$this->published = $this->element['published'] ? explode(',', (string) $this->element['published']) : array();
			$this->disable   = $this->element['disable'] ? explode(',', (string) $this->element['disable']) : array();
			$this->language  = $this->element['language'] ? explode(',', (string) $this->element['language']) : array();
		}

		return $result;
	}

	/**
	 * Method to get the field option groups.
	 *
	 * @return  array  The field option objects as a nested array in groups.
	 *
	 * @since   1.6
	 */
	protected function getInput()
	{
        $website=JFactory::getWebsite();
		// Get the menu items.
        $list_menu_type=MenusHelperFrontEnd::get_menu_type_by_website_id($website->website_id);
        ob_start();
        ?>
        <select name="<?php echo $this->name ?>" id="<?php echo $this->id ?>">
            <optgroup label="No select">
                <option value="">None</option>
            </optgroup>
            <?php foreach($list_menu_type as $memu_type){ ?>
            <optgroup label="<?php echo $memu_type->title ?>">
                <?php
                $root_menu_item_id=MenusHelperFrontEnd::get_root_menu_item_id_by_menu_type_id($memu_type->id);
                $list_children_menu_item=MenusHelperFrontEnd::get_children_menu_item_by_menu_item_id($root_menu_item_id);
                $children = array();
                // First pass - collect children
                foreach ($list_children_menu_item as $v) {
                    $pt = $v->parent_id;
                    $pt=($pt==''||$pt==$v->id)?'list_root':$pt;
                    $list = @$children[$pt] ? $children[$pt] : array();
                    array_push($list, $v);
                    $children[$pt] = $list;
                }
                if (!function_exists('sub_render_menu_item')) {
                    function sub_render_menu_item(&$html,$menu_item_selected=0, $root_menu_item_id, $children,$level=0,$max_level=999)
                    {
                        if ($children[$root_menu_item_id]) {
                            $level1=$level+1;
                            foreach ($children[$root_menu_item_id] as $v) {
                                $root_menu_item_id=$v->id;
                                $title=$v->title;
                                $title=str_repeat('---',$level).$title;
                                $html.='<option '.($menu_item_selected==$v->id?'selected':'').'  value="'.$v->id.'">'.$title.'</option>';
                                sub_render_menu_item($html,$menu_item_selected,$root_menu_item_id, $children,$level1,$max_level);
                            }
                        }
                    }
                }
                $html='';
                $menu_item_selected=$this->value;
                sub_render_menu_item($html,$menu_item_selected,$root_menu_item_id,$children);
                echo $html;
                ?>
            </optgroup>
            <?php } ?>
        </select>

        <?php
        $html=ob_get_clean();
        return $html;
	}
}
