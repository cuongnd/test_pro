<?php
JHtml::_('jquery.framework');
$doc = JFactory::getDocument();
$doc->addScriptNotCompile(JUri::root() . '/media/system/js/jquery-vertical-mega-menu-1/js/jquery.hoverIntent.js');
$doc->addScriptNotCompile(JUri::root() . '/modules/website/website_39/mod_virtuemart_category/asset/js/custom_mega_menu_styles/js/jquery.dcmegamenu.1.3.js');
$doc->addScriptNotCompile(JUri::root() . '/modules/website/website_39/mod_virtuemart_category/asset/js/dcmegamenu.js');
$doc->addLessStyleSheetTest(JUri::root() . '/modules/website/website_39/mod_virtuemart_category/asset/js/custom_mega_menu_styles/megamenu.less');


/* Setting */
$scriptId = "script_module_mod_virtuemart_category_" . $module->id;
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#mod_virtuemart_category_dcmegamenu_<?php echo $module->id ?>').mod_virtuemart_category_dcmegamenu({
            module_id:<?php echo $module->id ?>
        });



    });
</script>
<?php
$app=JFactory::getApplication();
$input=$app->input;
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);
$categoryModel = VmModel::getModel('shortedCategory');
$category_id = $input->get('virtuemart_category_id',0);
$menu_item_link_products = $params->get('config_layout.on_browser.vertical_mega_menu_config.menu_item_link_products', 0);
$html='';
mod_virtuemartCategoryHelper::render_horizontal_mega_menu($html,' id="mega-menu-tut" class="menu" ', array(), $category_id,0,$menu_item_link_products);
?>
<div id="mod_virtuemart_category_dcmegamenu_<?php echo $module->id ?>" class="dcjq-mega-menu">
<?php echo $html ?>

</div>

