<?php
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$lessInput = JPATH_ROOT . '/components/com_menus/views/menus/tmpl/assets/less/view_menus_ajaxloader.less';
$cssOutput = JPATH_ROOT . '/components/com_menus/views/menus/tmpl/assets/css/view_menus_ajaxloader.css';
$db = JFactory::getDbo();
JHtml::_('jquery.framework');
JUtility::compileLess($lessInput, $cssOutput);
$doc->addStyleSheet(JUri::root() . "/components/com_menus/views/menus/tmpl/assets/css/view_menus_ajaxloader.css");
$doc->addStyleSheet(JUri::root() . "/media/jui_front_end/css/select2.css");
$doc->addScript(JUri::root() . "/media/system/js/Nestable-master/jquery.nestable.js");
$doc->addScript(JUri::root() . "/media/jui_front_end/js/select2.jquery.js");
$doc->addScript(JUri::root() . "/media/system/js/cassandraMAP-cassandra/lib/cassandraMap.js");
$doc->addScript(JUri::root() . "/components/com_menus/views/menus/tmpl/assets/js/view_menus_ajaxloader.js");
$doc->addScript(JUri::root() . "/media/system/js/base64.js");

require_once JPATH_ROOT . '/libraries/upgradephp-19/upgrade.php';
$website = JFactory::getWebsite();
require_once JPATH_ROOT . '/libraries/joomla/form/fields/icon.php';
$db = JFactory::getDbo();
$website = JFactory::getWebsite();
$query = $db->getQuery(true);
$query->select('menu_types.id as menu_type_id,menu_types.title as title,menu_type_id_menu_id.menu_id AS menu_id')
    ->from('#__menu_type_id_menu_id AS menu_type_id_menu_id')
    ->leftJoin('#__menu_types AS menu_types ON menu_types.id=menu_type_id_menu_id.menu_type_id')
    ->where('menu_types.website_id=' . (int)$website->website_id);
$list_menu_type = $db->setQuery($query)->loadObjectList('menu_id');

$query = $db->getQuery(true);
$query->select('menu.*')
    ->from('#__menu As menu ')
    ->order('menu.ordering');
$db->setQuery($query);
$list_menu_item1 = $db->loadObjectList('id');
$children = array();

// First pass - collect children
foreach ($list_menu_item1 as $v) {
    $pt = $v->parent_id;
    $list = @$children[$pt] ? $children[$pt] : array();
    if ($v->id != $v->parent_id) {
        array_push($list, $v);
    }
    $children[$pt] = $list;
}

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


        menu_ajax_loader.init_menu_ajax_loader();
    });
    <?php
    $script = ob_get_clean();
    ob_start();
    ?>
</script>
<?php
ob_get_clean();
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);
require_once JPATH_ROOT . '/libraries/joomla/form/fields/bindingsource.php';
$binding_source = new JFormFieldBindingSource();
$xml_binding_source = <<<XML

<field
		name="binding_source"
		type="bindingSource"
		onchange="menu_ajax_loader.update_data_column(this,'binding_source')"
		>

</field>

XML;

