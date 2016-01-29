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
class JFormFieldGridformartheader extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.6
     */
    public $type = 'gridformartheader';

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
    function create_html_list_template($nodes,$list_data_type,$list_data_source,$list_editor,$level)
    {
        echo '<ol class="dd-list">';
        for ($i=0;$i<count($nodes);$i++) {
            $column= $nodes[$i];
            $childNodes = $column->children;
            ob_start();
            ?>
            <li class="dd-item"
            <?php foreach($column as $key=>$value){ ?>
                data-<?php echo $key ?>="<?php echo $value ?>"
            <?php } ?>

            data-level="<?php echo $level ?>"
            >
            <div class="dd-handle">
                <div class="dd-handle-move pull-left"><i class="fa-move"></i></div>
                <span class="key_name"><?php echo "$column->column_title ( $column->column_name ) " ?></span>
                <?php echo $column->id ?>
                <button  class="dd-handle-remove remove_item_nestable dd-nodrag pull-right"><i class="fa-remove"></i></button>
                <button  class="dd-handle-expand dd-nodrag pull-right expand_item_nestable"><i class="im-plus"></i></button>

            </div>
            <div class="more_options dd-nodrag">
                <div>
                    <button class="add_node">add node</button>
                    <button  class="add_sub_node">add sub node</button>
                </div>

                <table class="table">
                    <tr>
                        <td><label for="column_title"> Column title</label></td>
                        <td><input class="form-control update_data_column" style="" type="text" data-property="column_title" name="column_title"    value="<?php echo $column->column_title ?>"/></td>
                        <td><label for="column_name">Column name</label></td>
                        <td><input class="column_name form-control update_data_column" name="column_name"  style="" type="text" data-property="column_name"    value="<?php echo $column->column_name ?>"/></td>
                    </tr>
                    <tr>
                        <td><label for="">Type</label></td>
                        <td>
                            <select data-property="type"  name="data_type1"  class="data_type update_data_column">
                                <?php foreach ($list_data_type as $type) { ?>
                                    <option <?php echo $column->type == $type ? 'selected' : '' ?>
                                        value="<?php echo $type ?>"><?php echo $type ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td><label for="show_command">show command</label></td>
                        <td><input data-tags="true" data-property="show_command"  class="show_command form-control update_data_column" name="show_command"  type="text"   value="<?php echo $column->show_command ?>"/></td>

                    </tr>
                    <tr>
                        <td><label for="max_character">Max character</label></td>
                        <td><input class="form-control update_data_column" data-property="max_character"  style="" name="max_character" type="text"   value="<?php echo $column->max_character ?>"/></td>
                        <td><label for="column_width">Column width</label></td>
                        <td><input type="text" class="form-control update_data_column" data-property="column_width"  name="column_width"  value="<?php echo $column->column_width ?>"/></td>
                    </tr>
                    <tr>
                        <td><label for="link_key">Link key</label></td>
                        <td><input type="text" class="link_key form-control update_data_column" name="link_key" data-property="link_key"   value="<?php echo $column->link_key ?>"/></td>
                        <td><label for="editor">editor</label></td>
                        <td><select data-property="editor" name="editor"   class="update_data_column">
                                <?php foreach ($list_editor as $key => $editor) { ?>
                                    <option <?php echo $column->editor_type == $key ? 'selected' : '' ?>
                                        value="<?php echo $key ?>"><?php echo $editor ?></option>
                                <?php } ?>
                            </select></td>
                    </tr>
                    <tr>
                        <td><label for="">datasource</label></td>
                        <td>
                            <select data-property="data_source_id"  class="update_data_column" style="width: 200px" >
                                <?php foreach ($list_data_source as $key => $source) { ?>
                                    <option <?php echo $column->data_source_id == $source->id ? 'selected' : '' ?>
                                        value="<?php echo $source->id ?>"><?php echo $source->text ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        <td><label for="key_data_source">key datasource</label></td>
                        <td><input data-property="key_data_source" name="key_data_source"  type="text" class="form-control update_data_column"  value="<?php echo $column->key_data_source ?>"/></td>
                    </tr>
                    <tr>
                        <td><label for="">Access</label></td>
                        <td colspan="3">
                            <?php echo JHtml::_('access.level', 'access_level',$column->access,array(
                                "class"=>'column_access_level update_data_column',
                                'style'=>'width:100px',
                                "data-property"=>'access_level'
                            )); ?>
                        </td>

                    </tr>
                    <tr>
                        <td><label for="text_data_source">text datasource</label></td>
                        <td ><input type="text" data-property="text_data_source" name="text_data_source" class="form-control update_data_column"  value="<?php echo $column->text_data_source ?>"/></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><label for="filterable">filterable</label></td>
                        <td ><input class="update_data_column noStyle not-bootstrap-toggle" data-property="filterable" name="filterable" <?php echo $column->filterable == 1 ? 'checked' : '' ?>  type="checkbox"  value="1"/></td>
                        <td><label for="menu">menu</label></td>
                        <td><input class="update_data_column noStyle not-bootstrap-toggle" data-property="menu" name="menu" <?php echo $column->menu == 1 ? 'checked' : '' ?>  type="checkbox"  value="1"/></td>

                    </tr>
                    <tr>
                        <td><label for="sortable">sortable</label></td>
                        <td ><input class="update_data_column noStyle not-bootstrap-toggle" data-property="sortable" name="sortable" <?php echo $column->sortable == 1 ? 'checked' : '' ?>  type="checkbox"  value="1"/></td>
                        <td><label for="checked">button checked</label></td>
                        <td ><input class="update_data_column noStyle not-bootstrap-toggle" data-property="checked" name="checked" <?php echo $column->button_checked == 1 ? 'checked' : '' ?>  type="checkbox"  value="1"/></td>


                    </tr>
                    <tr>
                        <td><label for="locked">locked</label></td>
                        <td ><input class="update_data_column noStyle not-bootstrap-toggle" data-property="locked" id="locked" name="locked" <?php echo $column->locked == 1 ? 'checked' : '' ?>  type="checkbox"  value="1"/></td>

                        <td><label for="checked">Show</label></td>
                        <td ><input class="update_data_column noStyle not-bootstrap-toggle" name="checked" data-property="show" id="" <?php echo $column->show == 1 ? 'checked' : '' ?>  type="checkbox"  value="1"/></td>


                    </tr>
                    <tr>
                        <td colspan="4">
                            <label for="">template data source item select</label>
                            <textarea style="width: 400px; height: 150px;border: 1px solid #ccc" data-key="data_source_template_item_select" data-property="" id="data_source_template_item_select_texarea_<?php  echo $level.'_'.$i ?>" class="column_template_texarea update_data_column" >
                                <?php echo $column->template?base64_decode($column->data_source_template_item_select):'' ?>
                            </textarea>
                        </td>

                    </tr>
                    <tr>

                        <td colspan="4"><label for="">Column template</label>
                             <textarea style="width: 400px; height: 150px;border: 1px solid #ccc"  data-property="template"  data-key="template" id="column_template_texarea_<?php  echo $level.'_'.$i ?>" class="column_template_texarea  update_data_column" >
                                <?php echo $column->template?base64_decode($column->template):'' ?>
                            </textarea>
                        </td>
                    </tr>
                    <tr>

                        <td colspan="4">
                            <label for="">template data source item</label>
                            <textarea style="width: 400px; height: 150px;border: 1px solid #ccc" data-property="" data-key="data_source_template_item" id="data_source_template_item_texarea_<?php  echo $level.'_'.$i ?>" class="column_template_texarea update_data_column" >
                                <?php echo $column->template?base64_decode($column->data_source_template_item):'' ?>
                            </textarea>
                        </td>
                    </tr>

                </table>
            </div>

            <?php
            echo ob_get_clean();
            if (is_array($childNodes) && count($childNodes) > 0) {
                $level=$level+1;
                JFormFieldGridformartheader::create_html_list_template($childNodes,$list_data_type,$list_data_source,$list_editor,$level);
            }
            echo "</li>";
        }
        echo '</ol>';
    }

    function create_html_list_date_column($nodes,$list_data_type,$list_data_source,$list_editor,$level)
    {
        echo '<ol class="dd-list">';

        for ($i=0;$i<count($nodes);$i++) {
            $column= $nodes[$i];
            $childNodes = $column->children;
            ob_start();
            ?>
            <li class="dd-item"
            <?php foreach($column as $key=>$value){ ?>
                data-<?php echo $key ?>="<?php echo $value ?>"
            <?php } ?>

            data-level="<?php echo $level ?>"
            >
            <div class="dd-handle">
                <div class="dd-handle-move pull-left"><i class="fa-move"></i></div>
                <span class="key_name"><?php echo "$column->column_name" ?></span>
                <?php echo $column->id ?>
                <button  class="dd-handle-remove remove_item_nestable dd-nodrag pull-right"><i class="fa-remove"></i></button>
                <button  class="dd-handle-expand dd-nodrag pull-right expand_item_nestable"><i class="im-plus"></i></button>
            </div>
            <div class="more_options dd-nodrag">
                <div class="dd-nodrag">
                    <button class="add_node" >add node</button>
                    <button class="add_sub_node">add sub node</button>
                </div>

                <table class="table">
                    <tr>
                        <td>Column name</td>
                        <td><input class="column_name form-control update_data_column" data-property="column_name"  style="width: 200px" type="text"
                                   value="<?php echo $column->column_name ?>"/></td>
                        <td>Type</td>
                        <td>
                            <select style="width: 200px"  name="data_type1" data-property="type"
                                    class="data_type update_data_column">
                                <?php foreach ($list_data_type as $type) { ?>
                                    <option <?php echo $column->type == $type ? 'selected' : '' ?>
                                        value="<?php echo $type ?>"><?php echo $type ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Access</td>
                        <td><?php echo JHtml::_('access.level', 'access_level',$column->access,array("class"=>'column_access_level update_data_column'));?></td>
                        <td>Editable</td>
                        <td><input class="update_data_column noStyle not-bootstrap-toggle" data-property="editable" <?php echo $column->editable == 1 ? 'checked' : '' ?>  type="checkbox"  value="1"/></td>
                    </tr>
                    <tr>
                        <td>Primary key</td>
                        <td><input  <?php echo $column->primary_key == 1 ? 'checked' : '' ?>    class="primary-key update_data_column noStyle not-bootstrap-toggle" data-property="primary_key" data-level="<?php echo $level ?>" name="primary_key_<?php echo $level ?>"  type="radio"  value="<?php echo $column->primary_key == 1 ? 1 : 0 ?>"/></td>
                        <td></td>
                        <td></td>
                    </tr>
                </table>
            </div>
            <?php
            echo ob_get_clean();
            if (count($childNodes) > 0) {
                $level=$level+1;
                JFormFieldGridformartheader::create_html_list_date_column($childNodes,$list_data_type,$list_data_source,$list_editor,$level);
            }
            echo "</li>";
        }
        echo '</ol>';
    }
    protected function getInput()
    {
        $doc = JFactory::getDocument();
        $lessInput = JPATH_ROOT . '/libraries/cms/form/field/gridformartheader.less';
        $cssOutput = JPATH_ROOT . '/libraries/cms/form/field/gridformartheader.css';
        $db = JFactory::getDbo();
        JHtml::_('jquery.framework');
        JHtml::_('bootstrap.framework');
        JUtility::compileLess($lessInput, $cssOutput);
        $doc->addStyleSheet(JUri::root() . "/libraries/cms/form/field/gridformartheader.css");
        $doc->addStyleSheet(JUri::root() . "/media/jui_front_end/css/select2.css");
        $doc->addScript(JUri::root() . "/media/system/js/Nestable-master/jquery.nestable.js");
        $doc->addScript(JUri::root() . "/media/system/js/purl-master/purl-master/purl.js");
        $doc->addScript(JUri::root() . "/media/system/js/jQuery.uheprnGen-master/lib/uheprng.js");
        $doc->addScript(JUri::root() . "/media/system/js/jQuery.uheprnGen-master/lib/jquery.uheprnGen.js");
        $doc->addScript(JUri::root().'/media/system/js/base64.js');
        $doc->addStyleSheet(JUri::root()."/media/system/js/CodeMirror-master/lib/codemirror.css");

        $doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/lib/codemirror.js");
        $doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/selection/selection-pointer.js");
        $doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/mode/xml/xml.js");
        $doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/mode/css/css.js");


        $doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/mode/htmlmixed/htmlmixed.js");



        $doc->addStyleSheet(JUri::root()."/media/system/js/CodeMirror-master/addon/hint/show-hint.css");
        $doc->addStyleSheet(JUri::root()."/media/system/js/CodeMirror-master/addon/display/fullscreen.css");
        $doc->addStyleSheet(JUri::root()."/media/system/js/fseditor-master/fseditor.css");

        $doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/hint/show-hint.js");
        $doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/hint/css-hint.js");


        $doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/hint/html-hint.js");


        $doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/hint/xml-hint.js");
        $doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/hint/javascript-hint.js");
        $doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/hint/anyword-hint.js");

        $doc->addScript(JUri::root()."/media/system/js/CodeMirror-master/addon/display/fullscreen.js");




        $doc->addScript(JUri::root() . "/libraries/cms/form/field/gridformartheader.js");
        $doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
        $doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");
        //get data
        $data=$this->form->getData();
        //end get data

        //get bindingsource
        $bindingSource = $data->get('params.data.bindingSource',0);
        //end get bindingsource

        //get list field by datasource
        JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/models');
        $modalDataSource=JModelLegacy::getInstance('DataSource','phpMyAdminModel');

        $list_field=$modalDataSource->list_field_by_data_source($bindingSource,$data->get('id',0));
        //end get list field

        //format list field to girdfomartheader
        $list_key=array();
        foreach($list_field as $key=>$value) {

            if (JUtility::isJson($value)) {
                $item1=json_decode($value);
                foreach($item1 as $key1=>$value1) {
                    $list_key[]=array(
                        'id'=>$key.'.'.$key1,
                        'text'=>$key.'.'.$key1
                    );
                }
            }else{
                $list_key[]=array(
                    'id'=>$value,
                    'text'=>$value
                );
            }
        }
        //end format list field to girdfomartheader

        //get menu select
        $website=JFactory::getWebsite();
        $query=$db->getQuery(true);
        $query->from('#__menu As menu');
        $query->select('CONCAT("{",menu.alias,"}")');
        $query->leftJoin('#__menu_types AS menuType ON menuType.id=menu.menu_type_id');
        $query->where('menuType.website_id=' . (int)$website->website_id);
        $query->where('menuType.client_id=0');
        $query->where('menu.alias!="root"');
        $query->order('menu.title');

        $db->setQuery($query);
        $list_menu=$db->loadColumn();

        //end get menu
        $gridformartheader=$this->value;
        $gridformartheader=base64_decode($gridformartheader);

        $gridformartheader=json_decode($gridformartheader);
        $mode_select_column = $gridformartheader->mode_select_column;
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $object_mode_select_column = (array)up_json_decode($mode_select_column, false, 512, JSON_PARSE_JAVASCRIPT);




        if(count($object_mode_select_column)==0)
        {
            $column=new stdClass();
            $object_mode_select_column[]=$column;
        }

        $mode_select_column_template = $gridformartheader->mode_select_column_template;
        $object_mode_select_column_template = (array)up_json_decode($mode_select_column_template, false, 512, JSON_PARSE_JAVASCRIPT);


        if(count($object_mode_select_column_template)==0)
        {
            $column=new stdClass();
            $object_mode_select_column_template[]=$column;
        }



        $list_data_type = array(
            'string',
            'number',
            'json',
            'object',
            'browser_image',
            'array'
        );
        $list_command = array(
           array(
               'id'=>'edit',
               'text'=>'edit'
           ),
            array(
                'id'=>'destroy',
                'text'=>'destroy'
            ),
            array(
                'id'=>'move',
                'text'=>'move',
                'className'=>'move',
                'click'=>'alert("hello")'
            )

        );
        $list_icon=JUtility::get_class_icon_font();
/*        foreach($list_icon as $key=>$value)
        {
            $list_icon[$key]='<i class="'.$value.'"></i>'.$value;
        }*/
        $tables=$db->getTableList();
        $scriptId = "script_field_gridformartheader_" . $data->get('id',0);
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#field__gridformartheader_<?php echo $data->get('id',0) ?>').field_gridformartheader({
                    data_command :<?php echo json_encode($list_command) ?>,
                    data_column :<?php echo json_encode($list_key) ?>,
                    list_icon :<?php echo json_encode($list_icon) ?>,
                    list_menu:<?php echo json_encode($list_menu) ?>,
                    field_name:"<?php echo $this->name ?>",
                    field_id:"<?php echo $this->id ?>"
                });


            });
        </script>
        <?php
        $script = ob_get_clean();
        $script = JUtility::remove_string_javascript($script);
        $doc->addScriptDeclaration($script, "text/javascript", $scriptId);








        $list_editor = array(
            'none' => 'none',
            'DropDownList' => 'droplist',
            'Editor' => 'editor',
            'ComboBox' => 'ComboBox',
            'browser_image' => 'browser_image',
            'ColorPicker' => 'ColorPicker',
            'ColorPalette' => 'ColorPalette',
            'Calendar' => 'Calendar',
            'AutoComplete' => 'AutoComplete',
            'multiselect' => 'multiselect'
        );

        JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_phpmyadmin/models');
        $dataSourceModal = JModelLegacy::getInstance('DataSources', 'phpMyAdminModel');
        $currentDataSource = $dataSourceModal->getCurrentDataSources();

        $list_data_source = array();
        $a_item = new stdClass();
        $a_item->id = '';
        $a_item->text = "None";
        $list_data_source[] = $a_item;
        foreach ($currentDataSource as $item) {
            $a_item = new stdClass();
            $a_item->id = $item->datasource->id;
            $a_item->text = $item->datasource->title;
            $list_data_source[] = $a_item;
        }


        $db = JFactory::getDbo();
        $listFunction = JFormFieldDatasource::getListAllFunction();
        $tables = $db->getTableList();
        $html = '';
        ob_start();
        ?>
        <div id="field__gridformartheader_<?php echo $data->get('id',0) ?>">
            <div class="cf nestable-lists">
                <div class="row">
                    <div class="col-md-6">

                        <div class="row">
                            <div class="col-md-6">
                                <h5>config column template</h5>
                            </div>
                            <div class="col-md-6">
                                <label>show more option<input  type="checkbox" checked class="show_more_options"></label>
                            </div>
                        </div>



                        <div class="dd " id="gridformartheader2">
                            <?php if(count((array)$object_mode_select_column_template)){
                                $level=1;
                                JFormFieldGridformartheader::create_html_list_template($object_mode_select_column_template,$list_data_type,$list_data_source,$list_editor,$level);
                            }else{ ?>
                                <div class="dd-empty"></div>
                            <?php } ?>
                        </div>

                    </div>
                    <div class="col-md-6">

                        <div class="row">
                            <div class="col-md-6">
                                <h5>config column data</h5>
                            </div>
                            <div class="col-md-6">
                                <label>show more option<input  type="checkbox" checked class="show_more_options"></label>
                            </div>
                        </div>



                        <div class="dd " id="gridformartheader1">
                            <?php if(count((array)$object_mode_select_column)){
                                $level=1;
                                JFormFieldGridformartheader::create_html_list_date_column($object_mode_select_column,$list_data_type,$list_data_source,$list_editor,$level);
                            }else{ ?>
                                <div class="dd-empty"></div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

            </div>

            <input type="hidden" name="mode_select_column" value="<?php echo $mode_select_column ?>"  id="gridformartheader1-output"/>
            <input type="hidden" name="<?php echo $this->name ?>" value="<?php echo $this->value ?>"  id="<?php echo $this->id ?>"/>
            <input type="hidden" name="mode_select_column_template" value="<?php echo $mode_select_column_template ?>" id="gridformartheader2-output"/>
        </div>





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
