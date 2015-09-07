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
$query->from('#__menu As menu');
$query->select('menu.parent_id, menu.id,menu.access,menu.level,menu.icon,menu.title,menu.link,menu.alias');
$query->leftJoin('#__menu_types AS menuType ON menuType.id=menu.menu_type_id');
$query->select('menuType.id as menu_type_id,menuType.title as menu_type_title');
$query->where('menuType.website_id=' . (int)$website->website_id);
$query->where('menuType.client_id=0');
$query->order('menuType.id,menu.ordering');

$db->setQuery($query);
$list_menu = $db->loadObjectList();
$list_menu_item = array();
foreach ($list_menu as $item) {
    $list_menu_item[$item->menu_type_id][] = $item;
}

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

function create_html_list($nodes,$website)
{


echo '<ol class="dd-list">';

foreach ($nodes as $item) {
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
        <button onclick="menu_ajax_loader.remove_item_nestable(this)" class="dd-handle-remove pull-right"><i
                class="fa-remove"></i></button>
    </div>
    <label>id <?php echo $item->id ?>,</label>
    <label>parent_id <?php echo $item->parent_id ?>,</label>
    <label>level <?php echo $item->level ?>,</label>
    <label>lft <?php echo $item->lft ?>,</label>
    <label>rgt <?php echo $item->rgt ?>,</label>

    <div class="more_options">
        <div>
            <button class="add_node">add node</button>
            <button class="add_sub_node">add sub node</button>
        </div>

        <label>Name<input class="form-control" onchange="menu_ajax_loader.update_data_column(this,'title')"
                          value="<?php echo $item->title ?>" type="text"/></label>
        <label>Alias<input class="form-control" onchange="menu_ajax_loader.update_data_column(this,'alias')"
                           value="<?php echo $item->alias ?>" type="text"/></label>
        <label>Icon<input class="icon_menu_item" style="width: 200px" type="text"
                          onchange="menu_ajax_loader.update_data_column(this,'icon')"
                          value="<?php echo $item->icon ?>"/></label>
        <label>
            Access
            <?php
            echo JHtml::_('access.level', 'access_level',$item->access,array("class"=>'menu_access_level'));
            ?>
        </label>
        <label>Home<input <?php echo $item->home == 1 ? 'checked' : '' ?> name="home" type="radio"
                                                                          onchange="menu_ajax_loader.home_update_value(this);menu_ajax_loader.call_on_change(this)"
                                                                          value="<?php echo $item->home == 1 ? 1 : 0 ?>"/></label>
        <label>show<input <?php echo $item->published == 1 ? 'checked' : '' ?> name="show" type="checkbox"
                                                                               onchange="menu_ajax_loader.update_data_column(this,'published','checkbox')"
                                                                               value="1"/></label>
    </div>

    <?php
    echo ob_get_clean();
    if (count($childNodes) > 0) {
        create_html_list($childNodes,$website);
    }
    echo "</li>";
    }
    echo '</ol>';
    }


    ob_start();
    ?>
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
                    foreach ($list_menu_item as $menu_type_id => $list_item) {
                        $fist_item = new stdClass();
                        foreach ($list_item as $key => $item) {
                            if ($item->parent_id == $item->id) {
                                $fist_item = $item;
                                unset($list_item[$key]);
                                break;
                            }
                        }
                        ?>
                        <div class="menu_type_item col-md-6" data-menu-type-id="<?php echo $menu_type_id ?>">
                            <h3><?php echo $fist_item->menu_type_title ?></h3>
                            <input class="menu_input" value="<?php echo $json_list_item ?>"
                                   data-menu-type-id="<?php echo $menu_type_id ?>" type="hidden"
                                   name="menu_type_<?php echo $menu_type_id ?>_output"
                                   id="menu_type_<?php echo $menu_type_id ?>_output">

                            <div data-menu_root_id="<?php echo $fist_item->id ?>"
                                 data-menu_type_id="<?php echo $menu_type_id ?>" class="dd a_menu_type"
                                 id="menu_type_<?php echo $menu_type_id ?>">
                                <?php if (count((array)$list_item)) { ?>

                                    <?php
                                    $children = array();

                                    // First pass - collect children
                                    foreach ($list_item as $v) {
                                        $pt = $v->parent_id;
                                        $list = @$children[$pt] ? $children[$pt] : array();
                                        array_push($list, $v);
                                        $children[$pt] = $list;
                                    }

                                    $list_item = treerecurse($fist_item->id, array(), $children, 99, 0);
                                    echo create_html_list($list_item,$website);

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



    <?php
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



