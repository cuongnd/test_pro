<?php
$doc=JFactory::getDocument();
$doc->addScript(JUri::root().'/media/system/js/BobKnothe-autoNumeric/autoNumeric.js');
$doc->addScript(JPATH_VM_URL.'/assets/js/view_productdetails_default.js');
$scriptId='view_categories_manager';
ob_start();
?>

<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$('.view-productdetail-default').view_productdetails_default({
		});
	});
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);


$product=$this->product;

 ?>
<div class="view-productdetail-default">
<div class="product-detail">
	<div class="row">
		<div class="col-md-4">
			<div class="image">
				<img src="<?php echo $product->image ?>" />
			</div>
		</div>
		<div class="col-md-8">
			<div class="product-name"><?php echo $product->product_name ?></div>
			<div class="price"><span><?php echo JText::_('price') ?></span> <span data-a-sep="." data-a-dec="," data-a-sign="$ " class="auto"><?php echo $product->price ?></span> </div>
			<div class="add-to-cart">
				<button class="btn"><i class="cart"></i><?php echo JText::_('Add to cart') ?></button>
				<button class="btn"><i class="cart"></i><?php echo JText::_('Buy now') ?></button>
			</div>
		</div>
	</div>
</div>
</div>