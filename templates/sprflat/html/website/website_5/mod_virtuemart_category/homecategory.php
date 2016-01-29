<?php
$doc = JFactory::getDocument();
$doc->addLessStyleSheetTest(JUri::root() . '/templates/sprflat/html/website/website_39/mod_virtuemart_category/assets/less/homcategory.less');
$category_name = $params->get('config_layout.on_browser.home_page_category_config.category_name', '');
$parent_category_id = $params->get('config_layout.on_browser.home_page_category_config.parent_category_id', 0);
$parent_category_of_products = $params->get('config_layout.on_browser.home_page_category_config.parent_category_of_products', 0);
$shortCategoryModel = VmModel::getModel('shortedcategory');
$shortProductModel = VmModel::getModel('shortedproduct');
$categories = $shortCategoryModel->getCategory($parent_category_id, false);
$html = mod_virtuemartCategoryHelper::render_vertical_mega_menu(' ', array(), $parent_category_id);
$products = $shortProductModel->getProductsInCategory($parent_category_of_products);
$column = 4;
$row = 3;

$product = array_pop($products);
$list_products = array_chunk($products, $column);

?>
<div class="row form-group home-category">
    <div class="col-lg-3">
        <div class="row">
            <div class="col-lg-2">
                <i class="im-search"></i>
            </div>
            <div class="col-lg-10">
                <h5><?php echo $category_name ?></h5>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <i class="im-search"></i>
            </div>
            <div class="col-lg-6">
                <i class="im-search"></i>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php echo $html ?>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="main product">
            <h4><?php echo $product->product_name ?></h4>
            <img class="img-responsive"
                 src="<?php echo $product->image_url ? $product->image_url : $product->file_url ?>">
        </div>
    </div>
    <div class="col-lg-6">
        <?php
        $i = 0;
        ?>
        <?php
        foreach ($list_products as $products) {
            if ($i >= 3) {
                break;
            }

            ?>
            <div class="row form-group">
                <?php foreach ($products as $product) { ?>
                    <div class="col-lg-<?php echo round(12 / $column) ?>">
                        <div class="product">
                            <img class="img-responsive"
                                 src="<?php echo $product->image_url ? $product->image_url : $product->file_url ?>">
                            <h5><?php echo $product->product_name ?></h5>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <?php
            $i++;
        }
        ?>
    </div>
</div>