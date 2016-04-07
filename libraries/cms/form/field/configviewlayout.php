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
class JFormFieldConfigViewLayout extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.6
     */
    public $type = 'configviewlayout';

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
    function stree_node_xml($fields, $menu_item_id = 0, $key_path = '', $indent = '', $form,$param_config_value_setup, $maxLevel = 9999, $level = 0)
    {
        if ($level <= $maxLevel) {

            ?>
            <div class="panel-group" id="accordion<?php echo $indent ?>" role="tablist" aria-multiselectable="true">
                <?php
                $i = 0;
                foreach ($fields as $item) {
                    $indent1 = $indent != '' ? $menu_item_id . '_' . $indent . '_' . $i : $menu_item_id . '_' . $i;
                    $key_path1 = $key_path != '' ? ($key_path . '.' . $item->name) : $item->name;
                    if (is_array($item->children) && count($item->children) > 0) {
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading<?php echo $indent1 ?>">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion<?php echo $indent1 ?>"
                                       href="#collapse<?php echo $indent1 ?>" aria-expanded="true"
                                       aria-controls="collapse<?php echo $indent1 ?>">
                                        <?php echo $item->name; ?>
                                    </a>
                                </h4>
                            </div>
                            <div id="collapse<?php echo $indent1 ?>" class="panel-collapse collapse in" role="tabpanel"
                                 aria-labelledby="heading<?php echo $indent1 ?>">
                                <div class="panel-body">
                                    <?php JFormFieldConfigViewLayout::stree_node_xml($item->children, $menu_item_id, $key_path1, $indent1, $form,$param_config_value_setup, $maxLevel, $level++); ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    } else {
                        ?>
                        <?php
                        $group = explode('.', $key_path);

                        if (strtolower($group[0]) == 'option') {
                            $name = array_reverse($group);
                            array_pop($group);
                            $group = array_reverse($group);
                        }
                        $string_params = reset($group);
                        $group = implode('.', $group);

                        $name = strtolower($item->name);
                        $addfieldpath=JPATH_ROOT."/".$item->addfieldpath;
                        if(file_exists($addfieldpath))
                        {
                            $addfieldpath=dirname($item->addfieldpath);
                            $form->addFieldPath(JPATH_ROOT.'/'.$addfieldpath);
                        }

                        $item_field = $form->getField($item->name, $group);

                        if ($string_params == 'params') {

                            $setup_value_enable = $form->getData()->get($group.'.enable_' . $name );
                            $setup_value_enable = $setup_value_enable == 'on' ? 1 : 0;
                            $radio_yes_no = new JFormFieldRadioYesNo();
                            $string_radio_yes_no = <<<XML

<field
		name="enable_$name"
		type="radioyesno"
		class="btn-group btn-group-yesno"
		onchange="$item->onchange"
		default="1"
		label=""
		description="is publich">
	<option class="btn" value="1">JYES</option>
	<option class="btn" value="0">JNO</option>
</field>

XML;

                            $element_yes_no = simplexml_load_string($string_radio_yes_no);
                            $radio_yes_no->setup($element_yes_no, $setup_value_enable, 'jform.' . $group);
                            $radio_yes_no->show_title(false);

                            ?>
                            <?php if ($item_field) { ?>
                                <?php
                                $item_field->setValue($param_config_value_setup->get($key_path1));
                                ?>
                                <div class="form-horizontal property-item">

                                    <div class="row">
                                        <div class="col-md-3">
                                            <span data-clipboard-text="<?php echo $key_path1 ?>" class="copy_clipboard"><?php echo $key_path1 ?></span>
                                            <br/>
                                            <?php echo $radio_yes_no->renderField(); ?>
                                            <br/>

                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <?php

                                                echo $item_field->renderField(array(), true);
                                                ?>
                                            </div>

                                        </div>
                                    </div>


                                </div>
                            <?php }
                        } else {
                            if ($item_field) { ?>
                                <div class="form-horizontal property-item">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <span data-clipboard-text="<?php echo $key_path1 ?>" class="copy_clipboard"><?php echo $key_path1 ?></span>
                                            <div class="row">
                                                <?php

                                                echo $item_field->renderField(array(), true);
                                                ?>
                                            </div>

                                        </div>
                                    </div>


                                </div>
                            <?php }
                        }
                    }

                    $i++;
                }
                ?>
            </div>
            <?php

        }

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
        $doc->addScript(JUri::root() . "/media/system/js/Nestable-master/jquery.nestable.js");

        $doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
        $doc->addScript(JUri::root() . "/media/system/js/purl-master/purl-master/purl.js");
        $doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");
        $doc->addScript(JUri::root() . "/media/system/js/jQuery.serializeObject-master/jquery.serializeObject.js");
        $doc->addScript(JUri::root().'/media/system/js/clipboard.js-master/dist/clipboard.js');
        $doc->addScript(JUri::root() . "/libraries/cms/form/field/configviewlayout.js");
        $doc->addLessStyleSheet(JUri::root() . '/libraries/cms/form/field/configviewlayout.less');
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
        $link=$data->get('link','');
        $uri_link=JFactory::getURI($link);
        $option=$uri_link->getVar('option');
        $view=$uri_link->getVar('view',substr($option,4));
        $layout=$uri_link->getVar('layout','default');
        $website=JFactory::getWebsite();
        $website_name=JFactory::get_website_name();
        $element_path="components/website/website_$website_name/$option/views/$view/tmpl/$layout.xml";
		
        if(!JFile::exists(JPATH_ROOT.'/'.$element_path))
        {
            $element_path="components/$option/views/$view/tmpl/$layout.xml";
        }



        require_once JPATH_ROOT . '/components/com_phpmyadmin/tables/updatetable.php';
        $table_extensions = new JTableUpdateTable($db, 'control');
        $filter = array(
            'type' => 'component',
            'website_id' => $website->website_id
        );
        /*if ($id != 0) {
            $filter['id'] = $id;
        }*/
        if ($element_path == 'root_component') {
            $filter['element_path'] = 'root_component';
        } else {
            $filter['element_path'] =  $element_path;
        }
        $table_extensions->load($filter);

        if (!$table_extensions->id) {
            $table_extensions->id = 0;
            if ($element_path == 'root_component') {
                $table_extensions->element_path = 'root_component';
            } else {
                $table_extensions->element_path = $element_path;
            }
            $table_extensions->type = 'component';
            $table_extensions->website_id = $website->websie_id;
            $table_extensions->store();

        }

        $fields = $table_extensions->fields;
        $fields = base64_decode($fields);
		
        $field_block_output = $fields;
        require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
        $fields = (array)up_json_decode($fields, false, 512, JSON_PARSE_JAVASCRIPT);
        if (!count($fields)) {
            $fields = array(new stdClass());
        }

        ob_start();
        JUtility::render_to_xml($fields);
        $string_xml=ob_get_clean();
        $string_xml='<?xml version="1.0" encoding="utf-8"?> <metadata>'.$string_xml.'</metadata>';
        jimport('joomla.filesystem.file');


        JFile::write(JPATH_ROOT.'/'.$element_path,$string_xml);

		
        $config_value_setup=base64_decode($this->value);
        $param_config_value_setup = new JRegistry;
        $param_config_value_setup->loadString($config_value_setup);
        $scriptId = "script_field_configviewlayout_" . $data->get('id',0);
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#field_configviewlayout_<?php echo $data->get('id',0) ?>').field_configviewlayout({
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

        <div id="field_configviewlayout_<?php  echo $data->get('id',0) ?>" class="row">


            <div class="col-md-12">
                <div class="form-horizontal ">
                    <div class="form-group">
                        <div class="col-xs-5 control-label">
                            Filter
                        </div>
                        <div class="col-xs-7">
                            <div class="input-group">
                                <input class="form-control" value="" name="filter_label">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="properties view-layout">
                    <?php echo JFormFieldConfigViewLayout::stree_node_xml($fields, $data->get('id',0), '', '', $this->form,$param_config_value_setup); ?>
                </div>
                <input type="hidden" name="menu_item_id" value="<?php echo $data->get('id',0) ?>">


                <button class="btn btn-danger save-configviewlayout-property pull-right" >
                    <i class="fa-save"></i>Save&close                </button>
                &nbsp;&nbsp;
                <button class="btn btn-danger apply-configviewlayout-property pull-right"><i
                        class="fa-save"></i>Save
                </button>
                &nbsp;&nbsp;
                <button class="btn btn-danger cancel-configviewlayout-property pull-right" ><i
                        class="fa-save"></i>Cancel
                </button>



            </div>
        </div>







        <?php
        $html .= ob_get_clean();
        return $html;
    }


}
