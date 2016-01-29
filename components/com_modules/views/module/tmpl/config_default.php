<?php
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$lessInput = JPATH_ROOT . '/components/com_modules/views/module/tmpl/assets/less/view_module_config.less';
$cssOutput = JPATH_ROOT . '/components/com_modules/views/module/tmpl/assets/css/view_module_config.css';
$db = JFactory::getDbo();
JHtml::_('jquery.framework');
JUtility::compileLess($lessInput, $cssOutput);
$doc->addStyleSheet(JUri::root() . "/components/com_modules/views/module/tmpl/assets/css/view_module_config.css");
$doc->addStyleSheet(JUri::root() . "/media/jui_front_end/css/select2.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/jquery.appendGrid-master/jquery.appendGrid-development.css");
$doc->addScript(JUri::root() . "/media/system/js/Nestable-master/jquery.nestable.js");
$doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");
$doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
$doc->addScript(JUri::root() . "/components/com_modules/views/module/tmpl/assets/js/view_module_config.js");
$doc->addScript(JUri::root() . "/media/system/js/base64.js");
$doc->addScript(JUri::root() . "/media/system/js/jquery.appendGrid-master/jquery.appendGrid-development.js");
$id = $app->input->get('id', 0, 'int');
$element_path = $app->input->get('element_path', '', 'string');
require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
$website = JFactory::getWebsite();
require_once JPATH_ROOT . '/libraries/joomla/form/fields/icon.php';
$db = JFactory::getDbo();

require_once JPATH_ROOT . '/components/com_phpmyadmin/tables/updatetable.php';
$table_extensions = new JTableUpdateTable($db, 'control');
$filter = array(
    'type' => 'module',
    'website_id'=>$website->website_id
);
if ($id != 0) {
    $filter['id'] = $id;
}
if ($element_path == 'root_module') {
    $filter['element_path'] = 'root_module';
} else {
    $filter['element_path'] = 'modules/website/website_'.$website->website_id.'/' . $element_path;
}
$table_extensions->load($filter);
if (!$table_extensions->id) {
    $table_extensions->id = 0;
    $table_extensions->id = $id;
    if ($path == 'root_module') {
        $table_extensions->element_path = 'root_module';
    } else {
        $table_extensions->element_path = 'modules/website/website_'.$website->website_id.'/' . $element_path;
    }
    $table_extensions->type = 'module';
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
jimport('joomla.filesystem.folder');



$list_field_type = array();
$list_path = array(
    'libraries/joomla/form/fields',
    'libraries/legacy/form/field',
    'libraries/cms/form/field',
    'administrator/components/com_virtuemart/elements'
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
$list_field_table_position_config = $db->getTableColumns('#__modules');
$list_field_table_position_config = array_keys($list_field_table_position_config);
//end get list field table position config

require_once JPATH_ROOT . '/libraries/joomla/form/fields/groupedlist.php';


$scriptId = "com_menus_view_menus_jaxloader" . '_' . JUserHelper::genRandomPassword();
ob_start();
?>
<script type="text/javascript" id="<?php echo $scriptId ?>">

    <?php
        ob_get_clean();
        ob_start();
    ?>
    jQuery(document).ready(function ($) {

        view_module_config.field_name_option.tags =<?php echo json_encode($list_field_table_position_config) ?>;
        view_module_config.init_view_module_config();
    });
    <?php
     $script=ob_get_clean();
     ob_start();
      ?>
</script>
<?php
ob_get_clean();
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
        $reflector = new ReflectionClass(get_class($class_item_type));
        $field_path=dirname($reflector->getFileName());
        $field_path=str_replace(JPATH_ROOT.'/','',$field_path);
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

    >
    <div class="dd-handle">
        <div class="dd-handle-move pull-left"><i class="fa-move"></i></div>
        <span class="key_name"><?php echo "$item->label ( $item->name ) " ?></span>
        <button onclick="view_module_config.remove_item_nestable(this)" class="dd-handle-remove dd-nodrag pull-right"><i
                class="fa-remove"></i></button>
        <button onclick="view_module_config.expand_item_nestable(this)" class="dd-handle-expand dd-nodrag pull-right"><i
                class="im-plus"></i></button>
    </div>

    <div class="more_options ">
        <div>
            <button class="add_node">add node</button>
            <button class="add_sub_node">add sub node</button>
        </div>


        <label>Name<input class="form-control select_field_name" style="width: 200px"
                          onchange="view_module_config.update_data_column(this,'name')"
                          value="<?php echo $item->name ?>" type="text"/></label>
        <label>default<input class="form-control" onchange="view_module_config.update_data_column(this,'default')"
                             value="<?php echo $item->default ?>" type="text"/></label>
        <label>label<input class="form-control" onchange="view_module_config.update_data_column(this,'label')"
                           value="<?php echo $item->label ?>" type="text"/></label>
        <label>On change<input class="form-control" onchange="view_config.update_data_column(this,'onchange')"
                               value="<?php echo $item->onchange ?>" type="text"/></label>

        <label>Icon<input class="icon_menu_item" style="width: 200px" type="text"
                          onchange="view_module_config.update_data_column(this,'icon')"
                          value="<?php echo $item->icon ?>"/></label>
        <label>Description<textarea class="description" style="width: 200px"
                                    onchange="view_module_config.update_data_column(this,'description')"
                                    value="<?php echo $item->icon ?>"></textarea></label>
        <label>
            Access
            <?php
            echo JHtml::_('access.level', 'access_level', $item->access, array("class" => 'menu_access_level'));
            ?>
        </label>

        <label>
            type
            <select disableChosen="true" style="width: 200px"
                    onchange="view_module_config.update_data_column(this,'type');view_module_config.update_data_column(this,'addfieldpath');view_module_config.update_atrribute_param_config(this)"
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
        <label>Read only<input <?php echo $item->readonly == 1 ? 'checked' : '' ?>  type="checkbox"
                                                                                    onchange="view_module_config.update_data_column(this,'readonly','checkbox')"
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
            <label>show more option<input onchange="view_module_config.show_more_options(this);" type="checkbox" checked
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
    <input type="hidden" data-element_path="<?php echo $table_extensions->element_path ?>"
           value="<?php echo $field_block_output ?>" id="field_block-output"/>



    <?php

    $contents = ob_get_clean();
    $tmpl = $app->input->get('tmpl', '', 'string');
    if ($tmpl == 'field') {
        echo $contents;
        return;
    }
    $response_array[] = array(
        'key' => '.panel.extension-module-config .panel-body',
        'contents' => $contents
    );

    echo json_encode($response_array);
    ?>



