<?php
JHtml::_('jquery.framework');
$enableEditWebsite = UtilityHelper::getEnableEditWebsite();
$app=JFactory::getApplication();
$website=JFactory::getWebsite();
require_once JPATH_ROOT . '/modules/website/website_'.$website->website_id.'/mod_supper_admin_menu/helper.php';
require_once JPATH_ROOT.'/components/com_menus/helpers/menus.php';
$menu_type_id = $params->get('menu_type_id');
$list_menu_item=MenusHelperFrontEnd::get_list_all_menu_item_by_menu_type_id($menu_type_id);
$root_menu_item_id=MenusHelperFrontEnd::get_root_menu_item_id_by_menu_type_id($menu_type_id);
$menu=$app->getMenu();
$active_menu_item=$menu->getActive();
$active_menu_item_id=$active_menu_item->id;
?>

    <div id="sidebar_front_end">
        <div class="sidebar-inner-front-end">
            <div class="menu-menu-side-nav">

                <?php
                $html='';
                ModMenuHelper::create_html_list_left_menu($html,$root_menu_item_id, $list_menu_item,$active_menu_item_id);
                echo $html;

                ?>


            </div>
        </div>
    </div>

<?php



?>