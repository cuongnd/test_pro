<?php
$supperAdmin=JFactory::isSupperAdmin();
?>
<ul class="nav" id="menu">
    <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">System <span class="caret"></span></a><ul class="dropdown-menu">
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
                    <li><a href="index.php?option=com_users&amp;task=user.add" class="menu-newarticle">Add New User</a>
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
                    <li><a href="index.php?option=com_users&amp;task=level.add" class="menu-newarticle">Add New Access
                            Level</a></li>
                </ul>
            </li>
            <li class="divider"><span></span></li>
            <li class="dropdown-submenu"><a href="index.php?option=com_users&amp;view=notes" data-toggle="dropdown"
                                            class="dropdown-toggle menu-user-note">User Notes</a>
                <ul class="dropdown-menu menu-component" id="menu-com-users-notes">
                    <li><a href="index.php?option=com_users&amp;task=note.add" class="menu-newarticle">Add User Note</a>
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

        if ($createMenu)
        {
            ?>
            <ul class="dropdown-menu menu-component" id="menu-com-menus-menus">
                <li><a href="index.php?option=com_menus&amp;view=menu&amp;layout=edit" class="menu-newarticle">Add
                        New Menu</a></li>
            </ul>
            <?php
        }
        ?>
    </li>
        <li class="divider"><span></span></li>
        <?php

        // Menu Types
        foreach (ModMenuHelper::getMenus() as $menuType)
        {

            ?>
            <li class="dropdown-submenu"><a href="index.php?option=com_menus&amp;view=items&amp;menu_type_id=<?php echo $menuType->id ?>"
                                            data-toggle="dropdown" class="dropdown-toggle menu-menu"><?php echo $menuType->title ?> <i
                        class="<?php echo $menuType->home?'icon-home':'' ?>"></i></a>



            <?php


            if ($createMenu)
            {
                ?>
                <ul class="dropdown-menu menu-component" id="menu-com-menus-items-<?php echo $menuType->id ?>">
                    <li><a href="index.php?option=com_menus&amp;view=item&amp;layout=edit&amp;menutype=main-menu"
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
    <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Content <span class="caret"></span></a><ul class="dropdown-menu">
            <li class="dropdown-submenu"><a href="index.php?option=com_content" data-toggle="dropdown" class="dropdown-toggle menu-article">Article Manager</a><ul class="dropdown-menu menu-component" id="menu-com-content">
                    <li><a href="index.php?option=com_content&amp;task=article.add" class="menu-newarticle">Add New Article</a></li>
                </ul>
            </li>
            <li class="dropdown-submenu"><a href="index.php?option=com_categories&amp;extension=com_content" data-toggle="dropdown" class="dropdown-toggle menu-category">Category Manager</a><ul class="dropdown-menu menu-component" id="menu-com-categories-com-content">
                    <li><a href="index.php?option=com_categories&amp;task=category.add&amp;extension=com_content" class="menu-newarticle">Add New Category</a></li>
                </ul>
            </li>
            <li><a href="index.php?option=com_content&amp;view=featured" class="menu-featured">Featured Articles</a></li>
            <li class="divider"><span></span></li>
            <li><a href="index.php?option=com_media" class="menu-media">Media Manager</a></li>
        </ul>
    </li>
    <?php
    $components = ModMenuHelper::getComponents(true,$params);
    $children = array();
    if(!empty($components)){

        $children = array();

        // First pass - collect children
        foreach ($components as $v)
        {
            $pt = $v->parent_id;
            $list = @$children[$pt] ? $children[$pt] : array();
            array_push($list, $v);
            $children[$pt] = $list;
        }

    }

    $html='';
    ?>
    <li class="dropdown">
        <a href="#" data-toggle="dropdown" class="dropdown-toggle">Components <span class="caret"></span></a>
        <?php  echo treeReCurseMenu(124,$html,$children);  ?>
    </li>
    <?php
    $im = $user->authorise('core.manage', 'com_installer');
    $cm = $user->authorise('core.manage', 'com_components');
    $mm = $user->authorise('core.manage', 'com_modules');
    $pm = $user->authorise('core.manage', 'com_plugins');
    $tm = $user->authorise('core.manage', 'com_templates');
    $lm = $user->authorise('core.manage', 'com_languages');

    if ($im || $mm || $pm || $tm || $lm)
    {
        $menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_EXTENSIONS'), '#'), true);

        if ($im&&$supperAdmin)
        {
            $menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_EXTENSION_MANAGER'), 'index.php?option=com_installer', 'class:install'));
        }

        if ($im && ($mm || $pm || $tm || $lm))
        {
            $menu->addSeparator();
        }

        if ($cm&&$supperAdmin)
        {
            $menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_COMPONENT_MANAGER'), 'index.php?option=com_components', 'class:component'));
        }
        if ($mm)
        {
            $menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_MODULE_MANAGER'), 'index.php?option=com_modules', 'class:module'));
        }

        if ($pm)
        {
            $menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_PLUGIN_MANAGER'), 'index.php?option=com_plugins', 'class:plugin'));
        }

        if ($tm)
        {
            $menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_TEMPLATE_MANAGER'), 'index.php?option=com_templates', 'class:themes'));
        }

        if ($lm)
        {
            $menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_LANGUAGE_MANAGER'), 'index.php?option=com_languages', 'class:language'));
        }

        $menu->getParent();
    }
    if ($im || $mm || $pm || $tm || $lm)
    {
    ?>

    <li class="dropdown"><a href="#" data-toggle="dropdown" class="dropdown-toggle">Extensions <span class="caret"></span></a><ul class="dropdown-menu">
            <?php
            if ($im&&$supperAdmin)
            {
                ?>
                <li><a href="index.php?option=com_installer" class="menu-install">Extension Manager</a></li>
                <?php
            }

            if ($im && ($mm || $pm || $tm || $lm))
            {
                ?>
                <li class="divider"><span></span></li>
                <?php
            }

            if ($cm&&$supperAdmin)
            {
                ?>
                <li><a href="index.php?option=com_components" class="menu-component">Components Manager</a></li>
                <?php
            }
            if ($mm)
            {
                ?>
                <li><a href="index.php?option=com_modules" class="menu-module">Module Manager</a></li>

            <?php
            }

            if ($pm)
            {
                ?>
                <li><a href="index.php?option=com_plugins" class="menu-plugin">Plugin Manager</a></li>
            <?php
            }

            if ($tm)
            {
                ?>
                <li><a href="index.php?option=com_templates" class="menu-themes">Template Manager</a></li>
                <?php
            }

            if ($lm)
            {
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
/**
 * @package     Joomla.Administrator
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/* @var $menu JAdminCSSMenu */

$shownew = (boolean) $params->get('shownew', 1);
$showhelp = $params->get('showhelp', 1);
$user = JFactory::getUser();
$lang = JFactory::getLanguage();

/*
 * Site Submenu
 */
$menu->addChild(new JMenuNode(JText::_('MOD_MENU_SYSTEM'), '#'), true);
$menu->addChild(new JMenuNode(JText::_('MOD_MENU_CONTROL_PANEL'), 'index.php', 'class:cpanel'));

if ($user->authorise('core.admin'))
{
	$menu->addSeparator();
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_CONFIGURATION'), 'index.php?option=com_config', 'class:config'));
}

if ($user->authorise('core.manage', 'com_checkin'))
{
	$menu->addSeparator();
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_GLOBAL_CHECKIN'), 'index.php?option=com_checkin', 'class:checkin'));
}

if ($user->authorise('core.manage', 'com_cache'))
{
	$menu->addSeparator();
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_CLEAR_CACHE'), 'index.php?option=com_cache', 'class:clear'));
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_PURGE_EXPIRED_CACHE'), 'index.php?option=com_cache&view=purge', 'class:purge'));
}

if ($user->authorise('core.admin'))
{
	$menu->addSeparator();
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_SYSTEM_INFORMATION'), 'index.php?option=com_admin&view=sysinfo', 'class:info'));
}

$menu->getParent();

/*
 * Users Submenu
 */
if ($user->authorise('core.manage', 'com_users'))
{
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_COM_USERS_USERS'), '#'), true);
	$createUser = $shownew && $user->authorise('core.create', 'com_users');
	$createGrp  = $user->authorise('core.admin', 'com_users');

	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_COM_USERS_USER_MANAGER'), 'index.php?option=com_users&view=users', 'class:user'), $createUser);

	if ($createUser)
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_COM_USERS_ADD_USER'), 'index.php?option=com_users&task=user.add', 'class:newarticle'));
		$menu->getParent();
	}

	if ($createGrp)
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_COM_USERS_GROUPS'), 'index.php?option=com_users&view=groups', 'class:groups'), $createUser);

		if ($createUser)
		{
			$menu->addChild(new JMenuNode(JText::_('MOD_MENU_COM_USERS_ADD_GROUP'), 'index.php?option=com_users&task=group.add', 'class:newarticle'));
			$menu->getParent();
		}

		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_COM_USERS_LEVELS'), 'index.php?option=com_users&view=levels', 'class:levels'), $createUser);

		if ($createUser)
		{
			$menu->addChild(new JMenuNode(JText::_('MOD_MENU_COM_USERS_ADD_LEVEL'), 'index.php?option=com_users&task=level.add', 'class:newarticle'));
			$menu->getParent();
		}
	}

	$menu->addSeparator();
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_COM_USERS_NOTES'), 'index.php?option=com_users&view=notes', 'class:user-note'), $createUser);

	if ($createUser)
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_COM_USERS_ADD_NOTE'), 'index.php?option=com_users&task=note.add', 'class:newarticle'));
		$menu->getParent();
	}

	$menu->addChild(
		new JMenuNode(
			JText::_('MOD_MENU_COM_USERS_NOTE_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_users', 'class:category'),
		$createUser
	);

	if ($createUser)
	{
		$menu->addChild(
			new JMenuNode(
				JText::_('MOD_MENU_COM_CONTENT_NEW_CATEGORY'), 'index.php?option=com_categories&task=category.add&extension=com_users',
				'class:newarticle'
			)
		);
		$menu->getParent();
	}

	$menu->addSeparator();
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_MASS_MAIL_USERS'), 'index.php?option=com_users&view=mail', 'class:massmail'));

	$menu->getParent();
}

/*
 * Menus Submenu
 */
if ($user->authorise('core.manage', 'com_menus'))
{
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_MENUS'), '#'), true);
	$createMenu = $shownew && $user->authorise('core.create', 'com_menus');

	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_MENU_MANAGER'), 'index.php?option=com_menus&view=menus', 'class:menumgr'), $createMenu);

	if ($createMenu)
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_MENU_MANAGER_NEW_MENU'), 'index.php?option=com_menus&view=menu&layout=edit', 'class:newarticle'));
		$menu->getParent();
	}

	$menu->addSeparator();

	// Menu Types
	foreach (ModMenuHelper::getMenus() as $menuType)
	{
		$alt = '*' . $menuType->sef . '*';

		if ($menuType->home == 0)
		{
			$titleicon = '';
		}
		elseif ($menuType->home == 1 && $menuType->language == '*')
		{
			$titleicon = ' <i class="icon-home"></i>';
		}
		elseif ($menuType->home > 1)
		{
			$titleicon = ' <span>'
				. JHtml::_('image', 'mod_languages/icon-16-language.png', $menuType->home, array('title' => JText::_('MOD_MENU_HOME_MULTIPLE')), true)
				. '</span>';
		}
		else
		{
			$image = JHtml::_('image', 'mod_languages/' . $menuType->image . '.gif', null, null, true, true);

			if (!$image)
			{
				$image = JHtml::_('image', 'mod_languages/icon-16-language.png', $alt, array('title' => $menuType->title_native), true);
			}
			else
			{
				$image = JHtml::_('image', 'mod_languages/' . $menuType->image . '.gif', $alt, array('title' => $menuType->title_native), true);
			}

			$titleicon = ' <span>' . $image . '</span>';
		}

		$menu->addChild(
			new JMenuNode(
				$menuType->title, 'index.php?option=com_menus&view=items&menu_type_id=' . $menuType->id, 'class:menu', null, null, $titleicon
			),
			$createMenu
		);

		if ($createMenu)
		{
			$menu->addChild(
				new JMenuNode(
					JText::_('MOD_MENU_MENU_MANAGER_NEW_MENU_ITEM'), 'index.php?option=com_menus&view=item&layout=edit&menutype=' . $menuType->menutype,
					'class:newarticle')
			);
			$menu->getParent();
		}
	}

	$menu->getParent();
}

/*
 * Content Submenu
 */
if ($user->authorise('core.manage', 'com_content'))
{
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_COM_CONTENT'), '#'), true);
	$createContent = $shownew && $user->authorise('core.create', 'com_content');
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_COM_CONTENT_ARTICLE_MANAGER'), 'index.php?option=com_content', 'class:article'), $createContent);

	if ($createContent)
	{
		$menu->addChild(
			new JMenuNode(JText::_('MOD_MENU_COM_CONTENT_NEW_ARTICLE'), 'index.php?option=com_content&task=article.add', 'class:newarticle')
		);
		$menu->getParent();
	}

	$menu->addChild(
		new JMenuNode(
			JText::_('MOD_MENU_COM_CONTENT_CATEGORY_MANAGER'), 'index.php?option=com_categories&extension=com_content', 'class:category'),
		$createContent
	);

	if ($createContent)
	{
		$menu->addChild(
			new JMenuNode(JText::_('MOD_MENU_COM_CONTENT_NEW_CATEGORY'), 'index.php?option=com_categories&task=category.add&extension=com_content', 'class:newarticle')
		);
		$menu->getParent();
	}

	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_COM_CONTENT_FEATURED'), 'index.php?option=com_content&view=featured', 'class:featured'));

	if ($user->authorise('core.manage', 'com_media'))
	{
		$menu->addSeparator();
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_MEDIA_MANAGER'), 'index.php?option=com_media', 'class:media'));
	}

	$menu->getParent();
}

/*
 * Components Submenu
 */

// Get the authorised components and sub-menus.
$components = ModMenuHelper::getComponents(true,$params);

$children = array();
if(!empty($components)){

    $children = array();

    // First pass - collect children
    foreach ($components as $v)
    {
        $pt = $v->parent_id;
        $list = @$children[$pt] ? $children[$pt] : array();
        array_push($list, $v);
        $children[$pt] = $list;
    }

}

// Check if there are any components, otherwise, don't render the menu
if ($components)
{
    $menu->addChild(new JMenuNode(JText::_('MOD_MENU_COMPONENTS'), '#'), true);
   // treeReCurseMenu(124,',$html,$children);

    /*
    for($i=0;$i<count($components);$i++)
    {
        $component=$components[$i];
        $currentLevel=$component->level;
        $nextItem=$components[$i+1];
        $levelNextItem=$nextItem->level;
        $addChild=$levelNextItem<$currentLevel?true:false;

        $menu->addChild(new JMenuNode($component->text, 'index.php?option=com_content', 'class:article'), $addChild);
        $menu->addChild(
            new JMenuNode(JText::_('MOD_MENU_COM_CONTENT_NEW_ARTICLE'), 'index.php?option=com_content&task=article.add', 'class:newarticle')
        );
        if($addChild)
        {
            $menu->getParent();
        }
    }*/

    //addNode($menu,$components);
	$menu->getParent();
}
function treeReCurseMenu($id,&$html,&$children, $maxLevel = 9999, $level = 0)
{
    if (@$children[$id] && $level <= $maxLevel)
    {
        $html.='<ul class="dropdown-menu menu-component" id="menu-com-users-users">';
        foreach ($children[$id] as $v)
        {
            $id = $v->id;
            $html.='<li class="'.(count($children[$id])?'dropdown-submenu':'').'"><a href="'.$v->link.'" class="menu-newarticle">'.$v->title.'</a>';
            treeReCurseMenu($id,$html,$children, $maxLevel, $level + 1).'</li>';

        }
        $html.='</ul>';
    }
    return $html;

}




/*
 * Extensions Submenu
 */
$im = $user->authorise('core.manage', 'com_installer');
$cm = $user->authorise('core.manage', 'com_components');
$mm = $user->authorise('core.manage', 'com_modules');
$pm = $user->authorise('core.manage', 'com_plugins');
$tm = $user->authorise('core.manage', 'com_templates');
$lm = $user->authorise('core.manage', 'com_languages');

if ($im || $mm || $pm || $tm || $lm)
{
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_EXTENSIONS'), '#'), true);

	if ($im&&$supperAdmin)
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_EXTENSION_MANAGER'), 'index.php?option=com_installer', 'class:install'));
	}

	if ($im && ($mm || $pm || $tm || $lm))
	{
		$menu->addSeparator();
	}

	if ($cm&&$supperAdmin)
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_COMPONENT_MANAGER'), 'index.php?option=com_components', 'class:component'));
	}
	if ($mm)
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_MODULE_MANAGER'), 'index.php?option=com_modules', 'class:module'));
	}

	if ($pm)
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_PLUGIN_MANAGER'), 'index.php?option=com_plugins', 'class:plugin'));
	}

	if ($tm)
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_TEMPLATE_MANAGER'), 'index.php?option=com_templates', 'class:themes'));
	}

	if ($lm)
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_EXTENSIONS_LANGUAGE_MANAGER'), 'index.php?option=com_languages', 'class:language'));
	}

	$menu->getParent();
}

