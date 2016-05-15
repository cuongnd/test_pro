<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
// Include the component HTML helpers.

$list_category = $this->items;

$children_category = array();
foreach ($list_category as $category) {
    $pt = $category->parent_id;
    $pt = ($pt == '' || $pt == $category->id) ? 'list_root' : $pt;
    $list = @$children_category[$pt] ? $children_category[$pt] : array();
    array_push($list, $category);
    $children_category[$pt] = $list;
}
$list_root_category = $children_category['list_root'];
$user = JFactory::getUser();
$doc = JFactory::getDocument();
$doc->addLessStyleSheetTest(JUri::root() . 'components/website/website_websitetemplatepro/com_websitetemplatepro/assets/less/view_listtemplatecategory_frontend.less');
$doc->addScript(JUri::root() . '/media/system/js/Smooth-Multilevel-Accordion-Menu-Plugin-For-jQuery-vmenu/js/vmenuModule.js');
$doc->addLessStyleSheetTest(JUri::root() . '/media/system/js/Smooth-Multilevel-Accordion-Menu-Plugin-For-jQuery-vmenu/less/vmenuModule.less');


$doc->addScript(JUri::root() . 'components/website/website_websitetemplatepro/com_websitetemplatepro/assets/js/view_listtemplatecategory_frontend.js');

$script_id = "script_view_listtemplatecategory_frontend";
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-listtemplatecategory-frontend').view_listtemplatecategory_frontend({

        });
    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $script_id);
?>


<div class="view-listtemplatecategory-frontend">

    <div class="row">
        <div class="col-md-3">
            <div class="vertical-mega-menu">
                <div class="u-vmenu">
                    <ul class="nav menu">
                        <?php foreach ($list_root_category as $i => $category) :
                            $render_category_item = function ($item, $i = 0, $level = 0) {
                                ob_start();
                                ?>
                                <a data-category_id="<?php echo $item->id; ?>" href="javascript:void(0)"><?php echo $item->category_name; ?></a>
                                <?php
                                $html = ob_get_clean();
                                return $html;

                            };
                            echo '<li>' . $render_category_item($category, $i);
                            $render_categories = function ($function_callback, $category_id = 0, $children_category = array(), $list_category = array(), $render_category_item, $i, $level = 0, $max_level = 9999) {
                                $category = $list_category[$category_id];
                                $level1 = $level + 1;
                                if (count($children_category[$category_id])) {
                                    echo '<ul>';
                                    foreach ($children_category[$category_id] as $category) {
                                        echo '<li>' . $render_category_item($category, $i, $level1);
                                        $category_id1 = $category->id;
                                        $function_callback($function_callback, $category_id1, $children_category, $list_category, $render_category_item, $i, $level1, $max_level);
                                    }
                                    echo '</li></ul>';
                                }
                            };
                            $render_categories($render_categories, $category->id, $children_category, $list_category, $render_category_item, $i);

                        endforeach; ?>
                        </li></ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="area-list-template">

            </div>
        </div>
    </div>

</div>
