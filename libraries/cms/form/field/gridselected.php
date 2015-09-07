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
class JFormFieldGridSelected extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.6
     */
    public $type = 'gridselected';

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
                <?php echo $column->id ?>
                <button onclick="gridselected.remove_item_nestable(this)" class="dd-handle-remove pull-right"><i class="fa-remove"></i></button>
            </div>
            <div>
                <button class="add_node" onclick="gridselected.add_node(this,'template')">add node</button>
                <button style="display: none" class="add_sub_node">add sub node</button>
            </div>
            <label>Column title<input class="form-control" style="width: 200px" type="text"  onchange="gridselected.update_data_column(this,'column_title')"
                                    value="<?php echo $column->column_title ?>"/></label>
            <label>Column name <input class="column_name form-control"  style="width: 200px" type="text"  onchange="gridselected.update_data_column(this,'column_name')"
                                     value="<?php echo $column->column_name ?>"/></label>

            <label>show command <input data-tags="true" class="show_command form-control"  style="width: 200px" type="text"  onchange="gridselected.update_data_column(this,'show_command')"
                                     value="<?php echo $column->show_command ?>"/></label>
            <label>Type
                <select style="width: 200px" name="data_type1" onchange="gridselected.update_data_column(this,'type')"
                        class="data_type">
                    <?php foreach ($list_data_type as $type) { ?>
                        <option <?php echo $column->type == $type ? 'selected' : '' ?>
                            value="<?php echo $type ?>"><?php echo $type ?></option>
                    <?php } ?>
                </select>
            </label>


            <label>Column template
                <textarea style="width: 400px; height: 150px;border: 1px solid #ccc" data-key="template" id="column_template_texarea_<?php  echo $level.'_'.$i ?>" class="column_template_texarea " >
                    <?php echo $column->template?base64_decode($column->template):'' ?>
                </textarea>
            </label>
            <br/>
            <label>Max character<input class="form-control" style="width: 100px" type="text"  onchange="gridselected.update_data_column(this,'max_character')"
                                      value="<?php echo $column->max_character ?>"/></label>


            <label>Column width<input type="text" class="form-control" onchange="gridselected.update_data_column(this,'column_width')"
                                      value="<?php echo $column->column_width ?>"/></label>
            <label>Link key<input type="text" class="link_key form-control" onchange="gridselected.update_data_column(this,'link_key')"
                                  value="<?php echo $column->link_key ?>"/></label>
            <label>editor
                <select onchange="gridselected.update_data_column(this,'editor_type');">
                    <?php foreach ($list_editor as $key => $editor) { ?>
                        <option <?php echo $column->editor_type == $key ? 'selected' : '' ?>
                            value="<?php echo $key ?>"><?php echo $editor ?></option>
                    <?php } ?>
                </select>
            </label>

            <label>datasource
                <select style="width: 200px" onchange="gridselected.update_data_column(this,'data_source_id');gridselected.change_data_source(this)">
                    <?php foreach ($list_data_source as $key => $source) { ?>
                        <option <?php echo $column->data_source_id == $source->id ? 'selected' : '' ?>
                            value="<?php echo $source->id ?>"><?php echo $source->text ?></option>
                    <?php } ?>
                </select>
            </label>
            <label>key datasource<input type="text" class="form-control" onchange="gridselected.update_data_column(this,'key_data_source')"
                                        value="<?php echo $column->key_data_source ?>"/></label>
            <label>text datasource<input type="text" class="form-control" onchange="gridselected.update_data_column(this,'text_data_source')"
                                             value="<?php echo $column->text_data_source ?>"/></label>
            <label> template data source item
                <textarea style="width: 400px; height: 150px;border: 1px solid #ccc" data-key="data_source_template_item" id="data_source_template_item_texarea_<?php  echo $level.'_'.$i ?>" class="column_template_texarea " >
                    <?php echo $column->template?base64_decode($column->data_source_template_item):'' ?>
                </textarea>
            </label>
            <label> template data source item select
                <textarea style="width: 400px; height: 150px;border: 1px solid #ccc" data-key="data_source_template_item_select" id="data_source_template_item_select_texarea_<?php  echo $level.'_'.$i ?>" class="column_template_texarea " >
                    <?php echo $column->template?base64_decode($column->data_source_template_item_select):'' ?>
                </textarea>
            </label>


            <label>Show<input <?php echo $column->show == 1 ? 'checked' : '' ?>  type="checkbox"
                                                                                 onchange="gridselected.update_data_column(this,'show','checkbox')"
                                                                                 value="1"/></label>
            <label>filterable<input <?php echo $column->filterable == 1 ? 'checked' : '' ?>  type="checkbox"
                                                                                 onchange="gridselected.update_data_column(this,'filterable','checkbox')"
                                                                                 value="1"/></label>
            <label>sortable<input <?php echo $column->sortable == 1 ? 'checked' : '' ?>  type="checkbox"
                                                                                 onchange="gridselected.update_data_column(this,'sortable','checkbox')"
                                                                                 value="1"/></label>
            <label>menu<input <?php echo $column->menu == 1 ? 'checked' : '' ?>  type="checkbox"
                                                                                 onchange="gridselected.update_data_column(this,'menu','checkbox')"
                                                                                 value="1"/></label>
            <label>button checked<input <?php echo $column->button_checked == 1 ? 'checked' : '' ?>  type="checkbox"
                                                                                 onchange="gridselected.update_data_column(this,'button_checked','checkbox')"
                                                                                 value="1"/></label>


            <?php
            echo ob_get_clean();
            if (count($childNodes) > 0) {
                $level=$level+1;
                JFormFieldGridSelected::create_html_list_template($childNodes,$list_data_type,$list_data_source,$list_editor,$level);
            }
            echo "</li>";
        }
        echo '</ol>';
    }

    protected function getInput()
    {
        $doc = JFactory::getDocument();
        $lessInput = JPATH_ROOT . '/libraries/cms/form/field/gridselected.less';
        $cssOutput = JPATH_ROOT . '/libraries/cms/form/field/gridselected.css';
        $db = JFactory::getDbo();
        JHtml::_('jquery.framework');
        JHtml::_('bootstrap.framework');
        JUtility::compileLess($lessInput, $cssOutput);
        $doc->addStyleSheet(JUri::root() . "/libraries/cms/form/field/gridselected.css");
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




        $doc->addScript(JUri::root() . "/libraries/cms/form/field/gridselected.js");
        $doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
        $doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");

        $data_source_id = $this->form->getData()->get('params')->data->bindingSource;

        JTable::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/tables');
        $tableDataSource=JTable::getInstance('DataSource','JTable');
        $tableDataSource->load($data_source_id);

        $datasource= $tableDataSource->datasource;
        require_once JPATH_ROOT.'/components/com_phpmyadmin/helpers/datasource.php';
        $datasource=DataSourceHelper::OverWriteDataSource($datasource);
        $query=$db->getQuery(true);


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




        $query->setQuery(trim($datasource));
        $db->setQuery($query);
        //echo $query->dump();
        if(trim($query)=='')
        {
            return array();
        }
        $item=$db->loadObject();
        $list_key=array();
        foreach($item as $key=>$value) {
            $list_key[]=array(
                'id'=>$key,
                'text'=>$key
            );
        }



        $mode_select_column = $this->form->getData()->get('params')->mode_select_column;
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $object_mode_select_column = up_json_decode($mode_select_column, false, 512, JSON_PARSE_JAVASCRIPT);




        if(count($object_mode_select_column)==0)
        {
            $column=new stdClass();
            $object_mode_select_column[]=$column;
        }
        $params = new JRegistry;
        $params->loadString($this->form->getData()->get('params'));


        $gridselected =$this->value;
        $object_mode_select_column_template = up_json_decode($gridselected, false, 512, JSON_PARSE_JAVASCRIPT);

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

        $scriptId = "lib_cms_form_fields_grid_selected" . '_' . JUserHelper::genRandomPassword();
        ?>
        <script type="text/javascript">
            function <?php echo $scriptId ?>() {
                gridselected.data_command =<?php echo json_encode($list_command) ?>;
                gridselected.data_column =<?php echo json_encode($list_key) ?>;
                gridselected.list_icon =<?php echo json_encode($list_icon) ?>;
                gridselected.list_menu =<?php echo json_encode($list_menu) ?>;

                gridselected.init_gridselected();
            }
        </script>
        <?php
        $script_content=ob_get_clean();
        $script_content=JUtility::remove_string_javascript($script_content);
        $doc->addAjaxCallFunction($scriptId,$script_content,$scriptId);



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

        <div class="cf nestable-lists">
            <div class="row">
                <div class="col-md-12">
                    <h3>config column template</h3>
                    <div class="dd " id="gridselected">
                        <?php if(count((array)$object_mode_select_column_template)){
                            $level=1;
                            JFormFieldGridSelected::create_html_list_template($object_mode_select_column_template,$list_data_type,$list_data_source,$list_editor,$level);
                        }else{ ?>
                            <div class="dd-empty"></div>
                        <?php } ?>
                    </div>

                </div>
            </div>

        </div>

        <input type="hidden" name="<?php echo $this->name ?>" value="<?php echo $mode_select_column ?>" name="" value="" id="gridselected-output"/>







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
