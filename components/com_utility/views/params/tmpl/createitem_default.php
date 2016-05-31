<?php
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$db = JFactory::getDbo();
JHtml::_('jquery.framework');
$doc->addLessStyleSheet(JUri::root() . "/components/com_utility/views/params/tmpl/assets/less/createitem.less");
$doc->addStyleSheet(JUri::root() . "/media/jui_front_end/css/select2.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/jquery.appendGrid-master/jquery.appendGrid-development.css");
$doc->addScript(JUri::root() . "/media/system/js/Nestable-master/jquery.nestable.js");
$doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");
$doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
$doc->addScript(JUri::root() . "/components/com_utility/views/params/tmpl/assets/js/createitem.js");
$doc->addScript(JUri::root() . "/media/system/js/base64.js");

$doc->addScript(JUri::root() . "/media/system/js/jquery.appendGrid-master/jquery.appendGrid-development.js");
$element_path = $app->input->get('element_path', '', 'string');
$maxDepth = $app->input->get('maxDepth', 1, 'int');
$element_ouput = $app->input->get('element_ouput', '', 'string');

require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
$website = JFactory::getWebsite();
require_once JPATH_ROOT . '/libraries/joomla/form/fields/icon.php';
$db = JFactory::getDbo();

$nodes = $app->input->get('field_config', '', 'string');
$nodes = base64_decode($nodes);
$field_block_output = $fields;
require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
$nodes = (array)up_json_decode($nodes, false, 512, JSON_PARSE_JAVASCRIPT);
if (!count($nodes)) {
    $nodes = array(new stdClass());
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


$scriptId = "script_view_layout_createitem";
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
       $('#form_view_layout_createitem').view_tmpl_createitem({
            maxDepth: "<?php echo $maxDepth ?>",
            field_config: "<?php echo $field_config ?>",
            element_ouput: '<?php echo $element_ouput ?>'

        });


    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);


ob_start();
?>

<div id="form_view_layout_createitem">
    <div class="row">
        <div class="col-md-12">
            <label>show more option<input  type="checkbox" checked
                                          class="show_more_options"></label>
        </div>
    </div>
    <div class="row">


        <div class="col-md-12">

            <div class="cf nestable-lists">
                <div class="row">
                    <div class="menu_type_item col-md-12" data-menu-type-id="<?php echo $menu_type_id ?>">
                        <div id="field_block" class="dd">
                            <?php echo UtilityViewParams::create_html_list($nodes, '', $list_field_type, $list_field_table_position_config); ?>
                        </div>
                    </div>

                </div>

            </div>


        </div>
    </div>
    <input type="hidden" control-id="<?php echo $table_control->id ?>" value="<?php echo $field_block_output ?>"
           id="field_block-output"/>
    <div class="panel-footer createitem-handle-footer">
        <button class="btn btn-danger  pull-right save_and_close"  ><i class="fa-save"></i>Save&close</button>
        <button class="btn btn-danger  pull-right save"  ><i class="fa-save"></i>Save</button>
        <button class="btn btn-danger  pull-right cancel" ><i class="fa-save"></i>Cancel</button>
    </div>
</div>



<?php

$contents = ob_get_clean();
$tmpl = $app->input->get('tmpl', '', 'string');
if ($tmpl == 'field') {
    echo $contents;
    return;
}
$response_array[] = array(
    'key' => '.panel.params.createitem-config .panel-body.params',
    'contents' => $contents
);

echo json_encode($response_array);
?>



