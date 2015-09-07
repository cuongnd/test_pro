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
class JFormFieldInputmask extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.6
     */
    public $type = 'inputmask';

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
            <?php foreach($column as $key=>$value){ ?>
            data-<?php echo $key ?>="<?php echo $value ?>"
            <?php } ?>
            >
            <div class="dd-handle-move pull-left"><i class="fa-move"></i></div>
            <div class="dd-handle">
                <span  contenteditable style="min-width: 20px" class="key pull-left" placeholder="key" data-autocomplete-spy></span>
                <div   class="pull-left">:</div>
                <span  contenteditable style="min-width: 20px" class="value pull-left" placeholder="value" data-autocomplete-spy></span>
                <div class="add_node pull-left"><i class="fa-plus-sign2"></i></div>
                <div class="add_sub_node pull-left"><i class="en-add-to-list"></i></div>
                <div onclick="config_inputmask.remove_item_nestable(this)" class="dd-handle-remove pull-left"><i class="fa-remove"></i></div>

            </div>

            <?php
            echo ob_get_clean();
            if (count($childNodes) > 0) {
                $level=$level+1;
                JFormFieldInputmask::create_html_list($childNodes,$list_data_type,$level);
            }
            echo "</li>";
        }
        echo '</ol>';
    }
    protected function getInput()
    {
        $doc = JFactory::getDocument();
        $lessInput = JPATH_ROOT . '/libraries/cms/form/field/inputmask.less';
        $cssOutput = JPATH_ROOT . '/libraries/cms/form/field/inputmask.css';
        $db = JFactory::getDbo();
        JHtml::_('jquery.framework');
        JUtility::compileLess($lessInput, $cssOutput);
        $doc->addStyleSheet(JUri::root() . "/libraries/cms/form/field/inputmask.css");
        $doc->addStyleSheet(JUri::root() . "/libraries/cms/form/field/inputmask.css");
        $doc->addStyleSheet(JUri::root() . "/media/jui_front_end/css/select2.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/bootstrap-contenteditable-autocomplete-gh-pages/bower_components/bootstrap-expandable-input/bootstrap-expandable-input.css");
        $doc->addStyleSheet(JUri::root() . "/media/system/js/bootstrap-contenteditable-autocomplete-gh-pages/bootstrap-contenteditable-autocomplete.css");
        $doc->addScript(JUri::root() . "/media/system/js/bootstrap-contenteditable-autocomplete-gh-pages/bower_components/bootstrap-expandable-input/bootstrap-expandable-input.js");
        $doc->addScript(JUri::root() . "/media/system/js/bootstrap-contenteditable-autocomplete-gh-pages/bootstrap-contenteditable-autocomplete.js");
        $doc->addScript(JUri::root() . "/media/system/js/Nestable-master/jquery.nestable.js");
        $doc->addScript(JUri::root() . "/media/system/js/jquery.inputmask-3.x/js/inputmask.js");
        $doc->addScript(JUri::root() . "/libraries/cms/form/field/inputmask.js");
        $doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
        $doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");

        $data_source_id = $this->form->getData()->get('params')->data->bindingSource;
        $mode_select_column = $this->form->getData()->get('params')->config_inputmask_data;

        //$mode_select_column = "[{'id':1},{'id':2,'children':[{'id':3},{'id':4},{'id':5,'children':[{'id':6},{'id':7},{'id':8}]},{'id':9},{'id':10}]},{'id':11},{'id':12}]";

        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $object_mode_select_column = up_json_decode($mode_select_column, false, 512, JSON_PARSE_JAVASCRIPT);



        if(count($object_mode_select_column)==0)
        {
            $column=new stdClass();
            $object_mode_select_column[]=$column;
        }
        $list_data_type = array(
            'string',
            'number',
            'json',
            'array'
        );

        $tables=$db->getTableList();
        $scriptId = "lib_cms_form_fields_configupdate" . '_' . JUserHelper::genRandomPassword();
        ob_start();
        ?>
        <script type="text/javascript" id="<?php echo $scriptId ?>">

            <?php
                ob_get_clean();
                ob_start();
            ?>
            jQuery(document).ready(function($){


                config_inputmask.init_config_inputmask();
            });
            <?php
             $script=ob_get_clean();
             ob_start();
              ?>
        </script>
        <?php
        ob_get_clean();
        $doc->addScriptDeclaration($script, "text/javascript", $scriptId);


        $db = JFactory::getDbo();
        $listFunction = JFormFieldDatasource::getListAllFunction();
        $tables = $db->getTableList();
        $html = '';
        ob_start();
        ?>

        <div class="row">


            <div class="col-md-12">

                <div class="dd " id="config_inputmask1">
                    <?php if(count((array)$object_mode_select_column)){
                        $level=1;
                        JFormFieldInputmask::create_html_list($object_mode_select_column,$list_data_type,$level);
                    }else{ ?>
                        <div class="dd-empty"></div>
                    <?php } ?>
                </div>


                <input type="hidden" name="jform[params][config_inputmask_data]" value="<?php echo $mode_select_column ?>" name="" value="" id="config_inputmask1-output"/>



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

}
