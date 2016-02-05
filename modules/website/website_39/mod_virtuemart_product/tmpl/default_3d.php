<?php
if (!empty($product->images[0]))
    $image = $product->images[0]->displayMediaThumb('class="img-responsive" border="0"', false);
else $image = '';
$product->cmstype = $product->cmstype ? $product->cmstype : 'svg square-icon icon-monster_dark';
$url = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $product->virtuemart_product_id . '&virtuemart_category_id=' .$product->virtuemart_category_id . '&slug=' . $product->slug);
?>
<div class="thumbnail">
    <div class="ribbon-wrapper-green">
        <div class="ribbon-green">NEWS</div>
    </div>
    <?php $image ?>
    <div class="caption">
        <h4 class="pull-right"><?php echo $currency->createPriceDiv('salesPrice', '', $product->prices, true); ?></h4>
        <h4><a class="e-change-lang" href="<?php echo $url ?>"><?php echo $product->product_name ?></a> </h4>

    </div>
    <div>
        <p>
           <?php
           echo '<b class="' . str_replace('svgsquare-iconicon', 'svg square-icon icon', $product->cmstype) . '"></b>';
           ?>
        </p>
    </div>
</div>




        