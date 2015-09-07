<?php
$product=$this->product;

?>
<div class="ribbon-wrapper-green">
    <div class="ribbon-green">NEWS</div>
</div>




<div class="image row-fluid">
    <video controls="controls" height="200px" width="300px"  poster="<?php echo $product->data_preview_url ?>" preload="none" aria-describedby="full-descript">
        <source type="video/mp4" src="<?php echo $product->data_video_file_url ?>" />

        <track src="subs/TOS-arabic.srt" kind="subtitles" srclang="ar" label="Arabic" />
        <track src="subs/TOS-japanese.srt" kind="subtitles" srclang="jp" label="Japanese" />
        <track src="subs/TOS-english.srt" kind="subtitles" srclang="en" label="English" />
        <track src="subs/TOS-turkish.srt" kind="subtitles" srclang="tr" label="Turkish" />
        <track src="subs/TOS-ukrainian.srt" kind="subtitles" srclang="uk" label="Ukrainian" />

        You can download Tears of Steel at <a href="http://mango.blender.org/">mango.blender.org</a>.
    </video>
</div>
<div class="footer thumbnail-info row-fluid">

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
            