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
$urlRoot = JUri::root();
$js = <<<javascript
var urlRoot='{$urlRoot}';
javascript;
$document->addScriptDeclaration($js);
$document->addScript(JUri::root() . '/media/system/js/jquery.lazy.js');
if (!$input->get('from_search', 0, 'int') and !empty($this->category)) {
    ?>
    <div class="category_description row">
        <?php echo $this->category->category_description; ?>
    </div>
    <?php
}
if ($category->virtuemart_media_id)
    $file_url_thumb = $category->root_image ? $category->root_image : JUri::root() . $category->file_url_thumb;
else
    $file_url_thumb = JUri::root() . 'images/loading.gif';
$virtuemart_category_id = $category->virtuemart_category_id;
$number_column = 4;
$list_list_product = array_chunk($this->products, $number_column);
// Category Link
$caturl = JRoute::_("index.php?option=com_virtuemart&view=category&virtuemart_category_id=" . $virtuemart_category_id, FALSE);
?>

<?php // Show child categories
if (!empty($this->products)) {
    ?>
    <div class="row">
        <h1><?php echo $from_search != 0 ? JText::_('Search Result:') . $input->get('keyword', '', 'string') : $this->category->category_name; ?></h1>
    </div>
    <div class="row vm-pagination">
        <?php echo $this->vmPagination->getPagesLinks(); ?>
    </div>
    <?php
    // Category and Columns Counter


    // Start the Output
    $currency = $this->currency;
    $com_virtuemart_path = Jpath::get_component_path('com_virtuemart');
    foreach ($list_list_product as $list_product) {
        ?>
        <div class="row">
            <?php
            foreach ($list_product as $this->product) {
                $this->product->link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&slug=' . $this->product->slug);
                $layout = ($layout = $this->product->layout) ? "default_$layout" : 'default';
                ?>
                <div
                    class="product <?php echo $this->product->layout ?> col-lg-<?php echo round(12 / $number_column) ?> col-md-<?php echo round(12 / $number_column) ?> col-sm-<?php echo round(12 / $number_column) ?>">
                    <?php echo $this->loadTemplate($layout) ?>
                </div> <!-- end of product -->
            <?php }
            ?>
        </div>
        <?php
    } ?>
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
