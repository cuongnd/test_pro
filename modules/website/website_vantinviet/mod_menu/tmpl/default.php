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
$doc->addScript(JUri::root() . '/media/system/js/Smooth-Multilevel-Accordion-Menu-Plugin-For-jQuery-vmenu/js/vmenuModule.js');
$doc->addScript(JUri::root() . '/modules/website/website_supper_admin/mod_menu/assets/mod_menu.js');
$doc->addLessStyleSheetTest(JUri::root() . '/media/system/js/Smooth-Multilevel-Accordion-Menu-Plugin-For-jQuery-vmenu/less/vmenuModule.less');

$scriptId = "script_module_" . $module->id;
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#mod_menu_<?php echo $module->id ?>').mod_menu();
    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);



// Note. It is important to remove spaces between elements.
?>
<div class="vertical-mega-menu"  id="mod_menu_<?php echo $module->id ?>">
    <div class="u-vmenu">
        <?php // The menu class is deprecated. Use nav instead. ?>
        <ul class="nav menu<?php echo $class_sfx; ?>"<?php
        $tag = '';

        if ($params->get('tag_id') != null) {
            $tag = $params->get('tag_id') . '';
            echo ' id="' . $tag . '"';
        }
        ?>>
            <?php
            $first_menu_item=array_shift($list);
            $children = array();
            // First pass - collect children
            foreach ($list as $v) {
                $pt = $v->parent_id;
                $pt=($pt==''||$pt==$v->id)?'list_root':$pt;
                $list = @$children[$pt] ? $children[$pt] : array();
                if ($v->id != $v->parent_id || $v->parent_id!=null) {
                    array_push($list, $v);
                }
                $children[$pt] = $list;
            }
            unset($children['list_root']);
            if(!function_exists('render_menu_item_mod_menu')){
                function render_menu_item_mod_menu($root_menu_item_id=0, $children,$level=0,$max_level=999){

                    if ($children[$root_menu_item_id]&&$level<$max_level) {

                        usort($children[$root_menu_item_id], function ($item1, $item2) {
                            if ($item1->ordering == $item2->ordering) return 0;
                            return $item1->ordering < $item2->ordering ? -1 : 1;
                        });
                        $level1=$level+1;
                        if($level>0)
                        {
                            echo '<ul  class="nav-child">';

                        }
                        foreach ($children[$root_menu_item_id] as $i => $item) {
                            if($item->hidden==1)
                            {
                                continue;
                            }
                            $root_menu_item_id1 = $item->id;
                            ?>

                            <li class="item-<?php echo $item->id ?> ">
                            <?php
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

                            render_menu_item_mod_menu($root_menu_item_id1, $children,$level1,$max_level);
                        }
                        if($level>0)
                        {
                            echo '</li></ul>';

                        }



                    }





                }
            }
            render_menu_item_mod_menu($first_menu_item->id,$children);
            ?></ul>
    </div>
</div>