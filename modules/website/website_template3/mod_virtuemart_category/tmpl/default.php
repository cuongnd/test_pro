<?php // no direct access

defined('_JEXEC') or die('Restricted access');
//JHTML::stylesheet ( 'menucss.css', 'modules/mod_virtuemart_category/css/', false );



$document = JFactory::getDocument();
$document->addScript(JUri::root().'/modules/mod_virtuemart_category/assets/jquery.treeview/jquery.treeview.js');
$document->addScript(JUri::root().'/modules/mod_virtuemart_category/assets/jquery.treeview/lib/jquery.cookie.js');
$document->addStyleSheet(JUri::root().'/modules/mod_virtuemart_category/assets/jquery.treeview/jquery.treeview.css');


$html='';
$livel1_arrtr=' id="navigation_'.$category_id.'" class="treeview-red treeview" ';
echo mod_virtuemartCategoryHelper::treeReCurseCategories($category_id,$html,$categoryTree,4,0,$livel1_arrtr);

?>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery("#navigation_<?php echo $category_id ?>").treeview({
            animated: "fast",
            collapsed: true,
            unique: true,
            persist: "cookie",
            toggle: function() {

            }
        });
    });
</script>


