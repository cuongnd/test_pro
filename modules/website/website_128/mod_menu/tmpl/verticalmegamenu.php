<?php
JHtml::_('jquery.framework');
$enableEditWebsite = UtilityHelper::getEnableEditWebsite();
$app=JFactory::getApplication();
$website=JFactory::getWebsite();
require_once JPATH_ROOT . '/modules/website/website_'.$website->website_id.'/mod_menu/helper.php';
$menu_type_id = $params->get('menu_type_id');
$list_menu_item=MenusHelperFrontEnd::get_list_all_menu_item_by_menu_type_id($menu_type_id);
echo "<pre>";
print_r($list_menu_item);
echo "</pre>";
die;
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('menu.*')
    ->from('#__menu AS menu')
    ->where('menu.id=menu.parent_id')
    ->where('menu.menu_type_id=' . (int)$menu_type_id)
;

$menu_item_root = $db->setQuery($query)->loadObject();
$query = $db->getQuery(true);
$query->select('menu.*')
    ->from('#__menu AS menu')
    ->where('menu.id!=menu.parent_id')
    ->where('menu.menu_type_id=' . (int)$menu_type_id);
if(!$enableEditWebsite)
{
    $query->where('menu.hidden=0');
}
$list_menu_item=$db->setQuery($query)->loadObjectList();
$menu=$app->getMenu();
$active_menu_item=$menu->getActive();
$active_menu_item_id=$active_menu_item->id;
?>

    <div id="sidebar_front_end">
        <div class="sidebar-inner-front-end">
            <div class="menu-menu-side-nav">

                <?php
                $html='';
                ModMenuHelper::create_html_list_left_menu($html,$menu_item_root->id, $list_menu_item,$active_menu_item_id);
                echo $html;

                ?>


            </div>
        </div>
    </div>

<?php



?>