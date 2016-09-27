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
    private static $list_all_menu_item;
    private static $list_menu_item_id;
    private static $list_root_menu_item_id;
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
     * @param   string $name The property name for which to the the value.
     *
     * @return  mixed  The property value or null.
     *
     * @since   3.2
     */
    public function __get($name)
    {
        switch ($name) {
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
     * @param   string $name The property name for which to the the value.
     * @param   mixed $value The value of the property.
     *
     * @return  void
     *
     * @since   3.2
     */
    public function __set($name, $value)
    {
        switch ($name) {
            case 'menuType':
                $this->menuType = (string)$value;
                break;

            case 'language':
            case 'published':
            case 'disable':
                $value = (string)$value;
                $this->$name = $value ? explode(',', $value) : array();
                break;

            default:
                parent::__set($name, $value);
        }
    }

    /**
     * Method to attach a JForm object to the field.
     *
     * @param   SimpleXMLElement $element The SimpleXMLElement object representing the <field /> tag for the form field object.
     * @param   mixed $value The form field value to validate.
     * @param   string $group The field name group control value. This acts as as an array container for the field.
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

        if ($result == true) {
            $this->menu_type_id = (string)$this->element['menu_type_id'];
            $this->published = $this->element['published'] ? explode(',', (string)$this->element['published']) : array();
            $this->disable = $this->element['disable'] ? explode(',', (string)$this->element['disable']) : array();
            $this->language = $this->element['language'] ? explode(',', (string)$this->element['language']) : array();
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
        $website = JFactory::getWebsite();
        // Get the menu items.
        $list_menu_type = MenusHelperFrontEnd::get_menu_type_by_website_id($website->website_id);
        $is_supper_admin_site=JFactory::is_website_supper_admin();
        ob_start();
        ?>
        <select name="<?php echo $this->name ?>" id="<?php echo $this->id ?>">
            <optgroup label="No select">
                <option value="">None</option>
            </optgroup>
            <?php foreach ($list_menu_type as $menu_type) {
                if(!$is_supper_admin_site && $menu_type->supper_admin_menu_type_id)
                {
                    continue;
                }
                ?>

                <optgroup label="<?php echo $menu_type->title ?>">
                    <?php
                    $root_menu_item_id = MenusHelperFrontEnd::get_root_menu_item_id_by_menu_type_id($menu_type->id);
                    $list_children_menu_item = MenusHelperFrontEnd::get_children_menu_item_by_menu_item_id($root_menu_item_id);
                    $children = array();
                    // First pass - collect children
                    foreach ($list_children_menu_item as $v) {
                        $pt = $v->parent_id;
                        $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
                        $list = @$children[$pt] ? $children[$pt] : array();
                        array_push($list, $v);
                        $children[$pt] = $list;
                    }
                    if (!function_exists('sub_render_menu_item')) {
                        function sub_render_menu_item(&$html, $menu_item_selected = 0, $root_menu_item_id, $children, $level = 0, $max_level = 999)
                        {
                            if ($children[$root_menu_item_id]) {
                                $level1 = $level + 1;
                                foreach ($children[$root_menu_item_id] as $v) {
                                    $root_menu_item_id = $v->id;
                                    $title = $v->title;
                                    $title = str_repeat('---', $level) . $title;
                                    $html .= '<option ' . ($menu_item_selected == $v->id ? 'selected' : '') . '  value="' . $v->id . '">' . $title . '</option>';
                                    sub_render_menu_item($html, $menu_item_selected, $root_menu_item_id, $children, $level1, $max_level);
                                }
                            }
                        }
                    }
                    $html = '';
                    $menu_item_selected = $this->value;
                    sub_render_menu_item($html, $menu_item_selected, $root_menu_item_id, $children);
                    echo $html;
                    ?>
                </optgroup>
            <?php } ?>
        </select>

        <?php
        $html = ob_get_clean();
        return $html;
    }

    public function get_new_value_by_old_value($website_id)
    {

        if ($this->value) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->clear();
            $query->select('menu.id,menu.parent_id,menu.copy_from')
                ->from('#__menu AS menu');
            $md5_query=md5($query->dump());
            $list_all_menu_item=static::$list_all_menu_item[$md5_query];
            if(!$list_all_menu_item)
            {
                $db->setQuery($query);
                $list_all_menu_item = $db->loadObjectList();
                static::$list_all_menu_item[$md5_query]=$list_all_menu_item;
            }

            $list_menu_item = array();
            // First pass - collect children
            foreach ($list_all_menu_item as $v) {
                $pt = $v->parent_id;
                $pt = ($pt == '' || $pt == $v->id) ? 'root' : $pt;
                $list = @$list_menu_item[$pt] ? $list_menu_item[$pt] : array();
                array_push($list, $v);
                $list_menu_item[$pt] = $list;
            }

            unset($list_menu_item['root']);

            $query = $db->getQuery(true);
            $query->clear();
            $query->select('menu_type_id_menu_id.menu_id')
                ->from('#__menu_type_id_menu_id AS menu_type_id_menu_id')
                ->leftJoin('#__menu_types AS menu_types ON menu_types.id=menu_type_id_menu_id.menu_type_id')
                ->where('menu_types.website_id=' . (int)$website_id);


            $md5_query=md5($query->dump());
            $list_root_menu_item_id=static::$list_root_menu_item_id[$md5_query];
            if(!$list_root_menu_item_id)
            {
                $db->setQuery($query);
                $list_root_menu_item_id = $db->loadColumn();
                static::$list_root_menu_item_id[$md5_query]=$list_root_menu_item_id;
            }

            $get_menu_item_exclusion_root_of_website = function ($function_call_back, $root_menu_item_id = 0, &$list_menu_item_id_exclusion_root_of_website, $list_menu_item = array(), $level = 0, $max_level = 999) {
                if ($list_menu_item[$root_menu_item_id]) {
                    $level1 = $level + 1;
                    foreach ($list_menu_item[$root_menu_item_id] as $v) {
                        $list_menu_item_id_exclusion_root_of_website[] = $v->id;
                        $menu_item_id_1 = $v->id;
                        $function_call_back($function_call_back, $menu_item_id_1, $list_menu_item_id_exclusion_root_of_website, $list_menu_item, $level1, $max_level);
                    }
                }
            };


            $list_menu_item_id_exclusion_root_of_website = array();
            foreach ($list_root_menu_item_id as $root_menu_item_id) {
                $get_menu_item_exclusion_root_of_website($get_menu_item_exclusion_root_of_website, $root_menu_item_id, $list_menu_item_id_exclusion_root_of_website, $list_menu_item);
            }

            $query = $db->getQuery(true);
            $query->select('id')
                ->from('#__menu')
                ->where('copy_from=' . (int)$this->value)
                ->where('id IN (' . implode(',', $list_menu_item_id_exclusion_root_of_website) . ')');


            $md5_query=md5($query->dump());

            $menu_item_id=static::$list_menu_item_id[$md5_query];
            if(!$list_root_menu_item_id)
            {
                $db->setQuery($query);
                $menu_item_id = $db->loadResult();
                static::$list_menu_item_id[$md5_query]=$menu_item_id;
            }

            return $menu_item_id;
        } else {
            return $this->value;
        }
    }

}
