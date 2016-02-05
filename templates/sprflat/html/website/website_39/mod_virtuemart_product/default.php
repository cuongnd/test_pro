<?php // no direct access
echo  "sfsfsd";
return;
JHtml::_('jquery.framework');

$doc = JFactory::getDocument();
$doc->addStyleSheet(JUri::root() . '/modules/mod_virtuemart_product/assets/css/style.css');
$doc->addStyleSheet(JUri::root() . '/components/com_virtuemart/assets/css/vmcustom.css');
$doc->addScript(JUri::root() . '/modules/mod_virtuemart_product/assets/js/javacript.js');
?>





<?php

defined('_JEXEC') or die('Restricted access');
?>


<?php foreach ($products as $product) { ?>
    <div id="product-<?php echo $product->virtuemart_product_id ?>"
         data-product-id="<?php echo $product->virtuemart_product_id ?>"
         class="col-lg-3 col-md-4 col-sm-6  item-product item-product-<?php echo $product->layout ?>">
            <?php
            require JModuleHelper::getLayoutPath('mod_virtuemart_product', 'default_' . $product->layout);
            ?>

    </div>
<?php } ?>



