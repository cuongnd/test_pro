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
class JFormFieldShowInPage extends JFormFieldGroupedList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  1.6
	 */
	public $type = 'showinpage';

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
	protected function getGroups()
	{
		$groups = array();
		$app=JFactory::getApplication();
		$menu=$app->getMenu();
		$menuItemActive=$menu->getActive()?$menu->getActive():$menu->getDefault();

		$params=$menuItemActive->params;
		$use_main_frame=$params->get('use_main_frame',0);

		$menu_type_id = $this->menu_type_id;
		$db=JFactory::getDbo();
		$query=$db->getQuery(true);
		$query->select('menu.id AS value,menu.title AS text')
			->from('#__menu AS menu')
			->where('menu.id='.(int)$menuItemActive->id.' OR  menu.id='.(int)$use_main_frame)
			;
		$items=$db->setQuery($query)->loadObjectList();
		// Get the menu items.

		foreach ($items as $item)
		{
			// Initialize the group.
			$groups[$item->menu_type_title][] = JHtml::_(
				'select.option', $item->value, $item->text, 'value', 'text',
				in_array($item->type, $this->disable)  ,$this->value
			);
		}
		$scriptId='script_lib_cms_form_fields_menuitem';
		ob_start();
		?>
		<script type="text/javascript" id="<?php echo $scriptId ?>">
			<?php
				ob_get_clean();
				ob_start();
			?>
			jQuery(document).ready(function($){
			});
			<?php
			 $script=ob_get_clean();
			 ob_start();
			  ?>
		</script>
		<?php
		ob_get_clean();
		$doc=JFactory::getDocument();
		$doc->addScriptDeclaration($script,"text/javascript",$scriptId);



		// Merge any additional groups in the XML definition.
		$groups = array_merge(parent::getGroups(), $groups);

		return $groups;
	}
    public function get_new_value_by_old_value($website_id)
    {
        if ($this->value) {
            $db = JFactory::getDbo();
            $query = $db->getQuery(true);
            $query->clear();
            $query->select('menu.id,menu.parent_id,menu.copy_from')
                ->from('#__menu AS menu');
            $db->setQuery($query);
            $list_all_menu_item = $db->loadObjectList();

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
            $db->setQuery($query);
            $list_root_menu_item_id = $db->loadColumn();

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
                $list_menu_item_id_exclusion_root_of_website[]=$root_menu_item_id;
                $get_menu_item_exclusion_root_of_website($get_menu_item_exclusion_root_of_website, $root_menu_item_id, $list_menu_item_id_exclusion_root_of_website, $list_menu_item);
            }

            $query = $db->getQuery(true);
            $query->select('id')
                ->from('#__menu')
                ->where('copy_from=' . (int)$this->value)
                ->where('id IN (' . implode(',', $list_menu_item_id_exclusion_root_of_website) . ')');
            $db->setQuery($query);
            $menu_item_id = $db->loadResult();
            return $menu_item_id;
        } else {
            return null;
        }
    }

}
