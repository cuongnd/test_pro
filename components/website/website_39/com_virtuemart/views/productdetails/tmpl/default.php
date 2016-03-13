<?php
$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root() . '/components/com_virtuemart/assets/css/view-productdetails.css');
$doc->addStyleSheet(JUri::root() . '/components/com_virtuemart/assets/css/vmcustom.css');

$input = JFactory::getApplication()->input;
$show_link_detail = $input->get('show_link_detail', 0, 'int');
//$show_link_detail = 0;
?>
<?php if ($show_link_detail) { ?>
    <a href="<?php echo $this->product->linkdetail ?>"><?php echo JText::_('Root link demo') ?></a>
<?php } ?>
<div class="product-neighbours">
    <?php
    if (!empty($this->product->neighbours ['previous'][0])) {
        $prev_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['previous'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&slug=' . $this->product->neighbours ['previous'][0] ['slug'], FALSE);
        echo JHTML::_('link', $prev_link, $this->product->neighbours ['previous'][0]
        ['product_name'], array('class' => 'previous-page'));
    }
    if (!empty($this->product->neighbours ['next'][0])) {
        $next_link = JRoute::_('index.php?option=com_virtuemart&view=productdetails&virtuemart_product_id=' . $this->product->neighbours ['next'][0] ['virtuemart_product_id'] . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&slug=' . $this->product->neighbours ['next'][0] ['slug'], FALSE);
        echo JHTML::_('link', $next_link, $this->product->neighbours ['next'][0] ['product_name'], array('class' => 'next-page'));
    }
    ?>
    <div class="clear"></div>
</div>
<?php // Back To Category Button
if ($this->product->virtuemart_category_id) {
    $catURL = JRoute::_('index.php?option=com_virtuemart&view=category&virtuemart_category_id=' . $this->product->virtuemart_category_id, FALSE);
    $categoryName = $this->product->category_name;
} else {
    $catURL = JRoute::_('index.php?option=com_virtuemart');
    $categoryName = jText::_('COM_VIRTUEMART_SHOP_HOME');
}
?>
<div class="back-to-category">
    <a href="<?php echo $catURL ?>" class="product-details"
       title="<?php echo $categoryName ?>"><?php echo JText::sprintf('COM_VIRTUEMART_CATEGORY_BACK_TO', $categoryName) ?></a>
</div>
<article class="pd-item-article">
    <div class="page-masthead">

        <!-- masshead-->
        <div class="page-masthead article-masshead">
            <div class="jumbotron jumbotron-primary">
                <div class="container">
                    <h1>
                        <?php echo $this->product->product_name ?>        </h1>

                    <div class="pd-labels <?php echo $this->product->cmstype ?>">
                    </div>
                </div>
            </div>
        </div>
        <!-- //masshead -->
    </div>

    <!-- Product Intro -->
    <section class="row pd-item-intro">

        <!-- Product Intro Content -->
        <div class="col-xs-12 col-md-6 pd-item-intro-ct">

            <?php // afterDisplayTitle Event
            echo $this->product->event->afterDisplayTitle ?>
            <?php
            // Product Edit Link
            echo $this->edit_link;
            // Product Edit Link END
            ?>
            <div class="pd-quick-info">
                <!-- Quick Info -->
                <div class="quick-info">
                    <ul class="list-group">
                        <li class="list-group-item cmstyle <?php echo $this->product->cmstype ?>">type:<b><?php echo $this->product->layout ?></b></li>
                        <li class="list-group-item">Updated on:</li>
                        <li class="list-group-item">Version:</li>
                        <li class="list-group-item">Download:</li>
                    </ul>

                </div>
                <!-- //Quick Info -->
                <!-- Quick CTA -->
                <div class="main-pd-cta">
                    <div class="pd-cta">
                        <?php if($this->product->linkdemo){ ?>
                        <a title="Live Demo  " target="_blank"
                           href="<?php echo JRoute::_('index.php?option=com_virtuemart&view=productdetails&tmpl=component&layout=demo&virtuemart_product_id=' . $this->product->virtuemart_product_id . '&virtuemart_category_id=' . $this->product->virtuemart_category_id . '&slug=' . $this->product->slug) ?>"
                           class="btn btn-lg btn-success">
                            <i class="fa fa-eye"></i><?php echo JText::_('COM_VIRTUEMART_PRODUCTDETAILS_LIVE_DEMO') ?>
                        </a>
                        <?php } ?>
                        <?php if (count($this->product->virtuemart_media_file_Upload_id)) { ?>
                            <a title="Download "
                               href="index.php?option=com_virtuemart&controller=category&task=downloadFile&file_download=<?php echo $this->product->virtuemart_media_file_Upload_id[0] ?>"
                               class="btn btn-lg btn-default">
                                <i class="fa fa-download"></i> <?php echo JText::_('COM_VIRTUEMART_PRODUCTDETAILS_DOWNLOAD') ?>
                            </a>
                        <?php } else { ?>

                            <a title="Live Demo  "
                               href="index.php?option=com_virtuemart&controller=cart&task=add&virtuemart_product_id=<?php echo $this->product->virtuemart_product_id ?>"
                               class="btn btn-lg btn-success">
                                <i class="fa fa-download"></i> <?php echo $this->currency->priceDisplay($this->product->prices['salesPrice']) ?> <?php echo JText::_('COM_VIRTUEMART_PRODUCTDETAILS_ADD_TO_CART') ?>
                            </a>
                        <?php } ?>
                    </div>
                </div>
                <!-- //Quick CTA -->
            </div>
            <?php
            $metakeys = $this->product->metakey;
            $metakeys = explode(',', $metakeys);
            ?>
            <!-- Tags -->
            <div class="quick-info pd-quickglance">
                <h3>Tags</h3>

                <p>
                    <?php if (count($metakeys)) foreach ($metakeys as $metakey) { ?>
                        <a title="<?php echo $metakey ?>"
                           href="index.php?option=com_virtuemart&view=category&from_search=1&keyword=<?php echo $metakey ?>"><span
                                class="badge"><?php echo $metakey ?></span></a>
                    <?php } ?>
                </p>
            </div>
            <!-- // Tags -->

            <!-- Quick Intro -->
            <div itemprop="description" class="description">
                <?php echo $this->product->product_s_desc ?>
            </div>
            <!-- // Quick Intro -->

        </div>
        <!-- // Product Intro Content -->

        <!-- Product Intro Images -->
        <div class="col-xs-12 col-md-6 pd-screen pd-screen-large pd-item-intro-img">
            <?php
            echo $this->loadTemplate($this->product->layout);
            ?>
            <!-- Carousel -->
            <?php
            echo $this->loadTemplate('carouselimages');
            ?>
            <!-- Fix for conflict carousel with mootools-more --><!-- // Carousel -->
        </div>
        <!-- // Product Intro Images -->
    </section>


    <!-- // Product Intro -->
    <!-- Item fulltext -->
    <section class="pd-item-content">

        <?php echo $this->product->product_desc ?>
        <!-- Product Features -->
        <div class="features-list pd-features">


            <?php
            /** @todo Test if content plugins modify the product description */
            echo nl2br($this->product->product_s_desc);
            ?>


        </div>
        <!-- // Product Features -->      </section>


</article>





