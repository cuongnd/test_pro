<?php
$product=$this->product;

?>
<div class="product">
	<img src="<?php echo $product->image ?>" />
	<div class="price"><?php echo $product->price ?></div>
	<div class="title"><a href="<?php echo JUri::root() ?>index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=<?php echo $product->virtuemart_product_id ?>&Itemid=<?php echo $this->menu_item_product_detail ?>"><?php echo $product->product_name ?></a></div>
</div>
            