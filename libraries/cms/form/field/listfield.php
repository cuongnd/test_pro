<?php
/**
 * @package     Joomla.Libraries
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

JFormHelper::loadFieldClass('textarea');

/**
 * Form Field class for the Joomla CMS.
 * A textarea field for content creation
 *
 * @package     Joomla.Libraries
 * @subpackage  Form
 * @see         JEditor
 * @since       1.6
 */
class JFormFieldlistfield extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.6
     */
    public $type = 'listfield';

    /**
     * The JEditor object.
     *
     * @var    JEditor
     * @since  1.6
     */
    protected $editor;

    /**
     * The height of the editor.
     *
     * @var    string
     * @since  3.2
     */
    protected $height;

    /**
     * The width of the editor.
     *
     * @var    string
     * @since  3.2
     */
    protected $width;

    /**
     * The assetField of the editor.
     *
     * @var    string
     * @since  3.2
     */
    protected $assetField;

    /**
     * The authorField of the editor.
     *
     * @var    string
     * @since  3.2
     */
    protected $authorField;

    /**
     * The asset of the editor.
     *
     * @var    string
     * @since  3.2
     */
    protected $asset;

    /**
     * The buttons of the editor.
     *
     * @var    mixed
     * @since  3.2
     */
    protected $buttons;

    /**
     * The hide of the editor.
     *
     * @var    array
     * @since  3.2
     */
    protected $hide;

    /**
     * The editorType of the editor.
     *
     * @var    array
     * @since  3.2
     */
    protected $editorType;

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
            case 'height':
            case 'width':
            case 'assetField':
            case 'authorField':
            case 'asset':
            case 'buttons':
            case 'hide':
            case 'editorType':
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
            case 'height':
            case 'width':
            case 'assetField':
            case 'authorField':
            case 'asset':
                $this->$name = (string)$value;
                break;

            case 'buttons':
                $value = (string)$value;

                if ($value == 'true' || $value == 'yes' || $value == '1') {
                    $this->buttons = true;
                } elseif ($value == 'false' || $value == 'no' || $value == '0') {
                    $this->buttons = false;
                } else {
                    $this->buttons = explode(',', $value);
                }
                break;

            case 'hide':
                $value = (string)$value;
                $this->hide = $value ? explode(',', $value) : array();
                break;

            case 'editorType':
                // Can be in the form of: editor="desired|alternative".
                $this->editorType = explode('|', trim((string)$value));
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
            $this->height = $this->element['height'] ? (string)$this->element['height'] : '500';
            $this->width = $this->element['width'] ? (string)$this->element['width'] : '100%';
            $this->assetField = $this->element['asset_field'] ? (string)$this->element['asset_field'] : 'asset_id';
            $this->authorField = $this->element['created_by_field'] ? (string)$this->element['created_by_field'] : 'created_by';
            $this->asset = $this->form->getValue($this->assetField) ? $this->form->getValue($this->assetField) : (string)$this->element['asset_id'];

            $buttons = (string)$this->element['buttons'];
            $hide = (string)$this->element['hide'];
            $editorType = (string)$this->element['editor'];

            if ($buttons == 'true' || $buttons == 'yes' || $buttons == '1') {
                $this->buttons = true;
            } elseif ($buttons == 'false' || $buttons == 'no' || $buttons == '0') {
                $this->buttons = false;
            } else {
                $this->buttons = !empty($hide) ? explode(',', $buttons) : array();
            }

            $this->hide = !empty($hide) ? explode(',', (string)$this->element['hide']) : array();
            $this->editorType = !empty($editorType) ? explode('|', trim($editorType)) : array();
        }

        return $result;
    }

    /**
     * Method to get the field input markup for the editor area
     *
     * @return  string  The field input markup.
     *
     * @since   1.6
     */
    function create_html_list($nodes,$list_data_type,$level)
    {
        echo '<ol class="dd-list">';

        foreach ($nodes as $column) {
            $childNodes = $column->children;
            ob_start();
            ?>
            <li class="dd-item"
            data-post_name="<?php echo $column->post_name ?>"
            data-type="<?php echo $column->type ?>"
            data-editable="<?php echo $column->editable ?>"
            data-primary_key="<?php echo $column->primary_key ?>"
            data-table_name="<?php echo $column->table_name ?>"
            data-column_name="<?php echo $column->column_name ?>"
            data-level="<?php echo $level ?>"
            >
            <div class="dd-handle">
                <div class="dd-handle-move pull-left"><i class="fa-move"></i></div>
                <?php echo $column->id ?>
                <button onclick="config_update.remove_item_nestable(this)" class="dd-handle-remove pull-right"><i class="fa-remove"></i></button>
            </div>
            <div>
                <button class="add_node">add node</button>
                <button class="add_sub_node">add sub node</button>
            </div>
            <label>table name<input class="table_name" style="width: 200px" type="text"  onchange="config_update.update_data_column(this,'table_name')"
                                     value="<?php echo $column->table_name ?>"/></label>
            <label>Column name<input class="column_name"  style="width: 200px" type="text"  onchange="config_update.update_data_column(this,'column_name')"
                                     value="<?php echo $column->column_name ?>"/></label>

            <label>Label field<input class="label_field"  style="width: 200px" type="text"  onchange="config_update.update_data_column(this,'label_field')"
                                     value="<?php echo $column->label_field ?>"/></label>
            <label>Type
                <select style="width: 200px" name="data_type1" onchange="config_update.update_data_column(this,'type')"
                        class="data_type">
                    <?php foreach ($list_data_type as $type) { ?>
                <option <?php echo $column->type == $type ? 'selected' : '' ?>
                    value="<?php echo $type ?>"><?php echo $type ?></option>
            <?php } ?>
                </select>
            </label>
            <label>Post name
                <input class="post_name"  style="width: 200px" type="text"  onchange="config_update.update_data_column(this,'post_name')"
                       value="<?php echo $column->post_name ?>"/>
            </label>
            <label>Editable<input <?php echo $column->editable == 1 ? 'checked' : '' ?>  type="checkbox"
                                                                                         onchange="config_update.update_data_column(this,'editable','checkbox')"
                                                                                         value="1"/></label>
            <label>Primary key<input <?php echo $column->primary_key == 1 ? 'checked' : '' ?> class="primary-key" data-level="<?php echo $level ?>" name="primary_key_<?php echo $level ?>"  type="radio"
                                                                              onchange="config_update.primary_key_update_value(this);config_update.call_on_change(this)"
                                                                              value="<?php echo $column->primary_key == 1 ? 1 : 0 ?>"/></label>
            <?php
            echo ob_get_clean();
            if (count($childNodes) > 0) {
                $level=$level+1;
                JFormFieldlistfield::create_html_list($childNodes,$list_data_type,$level);
            }
            echo "</li>";
        }
        echo '</ol>';
    }
    protected function getInput()
    {
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $db = JFactory::getDbo();
        JHtml::_('jquery.framework');
        $doc->addLessStyleSheetTest(JUri::root() . "/libraries/cms/form/field/listfield.less");
        $doc->addStyleSheet(JUri::root() . "/media/jui_front_end/css/select2.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/jquery.appendGrid-master/jquery.appendGrid-development.css");
        $doc->addScript(JUri::root() . "/media/system/js/Nestable-master/jquery.nestable.js");
        $doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");
        $doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
        $doc->addScript(JUri::root() . "/libraries/cms/form/field/listfield.js");
        $doc->addScript(JUri::root() . "/media/system/js/base64.js");
        $doc->addScript(JUri::root() . "/media/system/js/jquery.appendGrid-master/jquery.appendGrid-development.js");
        $id = $app->input->get('id', 0, 'int');
        $menu=$app->getMenu();
        $menu_item=$menu->getItem($id);
        $link=$menu_item->link;
        $uri_link=JFactory::getURI($link);
        $view=$uri_link->getVar('view');
        $component=$uri_link->getVar('component');

        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        require_once JPATH_ROOT . '/components/com_modules/helpers/module.php';
        $website = JFactory::getWebsite();
        require_once JPATH_ROOT . '/libraries/joomla/form/fields/icon.php';
        $db = JFactory::getDbo();
        require_once JPATH_ROOT . '/components/com_phpmyadmin/tables/updatetable.php';
        $table_control = new JTableUpdateTable($db, 'control');
        $website_name=JFactory::get_website_name();
        $element_path='components/website/website_' . $website_name . '/' . $component.'/models/form/'.$view.'.xml';
        $filter['element_path'] = $element_path;
        $filter['website_id'] = $website->website_id;
        $table_control->load($filter);
        if (!$table_control->id) {
            $table_control->id = 0;
            $table_control->website_id = $website->website_id;
            $table_control->element_path = $element_path;
            require_once JPATH_ROOT.'/components/com_components/helpers/components.php';
            $table_control->type = componentsHelper::ELEMENT_TYPE;
            $ok = $table_control->store();
            if (!$ok) {
                throw new Exception($table_control->getError());
            }


        }
        $fields = $table_control->fields;
        $fields = base64_decode($fields);
        $field_block_output = $fields;
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $fields = (array)up_json_decode($fields, false, 512, JSON_PARSE_JAVASCRIPT);
        if (!count($fields)) {
            $fields = array(new stdClass());
        }
        jimport('joomla.filesystem.folder');

        $list_field_type = array();
        $list_path = JFormField::get_list_field_path();


        foreach ($list_path as $path) {
            $_list_field_type = JFolder::files(JPATH_ROOT . '/' . $path, '.php');
            foreach ($_list_field_type as $fied_type) {
                $list_field_type[] = (object)array(
                    name => $fied_type,
                    path => $path . '/' . $fied_type
                );
            }
        }


//get list field table position config
        $list_field_table_position_config = $db->getTableColumns('#__modules');
        $list_field_table_position_config = array_keys($list_field_table_position_config);
//end get list field table position config

        require_once JPATH_ROOT . '/libraries/joomla/form/fields/groupedlist.php';


        $scriptId = "script_list_field_" . '_' . JUserHelper::genRandomPassword();
        ob_start();
        ?>
        <script type="text/javascript" id="<?php echo $scriptId ?>">

            <?php
            ob_get_clean();
            ob_start();
            ?>
            jQuery(document).ready(function ($) {

                list_field_config.field_name_option.tags =<?php echo json_encode($list_field_table_position_config) ?>;
                list_field_config.init_list_field_config();
            });
            <?php
            $script = ob_get_clean();
            ob_start();
            ?>
        </script>
    <?php
    ob_get_clean();
    $doc->addScriptDeclaration($script, "text/javascript", $scriptId);

    function create_html_list($nodes, $indent = '', $list_field_type, $list_field_table_position_config)
    {


    echo '<ol class="dd-list">';
    $i = 1;
    foreach ($nodes as $item) {
    $indent1 = $indent != '' ? $indent . '_' . $i : $i;

    $groupedlist = new JFormFieldGroupedList();
    $groupedlist->setValue($item->group);
    $childNodes = $item->children;
    $list_attribute_config = array();
    foreach ($list_field_type as $item_type) {
        if (strtolower($item_type->name) == strtolower($item->type . '.php')) {
            require_once JPATH_ROOT . '/' . $item_type->path;
            $class_item_type = 'JFormField' . $item->type;
            $class_item_type = new $class_item_type;
            $list_attribute_config = $class_item_type->get_attribute_config();
            $reflector = new ReflectionClass(get_class($class_item_type));
            $field_path = dirname($reflector->getFileName());
            $field_path = str_replace(JPATH_ROOT . '/', '', $field_path);
            break;
        }
    }
    $item->config_property = base64_decode($item->config_property);
    $item->config_property = json_decode($item->config_property);
    $item->config_property = JArrayHelper::pivot($item->config_property, 'property_key');

    foreach ($list_attribute_config as $key_config_property => $value_config_property) {
        if (!$item->config_property[$key_config_property]) {
            $item->config_property[$key_config_property] = (object)array(
                property_key => $key_config_property,
                property_value => $value_config_property
            );
        }
    }
    $item->config_property = JArrayHelper::key_string_to_interger($item->config_property);
    $item->config_property = json_encode($item->config_property);
    $item->config_property = base64_encode($item->config_property);

    ob_start();
    ?>

        <li class="dd-item"
            <?php foreach ($item as $key => $value) { ?>
                data-<?php echo $key ?>="<?php echo $value ?>"
            <?php } ?>

        >
            <div class="dd-handle">
                <div class="dd-handle-move pull-left"><i class="fa-move"></i></div>
                <span class="key_name"><?php echo "$item->label ( $item->name ) " ?></span>
                <button onclick="list_field_config.remove_item_nestable(this)" class="dd-handle-remove dd-nodrag pull-right"><i
                        class="fa-remove"></i></button>
                <button onclick="list_field_config.expand_item_nestable(this)" class="dd-handle-expand dd-nodrag pull-right"><i
                        class="im-plus"></i></button>
            </div>

            <div class="more_options ">
                <div>
                    <button class="add_node">add node</button>
                    <button class="add_sub_node">add sub node</button>
                </div>


                <label>Name<input class="form-control select_field_name" style="width: 200px"
                                  onchange="list_field_config.update_data_column(this,'name')"
                                  value="<?php echo $item->name ?>" type="text"/></label>
                <label>default<input class="form-control" onchange="list_field_config.update_data_column(this,'default')"
                                     value="<?php echo $item->default ?>" type="text"/></label>
                <label>label<input class="form-control" onchange="list_field_config.update_data_column(this,'label')"
                                   value="<?php echo $item->label ?>" type="text"/></label>
                <label>On change<input class="form-control" onchange="view_config.update_data_column(this,'onchange')"
                                       value="<?php echo $item->onchange ?>" type="text"/></label>

                <label>Icon<input class="icon_menu_item" style="width: 200px" type="text"
                                  onchange="list_field_config.update_data_column(this,'icon')"
                                  value="<?php echo $item->icon ?>"/></label>
                <label>Description<textarea class="description" style="width: 200px"
                                            onchange="list_field_config.update_data_column(this,'description')"
                                            value="<?php echo $item->icon ?>"></textarea></label>
                <label>
                    Access
                    <?php
                    echo JHtml::_('access.level', 'access_level', $item->access, array("class" => 'menu_access_level'));
                    ?>
                </label>

                <label>
                    type
                    <select disableChosen="true" style="width: 200px"
                            onchange="list_field_config.update_data_column(this,'type');list_field_config.update_data_column(this,'addfieldpath');list_field_config.update_atrribute_param_config(this)"
                            type="hidden" class="select2 field_type">
                        <?php
                        foreach ($list_field_type as $a_item) {

                            $a_item_name = str_replace('.php', '', $a_item->name);
                            ?>
                            <option <?php echo $a_item_name == $item->type ? 'selected' : '' ?>
                                data-path="<?php echo $a_item->path ?>"
                                value="<?php echo $a_item_name ?>"><?php echo $a_item_name ?></option>
                        <?php } ?>
                    </select>

                </label>

                <label>Read
                    only<?php echo JHtml::_('input.radioyesno', '', 'readonly', $item->readonly, array('data-onchange' => "list_field_config.update_data_column(this,'readonly','checkbox')", 'class' => 'not-bootstrap-toggle')) ?></label>

                <div class="row">

                    <div class="config_property col-md-6">
                        <table class="tbl_append_grid_config_property"
                               data-config_property="<?php echo $item->config_property ?>"
                               id="tblAppendGrid_config_property_<?php echo $indent1 ?>"></table>
                    </div>
                    <div class="config_params col-md-6">
                        <table class="tbl_append_grid" data-config_params="<?php echo $item->config_params ?>"
                               id="tblAppendGrid_<?php echo $indent1 ?>"></table>
                    </div>
                </div>

            </div>

            <?php
            echo ob_get_clean();
            if (is_array($childNodes) && count($childNodes) > 0) {
                create_html_list($childNodes, $indent1, $list_field_type, $list_field_table_position_config);
            }
            echo "</li>";
            $i++;
            }
            echo '</ol>';
            }


            ob_start();
            ?>
            <div class="row">
                <div class="col-md-12">
                    <label>show more option<input onchange="list_field_config.show_more_options(this);" type="checkbox" checked
                                                  class="show_more_options"></label>
                </div>
            </div>
            <div class="row">


                <div class="col-md-12">

                    <div class="cf nestable-lists">
                        <div class="row">
                            <div class="menu_type_item col-md-12" data-menu-type-id="<?php echo $menu_type_id ?>">
                                <div id="field_block" class="dd">
                                    <?php echo create_html_list($fields, '', $list_field_type, $list_field_table_position_config); ?>
                                </div>
                            </div>

                        </div>

                    </div>


                </div>
            </div>
            <input type="hidden" data-element_path="<?php echo $table_control->element_path ?>"
                   value="<?php echo $field_block_output ?>" id="field_block-output"/>


            <button class="btn btn-danger  pull-right" onclick="list_field_config.save(self)"><i
                    class="fa-save"></i>Save
            </button>
            &nbsp;&nbsp;
            <button class="btn btn-danger cancel-block-property pull-right" onclick="list_field_config.cancel(self)"><i
                    class="fa-save"></i>Cancel
            </button>






        <?php
        $html .= ob_get_clean();
        return $html;
    }

    /**
     * Method to get a JEditor object based on the form field.
     *
     * @return  JEditor  The JEditor object.
     *
     * @since   1.6
     */
    public function getListAllFunction()
    {
        $listFunction = array(
            'aggregate' => array(
                'avg(expr)'
            , 'count(expr)'
            , 'group_concat(expr)'
            ),
            'cast' => array(
                'cast(expr)'

            ),
            'date and time' => array(
                'adddate(date,interval,exprunit)'
            , 'adddate(expr,days)'
            , 'addtime(expr1,expr2)'
            ),
            'mathematical' => array(),
            'other' => array(),
            'string' => array()
        );
        return $listFunction;
    }

}
