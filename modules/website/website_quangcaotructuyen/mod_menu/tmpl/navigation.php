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
$doc->addScript(JUri::root() . '/modules/website/website_39/mod_menu/assets/navigation_menu.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/Responsive-Cross-platform-jQuery-Navigation-Menu-Plugin-Smart-Menus/src/css/sm-core-css.css');
$doc->addStyleSheet(JUri::root() . '/modules/website/website_39/mod_menu/assets/Responsive-Cross-platform-jQuery-Navigation-Menu-Plugin-Smart-Menus/src/css/sm-blue/sm-blue.css');
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
$doc->addScriptDeclaration($js_content);


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
        foreach ($list as $i => &$item) {
            $class = 'item-' . $item->id;

            if ($item->id == $active_id) {
                $class .= ' current';
            }

            if (in_array($item->id, $path)) {
                $class .= ' active';
            } elseif ($item->type == 'alias') {
                $aliasToId = $item->params->get('aliasoptions');

                if (count($path) > 0 && $aliasToId == $path[count($path) - 1]) {
                    $class .= ' active';
                } elseif (in_array($aliasToId, $path)) {
                    $class .= ' alias-parent-active';
                }
            }

            if ($item->type == 'separator') {
                $class .= ' divider';
            }

            if ($item->deeper) {
                $class .= ' deeper';
            }

            if ($item->parent) {
                $class .= ' parent';
            }

            if (!empty($class)) {
                $class = ' class="' . trim($class) . '"';
            }
            $class .= ' e-change-lang';
            echo '<li' . $class . '>';

            // Render the menu item.
            switch ($item->type) :
                case 'separator':
                case 'url':
                case 'component':
                case 'heading':
                    require JModuleHelper::getLayoutPath('mod_menu', 'default_' . $item->type);
                    break;

                default:
                    require JModuleHelper::getLayoutPath('mod_menu', 'default_url');
                    break;
            endswitch;

            // The next item is deeper.
            if ($item->deeper) {
                echo '<ul class="nav-child unstyled small">';
            } elseif ($item->shallower) {
                // The next item is shallower.
                echo '</li>';
                echo str_repeat('</ul></li>', $item->level_diff);
            } else {
                // The next item is on the same level.
                echo '</li>';
            }
        }
        ?></ul>

</div>