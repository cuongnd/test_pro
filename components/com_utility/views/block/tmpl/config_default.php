<?php
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$lessInput = JPATH_ROOT . '/components/com_utility/views/block/tmpl/assets/less/view_config.less';
$cssOutput = JPATH_ROOT . '/components/com_utility/views/block/tmpl/assets/css/view_config.css';
$db = JFactory::getDbo();
JHtml::_('jquery.framework');
JUtility::compileLess($lessInput, $cssOutput);
$doc->addStyleSheet(JUri::root() . "/components/com_utility/views/block/tmpl/assets/css/view_config.css");
$doc->addStyleSheet(JUri::root() . "/media/jui_front_end/css/select2.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/jquery.appendGrid-master/jquery.appendGrid-development.css");
$doc->addScript(JUri::root() . "/media/system/js/Nestable-master/jquery.nestable.js");
$doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");
$doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
$doc->addScript(JUri::root() . "/components/com_utility/views/block/tmpl/assets/js/view_config.js");
$doc->addScript(JUri::root() . "/media/system/js/base64.js");
$doc->addScript(JUri::root() . "/media/system/js/jquery.appendGrid-master/jquery.appendGrid-development.js");
$element_path=$app->input->get('element_path','','string');
$element_config=$app->input->get('element_config','','string');
if($element_config=="global_element_config") {
    $element_path="root_element";
}
require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
$website = JFactory::getWebsite();
require_once JPATH_ROOT . '/libraries/joomla/form/fields/icon.php';
$db = JFactory::getDbo();

require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
$table_control=new JTableUpdateTable($db,'control');
$table_control->load(array("element_path"=>$element_path));
if(!$element_path->id)
{
    $table_control->element_path=$element_path;
    $table_control->store();
}
$fields=$table_control->fields;
$fields=base64_decode($fields);
require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
$fields = (array)up_json_decode($fields, false, 512, JSON_PARSE_JAVASCRIPT);
if(!count($fields))
{
    $fields=array(new stdClass());
}
jimport('joomla.filesystem.folder');
$list_field_type=array();
$list_field_type1=JFolder::files(JPATH_ROOT.'/libraries/joomla/form/fields','.php');
$list_field_type2=JFolder::files(JPATH_ROOT.'/libraries/cms/form/field','.php');
$list_field_type=array_merge($list_field_type1,$list_field_type2);

//get list field table position config
$list_field_table_position_config=$db->getTableColumns('#__position_config');
$list_field_table_position_config=array_keys($list_field_table_position_config);
//end get list field table position config

require_once JPATH_ROOT.'/libraries/joomla/form/fields/groupedlist.php';


$scriptId = "com_menus_view_menus_jaxloader" . '_' . JUserHelper::genRandomPassword();
ob_start();
?>
<script type="text/javascript" id="<?php echo $scriptId ?>">

    <?php
        ob_get_clean();
        ob_start();
    ?>
    jQuery(document).ready(function ($) {

        view_config.field_name_option.tags=<?php echo json_encode($list_field_table_position_config) ?>;
        view_config.init_view_config();
    });
    <?php
     $script=ob_get_clean();
     ob_start();
      ?>
</script>
<?php
ob_get_clean();
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);

function create_html_list($nodes,$indent='',$list_field_type,$list_field_table_position_config)
{


echo '<ol class="dd-list">';
 $i=1;
foreach ($nodes as $item) {
$indent1=$indent!=''?$indent.'_'.$i:$i;

$groupedlist=new JFormFieldGroupedList();
$groupedlist->setValue($item->group);
$childNodes = $item->children;
ob_start();
?>

<li class="dd-item"
    <?php foreach ($item as $key => $value) { ?>
        data-<?php echo $key ?>="<?php echo $value ?>"
    <?php } ?>

    >
    <div class="dd-handle">
        <div class="dd-handle-move pull-left"><i class="fa-move"></i></div>
        <?php echo $item->title ?>
        <button onclick="view_config.remove_item_nestable(this)" class="dd-handle-remove pull-right"><i
                class="fa-remove"></i></button>
    </div>

    <div class="more_options">
        <div>
            <button class="add_node">add node</button>
            <button class="add_sub_node">add sub node</button>
        </div>


        <label>Name<input class="form-control select_field_name" style="width: 200px"  onchange="view_config.update_data_column(this,'name')"
                          value="<?php echo $item->name ?>" type="text"/></label>
        <label>default<input class="form-control" onchange="view_config.update_data_column(this,'default')"
                          value="<?php echo $item->default ?>" type="text"/></label>
        <label>label<input class="form-control" onchange="view_config.update_data_column(this,'label')"
                           value="<?php echo $item->label ?>" type="text"/></label>
        <label>Icon<input class="icon_menu_item" style="width: 200px" type="text"
                          onchange="view_config.update_data_column(this,'icon')"
                          value="<?php echo $item->icon ?>"/></label>
        <label>Description<textarea class="description" style="width: 200px"
                          onchange="view_config.update_data_column(this,'description')"
                          value="<?php echo $item->icon ?>"></textarea></label>
        <label>
            Access
            <?php
            echo JHtml::_('access.level', 'access_level',$item->access,array("class"=>'menu_access_level'));
            ?>
        </label>
        <label>hide<input <?php echo $item->hide == 1 ? 'checked' : '' ?>  type="checkbox"
                                                                                     onchange="view_config.update_data_column(this,'hide','checkbox')"
                                                                                     value="1"/></label>

        <label>
            type
            <select disableChosen="true" style="width: 200px" onchange="view_config.update_data_column(this,'type')" type="hidden"  class="select2 field_type"   >
                <?php
                    foreach($list_field_type as $a_item){
                        $a_item=str_replace('.php','',$a_item);
                    ?>
                    <option <?php echo $a_item===$item->type?'selected':'' ?>  value="<?php echo $a_item ?>"><?php echo $a_item ?></option>
                <?php } ?>
            </select>

        </label>
        <div>
            <table class="tbl_append_grid" id="tblAppendGrid_<?php echo $indent1 ?>"></table>
        </div>
    </div>

    <?php
    echo ob_get_clean();
    if (count($childNodes) > 0) {
        create_html_list($childNodes,$indent1,$list_field_type,$list_field_table_position_config);
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
            <label>show more option<input onchange="view_config.show_more_options(this);" type="checkbox" checked
                                          class="show_more_options"></label>
        </div>
    </div>
    <div class="row">


        <div class="col-md-12">

            <div class="cf nestable-lists">
                <div class="row">
                    <div class="menu_type_item col-md-12" data-menu-type-id="<?php echo $menu_type_id ?>">
                        <div id="field_block">
                            <?php echo create_html_list($fields,'',$list_field_type,$list_field_table_position_config); ?>
                        </div>
                    </div>

                </div>

            </div>


        </div>
    </div>
    <input type="hidden" control-id="<?php echo $table_control->id ?>"  value="<?php echo $table_control->fields ?>"  id="field_block-output"/>



    <?php

    $contents = ob_get_clean();
    $tmpl = $app->input->get('tmpl', '', 'string');
    if ($tmpl == 'field') {
        echo $contents;
        return;
    }
    $response_array[] = array(
        'key' => '.panel.element.element-config .panel-body.element',
        'contents' => $contents
    );

    echo json_encode($response_array);
    ?>



