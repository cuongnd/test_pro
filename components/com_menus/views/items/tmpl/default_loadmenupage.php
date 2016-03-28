<?php
$website=JFactory::getWebsite();

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
?>
<ul class="nav sub hide">

    <?php foreach ($a_listMenu as $menu_type => $menus) { ?>
        <li><a href="javascript:void(0)" class="notExpand link_javascript"><i
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
        <ul class="nav sub hide">
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
<?php
function create_html_list($nodes,$menuItemIdActive)
{
    echo '<ul class="nav sub">';

    foreach ($nodes as $menu) {
        $childNodes = $menu->children;
        ob_start();

         $total_child_nodes= count($childNodes);
        ?>
    <li class="<?php echo $menuItemIdActive == $menu->id ? ' menu-active ' : '' ?>"><a class=" <?php echo $total_child_nodes?' notExpand link_javascript ':'' ?>"
            href="<?php echo $total_child_nodes?'javascript:void(0)':JUri::root(). '?Itemid=' . $menu->id ?>"><i
                class="<?php echo $menu->icon ?>"></i> <?php echo $menu->title ?></a>
        <?php
        echo ob_get_clean();
        if (is_array($childNodes) && count($childNodes) > 0) {
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
