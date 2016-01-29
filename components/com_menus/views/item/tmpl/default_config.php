<?php
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$lessInput = JPATH_ROOT . '/components/com_menus/views/item/tmpl/assets/less/view_item_phpcontent.less';
$cssOutput = JPATH_ROOT . '/components/com_menus/views/item/tmpl/assets/css/view_item_phpcontent.css';
$db = JFactory::getDbo();
JHtml::_('jquery.framework');
JUtility::compileLess($lessInput, $cssOutput);
$doc->addStyleSheet(JUri::root() . "/components/com_menus/views/item/tmpl/assets/css/view_item_phpcontent.css");
$doc->addStyleSheet(JUri::root() . "/media/jui_front_end/css/select2.css");
$doc->addStyleSheet(JUri::root() . "/media/system/js/jquery.appendGrid-master/jquery.appendGrid-development.css");
$doc->addScript(JUri::root() . "/media/system/js/Nestable-master/jquery.nestable.js");
$doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");
$doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
$doc->addScript(JUri::root() . "/components/com_menus/views/item/tmpl/assets/js/view_item_config.js");
$doc->addScript(JUri::root() . "/media/system/js/base64.js");
$doc->addScript(JUri::root() . "/media/system/js/jquery.appendGrid-master/jquery.appendGrid-development.js");


require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
$website = JFactory::getWebsite();

require_once JPATH_ROOT . '/libraries/joomla/form/fields/icon.php';
$db = JFactory::getDbo();

require_once JPATH_ROOT.'/components/com_phpmyadmin/tables/updatetable.php';
$table_website=new JTableUpdateTable($db,'website');
$table_website->load($website->website_id);
$fields=$table_website->menu_params_field;
$fields=base64_decode($fields);
$field_block_output=$fields;
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
$list_field_table_menu=$db->getTableColumns('#__menu');
$list_field_table_menu=array_keys($list_field_table_menu);
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

        view_config_menu_item.field_name_option.tags=<?php echo json_encode($list_field_table_menu) ?>;
        view_config_menu_item.init_view_config_menu_item();
    });
    <?php
     $script=ob_get_clean();
     ob_start();
      ?>
</script>
<?php
ob_get_clean();
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);

function com_menu_view_item_create_html_list($nodes,$indent='',$list_field_type,$list_field_table_menu)
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
        <button onclick="view_config_menu_item.remove_item_nestable(this)" class="dd-handle-remove pull-right"><i
                class="fa-remove"></i></button>
    </div>

    <div class="more_options">
        <div>
            <button class="add_node">add node</button>
            <button class="add_sub_node">add sub node</button>
        </div>


        <label>Name<input class="form-control select_field_name" style="width: 200px"  onchange="view_config_menu_item.update_data_column(this,'name')"
                          value="<?php echo $item->name ?>" type="text"/></label>
        <label>default<input class="form-control" onchange="view_config_menu_item.update_data_column(this,'default')"
                          value="<?php echo $item->default ?>" type="text"/></label>
        <label>label<input class="form-control" onchange="view_config_menu_item.update_data_column(this,'label')"
                           value="<?php echo $item->label ?>" type="text"/></label>
        <label>Icon<input class="icon_menu_item" style="width: 200px" type="text"
                          onchange="view_config_menu_item.update_data_column(this,'icon')"
                          value="<?php echo $item->icon ?>"/></label>
        <label>Description<textarea class="description" style="width: 200px"
                          onchange="view_config_menu_item.update_data_column(this,'description')"
                          value="<?php echo $item->icon ?>"></textarea></label>
        <label>
            Access
            <?php
            echo JHtml::_('access.level', 'access_level',$item->access,array("class"=>'menu_access_level'));
            ?>
        </label>

        <label>
            type
            <select disableChosen="true" style="width: 200px" onchange="view_config_menu_item.update_data_column(this,'type')" type="hidden"  class="select2 field_type"   >
                <?php
                    foreach($list_field_type as $a_item){
                        $a_item=str_replace('.php','',$a_item);
                    ?>
                    <option <?php echo $a_item===$item->type?'selected':'' ?>  value="<?php echo $a_item ?>"><?php echo $a_item ?></option>
                <?php } ?>
            </select>

        </label>
        <label>Read only<input <?php echo $item->readonly == 1 ? 'checked' : '' ?>  type="checkbox"
                                                                                     onchange="view_config_menu_item.update_data_column(this,'readonly','checkbox')"
                                                                                     value="1"/></label>
        <div class="row">

            <div class="config_property col-md-6">
                <table class="tbl_append_grid_config_property" data-config_property="<?php echo $item->config_property  ?>" id="tblAppendGrid_config_property_<?php echo $indent1 ?>"></table>
            </div>
            <div class="config_params col-md-6">
                <table class="tbl_append_grid" data-config_params="<?php echo $item->config_params  ?>" id="tblAppendGrid_<?php echo $indent1 ?>"></table>
            </div>
        </div>

    </div>

    <?php
    echo ob_get_clean();
    if (count($childNodes) > 0) {
        com_menu_view_item_create_html_list($childNodes,$indent1,$list_field_type,$list_field_table_menu);
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
            <label>show more option<input onchange="view_config_menu_item.show_more_options(this);" type="checkbox" checked
                                          class="show_more_options"></label>
        </div>
    </div>
    <div class="row">


        <div class="col-md-12">

            <div class="cf nestable-lists">
                <div class="row">
                    <div class="menu_type_item col-md-12" data-menu-type-id="<?php echo $menu_type_id ?>">
                        <div id="field_block">
                            <?php echo com_menu_view_item_create_html_list($fields,'',$list_field_type,$list_field_table_menu); ?>
                        </div>
                    </div>

                </div>

            </div>


        </div>
    </div>
    <input type="hidden" control-id="<?php echo $table_control->id ?>"  value="<?php echo $field_block_output ?>"  id="field_block-output"/>



    <?php

    $contents = ob_get_clean();
    $tmpl = $app->input->get('tmpl', '', 'string');
    if ($tmpl == 'field') {
        echo $contents;
        return;
    }
    $response_array[] = array(
        'key' => '.panel.element.menu-item-config .panel-body.menu-item',
        'contents' => $contents
    );

    echo json_encode($response_array);
    ?>



