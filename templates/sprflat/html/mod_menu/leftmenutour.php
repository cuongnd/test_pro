<div id="sidebar_front_end">
    <div class="sidebar-inner-front-end">
        <div class="menu-menu-side-nav">

            <?php

            $children = array();

            // First pass - collect children
            foreach ($list as $v) {
                $pt = $v->parent_id;
                $a_list = @$children[$pt] ? $children[$pt] : array();
                array_push($a_list, $v);
                $children[$pt] = $a_list;
            }
            $menus = treerecurse_left_menu(172, array(), $children, 99, 0);
            create_html_list_left_menu($menus, $menuItemIdActive);

            ?>


        </div>
    </div>
</div>

<?php

function create_html_list_left_menu($nodes, $menuItemIdActive,$level=0)
{
    echo '<ul '.($level==0?' id="sideNav_front_end"':'').' class="nav sub">';

    foreach ($nodes as $menu) {
        $childNodes = $menu->children;
        ob_start();


        ?>
    <li class="<?php echo $menuItemIdActive == $menu->id ? ' menu-active ' : '' ?>"><a
            href="<?php echo JUri::root() . '?Itemid=' . $menu->id ?>"><i
                class="<?php echo $menu->icon ?>"></i> <?php echo $menu->title ?></a>
        <?php
        echo ob_get_clean();
        if (count($childNodes) > 0) {
            create_html_list_left_menu($childNodes, $menuItemIdActive,$level++);
        }
        echo "</li>";
    }
    echo '</ul>';
}

function treerecurse_left_menu($id, $list, &$children, $maxlevel = 9999, $level = 0)
{
    if (@$children[$id] && $level <= $maxlevel) {

        foreach ($children[$id] as $v) {
            $id = $v->id;
            $list[$id] = $v;
            $list[$id]->children = @$children[$id];
            unset($children[$id]);
            $list = treerecurse_left_menu($id, $list, $children, $maxlevel, $level + 1);
        }
    }
    return $list;
}

?>