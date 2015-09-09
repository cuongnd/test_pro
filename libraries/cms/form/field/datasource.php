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
        $lessInput = JPATH_ROOT . '/libraries/cms/form/field/datasource.less';
        $cssOutput = JPATH_ROOT . '/libraries/cms/form/field/datasource.css';
        JUtility::compileLess($lessInput, $cssOutput);
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


        $list_file_less_css=array(
            "/media/Kendo_UI_Professional_Q2_2015/src/styles/web/kendo.common",
            "/media/Kendo_UI_Professional_Q2_2015/src/styles/web/kendo.default",
            "/media/Kendo_UI_Professional_Q2_2015/src/styles/dataviz/kendo.dataviz",
            "/media/Kendo_UI_Professional_Q2_2015/src/styles/dataviz/kendo.dataviz.default"
            // "/media/Kendo_UI_Professional_Q2_2015/src/styles/web/kendo.bootstrap"
        );
        foreach($list_file_less_css as $less_css_file)
        {
            $lessInput = JPATH_ROOT . $less_css_file.".less";
            $cssOutput = JPATH_ROOT . $less_css_file.".css";
            $error=JUtility::compileLess($lessInput, $cssOutput);
            if($error!=true)
            {
                echo   $lessInput;
                echo "<br/>";
                echo $error;
                echo "<br/>";
                echo   $cssOutput;
                die;
            }
            $doc->addStyleSheet(JUri::root() . $less_css_file.'.css');
        }
        $doc->addStyleSheet(JUri::root() . '/media/Kendo_UI_Professional_Q2_2015/styles/kendo.rtl.min.css');




        $doc->addStyleSheet(JUri::root() . "/libraries/cms/form/field/datasource.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-master/lib/codemirror.css");





        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/lib/codemirror.js");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/show-hint.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-master/addon/display/fullscreen.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/fseditor-master/fseditor.css");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/mode/sql/sql.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/show-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/sql-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/css-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/html-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/xml-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/javascript-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/anyword-hint.js");

        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/mode/php/php.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/display/fullscreen.js");
        $doc->addScript(JUri::root() . "/media/system/js/fseditor-master/jquery.fseditor.js");


        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/oz.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/config.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/globals.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/visual.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/row.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/table.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/relation.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/key.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/rubberband.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/map.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/toggle.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/io.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/tablemanager.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/rowmanager.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/keymanager.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/window.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/options.js");
        $doc->addScript(JUri::root() . "/libraries/wwwsqldesigner-master/js/wwwsqldesigner.js");

        $data=$this->form->getData();


        $doc->addScript(JUri::root() . "/libraries/cms/form/field/datasource.js");
        $db = JFactory::getDbo();
        $tables = $db->getTableList();
        $list_table = array();
        for ($i=0;$i<count($tables);$i++) {
            $table=$tables[$i];
            $list_table[] = str_replace($db->getPrefix(), '#__', $table);
            if($i==10)
            {
                break;
            }
        }


        $idTextArea = str_replace(array('[', ']'), '_', $this->name);
        $scriptId = "lib_cms_form_fields_datasource" . $idTextArea . '_' . JUserHelper::genRandomPassword();
        ob_start();
        ?>
        <script type="text/javascript">
            function getDataByQuery() {
                query = window.editor.getValue();
                ajaxGetStanderQuery = $.ajax({
                    type: "GET",
                    url: this_host + '/index.php',

                    data: (function () {

                        dataPost = {
                            option: 'com_phpmyadmin',
                            task: 'datasource.ajaxGetDataByQuery',
                            query: query,
                            type:'data_source',
                            source_id:<?php echo $data->get('id',0) ?>

                        };
                        return dataPost;
                    })(),
                    beforeSend: function () {
                        $('.div-loading').css({
                            display: "block"


                        });

                        // $('.loading').popup();
                    },
                    success: function (response) {
                        $('.div-loading').css({
                            display: "none"


                        });
                        response = $.parseJSON(response);

                        if (response.e == 1) {
                            $('#grid_result_error').html(response.m).show();
                            $('#grid_result').hide();
                        }
                        else {
                            var grid_result=$('#grid_result').data("kendoGrid");
                            var columns=[];
                            $.each(response.r[0], function( key, value ) {
                                var column={};
                                column.field=key;
                                column.width=150;
                                columns.push(column);
                            });

                            grid_result. setOptions({
                                columns: columns
                            });
                            grid_result.dataSource.data(response.r);
                        }
                    }
                });

            }


            function <?php echo $scriptId ?>() {
                console.log('hello 213');
                var jfield_data_source = {
                    kendo_grid_option: {
                        height: 300,
                        width:1000,
                        groupable: true,
                        scrollable: true,
                        pageable: {
                            refresh: true,
                            pageSizes: true,
                            buttonCount: 5
                        }
                    }
                };

                $('#grid_result').kendoGrid(jfield_data_source.kendo_grid_option);
                $('#table-result a:first').tab('show');


                $('#table-result a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                    query = window.editor.getValue();
                    var target = $(e.target).attr("href");
                    switch (target) {
                        case '#stander_query':
                            //code block here
                            ajaxGetStanderQuery = $.ajax({
                                type: "GET",
                                url: this_host + '/index.php',

                                data: (function () {

                                    dataPost = {
                                        option: 'com_phpmyadmin',
                                        task: 'datasource.ajaxGetStanderQuery',
                                        query: query

                                    };
                                    return dataPost;
                                })(),
                                beforeSend: function () {


                                    // $('.loading').popup();
                                },
                                success: function (response) {

                                    $('.stander_query').html(response);
                                }
                            });


                            break;
                        case '#result':
                            //code block
                            getDataByQuery();

                            break;
                        default:
                        //default code block
                    }
                });

                query = $('textarea[name="<?php echo $this->name ?>"]').val();
                console.log(query);

                query = query.replace("/^\s*|\s*$/g", '');
                $('#<?php echo $idTextArea ?>').val(query.trim());
                var mime = 'text/x-mysql';
                if (window.location.href.indexOf('mime=') > -1) {
                    mime = window.location.href.substr(window.location.href.indexOf('mime=') + 5);
                }
                ;

                window.editor = CodeMirror.fromTextArea(document.getElementById('<?php echo $idTextArea ?>'), {
                    mode: mime,
                    indentWithTabs: true,
                    smartIndent: true,
                    lineNumbers: true,
                    matchBrackets: true,
                    fullScreen: false,
                    autofocus: true,
                    extraKeys: {
                        "'?'": "autocomplete1",
                        "Ctrl-Space": "autocomplete",
                        "Ctrl-F": "list_function"
                    },
                    hintOptions: {tables: {
                        /*table__users: {name: null, score: null, birthDate: null},
                         countries: {name: null, population: null, size: null}*/
                    }},
                    ajax_loader:{
                        ajax:true,
                        component:'com_phpmyadmin',
                        task:'tables.ajax_get_list_table_and_field',
                        func_success:function(response,cm){
                            jQuery.each(response, function (index, table) {
                                cm.options.hintOptions.tables[index]= {};
                                jQuery.each(table, function (field, type) {
                                    cm.options.hintOptions.tables[index][field]=null;
                                });

                            });
                        }
                    },
                    list_table: <?php echo json_encode($list_table) ?>



                });
                list_function=[
                    'get_json_group_concat(id:id,title:title)',
                    'request(id,0)'
                ];

                CodeMirror.commands.autocomplete1 = function(cm) {
                    cm.showHint({hint: CodeMirror.hint.anyword});
                };
                CodeMirror.commands.list_function = function(cm) {
                    cm.showHint({hint: CodeMirror.hint.list_function});
                };

                CodeMirror.registerHelper("hint", "list_function", function(editor, options) {
                    var WORD = /[\w$]+/, RANGE = 500;
                    var word = options && options.word || WORD;
                    var range = options && options.range || RANGE;
                    var cur = editor.getCursor(), curLine = editor.getLine(cur.line);
                    var end = cur.ch, start = end;
                    while (start && word.test(curLine.charAt(start - 1))) --start;
                    var curWord = start != end && curLine.slice(start, end);

                    var list = list_function, seen = {};
                    var re = new RegExp(word.source, "g");
                    for (var dir = -1; dir <= 1; dir += 2) {
                        var line = cur.line, endLine = Math.min(Math.max(line + dir * range, editor.firstLine()), editor.lastLine()) + dir;
                        for (; line != endLine; line += dir) {
                            var text = editor.getLine(line), m;
                            while (m = re.exec(text)) {
                                if (line == cur.line && m[0] === curWord) continue;
                                if ((!curWord || m[0].lastIndexOf(curWord, 0) == 0) && !Object.prototype.hasOwnProperty.call(seen, m[0])) {
                                    seen[m[0]] = true;
                                    list.push(m[0]);
                                }
                            }
                        }
                    }
                    return {list: list, from: CodeMirror.Pos(cur.line, start), to: CodeMirror.Pos(cur.line, end)};
                });



                window.getValueFromTextMirror = function (self) {
                    self.val(window.editor.getValue());
                    console.log(window.editor.getValue());
                };
                $('.datasource-result').fseditor({
                    overlay: true,
                    disable_escape: true,
                    expandOnFocus: false,
                    transition: '', // 'fade', 'slide-in',
                    placeholder: '',
                    maxWidth: '', // maximum width of the editor on fullscreen mode
                    maxHeight: '', // maximum height of the editor on fullscreen mode,
                    onExpand: function () {
                    }, // on switch to fullscreen mode callback
                    onMinimize: function () {
                    } // on switch to inline mode callback
                });
                //utilityDataSource.init_utility_dataSource();
            };
            <?php echo $scriptId ?>();
        </script>
        <?php
        $script=ob_get_clean();
        $script=JUtility::remove_string_javascript($script);
        $doc->addScriptDeclaration($script, "text/javascript", $scriptId);
        $listFunction = JFormFieldDatasource::getListAllFunction();

        $html = '';
        ob_start();
        ?>
        <div class="row">
            <div id="drag-drop-demo" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 diagrams  demo drag-drop-demo">

                <div id="area"></div>

                <div id="controls">
                    <div id="bar">
                        <div id="toggle"></div>
                        <input type="button" id="saveload" />

                        <hr/>

                        <input type="button" id="addtable" />
                        <input type="button" id="edittable" />
                        <input type="button" id="tablekeys" />
                        <input type="button" id="removetable" />
                        <input type="button" id="aligntables" />
                        <input type="button" id="cleartables" />

                        <hr/>

                        <input type="button" id="addrow" />
                        <input type="button" id="editrow" />
                        <input type="button" id="uprow" class="small" /><input type="button" id="downrow" class="small"/>
                        <input type="button" id="foreigncreate" />
                        <input type="button" id="foreignconnect" />
                        <input type="button" id="foreigndisconnect" />
                        <input type="button" id="removerow" />

                        <hr/>

                        <input type="button" id="options" />
                        <a href="https://github.com/ondras/wwwsqldesigner/wiki" target="_blank"><input type="button" id="docs" value="" /></a>
                    </div>

                    <div id="rubberband"></div>

                    <div id="minimap"></div>

                    <div id="background"></div>

                    <div id="window">
                        <div id="windowtitle"><img id="throbber" src="images/throbber.gif" alt="" title=""/></div>
                        <div id="windowcontent"></div>
                        <input type="button" id="windowok" />
                        <input type="button" id="windowcancel" />
                    </div>
                </div> <!-- #controls -->

                <div id="opts">
                    <table>
                        <tbody>
                        <tr>
                            <td>
                                * <label id="language" for="optionlocale"></label>
                            </td>
                            <td>
                                <select id="optionlocale"><option></option></select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                * <label id="db" for="optiondb"></label>
                            </td>
                            <td>
                                <select id="optiondb"><option></option></select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="snap" for="optionsnap"></label>
                            </td>
                            <td>
                                <input type="text" size="4" id="optionsnap" />
                                <span class="small" id="optionsnapnotice"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="pattern" for="optionpattern"></label>
                            </td>
                            <td>
                                <input type="text" size="6" id="optionpattern" />
                                <span class="small" id="optionpatternnotice"></span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="hide" for="optionhide"></label>
                            </td>
                            <td>
                                <input type="checkbox" id="optionhide" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                * <label id="vector" for="optionvector"></label>
                            </td>
                            <td>
                                <input type="checkbox" id="optionvector" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                * <label id="showsize" for="optionshowsize"></label>
                            </td>
                            <td>
                                <input type="checkbox" id="optionshowsize" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                * <label id="showtype" for="optionshowtype"></label>
                            </td>
                            <td>
                                <input type="checkbox" id="optionshowtype" />
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <hr />

                    * <span class="small" id="optionsnotice"></span>
                </div>

                <div id="io">
                    <table>
                        <tbody>
                        <tr>
                            <td style="width:60%">
                                <fieldset>
                                    <legend id="client"></legend>
                                    <div id="singlerow">
                                        <input type="button" id="clientsave" />
                                        <input type="button" id="clientload" />
                                    </div>
                                    <div id="singlerow">
                                        <input type="button" id="clientlocalsave" />
                                        <input type="button" id="clientlocalload" />
                                        <input type="button" id="clientlocallist" />
                                    </div>
                                    <div id="singlerow">
                                        <input type="button" id="dropboxsave" /><!-- may get hidden by dropBoxInit() -->
                                        <input type="button" id="dropboxload" /><!-- may get hidden by dropBoxInit() -->
                                        <input type="button" id="dropboxlist" /><!-- may get hidden by dropBoxInit() -->
                                    </div>
                                    <hr/>
                                    <input type="button" id="clientsql" />
                                </fieldset>
                            </td>
                            <td style="width:40%">
                                <fieldset>
                                    <legend id="server"></legend>
                                    <label for="backend" id="backendlabel"></label> <select id="backend"><option></option></select>
                                    <hr/>
                                    <input type="button" id="serversave" />
                                    <input type="button" id="quicksave" />
                                    <input type="button" id="serverload" />
                                    <input type="button" id="serverlist" />
                                    <input type="button" id="serverimport" />
                                </fieldset>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <fieldset>
                                    <legend id="output"></legend>
                                    <textarea id="textarea" rows="1" cols="1"></textarea><!--modified by javascript later-->
                                </fieldset>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div id="keys">
                    <fieldset>
                        <legend id="keyslistlabel"></legend>
                        <select id="keyslist"><option></option></select>
                        <input type="button" id="keyadd" />
                        <input type="button" id="keyremove" />
                    </fieldset>
                    <fieldset>
                        <legend id="keyedit"></legend>
                        <table>
                            <tbody>
                            <tr>
                                <td>
                                    <label for="keytype" id="keytypelabel"></label>
                                    <select id="keytype"><option></option></select>
                                </td>
                                <td></td>
                                <td>
                                    <label for="keyname" id="keynamelabel"></label>
                                    <input type="text" id="keyname" size="10" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3"><hr/></td>
                            </tr>
                            <tr>
                                <td>
                                    <label for="keyfields" id="keyfieldslabel"></label><br/>
                                    <select id="keyfields" size="5" multiple="multiple"><option></option></select>
                                </td>
                                <td>
                                    <input type="button" id="keyleft" value="&lt;&lt;" /><br/>
                                    <input type="button" id="keyright" value="&gt;&gt;" /><br/>
                                </td>
                                <td>
                                    <label for="keyavail" id="keyavaillabel"></label><br/>
                                    <select id="keyavail" size="5" multiple="multiple"><option></option></select>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </fieldset>
                </div>

                <div id="table">
                    <table>
                        <tbody>
                        <tr>
                            <td>
                                <label id="tablenamelabel" for="tablename"></label>
                            </td>
                            <td>
                                <input id="tablename" type="text" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label id="tablecommentlabel" for="tablecomment"></label>
                            </td>
                            <td>
                                <textarea rows="5" cols="40" id="tablecomment"></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>



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

                            <li><a href="#mode_select_column" data-toggle="tab">mode select column</a></li>
                            <li><a href="#show_column" data-toggle="tab">show column</a></li>
                            <li><a href="#result" data-toggle="tab">Result</a></li>
                            <li><a href="#config" data-toggle="tab">Config</a></li>


                        </ul>

                        <div id="table-result-content" class="tab-content">
                            <div class="tab-pane fade  active in" id="query">
								<textarea style="width: 100%; height: 300px"
                                          function-call-before-save="getValueFromTextMirror"
                                          id="<?php echo $idTextArea ?>" name="<?php echo $this->name ?>">
									<?php echo trim($this->value) ?>
								</textarea>
                            </div>
                            <div class="tab-pane fade  active in" id="stander_query">
                                <div class="stander_query"></div>
                                </textarea>
                            </div>

                            <div class="tab-pane fade  active in" id="mode_select_column">

								<textarea style="width: 50%; height: 300px" class="pull-left"
                                          name="jform[params][mode_select_column]">
									<?php
                                    echo trim($this->form->getValue('params')->mode_select_column);
                                    ?>
								</textarea>

                                <div class="pull-left" style="width: 50%">
                                    {
                                    "column1":{
                                    "type":"type",
                                    "editable":false
                                    },
                                    "column2":{
                                    "type":"type"
                                    }
                                    }
                                </div>
                            </div>
                            <div class="tab-pane fade  active in" id="show_column">
								<textarea style="width: 50%; height: 300px" class="pull-left"
                                          name="jform[params][show_column]">
									<?php
                                    echo trim($this->form->getValue('params')->show_column);
                                    ?>
								</textarea>

                                <div style="width: 50%" class="pull-left">
                                    {
                                    "column1":{
                                    "title":"title",
                                    "width":"width(int)"
                                    },
                                    "column2":{
                                    "title":"title",
                                    "width":"width(int)"
                                    }
                                    }
                                </div>
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
