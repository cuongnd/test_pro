<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_menu
 *
 * @package     Joomla.Site
 * @subpackage  mod_menu
 * @since       1.5
 */
class ModMenuHelper
{
    /**
     * Get a list of the menu items.
     *
     * @param   JRegistry &$params The module options.
     *
     * @return  array
     *
     * @since   1.5
     */
    public static function android_set_data_source(&$module, $params, &$data_source = array())
    {
        $app = JFactory::getApplication();
        $menu = $app->getMenu();
        $base = self::getBase($params);
        $user = JFactory::getUser();

        $levels = $user->getAuthorisedViewLevels();
        asort($levels);
        $key = 'menu_items' . $params . implode(',', $levels) . '.' . $base->id;
        $path = $base->tree;
        $start = (int)$params->get('startLevel', 0);
        $end = (int)$params->get('endLevel', 10);
        $showAll = $params->get('showAllChildren', 1);
        $menu_type_id = $params->get('menu_type_id');
        $menu_type_id = $menu_type_id ? $menu_type_id : ModMenuHelper::getDefaultMenuType();
        $items = $menu->get_menu_item_by_menu_type_id($menu_type_id);
        $module->list_menu_item = $items;

    }

    public static function getList(&$params)
    {
        $app = JFactory::getApplication();
        $menu = $app->getMenu();

        // Get active menu item
        $base = self::getBase($params);
        $user = JFactory::getUser();

        $levels = $user->getAuthorisedViewLevels();

        asort($levels);
        $key = 'menu_items' . $params . implode(',', $levels) . '.' . $base->id;
        $cache = JFactory::getCache('mod_menu', '');

        if (!($items = $cache->get($key))) {
            $path = $base->tree;
            $start = (int)$params->get('startLevel', 0);
            $end = (int)$params->get('endLevel', 10);
            $showAll = $params->get('showAllChildren', 1);
            $menu_type_id = $params->get('menu_type_id');
            $menu_type_id = $menu_type_id ? $menu_type_id : ModMenuHelper::getDefaultMenuType();
            $items = $menu->get_menu_item_by_menu_type_id($menu_type_id,true);
            $lastitem = 0;

            if ($items) {
                foreach ($items as $i => $item) {
                    if (($start && $start > $item->level)
                        || ($end && $item->level > $end)
                        || (!$showAll && $item->level > 1 && !in_array($item->parent_id, $path))
                        || ($start > 1 && !in_array($item->tree[$start - 2], $path))
                    ) {
                        unset($items[$i]);
                        continue;
                    }

                    $item->deeper = false;
                    $item->shallower = false;
                    $item->level_diff = 0;

                    if (isset($items[$lastitem])) {
                        $items[$lastitem]->deeper = ($item->level > $items[$lastitem]->level);
                        $items[$lastitem]->shallower = ($item->level < $items[$lastitem]->level);
                        $items[$lastitem]->level_diff = ($items[$lastitem]->level - $item->level);
                    }

                    $item->parent = (boolean)$menu->getItems('parent_id', (int)$item->id, true);

                    $lastitem = $i;
                    $item->active = false;
                    $item->flink = $item->link;

                    // Reverted back for CMS version 2.5.6
                    switch ($item->type) {
                        case 'separator':
                        case 'heading':
                            // No further action needed.
                            continue;

                        case 'url':
                            if ((strpos($item->link, 'index.php?') === 0) && (strpos($item->link, 'Itemid=') === false)) {
                                // If this is an internal Joomla link, ensure the Itemid is set.
                                $item->flink = $item->link . '&Itemid=' . $item->id;
                            }
                            break;

                        case 'alias':
                            // If this is an alias use the item id stored in the parameters to make the link.
                            $item->flink = 'index.php?Itemid=' . $item->params->get('aliasoptions');
                            break;

                        default:
                            $router = $app::getRouter();
                            if ($router->getMode() == JROUTER_MODE_SEF) {
                                $item->flink = JRoute::_('index.php?Itemid=' . $item->id);
                               if($item->home==1)
                               {
                                   $item->flink=JUri::root();
                               }
                                if (isset($item->query['format']) && $app->get('sef_suffix')) {
                                    $item->flink .= '&format=' . $item->query['format'];
                                }
                            } else {
                                $item->flink .= '&Itemid=' . $item->id;
                            }
                            break;
                    }

                    if (strcasecmp(substr($item->flink, 0, 4), 'http') && (strpos($item->flink, 'index.php?') !== false)) {

                        $item->flink = JRoute::_($item->flink, true, $item->params->get('secure'));
                    } else {

                        $item->flink = JRoute::_($item->flink);
                    }
                    // We prevent the double encoding because for some reason the $item is shared for menu modules and we get double encoding
                    // when the cause of that is found the argument should be removed
                    $item->title = htmlspecialchars($item->title, ENT_COMPAT, 'UTF-8', false);
                    $item->anchor_css = htmlspecialchars($item->params->get('menu-anchor_css', ''), ENT_COMPAT, 'UTF-8', false);
                    $item->anchor_title = htmlspecialchars($item->params->get('menu-anchor_title', ''), ENT_COMPAT, 'UTF-8', false);
                    $item->menu_image = $item->params->get('menu_image', '') ?
                        htmlspecialchars($item->params->get('menu_image', ''), ENT_COMPAT, 'UTF-8', false) : '';
                }

                if (isset($items[$lastitem])) {
                    $items[$lastitem]->deeper = (($start ? $start : 1) > $items[$lastitem]->level);
                    $items[$lastitem]->shallower = (($start ? $start : 1) < $items[$lastitem]->level);
                    $items[$lastitem]->level_diff = ($items[$lastitem]->level - ($start ? $start : 1));
                }
            }

            $cache->store($items, $key);
        }
        return $items;
    }


