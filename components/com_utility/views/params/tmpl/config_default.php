<?php
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$lessInput = JPATH_ROOT . '/components/com_utility/views/params/tmpl/assets/less/params_ajax_loader.less';
$cssOutput = JPATH_ROOT . '/components/com_utility/views/params/tmpl/assets/css/params_ajax_loader.css';
$db = JFactory::getDbo();
JHtml::_('jquery.framework');
JUtility::compileLess($lessInput, $cssOutput);
$doc->addStyleSheet(JUri::root() . "/components/com_utility/views/params/tmpl/assets/css/params_ajax_loader.css");
$doc->addStyleSheet(JUri::root() . "/media/jui_front_end/css/select2.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/jquery.appendGrid-master/jquery.appendGrid-development.css");
$doc->addScript(JUri::root() . "/media/system/js/Nestable-master/jquery.nestable.js");
$doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");
$doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
$doc->addScript(JUri::root() . "/components/com_utility/views/params/tmpl/assets/js/params_ajax_loader.js");
$doc->addScript(JUri::root() . "/media/system/js/base64.js");
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/core.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/widget.js');
$doc->addScript(JUri::root() . '/media/jui_front_end/jquery-ui-1.11.1/ui/draggable.js');
$doc->addScript(JUri::root() . "/media/system/js/jquery.appendGrid-master/jquery.appendGrid-development.js");
$element_path = $app->input->get('element_path', '', 'string');
$element_config = $app->input->get('element_config', '', 'string');
if ($element_config == "global_element_config") {
    $element_path = "root_field";
}
require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
$website = JFactory::getWebsite();
require_once JPATH_ROOT . '/libraries/joomla/form/fields/icon.php';
$db = JFactory::getDbo();

require_once JPATH_ROOT . '/components/com_phpmyadmin/tables/updatetable.php';
$table_control = new JTableUpdateTable($db, 'control');
$table_control->load(array(
    "element_path" => $element_path,
    'type' => 'field'
));
if (!$element_path->id) {
    $table_control->element_path = $element_path;
    $table_control->type = 'field';
    $table_control->store();
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
$list_path = array(
    'libraries/joomla/form/fields',
    'libraries/legacy/form/field',
    'libraries/cms/form/field'
);


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
$list_field_table_position_config = $db->getTableColumns('#__control');
$list_field_table_position_config = array_keys($list_field_table_position_config);
//end get list field table position config

require_once JPATH_ROOT . '/libraries/joomla/form/fields/groupedlist.php';


$scriptId = "com_menus_view_params_jaxloader" . '_' . JUserHelper::genRandomPassword();

ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {

        params_view_config.field_name_option.tags =<?php echo json_encode($list_field_table_position_config) ?>;
        params_view_config.init_view_config();
    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
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
    data-id="<?php echo rand(1, 1000) ?>"
    >
    <div class="dd-handle">
        <div class="dd-handle-move pull-left"><i class="fa-move"></i></div>
        <span class="key_name"><?php echo "$item->label ( $item->name ) " ?></span>
        <button onclick="params_view_config.remove_item_nestable(this)" class="dd-handle-remove dd-nodrag pull-right"><i
                class="fa-remove"></i></button>

        <button onclick="params_view_config.expand_item_nestable(this)" class="dd-handle-expand dd-nodrag pull-right"><i
                class="im-plus"></i></button>
    </div>

    <div class="more_options dd-nodrag">
        <div>
            <button class="add_node">add node</button>
            <button class="add_sub_node">add sub node</button>
        </div>


        <label>Name<input class="form-control select_field_name" style="width: 200px"
                          onchange="params_view_config.update_data_column(this,'name')"
                          value="<?php echo $item->name ?>" type="text"/></label>
        <label>default<input class="form-control" onchange="params_view_config.update_data_column(this,'default')"
                             value="<?php echo $item->default ?>" type="text"/></label>
        <label>label<input class="form-control" onchange="params_view_config.update_data_column(this,'label')"
                           value="<?php echo $item->label ?>" type="text"/></label>
        <label>Icon<input class="icon_menu_item" style="width: 200px" type="text"
                          onchange="params_view_config.update_data_column(this,'icon')"
                          value="<?php echo $item->icon ?>"/></label>
        <label>Description<textarea class="description" style="width: 200px"
                                    onchange="params_view_config.update_data_column(this,'description')"
                                    value="<?php echo $item->icon ?>"></textarea></label>
        <label>onchange<input class="form-control select_field_onchange" style="width: 200px"
                          onchange="params_view_config.update_data_column(this,'onchange')"
                          value="<?php echo $item->onchange ?>" type="text"/></label>
        <label>
            Access
            <?php
            echo JHtml::_('access.level', 'access_level', $item->access, array("class" => 'menu_access_level'));
            ?>
        </label>

        <label>
            type
            <select disableChosen="true" style="width: 200px"
                    onchange="params_view_config.update_data_column(this,'type');params_view_config.update_atrribute_param_config(this)"
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
        <label>Read only<input <?php echo $item->readonly == 1 ? 'checked' : '' ?> type="checkbox"
                                                                                   onchange="params_view_config.update_data_column(this,'readonly','checkbox')"
                                                                                   value="1"/></label>

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
            <label>show more option<input onchange="params_view_config.show_more_options(this);" type="checkbox" checked
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
    <input type="hidden" control-id="<?php echo $table_control->id ?>" value="<?php echo $field_block_output ?>"
           id="field_block-output"/>


    <?php

    $contents = ob_get_clean();
    $tmpl = $app->input->get('tmpl', '', 'string');
    if ($tmpl == 'field') {
        echo $contents;
        return;
    }
    $response_array[] = array(
        'key' => '.panel.params.params-config .panel-body.params',
        'contents' => $contents
    );

    echo json_encode($response_array);
    ?>



