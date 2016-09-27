<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('jquery.framework');
JHTML::_('behavior.core');
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/media/system/js/Responsive-Cross-platform-jQuery-Navigation-Menu-Plugin-Smart-Menus/src/jquery.smartmenus.js');
$doc->addScript(JUri::root() . '/modules/website/website_websitetemplatepro/mod_menu/assets/navigation_menu.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Responsive-Cross-platform-jQuery-Navigation-Menu-Plugin-Smart-Menus/src/css/sm-core-css.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Responsive-Cross-platform-jQuery-Navigation-Menu-Plugin-Smart-Menus/src/css/sm-blue/sm-blue.css');
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.navigation-mega-menu').navigation_menu({});
    });
</script>
<?php
$js_content = ob_get_clean();
$js_content = JUtility::remove_string_javascript($js_content);
$doc->addScriptDeclaration($js_content, "text/javascript", 'script_navigation_menu');

// Note. It is important to remove spaces between elements.
?>
<div class="navigation-mega-menu">

    <?php // The menu class is deprecated. Use nav instead. ?>
    <ul id="main-menu" class="nav menu<?php echo $class_sfx; ?> sm sm-blue"<?php
    $tag = '';

    if ($params->get('tag_id') != null) {
        $tag = $params->get('tag_id') . '';
        echo ' id="' . $tag . '"';
    }
    ?>>
        <?php

        $first_menu_item = reset($list);
        $children = array();
        // First pass - collect children
        foreach ($list as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $a_list = @$children[$pt] ? $children[$pt] : array();
            if ($v->id != $v->parent_id || $v->parent_id != null) {
                array_push($a_list, $v);
            }
            $children[$pt] = $a_list;
        }
        unset($children['list_root']);
        $render_menu_item_mod_menu = function ($function_call_back, $root_menu_item_id = 0, $children, $level = 0, $max_level = 999) {
            if (count($children[$root_menu_item_id]) > 0 && $level < $max_level) {

                usort($children[$root_menu_item_id], function ($item1, $item2) {
                    if ($item1->ordering == $item2->ordering) return 0;
                    return $item1->ordering < $item2->ordering ? -1 : 1;
                });
                $level1 = $level + 1;
                if ($level > 0) {
                    echo '<ul  class="nav-child">';

                }
                foreach ($children[$root_menu_item_id] as $i => $item) {
                    $root_menu_item_id1 = $item->id;
                    if ($item->hidden == 1 || !$item->published) {
                        continue;
                    }

                    ?>

                <li class="item-<?php echo $item->id ?> ">
                    <?php
                    switch ($item->type) :
                        case 'separator':
                        case 'url':
                        case 'component':
                        case 'heading':
                            require JModuleHelper::getLayoutPath('mod_menu', 'navigation_' . $item->type);
                            break;

                        default:
                            require JModuleHelper::getLayoutPath('mod_menu', 'navigation_url');
                            break;
                    endswitch;

                    $function_call_back($function_call_back, $root_menu_item_id1, $children, $level1, $max_level);
                }
                if ($level > 0) {
                    echo '</li></ul>';

                }


            }


        };

        $list = JArrayHelper::pivot($list, 'id');

        $render_menu_item_mod_menu($render_menu_item_mod_menu, $first_menu_item->id, $children);
        ?></ul>

</div>