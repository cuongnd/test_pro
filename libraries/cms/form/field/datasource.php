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
class JFormFieldDatasource extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.6
     */
    public $type = 'datasource';

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
    protected function getInput()
    {
        $doc = JFactory::getDocument();
        JHtml::_('jquery.framework');
        JHtml::_('bootstrap.framework');

        //load for kendo

        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.core.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.data.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.virtuallist.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.list.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.dropdownlist.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.pager.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.userevents.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.draganddrop.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.sortable.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.menu.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.columnmenu.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.popup.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.binder.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.filtermenu.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.editable.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.validator.js');

        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.combobox.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.selectable.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.groupable.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.columnsorter.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.resizable.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.window.js');
        //$doc->addScript(JUri::root().'/media/kendotest/php/data/products.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.grid.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.multiselect.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.numerictextbox.js');
        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/js/kendo.editor.js');

        $doc->addScript(JUri::root().'/media/system/js/twitter-typeahead.js/dist/typeahead.jquery.js');

        $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/web/kendo.default.less');
        $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/web/kendo.common.less');
        $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/dataviz/kendo.dataviz.less');
        $doc->addLessStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/styles/dataviz/kendo.dataviz.default.less');

        $doc->addLessStyleSheet(JUri::root() . '/libraries/cms/form/field/datasource.less');


        $doc->addScript(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/src/build/less-js/dist/less-1.5.0.js');


        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-master/lib/codemirror.css");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/lib/codemirror.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/display/fullscreen.js");

        $doc->addStyleSheet(JUri::root() . "/media/system/js/fseditor-master/fseditor.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/show-hint.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-master/addon/display/fullscreen.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/hint/xquery-hint.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/hint/templates-hint.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/theme/eclipse.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/doc/docs.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/hover/text-hover.css");
//$doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/theme/xq-light.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/lint/lint.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/mode/php/phpcolors.css");

        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/hint/show-context-info.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/lint/status-lint.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/fold/folding-eclipse.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/hover/xquery-hover.css");

        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/mode/sql/sql.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/show-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/sql-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/jquery.codemirror.js");

        $doc->addScript(JUri::root() . "/media/system/js/fseditor-master/jquery.fseditor.js");

        $doc->addScript(JUri::root() . "/media/system/js/jquery.popupWindow.js");
        $app=JFactory::getApplication();
        $ajaxgetcontent=$app->input->get('ajaxgetcontent',0,'int');
        $data = $this->form->getData();


        $doc->addScript(JUri::root() . "/libraries/cms/form/field/datasource.js");
        $db = JFactory::getDbo();
        $tables = $db->getTableList();
        $list_table = array();
        for ($i = 0; $i < count($tables); $i++) {
            $table = $tables[$i];
            $list_table[] = str_replace($db->getPrefix(), '#__', $table);
        }
        $idTextArea = str_replace(array('[', ']'), '_', $this->fieldname);
        $scriptId = "lib_cms_form_fields_datasource_" . JUserHelper::genRandomPassword();
        $data = $this->form->getData();
        $source_id = $data->get('id', 0);
        $field_name = $this->name;
        $uri=JFactory::getURI();
        $host = $uri->toString(array('scheme', 'host', 'port'));
        $list_table1=array();
        foreach($list_table as $table)
        {
            $list_table1[]=str_replace('#__','',$table);
        }


        require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
        $table_diagram=new JTableUpdateTable($db,'diagram');
        $table_diagram->load(
            array(
                "type"=>'global',
                "website_id"=>0
            )
        );
        if(!$table_diagram->id)
        {
            $table_diagram->type='global';
            $table_diagram->website_id=0;
            $table_diagram->store();
        }
        $xml_input=$table_diagram->xml;

        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#datasource_<?php echo $this->fieldname ?>').field_datasource({
                    source_id:<?php echo $source_id ?>,
                    field_name: "<?php echo $field_name ?>",
                    list_table:<?php echo json_encode($list_table1) ?>,
                    ajaxgetcontent:<?php echo $ajaxgetcontent ?>
                });
            });
        </script>
        <?php
        $script = ob_get_clean();
        $script = JUtility::remove_string_javascript($script);
        $doc->addScriptDeclaration($script, "text/javascript", $scriptId);


        $html = '';
        ob_start();
        ?>
        <div id="datasource_<?php echo $this->fieldname ?>" class="datasource_build">
            <div class="row">
                <div class="col-md-12">
                    <a class="btn umldrawer" href="/index.php?enable_load_component=1&option=com_phpmyadmin&view=umldrawer&tmpl=field&hide_panel_component=1">UML</a>
                    <a class="btn main_ralationship" href="/index.php?enable_load_component=1&option=com_phpmyadmin&view=relation&tmpl=field&hide_panel_component=1">Main relationship</a>
                    <a class="btn project_ralationship" href="/index.php?enable_load_component=1&option=com_phpmyadmin&view=projectrelation&tmpl=field&hide_panel_component=1">project relationship</a>
                    <a class="btn datasourcerelation" href="/index.php?enable_load_component=1&option=com_phpmyadmin&view=datasourcerelation&tmpl=field&datasource_id=<?php echo $source_id ?>&hide_panel_component=1">curent relationship</a>
                    <a class="btn field_data_source" href="/index.php?enable_load_component=1&option=com_phpmyadmin&view=field_data_source&tmpl=field&datasource_id=<?php echo $source_id ?>&hide_panel_component=1">field data source</a>
                </div>
            </div>
            <div class="row">
                <div class="query">

                    <div class="filter">
                        <div class="tabs">
                            <ul id="myTab" class="nav nav-tabs tabdrop">
                                <li><a href="#selection" data-toggle="tab">Selection</a></li>

                                <li><a href="#joins" data-toggle="tab">Joins</a></li>

                                <li><a href="#where" data-toggle="tab">Where</a></li>

                                <li><a href="#group_by" data-toggle="tab">Group by</a></li>

                                <li><a href="#having" data-toggle="tab">Having</a></li>

                                <li><a href="#oder_by" data-toggle="tab">Oder by</a></li>
                            </ul>

                            <div id="set-query" class="tab-content">
                                <div class="tab-pane fade active in" id="selection">
                                    <table class="table table-bordered select-column">
                                        <thead>
                                        <tr>

                                            <th class="per40">Column</th>

                                            <th class="per40">Alias</th>

                                            <th class="per15">Table</th>
                                            <th class="per15">Aggregate</th>
                                            <th class="per15">Sort</th>
                                            <th class="per15">Filter</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr>
                                            <td class="per40  ">
                                                <div>
                                                    <div class="edit-row pull-left show-select-table-and-function"
                                                         contenteditable="true">test
                                                    </div>
                                                    <div class="select-popup-column pull-right">
                                                        <a href="javascript:void(0)" class="show-table-and-function"><i
                                                                class="fa-circle-arrow-down"></i></a>
                                                    </div>
                                                </div>
                                                <div class="table-and-function  table-and-function-hide">
                                                    <div class="pull-left list-table">
                                                        <select class="select-tables pull-left">
                                                            <option value="0"> all table</option>
                                                        </select>
                                                        <ul class="list-field pull-left">

                                                        </ul>
                                                    </div>
                                                    <div class="pull-left list-function">
                                                        <select class="select pull-left group-function"
                                                                name="group_function">
                                                            <option value="0">all function</option>
                                                            <?php foreach ($listFunction as $groupFunction => $functions) { ?>
                                                                <option
                                                                    value="<?php echo $groupFunction ?>"><?php echo $groupFunction ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <ul class="functions pull-left">
                                                            <?php foreach ($listFunction as $groupFunction => $functions) { ?>
                                                                <?php foreach ($functions as $function) { ?>
                                                                    <li data-group-function="<?php echo $groupFunction ?>"><?php echo $function ?></li>
                                                                <?php } ?>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="per40 edit-row" contenteditable="true">Alias</td>

                                            <td class="per15 edit-row" contenteditable="true">Table</td>
                                            <td class="per15 edit-row" contenteditable="true">Aggregate</td>
                                            <td class="per15 edit-row" contenteditable="true">Sort</td>
                                            <td class="per15 edit-row" contenteditable="true">Filter</td>
                                        </tr>


                                        </tbody>
                                    </table>

                                </div>

                                <div class="tab-pane fade" id="joins">
                                    joins
                                </div>

                                <div class="tab-pane fade" id="where">
                                    where
                                </div>

                                <div class="tab-pane fade" id="group_by">
                                    group_by
                                </div>

                                <div class="tab-pane fade" id="having">
                                    having
                                </div>

                                <div class="tab-pane fade" id="oder_by">
                                    oder_by
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="result datasource-result">
                        <div class="tabs">
                            <ul id="table-result" class="nav nav-tabs tabdrop">
                                <li><a href="#query" data-toggle="tab">Query</a></li>
                                <li><a href="#stander_query" data-toggle="tab">Stander Query</a></li>
                                <li><a href="#result" data-toggle="tab">Result</a></li>
                                <li><a href="#config" data-toggle="tab">Config</a></li>


                            </ul>
                            <div>
                                Shift+?:autocomplete1,Ctrl-Space:autocomplete,Ctrl-F:list_function
                            </div>
                            <div id="table-result-content" class="tab-content">
                                <div class="tab-pane fade  active in" id="query">
								<textarea id="<?php echo $this->name ?>" style="width: 100%; height: 300px"
                                          function-call-before-save="getValueFromTextMirror"
                                          name="<?php echo $this->name ?>">
									<?php echo trim($this->value) ?>
								</textarea>
                                </div>
                                <div class="tab-pane fade  active in" id="stander_query">
                                    <div class="stander_query"></div>
                                    </textarea>
                                </div>

                                <div class="tab-pane fade" id="result">
                                    <div id="grid_result"></div>
                                    <div id="grid_result_error"></div>
                                </div>
                                <div class="tab-pane fade" id="config">
                                    config
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <style>

            .item {
                height: 80px;
                width: 80px;
                border: 1px solid blue;
                float: left;
            }
        </style>


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

    protected function getEditor()
    {
        // Only create the editor if it is not already created.
        if (empty($this->editor)) {
            $editor = null;

            if ($this->editorType) {
                // Get the list of editor types.
                $types = $this->editorType;

                // Get the database object.
                $db = JFactory::getDbo();

                // Iterate over teh types looking for an existing editor.
                foreach ($types as $element) {
                    // Build the query.
                    $query = $db->getQuery(true)
                        ->select('element')
                        ->from('#__extensions')
                        ->where('element = ' . $db->quote($element))
                        ->where('folder = ' . $db->quote('editors'))
                        ->where('enabled = 1');

                    // Check of the editor exists.
                    $db->setQuery($query, 0, 1);
                    $editor = $db->loadResult();

                    // If an editor was found stop looking.
                    if ($editor) {
                        break;
                    }
                }
            }

            // Create the JEditor instance based on the given editor.
            if (is_null($editor)) {
                $conf = JFactory::getConfig();
                $editor = $conf->get('editor');
            }

            $this->editor = JEditor::getInstance($editor);
        }

        return $this->editor;
    }

    /**
     * Method to get the JEditor output for an onSave event.
     *
     * @return  string  The JEditor object output.
     *
     * @since   1.6
     */
    public function save()
    {
        return $this->getEditor()->save($this->id);
    }
}
