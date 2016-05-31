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
class JFormFieldCreateitem extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.6
     */
    public $type = 'createitem';

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
    function create_html_list($nodes,$field_config,$level=0,$flag_setup_edit=false)
    {

        echo '<ol class="dd-list">';

        foreach ($nodes as $node) {
            $childNodes = $node->children;
            ob_start();
            ?>
            <li class="dd-item"
            <?php foreach($node as $key=>$value){
                if($key!='children') {
                    ?>
                    data-<?php echo $key ?>="<?php echo $value ?>"
                    <?php
                }
                } ?>
            data-level="<?php echo $level ?>"
            >
            <div class="dd-handle">
                <div class="dd-handle-move pull-left"><i class="fa-move"></i></div>
                <span class="key_name"><?php echo "$node->label ( $node->name ) " ?></span>
                <?php echo $node->id ?>
                <button  class="dd-handle-remove dd-nodrag pull-right remove_item_nestable"><i class="fa-remove"></i></button>
                <button  class="dd-handle-expand dd-nodrag pull-right expand_item_nestable"><i class="im-plus"></i></button>
            </div>
            <div class="more_options dd-nodrag">
                <div class="row">
                    <div class="col-md-4">
                        <button class="add_node">add node</button>
                        <button class="add_sub_node">add sub node</button>
                        <button class="btn btn-primary  dd-nodrag pull-right edit_item_nestable"><i class="fa-edit"></i>Edit</button>
                        <button class="btn btn-primary  dd-nodrag pull-right save_item_nestable hide"><i class="fa-save"></i>Save</button>
                        <button class="btn btn-primary  dd-nodrag pull-right save_and_close_item_nestable hide"><i class="fa-save"></i>Save&close</button>
                        <button class="btn btn-primary  dd-nodrag pull-right cancel_item_nestable hide"><i class="fa-save"></i>cancel</button>
                    </div>
                    <div class="col-md-6">

                    </div>
                </div>
                 <div class="view_wapper_more_options">
                     <?php if (count($field_config)): ?>
                         <table class="table list_field">
                             <?php foreach ($field_config as $field) { ?>
                                 <tr>
                                     <?php
                                     $node_value=$node->{$field->name};
                                     ?>
                                     <td><?php echo $field->label ?></td>
                                     <td>
                                         <?php echo $node_value ?>
                                         <input type="hidden" name="<?php echo $field->name ?>" value="<?php echo $node_value ?>"/>
                                     </td>
                                 </tr>
                             <?php } ?>
                         </table>
                     <?php endif; ?>
                 </div>
            <?php if(!$flag_setup_edit){ ?>
                <div class="wapper_more_options">

                </div>
                <?php
                $flag_setup_edit=true;
            } ?>
            </div>


            <?php
            echo ob_get_clean();
            if (is_array($childNodes)&&count($childNodes) > 0) {
                $level1=$level+1;
                JFormFieldCreateitem::create_html_list($childNodes,$field_config,$level1,$flag_setup_edit);
            }
            echo "</li>";
        }
        echo '</ol>';
    }
    public function get_attribute_config()
    {
        return array(
            maxDepth=>1
        );
    }


    protected function getInput()
    {
        $doc = JFactory::getDocument();
        $db = JFactory::getDbo();
        JHtml::_('jquery.framework');
        $doc->addStyleSheet(JUri::root() . "/media/jui_front_end/css/select2.css");

        $doc->addScript(JUri::root() . "/libraries/cms/form/field/createitem.js");
        $doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
        $doc->addScript(JUri::root() . "/media/system/js/purl-master/purl-master/purl.js");
        $doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");
        $doc->addScript(JUri::root() . "/media/system/js/jQuery.serializeObject-master/jquery.serializeObject.js");
        $doc->addLessStyleSheet(JUri::root() . '/libraries/cms/form/field/createitem.less');
        $data_source_id = $this->form->getData()->get('params')->data->bindingSource;
        $mode_select_column = $this->form->getData()->get('params')->config_update_data;
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $object_mode_select_column = up_json_decode($mode_select_column, false, 512, JSON_PARSE_JAVASCRIPT);
        require_once JPATH_ROOT.'/components/com_utility/helper/utility.php';
        $maxDepth=(int)$this->element['maxDepth'];
        if($maxDepth==0)
        {
            $maxDepth=1;
        }


        /*
         * base64
         * items{
         *      list:{},
         *      field_config:''
         * }
         */
        $data=$this->form->getData();
        $this->value=base64_decode($this->value);
        $this->value=json_decode($this->value);

        $nodes=$this->value->list;
        $nodes = (array)up_json_decode($nodes, false, 512, JSON_PARSE_JAVASCRIPT);
        if (!count($nodes)) {
            $nodes = array(new stdClass());
        }
        $field_config=$this->value->field_config;
        $parse_field_config=(array)up_json_decode($field_config, false, 512, JSON_PARSE_JAVASCRIPT);
        $scriptId = "script_field_createitem_" . $this->id;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#field_createitem_<?php echo $data->get('id',0) ?>').field_createitem({
                    field_name:"<?php echo $this->name ?>",
                    maxDepth:"<?php echo $maxDepth ?>",
                });


            });
        </script>
    <?php
    $script = ob_get_clean();
    $script = JUtility::remove_string_javascript($script);
    $doc->addScriptDeclaration($script, "text/javascript", $scriptId);



    $db = JFactory::getDbo();
        $listFunction = JFormFieldDatasource::getListAllFunction();
        $tables = $db->getTableList();
        $html = '';
        ob_start();
        ?>

        <div id="field_createitem_<?php  echo $data->get('id',0) ?>" class="row">


            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <label>show more option<input  type="checkbox" checked
                                                       class="show_more_options"></label>

                    </div>
                    <div class="col-md-6">
                        <button type="button"   class="btn btn-danger config-field-createitem pull-right"><i class="im-paragraph-justify"></i>Config field</button>
                    </div>
                </div>

                <div class="cf nestable-lists">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="dd " id="config_update1">
                                <?php if(count((array)$nodes)){
                                    $level=1;
                                    JFormFieldCreateitem::create_html_list($nodes,$parse_field_config,$level);
                                 }else{ ?>
                                    <div class="dd-empty"></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                </div>

                <input type="hidden" name="<?php echo $this->name ?>" value="<?php echo  base64_encode(json_encode($this->value)) ?>"  />
                <input type="hidden"  value="<?php echo $this->value->field_config ?>" id="field_config"/>
                <input type="hidden"  value="<?php echo $this->value->list?>" id="config_update1-output"/>



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
