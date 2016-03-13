<?php
$product=$this->product;

?>
<div class="ribbon-wrapper-green">
    <div class="ribbon-green">NEWS</div>
</div>
<div class=" image">
    <?php //echo "<pre>"; print_r($this->show_prices);die; ?>
    <a title="<?php echo $this->showBasePrice ?>" rel="vm-additional-images"
       href="<?php echo $product->link; ?>">
        <?php if ($product->file_url) { ?><img alt="<?php echo $product->product_name ?>"
                                               src="<?php echo $product->images[0]->file_url_thumb ?>"><?php } ?>

    </a>
</div>
<div class="footer thumbnail-info">

    <div class="thumbnail-arrow"></div>

    <?php
    $product->cmstype = $product->cmstype ? $product->cmstype : 'svg square-icon icon-monster_dark';
    echo '<b class="' . $product->cmstype . '"></b>';
    ?>
    <?php echo JHTML::link($product->link, $product->product_name); ?>
    <?php if ($product->free_download && count($product->virtuemart_media_file_Upload_id)) { ?>
        <div class="free-download"><a
                href="index.php?option=com_virtuemart&controller=category&task=downloadFile&file_download=<?php echo $product->virtuemart_media_file_Upload_id[0] ?>"><span
                    class="free-product-download ion-android-download"></span><span><?php echo JText::_('COM_VIRTUEMART_PRODUCT_FREE_DOWNLOAD') ?></span></a>
        </div>
    <? } else { ?>
        <div id="ribbon-3" class="ribbons">
            <?php
            echo $this->currency->createPriceDiv('priceWithoutTax', '', $product->prices);
            ?>
        </div>
    <?php } ?>


    <div class="clear"></div>
</div>
<!-- end of spacer -->
            