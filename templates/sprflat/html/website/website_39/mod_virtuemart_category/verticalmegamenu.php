<?php
JHtml::_('jquery.framework');
$doc = JFactory::getDocument();
$doc->addScriptNotCompile(JUri::root() . '/media/system/js/jquery-vertical-mega-menu-1/js/jquery.hoverIntent.js');
$doc->addScriptNotCompile(JUri::root() . '/media/system/js/jquery-vertical-mega-menu-1/js/jquery.dcverticalmegamenu.1.1.js');
$doc->addStyleSheet(JUri::root() . '/media/system/js/jquery-vertical-mega-menu-1/css/vertical_menu_basic.css');
$doc->addStyleSheet(JUri::root() . '/media/system/js/jquery-vertical-mega-menu-1/css/vertical_menu.css');


/* Setting */
$scriptId = "script_module_mod_virtuemart_category_" . $module->id;
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#mega-1').dcVerticalMegaMenu({
            rowItems: '5',
            speed: 'fast',
            effect: 'fade',
            direction: 'right'
        });


    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);
$categoryModel = VmModel::getModel('shortedCategory');
$category_id = $params->get('config_layout.on_browser.vertical_mega_menu_config.category_id', 0);
$menu_item_link_products = $params->get('config_layout.on_browser.vertical_mega_menu_config.menu_item_link_products', 0);
$cache = JFactory::getCache('_system', 'callback');
$caching=$cache->cache->_options['caching'];
$cache->cache->_options['caching']=1;
$cache_id=md5($module->id);
$html=$cache->cache->get($cache_id);
if(!$html)
{
    $html = mod_virtuemartCategoryHelper::render_vertical_mega_menu(' id="mega-1" class="menu" ', array(), $category_id,0,$menu_item_link_products);
    $cache->store($html,$cache_id);
}

$cache->cache->_options['caching']=$caching;




?>
<div class="dcjq-vertical-mega-menu">
    <?php echo $html ?>

</div>