$xml_binding_source = simplexml_load_string($xml_binding_source);
$binding_source->setup($xml_binding_source, 0, 'binding_source');
ob_start();
?>
<div style="background: #fff">
    <div class="row">
        <div class="col-md-3">
            <label>show more option<input onchange="menu_ajax_loader.show_more_options(this);" type="checkbox" checked
                                          class="show_more_options"></label>

        </div>
        <div class="col-md-3">
            <div class="input-group">
                <input type="text" class="form-control" style="width: 300px" name="menu_type_name" placeholder="Menu type name">
                  <span class="input-group-btn">
                    <button class="btn btn-secondary create_menu_type" type="button">create!</button>
                  </span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="cf nestable-lists">
                <div class="row">
                    <?php
                    foreach ($list_menu_type as $root_menu) {
                        $list_menu_item1 = $children[$root_menu->menu_id];
                        ?>
                        <div class="menu_type_item col-md-6" data-menu-type-id="<?php echo $root_menu->menu_type_id ?>">
                            <?php echo JHtml::_('input.button', 'create_new_menu_item', 'create new menu item') ?>
                            <h3><?php echo $root_menu->title ?>(<?php echo $root_menu->menu_type_id ?>
                                )<i
                                    class="fa-copy"></i></h3><a title="rebuid menu" href="javascript:void(0)"
                                                                data-menu_item_id="<?php echo $root_menu->menu_id ?>"
                                                                data-menu_type_id="<?php echo $root_menu->menu_type_id ?>"
                                                                class="rebuild_root_menu"><i
                                    class="im-spinner5"></i></a>
                            <input class="menu_input" value="<?php echo $json_list_item ?>"
                                   data-menu-type-id="<?php echo $root_menu->menu_type_id ?>" type="hidden"
                                   name="menu_type_<?php echo $root_menu->menu_type_id ?>_output"
                                   id="menu_type_<?php echo $root_menu->menu_type_id ?>_output">
                            <div data-menu_root_id="<?php echo $root_menu->menu_id ?>"
                                 data-menu_type_id="<?php echo $root_menu->menu_type_id ?>" class="dd a_menu_type"
                                 id="menu_type_<?php echo $root_menu->menu_type_id ?>">
                                <?php if (count($list_menu_item1)) { ?>

                                    <?php
                                    echo create_html_list($root_menu->menu_id, $children, $binding_source);

                                    ?>
                                <?php } else { ?>
                                    <div class="dd-empty"></div>
                                <?php } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>

            </div>


        </div>
    </div>
</div>

<?php
function create_html_list($root_id, $children, $binding_source)
{
    $nodes = $children[$root_id];
    if (count($nodes)) {
        ?>
        <ol class="dd-list">

            <?php
            foreach ($nodes as $item) {
                $groupedlist = new JFormFieldGroupedList();
                $groupedlist->setValue($item->group);
                ob_start();
                ?>
                <li class="dd-item"
                    <?php foreach ($item as $key => $value) { ?>
                        data-<?php echo $key ?>="<?php echo $value ?>"
                    <?php } ?>

                >
                    <div class="dd-handle">
                        <div class="dd-handle-move pull-left"><i class="fa-move"></i></div>
                        <span class="key_name"><?php echo "$item->title ($item->id, $item->alias ) " ?></span>
                        <button onclick="menu_ajax_loader.remove_item_nestable(this)"
                                class="dd-handle-remove dd-nodrag pull-right"><i
                                class="fa-remove"></i></button>
                        <button onclick="menu_ajax_loader.expand_item_nestable(this)"
                                class="dd-handle-expand dd-nodrag pull-right"><i
                                class="im-plus"></i></button>

                    </div>
                    <h6><?php echo $item->link ?></h6>
                    <div class="more_options">
                        <div>
                            <button class="add_node">add node</button>
                            <button class="add_sub_node">add sub node</button>
                        </div>

                        <label>Name<input class="form-control"
                                          onchange="menu_ajax_loader.update_data_column(this,'title')"
                                          value="<?php echo $item->title ?>" type="text"/></label>
                        <label>Alias<input class="form-control"
                                           onchange="menu_ajax_loader.update_data_column(this,'alias')"
                                           value="<?php echo $item->alias ?>" type="text"/></label>
                        <label>Icon<input class="icon_menu_item" style="width: 200px" type="text"
                                          onchange="menu_ajax_loader.update_data_column(this,'icon')"
                                          value="<?php echo $item->icon ?>"/></label>
                        <label>
                            Access
                            <?php
                            echo JHtml::_('access.level', 'access_level', $item->access, array("class" => 'menu_access_level'));
                            ?>
                        </label>
                        <label>
                            Menu item alias
                            <?php
                            //echo JHtml::_('access.level', 'access_level', $item->access, array("class" => 'menu_access_level'));
                            ?>
                        </label>
                        <label>
                            Link
                            <?php
                            echo JHtml::_('input.text', '', 'link', $item->link, array("class" => 'menu_link', 'onchange' => "menu_ajax_loader.update_data_column(this,'link')"), '', 200);
                            ?>
                        </label>
                        <label>Home<input <?php echo $item->home == 1 ? 'checked' : '' ?> name="home" type="radio"
                                                                                          onchange="menu_ajax_loader.home_update_value(this);menu_ajax_loader.call_on_change(this)"
                                                                                          value="<?php echo $item->home == 1 ? 1 : 0 ?>"/></label>
                        <label>published<input <?php echo $item->published == 1 ? 'checked' : '' ?> name="published"
                                                                                                    type="checkbox"
                                                                                                    onchange="menu_ajax_loader.update_data_column(this,'published','checkbox')"
                                                                                                    value="1"/></label>
                        <?php echo JHtml::row_control('', '', 'hide', 'input.radioyesno', $item->id . '-hide', $item->hidden, array("data-onchange" => "menu_ajax_loader.update_data_column(this,'hidden','checkbox')")) ?>
                        <?php
                        if ($item->binding_source) {
                            $binding_source->setValue($item->binding_source);
                        } else {
                            $binding_source->setValue(null);
                        }

                        ?>
                        <label>
                            sub menu by datasource
                            <?php echo $binding_source->renderField(); ?>
                        </label>
                        <label>Binding source Key<input class="form-control"
                                                        onchange="menu_ajax_loader.update_data_column(this,'binding_source_key')"
                                                        value="<?php echo $item->binding_source_key ?>"
                                                        type="text"/></label>
                        <label>Binding source Key<input class="form-control"
                                                        onchange="menu_ajax_loader.update_data_column(this,'binding_source_value')"
                                                        value="<?php echo $item->binding_source_value ?>"
                                                        type="text"/></label>


                    </div>

                    <?php
                    echo ob_get_clean();
                    create_html_list($item->id, $children, $binding_source);
                    ?>
                </li>
                <?php
            }
            ?>
        </ol>
        <?php
    } else {

    }
}


$contents = ob_get_clean();
$tmpl = $app->input->get('tmpl', '', 'string');
if ($tmpl == 'field') {
    echo $contents;
    return;
}
$response_array[] = array(
    'key' => '.panel.menu.menus-config .panel-body.menu',
    'contents' => $contents
);

echo json_encode($response_array);
?>



