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
class JFormFieldCoding extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.6
     */
    public $type = 'coding';

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
        $app = JFactory::getApplication();
        $lessInput = JPATH_ROOT . '/libraries/cms/form/field/coding.less';
        $cssOutput = JPATH_ROOT . '/libraries/cms/form/field/coding.css';
        $mode = $this->element['mode'];
        JUtility::compileLess($lessInput, $cssOutput);
        $field_param = $this->element['field_param'];

        if (!trim($field_param)) {
            $field_param_value = $this->value;
        } else {
            $field_param_value = $this->form->getValue('params')->$field_param;
        }
        if (trim(base64_encode(base64_decode($field_param_value, true))) === trim($field_param_value)) {
            $field_param_value = base64_decode($field_param_value);
        } else {
            $field_param_value = '';
        }
        //load for kendo

        $db = JFactory::getDbo();
        JHtml::_('jquery.framework');
        JHtml::_('bootstrap.framework');
        $doc->addStyleSheet(JUri::root() . '/libraries/cms/form/field/coding.css');
        $doc->addStyleSheet(JUri::root() . "/media/jui_front_end/css/select2.css");
        $doc->addScript(JUri::root() . "/media/system/js/Nestable-master/jquery.nestable.js");
        $doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");
        $doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");

        $doc->addScript(JUri::root() . "/media/system/js/base64.js");

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



        $doc->addStyleSheet(JUri::root() . "/media/system/js/CodeMirror-master/lib/codemirror.css");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/lib/codemirror.js");
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


        $doc->addStyleSheet(JUri::root() . "/media/system/js/fseditor-master/fseditor.css");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/mode/sql/sql.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/hint/show-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/sql-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/css-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/html-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/xml-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/javascript-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/hint/anyword-hint.js");

        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/edit/matchbrackets.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/edit/closebrackets.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/lint/lint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/runmode/runmode.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/format/formatting.js");


        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/fold/foldcode.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/fold/foldgutter.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/fold/brace-fold.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/fold/xml-fold.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/fold/comment-fold.js");


        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/hint/show-context-info.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/lint/remoting-lint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/lint/status-lint.js");


        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/mode/htmlmixed/htmlmixed.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/mode/xml/xml.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/mode/javascript/javascript.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/mode/css/css.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/mode/clike/clike.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/mode/php/php.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror/addon/selection/active-line.js");
//$doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/hover/text-hover.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/hover/token-hover.js");

        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/xquery-commons.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/hint/templates-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-extension/addon/execute/remoting-execute.js");


        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/hint/xquery-templates.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/hint/xquery-hint.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/hint/system-functions.xml.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/hint/xhive-functions.xml.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/mode/xquery/xquery.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-XQuery-master/codemirror-xquery/addon/hover/xquery-hover.js");
        $doc->addScript(JUri::root() . "/media/system/js/CodeMirror-master/addon/display/fullscreen.js");
        $doc->addScript(JUri::root() . "/media/system/js/jquery.hotkeys-master/shortcut.js");
        $doc->addScript(JUri::root() . '/libraries/cms/form/field/coding.js');
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $website = JFactory::getWebsite();
        require_once JPATH_ROOT . '/libraries/joomla/form/fields/icon.php';
        $db = JFactory::getDbo();
        $db = JFactory::getDbo();
        $tables = $db->getTableList();
        $list_table = array();
        for ($i = 0; $i < count($tables); $i++) {
            $table = $tables[$i];
            $list_table[] = str_replace($db->getPrefix(), '#__', $table);
            if ($i == 10) {
                break;
            }
        }


        $idTextArea = str_replace(array('[', ']'), '_', $this->name);
        $scriptId = "lib_cms_form_fields_coding" . $idTextArea . '_' . JUserHelper::genRandomPassword();
        ob_start();
        ?>
        <script type="text/javascript">
            coding_php_content_loader.init_php_content_ajax_loader();
            shortcut.add("Ctrl+Shift+F", function () {
                coding_php_content_loader.format_code();
            });
        </script>
        <?php

        $script = ob_get_clean();
        $script = JUtility::remove_string_javascript($script);
        $doc->addAjaxCallFunction($scriptId, $script, $scriptId);


        $listFunction = JFormFieldDatasource::getListAllFunction();

        $html = '';
        ob_start();
        ?>
        <div class="row">
            <div class="col-md-12">
                <nav class="navbar navbar-default" role="navigation">
                    <div class="navbar-header">
                        <a href="#" class="navbar-brand">Home</a>
                    </div>
                    <div>
                        <ul class="nav navbar-nav">
                            <li class="active"><a href="#">Tutorials</a></li>
                            <li><a href="#">Java/J2EE</a></li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Code<span
                                        class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="javascript:void(0)"
                                           onclick="coding_php_content_loader.format_code(this)">Re format code</a></li>
                                    <li><a href="#">JavaScript</a></li>
                                    <li><a href="#">jQuery</a></li>
                                    <li><a href="#">Ajax</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?php
                $data=$this->form->getData();
                $currenrt_url=$app->input->get('currenrt_url','','string');
                $currenrt_url=base64_decode($currenrt_url);
                $uri=JFactory::getURI($currenrt_url);
                $uri->setVar('file_name_change','get_data_by_data_source_'.$data->get('id',0));
                ?>
                <a href="<?php echo $uri->toString() ?>"><?php echo $uri->toString() ?></a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div>

                    <!-- Nav tabs -->
                    <ul id="tab_coding_php" class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#code_php" aria-controls="home" role="tab"
                                                                  data-toggle="tab">coding</a></li>
                        <li role="presentation"><a href="#result" aria-controls="profile" role="tab" data-toggle="tab">Result</a>
                        </li>
                        <li role="presentation"><a href="#messages" aria-controls="messages" role="tab"
                                                   data-toggle="tab">Messages</a></li>
                        <li role="presentation"><a href="#settings" aria-controls="settings" role="tab"
                                                   data-toggle="tab">Settings</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="code_php">
                            <textarea data-source-id="<?php echo $app->input->get('add_on_id',0) ?>" cols="100" data-mode="<?php echo $mode ?>" rows="300"
                                      name="<?php echo $this->name ?>"
                                      id="coding_php_content"><?php echo trim($field_param_value) ?></textarea>

                        </div>
                        <div role="tabpanel" class="tab-pane" id="result">
                            <div id="grid_result"></div>
                            <div id="grid_result_error"></div>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="messages">...</div>
                        <div role="tabpanel" class="tab-pane" id="settings">...</div>
                    </div>

                </div>


            </div>
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