    function create_html_list_left_menu(&$html = '', $parent_id, $list_menu_item, $active_menu_item_id, $level = 0)
    {
        $list_menu_item1 = array();
        foreach ($list_menu_item as $key => $menu_item) {
            if ($menu_item->parent_id == $parent_id) {
                $list_menu_item1[] = $menu_item;
                unset($list_menu_item[$key]);
            }
        }
        usort($list_menu_item1, function ($item1, $item2) {
            if ($item1->ordering == $item2->ordering) return 0;
            return $item1->ordering < $item2->ordering ? -1 : 1;
        });

        $html .= count($list_menu_item1) ? '<ul ' . ($level == 0 ? ' id="sideNav_front_end"' : '') . ' class="nav sub">' : '';
        $level1 = $level + 1;
        foreach ($list_menu_item1 as $menu_item) {
            ob_start();
            ?>
        <li class="<?php echo $active_menu_item_id == $menu_item->id ? ' menu-active ' : '' ?>">
            <a
                href="<?php echo JUri::root().$menu_item->link ?>"><i
                    class="<?php echo $menu_item->icon ?>"></i> <?php echo $menu_item->title ?></a>
            <?php
            $html .= ob_get_clean();
            ModMenuHelper::create_html_list_left_menu($html, $menu_item->id, $list_menu_item, $active_menu_item_id, $level1);
            $html .= "</li>";
        }
        $html .= count($list_menu_item1)?'</ul>':'';
    }

    function treerecurse_left_menu($id, $list, &$children, $maxlevel = 9999, $level = 0)
    {
        if (@$children[$id] && $level <= $maxlevel) {

            foreach ($children[$id] as $v) {
                $id = $v->id;
                $list[$id] = $v;
                $list[$id]->children = @$children[$id];
                unset($children[$id]);
                $list = ModMenuHelper::treerecurse_left_menu($id, $list, $children, $maxlevel, $level + 1);
            }
        }
        return $list;
    }

    public function changeParam($params)
    {
        //change menu_type_id
        $website = JFactory::getWebsite();
        $menu_type_id = $params->get('menu_type_id');
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('mt.id')
            ->from('#__menu_types as mt')
            ->where('mt.copy_from=' . (int)$menu_type_id)
            ->where('mt.website_id=' . (int)$website->website_id);
        $db->setQuery($query);
        $new_menu_type_id = $db->loadResult();
        if ($new_menu_type_id) {
            $params->set('menu_type_id', $new_menu_type_id);
        }
        return $params;

    }

    public function getDefaultMenuType()
    {
        $website = JFactory::getWebsite();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('id')
            ->from('#__menu_types as menu_types')
            ->where(array(
                'menu_types.client_id=0',
                'menu_types.website_id=' . $website->website_id
            ));
        $db->setQuery($query);
        $result = $db->loadResult();
        return $result;

    }

    /**
     * Get base menu item.
     *
     * @param   JRegistry &$params The module options.
     *
     * @return   object
     *
     * @since    3.0.2
     */
    public static function getBase(&$params)
    {
        // Get base menu item from parameters
        if ($params->get('base')) {
            $base = JFactory::getApplication()->getMenu()->getItem($params->get('base'));
        } else {
            $base = false;
        }

        // Use active menu item if no base found
        if (!$base) {
            $base = self::getActive($params);
        }

        return $base;
    }

    /**
     * Get active menu item.
     *
     * @param   JRegistry &$params The module options.
     *
     * @return  object
     *
     * @since    3.0.2
     */
    public static function getActive(&$params)
    {
        $menu = JFactory::getApplication()->getMenu();

        return $menu->getActive() ? $menu->getActive() : $menu->getDefault();
    }

    public static function getMenus()
    {
        $supperAdmin = JFactory::isSupperAdmin();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
            ->select('a.*, SUM(b.home) AS home')
            ->from('#__menu_types AS a')
            ->join('LEFT', '#__menu AS b ON b.menu_type_id = a.id AND b.home != 0')
            ->select('b.language')
            ->join('LEFT', '#__languages AS l ON l.lang_code = language')
            ->select('l.image')
            ->select('l.sef')
            ->select('l.title_native')
            ->where('(b.client_id = 0 OR b.client_id IS NULL)');

        if ($supperAdmin) {

        } else {
            $website = JFactory::getWebsite();
            $query->where('a.website_id=' . $website->website_id);
            $query->where('a.issystem=0');
        }
        // Sqlsrv change
        $query->group('a.id, a.menutype, a.description, a.title, b.menutype,b.language,l.image,l.sef,l.title_native');

        $db->setQuery($query);

        $result = $db->loadObjectList();
        return $result;
    }

    public static function getComponents($authCheck = true, $params)
    {

        //$menu_type_id=$params->get('menu_type_id',0);
        $menu_type_id = 0;
        $supperAdmin = JFactory::isSupperAdmin();
        $lang = JFactory::getLanguage();
        $user = JFactory::getUser();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $result = array();

        // Prepare the query.
        $query->select('m.id, m.title,m.level,m.lft,m.rgt,. m.alias, m.link, m.parent_id, m.img')
            ->from('#__menu AS m');

        // Filter on the enabled states.
        //$query->where('m.client_id = 1')
        //$query->where('m.id > 1');
        $website = JFactory::getWebsite();
        $query->leftJoin('#__menu_types AS mt ON mt.id=m.menu_type_id');
        $query->where('mt.website_id=' . $website->website_id);
        $query->where('mt.id=' . $menu_type_id);
        $query->where('m.alias!=' . $query->quote('root'));

        // Order by lft.
        $query->order('m.lft');
        $db->setQuery($query);
        // Component list
        $components = $db->loadObjectList();


        return $components;
    }

}
