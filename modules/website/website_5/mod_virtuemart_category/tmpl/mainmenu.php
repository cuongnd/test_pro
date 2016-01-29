<?php // no direct access

defined('_JEXEC') or die('Restricted access');
//JHTML::stylesheet ( 'menucss.css', 'modules/mod_virtuemart_category/css/', false );

/* ID for jQuery dropdown */
$ID = str_replace('.', '_', substr(microtime(true), -8, 8));
$js = "jQuery(document).ready(function() {
		jQuery('#VMmenu" . $ID . " li.VmClose ul').hide();
		jQuery('#VMmenu" . $ID . " li .VmArrowdown').click(
		function() {

			if (jQuery(this).parent().next('ul').is(':hidden')) {
				jQuery('#VMmenu" . $ID . " ul:visible').delay(500).slideUp(500,'linear').parents('li').addClass('VmClose').removeClass('VmOpen');
				jQuery(this).parent().next('ul').slideDown(500,'linear');
				jQuery(this).parents('li').addClass('VmOpen').removeClass('VmClose');
			}
		});
	});";

$document = JFactory::getDocument();
$document->addScriptDeclaration($js);


$html='';
echo mod_virtuemartCategoryHelper::treeReCurseCategories($category_id,$html,$categoryTree,1,0);


?>


