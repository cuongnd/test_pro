<?php
$doc = JFactory::getDocument();
$website = JFactory::getWebsite();
/*$doc->addScript(JUri::root() . '/media/system/js/sidr-master/src/jquery.sidr.js');

$doc->addStyleSheet(JUri::root() . '/media/system/js/sidr-master/dist/stylesheets/jquery.sidr.light.css');
*/
$doc->addScript(JUri::root() . '/media/system/js/bootstrap-switch-master/dist/js/bootstrap-switch.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/bootstrap-switch-master/dist/css/bootstrap3/bootstrap-switch.css');
$doc->addStyleSheet(JUri::root() . '/libraries/adminfrontend/assets/admin_left.css');
$doc->addScript(JUri::root() . '/libraries/adminfrontend/assets/admin_left.js');
$listScreenSize = UtilityHelper::getListScreenSize();
$currentScreenSize = UtilityHelper::getCurrentScreenSizeEditing();
require_once JPATH_ROOT . '/components/com_modules/helpers/modules.php';
$modules = ModulesHelper::getModules(0);

$app = JFactory::getApplication();

$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->from('#__menu As menu');
$query->select('menu.parent_id, menu.id,menu.title,menu.link,menu.icon');
$query->leftJoin('#__menu_types AS menuType ON menuType.id=menu.menu_type_id');
$query->select('menuType.title as menu_type');
$query->where('menuType.website_id=' . (int)$website->website_id);
$query->where('menuType.client_id=0');
$query->order('menuType.id,menu.ordering');
$listMenu = $db->setQuery($query)->loadObjectList();
$a_listMenu = array();
foreach ($listMenu as $menu) {
    $a_listMenu[$menu->menu_type][] = $menu;
}





JModelLegacy::addIncludePath(JPATH_ROOT . '/components/com_menus/models');
$menusModel = JModelLegacy::getInstance('menus', 'MenusModel');
$menusModel->setState('filter.client_id', 0);
$listMenuType = $menusModel->getItems();
$listIdMenuType = array();

foreach ($listMenuType as $menu_type) {
    $listIdMenuType[] = $menu_type->id;
}
$menuItemsModel = JModelLegacy::getInstance('Items', 'MenusModel');

$menuItemsModel->setState('filter.list_id_menu_type', $listIdMenuType);
$query = $menuItemsModel->getListQuery();
$menuItemsModel->getDbo()->setQuery($query);
$menus = $menuItemsModel->getDbo()->loadObjectList();


$listMenu = array();
foreach ($menus as $menu) {
    $listMenu[$menu->menu_type][] = $menu;
}
/*
JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_plugins/models');
$pluginModel=JModelLegacy::getInstance('Plugins','PluginsModel');
$plugins=$pluginModel->getItems();
$listPlugin=array();
foreach($plugins as $plugin)
{
    $listPlugin[$plugin->folder][]=$plugin;
}

$doc->addScript(JUri::root().'/media/system/js/bootstrap-switch-master/docs/js/highlight.js');

JHtml::_('formbehavior.chosen', 'select');




//get list view of component

JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_menus/models');
$menuTypeModel=JModelLegacy::getInstance('Menutypes','MenusModel');

$website=JFactory::getWebsite();
$types = $menuTypeModel->getTypeOptionsByWebsiteId($website->website_id);


$menuItemIdActive=$app->input->get('Itemid',0,'int');
//-------------
//get Data source

JModelLegacy::addIncludePath(JPATH_ROOT.'/components/com_phpmyadmin/models');
$dataSourceModal=JModelLegacy::getInstance('DataSources','phpMyAdminModel');
$currentDataSource=$dataSourceModal->getCurrentDataSources();*/
//end get Data Source
$query = $db->getQuery(true);
$query->from('#__components as component')
    ->select('component.name')
    ->where('type="component"')
    ->where('website_id=' . (int)$website->website_id)
    ->group('component.name');
$listComponent = $db->setQuery($query)->loadColumn();
$listLayOut = array();
foreach ($listComponent as $com) {
    $views = JFolder::folders(JPATH_ROOT . '/components/' . $com . '/views');
    foreach ($views as $view) {
        $layouts = JFolder::files(JPATH_ROOT . '/components/' . $com . '/views/' . $view . '/tmpl/', '.xml');
        if (count($layouts))
            $listLayOut[$com][$view] = $layouts;
    }

}

