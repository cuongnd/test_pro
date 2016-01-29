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
$query = $db->getQuery(true);
$query->from('#__menu As menu ')
    ->select('menu.parent_id,menu.home, menu.id,menu.binding_source,menu.binding_source_key,menu.binding_source_value,menu.access,menu.level,menu.icon,menu.title,menu.link,menu.alias,menu.published,menu.hidden')
    ->leftJoin('#__menu_types AS menuType ON menu.menu_type_id=menuType.id')
    ->where('menuType.website_id='.(int)$website->website_id)
    ->select('menuType.id as menu_type_id,menuType.title as menu_type_title');

$query->order('menu.ordering');

$db->setQuery($query);
$list_menu_item1 = $db->loadObjectList();

//get list menu type
$query->clear()
    ->from('#__menu_types AS menuType')
    ->select('menuType.*')
    ->where('menuType.website_id='.(int)$website->website_id)
    ->leftJoin('#__website AS website ON website.id=menuType.website_id')
    ->select('website.title AS website_title')
    ->order('menuType.id');
$list_menu_type = $db->setQuery($query)->loadObjectList('id');

//end get list menu type
//

$list_menu_item = array();
foreach ($list_menu_type as $menu_type) {
    $item = new stdClass();
    $item->menu_type = $menu_type;
    $item->list_menu_item1 = array();
    $item->root_menu_item = new stdClass();
    foreach ($list_menu_item1 as $menu_item) {
        if ($menu_item->menu_type_id == $menu_type->id && $menu_item->id != $menu_item->parent_id) {
            $item->list_menu_item1[] = $menu_item;
        }
        if ($menu_item->menu_type_id == $menu_type->id && $menu_item->id == $menu_item->parent_id) {
            $item->root_menu_item = $menu_item;
        }
    }
    $list_menu_item[$menu_type->id] = $item;
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
     $script=ob_get_clean();
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
        <div class="col-md-12">
            <label>show more option<input onchange="menu_ajax_loader.show_more_options(this);" type="checkbox" checked
                                          class="show_more_options"></label>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="cf nestable-lists">
                <div class="row">
                    <?php
                    foreach ($list_menu_item as $menu_type_id => $item) {
                        $menu_type = $item->menu_type;
                        $root_menu_item = $item->root_menu_item;
                        $list_menu_item1 = $item->list_menu_item1;

                        ?>
                        <div class="menu_type_item col-md-6" data-menu-type-id="<?php echo $menu_type_id ?>">
                            <h3><?php echo $menu_type->title ?>(<?php echo $menu_type_id ?>
                                )<i
                                    class="fa-copy"></i></h3><a title="rebuid menu" href="javascript:void(0)"
                                                                data-menu_item_id="<?php echo $root_menu_item->id ?>"
                                                                data-menu_type_id="<?php echo $menu_type_id ?>"
                                                                class="rebuild_root_menu"><i
                                    class="im-spinner5"></i></a>
                            <input class="menu_input" value="<?php echo $json_list_item ?>"
                                   data-menu-type-id="<?php echo $menu_type_id ?>" type="hidden"
                                   name="menu_type_<?php echo $menu_type_id ?>_output"
                                   id="menu_type_<?php echo $menu_type_id ?>_output">

                            <div data-menu_root_id="<?php echo $root_menu_item->id ?>"
                                 data-menu_type_id="<?php echo $menu_type_id ?>" class="dd a_menu_type"
                                 id="menu_type_<?php echo $menu_type_id ?>">
                                <?php if (count($list_menu_item1)) { ?>

                                    <?php


                                    echo create_html_list($root_menu_item->id, $list_menu_item1, $website, $binding_source);

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
function create_html_list($root_id, $list_nodes, $website, $binding_source)
{


    $nodes = array();
    foreach ($list_nodes as $key => $item) {
        if ($item->parent_id == $root_id) {
            $nodes[] = $item;
            unset($list_nodes[$key]);
        }
    }
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
                        <label>Home<input <?php echo $item->home == 1 ? 'checked' : '' ?> name="home" type="radio"
                                                                                          onchange="menu_ajax_loader.home_update_value(this);menu_ajax_loader.call_on_change(this)"
                                                                                          value="<?php echo $item->home == 1 ? 1 : 0 ?>"/></label>
                        <label>published<input <?php echo $item->published == 1 ? 'checked' : '' ?> name="published"
                                                                                               type="checkbox"
                                                                                               onchange="menu_ajax_loader.update_data_column(this,'published','checkbox')"
                                                                                               value="1"/></label>
                        <label>show<input <?php echo $item->hidden == 1 ? 'checked' : '' ?> name="hidden"
                                                                                               type="checkbox"
                                                                                               onchange="menu_ajax_loader.update_data_column(this,'hidden','checkbox')"
                                                                                               value="1"/></label>
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
                    create_html_list($item->id, $list_nodes, $website, $binding_source);
                    ?>
                </li>
                <?php
            }
            ?>
        </ol>
        <?php
    }else{

    }
}


function treerecurse($id, $list, &$children, $maxlevel = 9999, $level = 0)
{
    if (@$children[$id] && $level <= $maxlevel) {

        foreach ($children[$id] as $v) {
            $id = $v->id;
            $list[$id] = $v;
            $list[$id]->children = @$children[$id];
            unset($children[$id]);
            $list = treerecurse($id, $list, $children, $maxlevel, $level + 1);
        }
    }
    return $list;
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



