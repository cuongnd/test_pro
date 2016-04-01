<?php
$image=$product->data_preview_url?$product->data_preview_url:JUri::root().'/'.$product->images[0]->file_url_thumb;

$product->cmstype = $product->cmstype ? $product->cmstype : 'svg square-icon icon-monster_dark';
$url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .$product->virtuemart_category_id . '&slug=' . $product->slug);
?>
<div class="thumbnail">
    <div class="ribbon-wrapper-green">
        <div class="ribbon-green">NEWS</div>
    </div>
    <img src="<?php echo $image ?>">
    <div class="caption">
        <h4 class="pull-right"><?php echo $currency->createPriceDiv('salesPrice', '', $product->prices, true); ?></h4>
        <h4>
            <a class="e-change-lang <?php echo str_replace('svgsquare-iconicon', 'svg square-icon icon', $product->cmstype) ?>" href="<?php echo $url ?>"><?php echo $product->product_name ?></a> </h4>

    </div>

</div>