$foldersElement = JFolder::folders(JPATH_ROOT . '/media/elements');
?>
<div class="div-loading"></div>
<ul id="sideNav" class="nav nav-pills nav-stacked">
    <li class=top-search>
        <form>
            <input name=search placeholder="Search ...">
            <button type=submit><i class="ec-search s20"></i></button>
        </form>
    </li>
    <li><a href="index.php?option=com_bookpro">Dashboard <i class=im-screen></i></a></li>
    <li><a href="javascript:void(0)">Option <i class=im-paragraph-justify></i></a>
        <ul class="nav sub">
            <li class="form-group">
                <div class="col-lg-5 col-md-5 col-sm-5">
                    <label class=" control-label">Smartphone size</label>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-7">
                    <select name="smart_phone" class="btn-primary btn-block smart-phone">
                        <?php foreach ($listScreenSize as $item) { ?>
                            <option <?php echo $item == $currentScreenSize ? 'selected' : ''; ?>
                                value="<?php echo $item ?>"><?php echo $item ?></option>
                        <?php } ?>
                    </select>
                </div>


            </li>
            <li class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button href="javascript:void(0)" class="btn btn-primary btn-block add_widget">Add new block
                    </button>
                </div>
            </li>
            <li class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button href="javascript:void(0)" class="btn btn-primary btn-block save-position"
                            id="save-position">Save
                    </button>
                </div>
            </li>
            <li class="form-group">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button href="javascript:void(0)" class="btn btn-primary btn-block change_margin_widget"
                            id="change_margin_widget">Change Margin Widget
                    </button>
                </div>
            </li>
            <li>
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button href="javascript:void(0)" class="btn btn-primary btn-block change_background"
                            id="change_background">Change Background
                    </button>
                </div>
            </li>
            <li>
                &nbsp;
            </li>

            <li class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6 control-label" for="disable_widget">Select border color
                    widget</label>

                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="row form-group">
                        <input name="disable_border_module" id="disable_border_module" type="checkbox" checked>
                    </div>
                    <div class="row">
                        <input type="color" class="input-sm" style="width: 25%" name="colorpickerFieldSelect"
                               id="colorpickerFieldSelect"/>

                    </div>
                </div>

            </li>

            <li class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6 control-label" for="disable_widget">Disable Widget</label>
                <input class="noStyle" name="disable_widget" id="disable_widget" type="checkbox" checked>
            </li>
            <li class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6" for="editing">Enable Editing:</label>
                <input class="noStyle" name="editing" id="editing" type="checkbox" checked>
            </li>
            <li class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6" for="hide_setting">Hiden Position Setting:</label>
                <input class="noStyle" name="hide_setting" id="hide_setting" type="checkbox" checked>
            </li>
            <li class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6" for="hide_module_item_setting">Hiden Module Setting:</label>
                <input class="noStyle" name="hide_module_item_setting" id="hide_module_item_setting" type="checkbox"
                       checked>
            </li>
            <li class="form-group">
                <label class="col-lg-6 col-md-6 col-sm-6" for="full_height">Full height:</label>
                <input class="noStyle" id="full_height" name="full_height" type="checkbox">
            </li>


        </ul>

    </li>
    <li><a href="javascript:void(0)">Data <i class=st-files></i></a>
        <ul class="nav sub">
            <li class="item-element item-data-source-ui" data-add-on-type="binding-source"><a href="javascript:void(0)"><i
                        class=st-files></i> BindingSource</a>
        </ul>
    </li>
    <li class="data-set"><a href="javascript:void(0)">Data set <i class=st-files></i></a>

        <ul class="nav sub">
            <?php foreach ($currentDataSource as $item) { ?>
                <li class="item-element item-data-source-ui" data-add-on-id="<?php echo $item->datasource->id; ?>"
                    data-add-on-type="binding-source">
                    <a href="javascript:void(0)"><i class=st-files></i><?php echo $item->datasource->title; ?></a>
                    <ul class="nav sub">
                        <?php foreach ($item->listField as $column => $columnValue) { ?>
                            <li class="item-element item-data-column" data-column-name="<?php echo $column ?>"><a
                                    href="javascript:void(0)"><i class=st-files></i> <?php echo $column ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        </ul>
    </li>
    <li class=""><a class="list-menu" href="javascript:void(0)">Pages <i class=st-files></i><span
                class="manager-menu"><i class="fa-list-alt" menu="menu"></i></span> </a>
        <ul class="nav sub">

            <?php foreach ($a_listMenu as $menu_type => $menus) { ?>
                <li><a href="javascript:void(0)"><i
                            class=st-files></i> <?php echo JString::sub_string($menu_type, 12) ?></a>

                    <?php
                    $menu_root=new stdClass();
                    foreach ($menus as $key=>$item)
                    {
                        if($item->parent_id==$item->id)
                        {
                            $menu_root=$item;
                            unset($menus[$key]);
                            break;
                        }
                    }

                    $children = array();

                    // First pass - collect children
                    foreach ($menus as $v)
                    {
                        $pt = $v->parent_id;
                        $list = @$children[$pt] ? $children[$pt] : array();
                        array_push($list, $v);
                        $children[$pt] = $list;
                    }

                    $menus= treerecurse($menu_root->id,array(),$children,99,0);
                    create_html_list($menus,$menuItemIdActive);

                    ?>

                </li>
            <?php } ?>
            <li><a href="javascript:void(0)"><i class=st-files></i> system pages</a>
                <ul class="nav sub">
                    <li><a href=timeline.html><i class=ec-clock></i> Timeline page</a></li>
                    <li><a href=invoice.html><i class=st-file></i> Invoice</a></li>
                    <li><a href=profile.html><i class=ec-user></i> Profile page</a></li>
                    <li><a href=search.html><i class=ec-search></i> Search page</a></li>
                    <li><a href=blank.html><i class=im-file4></i> Blank page</a></li>
                    <li><a href=login.html><i class=ec-locked></i> Login page</a></li>
                    <li><a href=lockscreen.html><i class=ec-locked></i> Lock screen</a></li>
                </ul>
            </li>
            <li><a href="javascript:void(0)"><i class=st-files></i> Error pages</a>
                <ul class="nav sub">
                    <li><a href=400.html><i class=st-file-broken></i> Error 400</a></li>
                    <li><a href=401.html><i class=st-file-broken></i> Error 401</a></li>
                    <li><a href=403.html><i class=st-file-broken></i> Error 403</a></li>
                    <li><a href=404.html><i class=st-file-broken></i> Error 404</a></li>
                    <li><a href=405.html><i class=st-file-broken></i> Error 405</a></li>
                    <li><a href=500.html><i class=st-file-broken></i> Error 500</a></li>
                    <li><a href=503.html><i class=st-file-broken></i> Error 503</a></li>
                    <li><a href=offline.html><i class=st-window></i> Offline</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li><a href="javascript:void(0)">add module <i class=im-paragraph-justify></i></a>
        <ul class="nav sub list-module">
            <?php foreach ($modules as $module) { ?>
                <li data-module-id="<?php echo $module->extension_id ?>" class="item-element module_item"><a
                        href="javascript:void(0)"><i class=ec-pencil2></i><?php echo $module->text ?></a></li>
            <?php } ?>
        </ul>
    </li>
    <li><a href="javascript:void(0)">List plugin <i class=im-paragraph-justify></i></a>
        <ul class="nav sub list-plugin">
            <?php foreach ($listPlugin as $key => $plugins) { ?>
                <li><a href="javascript:void(0)"><?php echo $key ?> <i class=im-paragraph-justify></i></a>
                    <ul class="nav sub">
                        <?php foreach ($plugins as $plugin) { ?>
                            <li data-plugin-id="<?php echo $plugin->id ?>" title="<?php echo $plugin->title ?>"
                                class="plugin_item"><a href="javascript:void(0)">
                                    <div class="pull-left"><i
                                            class="en-shuffle"></i><?php echo JString::sub_string($plugin->name, 7) ?>
                                    </div>
                                    <i class="st-settings pull-right"></i>

                                    <div style="width: 69px" class="pull-right"><input name="enable_plugin"
                                                                                       id="enable_plugin"
                                                                                       class="plugin_item"
                                                                                       title="<?php echo $plugin->title ?>"
                                                                                       type="checkbox"
                                                                                       data-size="mini" <?php echo $plugin->issystem ? 'disabled' : '' ?>   <?php echo $plugin->enabled ? 'checked' : '' ?>>
                                    </div>
                                </a>

                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        </ul>
    </li>

    <li><a href="javascript:void(0)">List Component <i class=im-paragraph-justify></i></a>
        <ul class="nav sub list-plugin">
            <?php foreach ($listLayOut as $com => $views) { ?>
                <li><a href="javascript:void(0)"><?php echo $com ?> <i class=im-paragraph-justify></i></a>
                    <ul class="nav sub">
                        <?php foreach ($views as $view => $layouts) { ?>
                            <li>
                                <a href="javascript:void(0)"><i
                                        class=ec-pencil2></i><?php echo JString::sub_string(JText::_($view), 7) ?></a>
                                <ul class="nav sub">
                                    <?php foreach ($layouts as $layout) { ?>
                                        <li data-component="<?php echo $com ?>" data-view="<?php echo $view ?>"
                                            data-layout="<?php echo $layout ?>" title="<?php echo JText::_($layout) ?>"
                                            class="item-element view_item">
                                            <a href="javascript:void(0)"><i
                                                    class=ec-pencil2></i><?php echo JString::sub_string(JText::_($layout), 7) ?>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>



                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>
        </ul>
    </li>
    <li><a href="javascript:void(0)">Elements <i class=im-paragraph-justify></i><span class="element-property-manager"><i element-config="global_element_config" class="fa-list-alt"></i></span></a>
        <ul class="nav sub">
            <?php foreach ($foldersElement as $element) { ?>
                <li>
                    <?php
                    $listFileElement = JFolder::files(JPATH_ROOT . '/media/elements/' . $element, '.php');
                    ?>
                    <a href=forms.html><i class=ec-pencil2></i> <?php echo $element ?></a>
                    <ul class="nav sub">
                        <?php foreach ($listFileElement as $fileElement) { ?>
                            <li class="item-element item-element-ui"
                                data-element-type="<?php echo str_replace('.php', '', $fileElement) ?>"
                                data-element-path="media/elements/<?php echo $element . '/' . $fileElement ?>">
                                <?php
                                $path_parts = pathinfo($fileElement);
                                ?>
                                <a href="javascript:void(0)"><i class=ec-pencil2></i> <?php echo $path_parts['filename'] ?><span class="ui-property-manager"><i element-config="<?php echo $path_parts['filename'] ?>"  class="fa-list-alt"></i></span></a>

                            </li>
                        <?php } ?>
                    </ul>

                </li>
            <?php } ?>
        </ul>
    </li>
</ul>

<?php

function create_html_list($nodes,$menuItemIdActive)
{
echo '<ul class="nav sub">';

foreach ($nodes as $menu) {
$childNodes = $menu->children;
ob_start();


?>
<li class="<?php echo $menuItemIdActive == $menu->id ? ' menu-active ' : '' ?>"><a
        href="<?php echo JUri::root(). '?Itemid=' . $menu->id ?>"><i
            class="<?php echo $menu->icon ?>"></i> <?php echo $menu->title ?></a>
    <?php
    echo ob_get_clean();
    if (count($childNodes) > 0) {
        create_html_list($childNodes, $menuItemIdActive);
    }
    echo "</li>";
    }
    echo '</ul>';
    }

   function treerecurse($id,  $list, &$children, $maxlevel = 9999, $level = 0)
    {
        if (@$children[$id] && $level <= $maxlevel)
        {

            foreach ($children[$id] as $v)
            {
                $id = $v->id;
                $list[$id] = $v;
                $list[$id]->children = @$children[$id];
                unset($children[$id]);
                $list = treerecurse($id,$list, $children, $maxlevel, $level + 1);
            }
        }
        return $list;
    }

    include JPATH_ROOT . '/templates/sprflat/html/layouts/joomla/system/tooleditstyle.php';
    ?>
    <?php
    include JPATH_ROOT . '/templates/sprflat/html/layouts/joomla/system/blockproperty.php';
    include JPATH_ROOT . '/libraries/adminfrontend/rightsidebar.php';
    ?>
    <style type="text/css">
        .input-title {
            border: 1px #ccc dotted;
        }
    </style>


    <style type="text/css">
        .div-loading {
            display: none;
            background: url("<?php echo JUri::root() ?>/global_css_images_js/images/loading.gif") center center no-repeat;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%
        }
    </style>