<?php
$supperAdmin=JFactory::isSupperAdmin();
?>
    <ul class="nav" id="menu">
        <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">System <span
                    class="caret"></span></a>
            <ul class="dropdown-menu">
                <li><a href="index.php" class="menu-cpanel">Control Panel</a></li>
                <li class="divider"><span></span></li>
                <li><a href="index.php?option=com_config" class="menu-config">Global Configuration</a></li>
                <li class="divider"><span></span></li>
                <li><a href="index.php?option=com_checkin" class="menu-checkin">Global Check-in</a></li>
                <li class="divider"><span></span></li>
                <li><a href="index.php?option=com_cache" class="menu-clear">Clear Cache</a></li>
                <li><a href="index.php?option=com_cache&amp;view=purge" class="menu-purge">Purge Expired Cache</a></li>
                <li class="divider"><span></span></li>
                <li><a href="index.php?option=com_admin&amp;view=sysinfo" class="menu-info">System Information</a></li>
            </ul>
        </li>
        <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Users <span
                    class="caret"></span></a>
            <ul class="dropdown-menu">
                <li class="dropdown-submenu"><a href="index.php?option=com_users&amp;view=users" data-toggle="dropdown"
                                                class="dropdown-toggle menu-user">User Manager</a>
                    <ul class="dropdown-menu menu-component" id="menu-com-users-users">
                        <li><a href="index.php?option=com_users&amp;task=user.add" class="menu-newarticle">Add New
                                User</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown-submenu"><a href="index.php?option=com_users&amp;view=groups" data-toggle="dropdown"
                                                class="dropdown-toggle menu-groups">Groups</a>
                    <ul class="dropdown-menu menu-component" id="menu-com-users-groups">
                        <li><a href="index.php?option=com_users&amp;task=group.add" class="menu-newarticle">Add New
                                Group</a></li>
                    </ul>
                </li>
                <li class="dropdown-submenu"><a href="index.php?option=com_users&amp;view=levels" data-toggle="dropdown"
                                                class="dropdown-toggle menu-levels">Access Levels</a>
                    <ul class="dropdown-menu menu-component" id="menu-com-users-levels">
                        <li><a href="index.php?option=com_users&amp;task=level.add" class="menu-newarticle">Add New
                                Access
                                Level</a></li>
                    </ul>
                </li>
                <li class="divider"><span></span></li>
                <li class="dropdown-submenu"><a href="index.php?option=com_users&amp;view=notes" data-toggle="dropdown"
                                                class="dropdown-toggle menu-user-note">User Notes</a>
                    <ul class="dropdown-menu menu-component" id="menu-com-users-notes">
                        <li><a href="index.php?option=com_users&amp;task=note.add" class="menu-newarticle">Add User
                                Note</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown-submenu"><a
                        href="index.php?option=com_categories&amp;view=categories&amp;extension=com_users"
                        data-toggle="dropdown" class="dropdown-toggle menu-category">User Note Categories</a>
                    <ul class="dropdown-menu menu-component" id="menu-com-categories-categories-com-users">
                        <li><a href="index.php?option=com_categories&amp;task=category.add&amp;extension=com_users"
                               class="menu-newarticle">Add New Category</a></li>
                    </ul>
                </li>
                <li class="divider"><span></span></li>
                <li><a href="index.php?option=com_users&amp;view=mail" class="menu-massmail">Mass Mail Users</a></li>
            </ul>
        </li>
        <?php
        if ($user->authorise('core.manage', 'com_menus'))
        {
        ?>
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle">Menus <span class="caret"></span></a>
            <?php
            $createMenu = $shownew && $user->authorise('core.create', 'com_menus');

            ?>
            <ul class="dropdown-menu">
                <li class="dropdown-submenu"><a href="index.php?option=com_menus&amp;view=menus" data-toggle="dropdown"
                                                class="dropdown-toggle menu-menumgr">Menu Manager</a>



                    <?php

                    if ($createMenu) {
                        ?>
                        <ul class="dropdown-menu menu-component" id="menu-com-menus-menus">
                            <li><a href="index.php?option=com_menus&amp;view=menu&amp;layout=edit"
                                   class="menu-newarticle">Add
                                    New Menu</a></li>
                        </ul>
                    <?php
                    }
                    ?>
                </li>
                <li class="divider"><span></span></li>
                <?php

                // Menu Types
                foreach (ModMenuHelper::getMenus() as $menuType) {

                    ?>
                    <li ><a
                            href="index.php?option=com_menus&amp;view=items&amp;menu_type_id=<?php echo $menuType->id ?>"
                             class=" menu-menu"><?php echo $menuType->title ?> <i
                                class="<?php echo $menuType->home ? 'icon-home' : '' ?>"></i></a>



                        <?php


                        if ($createMenu) {
                            ?>
                            <ul class="dropdown-menu menu-component"
                                id="menu-com-menus-items-<?php echo $menuType->id ?>">
                                <li>
                                    <a href="index.php?option=com_menus&amp;view=item&amp;layout=edit&amp;menutype=main-menu"
                                       class="menu-newarticle">Add New Menu Item</a></li>
                            </ul>
                        <?php
                        }
                        ?>
                    </li>
                <?php
                }

                }

                ?>
            </ul>
        </li>
        <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Content <span
                    class="caret"></span></a>
            <ul class="dropdown-menu">
                <li class="dropdown-submenu"><a href="index.php?option=com_content"
                                                class=" menu-article">Article Manager</a>
                    <ul class="dropdown-menu menu-component" id="menu-com-content">
                        <li><a href="index.php?option=com_content&amp;task=article.add" class="menu-newarticle">Add New
                                Article</a></li>
                    </ul>
                </li>
                <li class="dropdown-submenu"><a href="index.php?option=com_categories&amp;extension=com_content"
                                                data-toggle="dropdown" class="dropdown-toggle menu-category">Category
                        Manager</a>
                    <ul class="dropdown-menu menu-component" id="menu-com-categories-com-content">
                        <li><a href="index.php?option=com_categories&amp;task=category.add&amp;extension=com_content"
                               class="menu-newarticle">Add New Category</a></li>
                    </ul>
                </li>
                <li><a href="index.php?option=com_content&amp;view=featured" class="menu-featured">Featured Articles</a>
                </li>
                <li class="divider"><span></span></li>
                <li><a href="index.php?option=com_media" class="menu-media">Media Manager</a></li>
            </ul>
        </li>
        <?php
        $components = ModMenuHelper::getComponents(true, $params);
        $children = array();
        if (!empty($components)) {

            $children = array();

            // First pass - collect children
            foreach ($components as $v) {
                $pt = $v->parent_id;
                $list = @$children[$pt] ? $children[$pt] : array();
                array_push($list, $v);
                $children[$pt] = $list;
            }

        }

        $html = '';
        $firstItemKey = reset(array_keys($children));
        ?>
        <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle">Components <span class="caret"></span></a>
            <?php echo treeReCurseMenu($firstItemKey, $html, $children); ?>
        </li>
        <?php
        $im = $user->authorise('core.manage', 'com_installer');
        $cm = $user->authorise('core.manage', 'com_components');
        $mm = $user->authorise('core.manage', 'com_modules');
        $pm = $user->authorise('core.manage', 'com_plugins');
        $tm = $user->authorise('core.manage', 'com_templates');
        $lm = $user->authorise('core.manage', 'com_languages');

        if ($im || $mm || $pm || $tm || $lm) {
            $menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_EXTENSIONS'), '#'), true);

            if ($im) {
                $menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_EXTENSION_MANAGER'), 'index.php?option=com_installer', 'class:install'));
            }

            if ($im && ($mm || $pm || $tm || $lm)) {
                $menu->addSeparator();
            }

            if ($cm && $supperAdmin) {
                $menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_COMPONENT_MANAGER'), 'index.php?option=com_components', 'class:component'));
            }
            if ($mm) {
                $menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_MODULE_MANAGER'), 'index.php?option=com_modules', 'class:module'));
            }

            if ($pm) {
                $menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_PLUGIN_MANAGER'), 'index.php?option=com_plugins', 'class:plugin'));
            }

            if ($tm) {
                $menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_TEMPLATE_MANAGER'), 'index.php?option=com_templates', 'class:themes'));
            }

            if ($lm) {
                $menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_LANGUAGE_MANAGER'), 'index.php?option=com_languages', 'class:language'));
            }

            $menu->getParent();
        }
        if ($im || $mm || $pm || $tm || $lm) {
            ?>

            <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Extensions <span
                        class="caret"></span></a>
                <ul class="dropdown-menu">
                    <?php
                    if ($im) {
                        ?>
                        <li><a href="index.php?option=com_installer" class="menu-install">Extension Manager</a></li>
                    <?php
                    }

                    if ($im && ($mm || $pm || $tm || $lm)) {
                        ?>
                        <li class="divider"><span></span></li>
                    <?php
                    }

                    if ($cm) {
                        ?>
                        <li><a href="index.php?option=com_components" class="menu-component">Components Manager</a></li>
                    <?php
                    }
                    if ($mm) {
                        ?>
                        <li><a href="index.php?option=com_modules" class="menu-module">Module Manager</a></li>

                    <?php
                    }

                    if ($pm) {
                        ?>
                        <li><a href="index.php?option=com_plugins" class="menu-plugin">Plugin Manager</a></li>
                    <?php
                    }

                    if ($tm) {
                        ?>
                        <li><a href="index.php?option=com_templates" class="menu-themes">Template Manager</a></li>
                    <?php
                    }

                    if ($lm) {
                        ?>
                        <li><a href="index.php?option=com_languages" class="menu-language">Language Manager</a></li>
                    <?php
                    }

                    ?>
                </ul>
            </li>
        <?php } ?>
    </ul>

<?php
function treeReCurseMenu($id,&$html,&$children, $maxLevel = 9999, $level = 0)
{
    if (@$children[$id] && $level <= $maxLevel)
    {
        $html.='<ul class="dropdown-menu menu-component" id="menu-com-users-users">';
        foreach ($children[$id] as $v)
        {
            $id = $v->id;
            $html.='<li class="'.(count($children[$id])?'dropdown-submenu':'').'"><a href="'.JUri::root().$v->link.'&Itemid='.$id.'" class="menu-newarticle">'.$v->title.'</a>';
            treeReCurseMenu($id,$html,$children, $maxLevel, $level + 1).'</li>';

        }
        $html.='</ul>';
    }
    return $html;

}
?>
