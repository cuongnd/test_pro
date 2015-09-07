<?php
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root() . '/components/com_virtuemart/assets/css/view-category.css');
$doc->addStyleSheet(JUri::root() . '/components/com_virtuemart/assets/css/vmcustom.css');
$doc->addStyleSheet(JUri::root() . '/media/system/css/ionicons.min.css');
$input = JFactory::getApplication()->input;
$from_search = $input->get('from_search', 0, 'int');
$keyword = $input->get('keyword', '', 'string');
defined('_JEXEC') or die('Restricted access');
JHTML::_('behavior.modal');
/* javascript for list Slide
  Only here for the order list
  can be changed by the template maker
*/
$js = "
jQuery(document).ready(function () {
	jQuery('.orderlistcontainer').hover(
		function() { jQuery(this).find('.orderlist').stop().show()},
		function() { jQuery(this).find('.orderlist').stop().hide()}
	)
});
";

$document = JFactory::getDocument();
$document->addScriptDeclaration($js);
$urlRoot=JUri::root();
$js=<<<javascript
var urlRoot='{$urlRoot}';
javascript;
$document->addScriptDeclaration($js);
$document->addScript(JUri::root().'/media/system/js/jquery.lazy.js');
if (!$input->get('from_search', 0, 'int') and !empty($this->category)) {
    ?>
    <div class="category_description row">
        <?php echo $this->category->category_description; ?>
    </div>
<?php
}
if ($category->virtuemart_media_id)
$file_url_thumb = $category->root_image?$category->root_image:JUri::root() . $category->file_url_thumb;
else
$file_url_thumb = JUri::root() . 'images/loading.gif';
$virtuemart_category_id = $category->virtuemart_category_id;
// Category Link
$caturl = JRoute::_("index.php?option=com_virtuemart&view=category&virtuemart_category_id=" . $virtuemart_category_id, FALSE);
?>

<div class="row">
<?php // Start the Output

foreach ($this->category->children as $category) {
    if ($category->virtuemart_media_id)
        $file_url_thumb = $category->root_image?$category->root_image:JUri::base() . $category->file_url_thumb;
    else
        $file_url_thumb = JUri::base() . 'images/loading.gif';
    $virtuemart_category_id = $category->virtuemart_category_id;
    // Category Link
    $caturl = JRoute::_("index.php?option=com_virtuemart&view=category&virtuemart_category_id=" . $virtuemart_category_id, FALSE);
    ?>
    <div  class="col-lg-3 col-md-4 col-sm-6">
        <div class="thumbnail">



            <img class="img-responsive img-circle" alt="" src="<?php echo $file_url_thumb ?>">
            <div class="caption">
                <h4><a href="<?php echo $caturl ?>"><?php echo $category->category_name ?></a></h4>
            </div>

        </div>

    </div>
    <?php
}
?>
</div>
<?php // Show child categories
if (!empty($this->products)) {
    ?>
    <div class="row"><h1><?php echo $from_search != 0 ? JText::_('Search Result:') . $input->get('keyword', '', 'string') : $this->category->category_name; ?></h1></div>
    <div class="row vm-pagination">
        <?php echo $this->vmPagination->getPagesLinks(); ?>
    </div>
    <div class="row">
    <?php
    // Category and Columns Counter


    // Start the Output
    $currency=$this->currency;
    foreach ($this->products as $product) {

        $product->link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' . $product->virtuemart_category_id . '&slug=' . $product->slug);
        ?>
        <div class="product <?php echo $product->layout ?> col-lg-3 col-md-4 col-sm-6">
            <?php include(JPATH_ROOT.'/modules/mod_virtuemart_product/tmpl/default_'.$product->layout.'.php') ?>
        </div> <!-- end of product -->
    <?php } ?>
    </div>
    <div class="row vm-pagination">
        <?php echo $this->vmPagination->getPagesLinks(); ?>
    </div>
<?php
} elseif ($from_search) {
    ?>
    <div style="text-align: center" class="row-fluid">
        <h3><?php echo JText::_('COM_VIRTUEMART_NO_RESULT') . ($keyword ? ' : (' . $keyword . ')' : ''); ?>
            <b><?php echo JText::_('Please search again') ?></b></h3></div>
<?php
}
?>

<script type="text/javascript">
    // javascript code
    jQuery(document).ready(function ($) {
        $("img.category-image-load-lazy").lazy({
            delay: 2000,
            effect: 'fadeIn'
        });
    });
</script>
