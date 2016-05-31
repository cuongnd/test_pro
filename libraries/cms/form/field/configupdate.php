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
class JFormFieldConfigUpdate extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.6
     */
    public $type = 'configupdate';

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
                JFormFieldConfigUpdate::create_html_list($childNodes,$list_data_type,$level);
            }
            echo "</li>";
        }
        echo '</ol>';
    }
    protected function getInput()
    {
        $doc = JFactory::getDocument();
        $lessInput = JPATH_ROOT . '/libraries/cms/form/field/configupdate.less';
        $cssOutput = JPATH_ROOT . '/libraries/cms/form/field/configupdate.css';
        $db = JFactory::getDbo();
        JHtml::_('jquery.framework');
        JUtility::compileLess($lessInput, $cssOutput);
        $doc->addStyleSheet(JUri::root() . "/libraries/cms/form/field/configupdate.css");
        $doc->addStyleSheet(JUri::root() . "/libraries/cms/form/field/configupdate.css");
        $doc->addStyleSheet(JUri::root() . "/media/jui_front_end/css/select2.css");
        $doc->addScript(JUri::root() . "/media/system/js/Nestable-master/jquery.nestable.js");
        $doc->addScript(JUri::root() . "/libraries/cms/form/field/configupdate.js");
        $doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
        $doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");
        $doc->addScript(JUri::root() . "/media/system/js/purl-master/purl-master/purl.js");

        $data_source_id = $this->form->getData()->get('params.data.bindingSource');


        $object_mode_select_column=$this->value;

        if ( base64_encode(base64_decode($object_mode_select_column, true)) == $object_mode_select_column){
            $object_mode_select_column=base64_decode($object_mode_select_column, true);
            $this->value=base64_decode($object_mode_select_column, true);

        }else{
            $object_mode_select_column='';
            $this->value='';
        }


        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $object_mode_select_column = up_json_decode($object_mode_select_column, false, 512, JSON_PARSE_JAVASCRIPT);



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
        $scriptId = "script_lib_cms_form_fields_configupdate" . '_' . JUserHelper::genRandomPassword();
        ob_start();
        ?>
        <script type="text/javascript" id="<?php echo $scriptId ?>">

            <?php
                ob_get_clean();
                ob_start();
            ?>
            jQuery(document).ready(function($){


                config_update.init_config_update();
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

                <div class="cf nestable-lists">
                    <div class="row">
                        <div class="col-md-2">
                            <input class="form-control" onchange="config_update.filter_table(this)">
                            <ul class="config-upate-table">
                                <?php foreach($tables as $table){ ?>
                                    <?php
                                    $table=str_replace($db->getPrefix(),'', $table);
                                    ?>
                                    <li class="table">
                                        <div class="input-group configupdate-item-table">
                                          <div class="input-group-btn"><a data-table="<?php echo $table ?>" class="btn btn-primary plus"><i class="im-plus"></i></a></div>
                                          <div class="input-group-btn">
                                              <a data-table="<?php echo $table ?>" class="btn btn-danger "><?php echo $table ?></a>
                                          </div>
                                        </div >
                                        <div style="display: none" class="list-field">
                                            <ol class="dd-list">
    
                                            </ol>
                                        </div>
                                    </li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="col-md-10">

                            <div class="dd " id="config_update1">
                                <?php if(count((array)$object_mode_select_column)){
                                    $level=1;
                                    JFormFieldConfigUpdate::create_html_list($object_mode_select_column,$list_data_type,$level);
                                 }else{ ?>
                                    <div class="dd-empty"></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                </div>

                <input type="hidden" name="<?php echo $this->name ?>" value="<?php echo $this->value ?>" name=""  id="config_update1-output"/>



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