/*
 * Help Submenu
 */
if ($showhelp == 1)
{
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_HELP'), '#'), true);
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_HELP_JOOMLA'), 'index.php?option=com_admin&view=help', 'class:help'));
	$menu->addSeparator();

	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_HELP_SUPPORT_OFFICIAL_FORUM'), 'http://forum.joomla.org', 'class:help-forum', false, '_blank'));

	if ($forum_url = $params->get('forum_url'))
	{
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_HELP_SUPPORT_CUSTOM_FORUM'), $forum_url, 'class:help-forum', false, '_blank'));
	}

	$debug = $lang->setDebug(false);

	if ($lang->hasKey('MOD_MENU_HELP_SUPPORT_OFFICIAL_LANGUAGE_FORUM_VALUE') && JText::_('MOD_MENU_HELP_SUPPORT_OFFICIAL_LANGUAGE_FORUM_VALUE') != '')
	{
		$forum_url = 'http://forum.joomla.org/viewforum.php?f=' . (int) JText::_('MOD_MENU_HELP_SUPPORT_OFFICIAL_LANGUAGE_FORUM_VALUE');
		$lang->setDebug($debug);
		$menu->addChild(new JMenuNode(JText::_('MOD_MENU_HELP_SUPPORT_OFFICIAL_LANGUAGE_FORUM'), $forum_url, 'class:help-forum', false, '_blank'));
	}

	$lang->setDebug($debug);
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_HELP_DOCUMENTATION'), 'http://docs.joomla.org', 'class:help-docs', false, '_blank'));
	$menu->addSeparator();

	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_HELP_EXTENSIONS'), 'http://extensions.joomla.org', 'class:help-jed', false, '_blank'));
	$menu->addChild(
		new JMenuNode(JText::_('MOD_MENU_HELP_TRANSLATIONS'), 'http://community.joomla.org/translations.html', 'class:help-trans', false, '_blank')
	);
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_HELP_RESOURCES'), 'http://resources.joomla.org', 'class:help-jrd', false, '_blank'));
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_HELP_COMMUNITY'), 'http://community.joomla.org', 'class:help-community', false, '_blank'));
	$menu->addChild(
		new JMenuNode(JText::_('MOD_MENU_HELP_SECURITY'), 'http://developer.joomla.org/security.html', 'class:help-security', false, '_blank')
	);
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_HELP_DEVELOPER'), 'http://developer.joomla.org', 'class:help-dev', false, '_blank'));
	$menu->addChild(new JMenuNode(JText::_('MOD_MENU_HELP_SHOP'), 'http://shop.joomla.org', 'class:help-shop', false, '_blank'));
	$menu->getParent();
}
