<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_footer
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
$doc=JFactory::getDocument();
$doc->addStyleSheet(JUri::root().'/components/website/website_websitetemplatepro/com_websitetemplatepro/assets/less/svg-icons.css');
defined('_JEXEC') or die;
$column=4;
$list_list_product_today=array_chunk($list_product_today,$column);
?>
<?php foreach($list_list_product_today AS $list_product_today){ ?>
    <div class="row form-group">
        <?php  foreach($list_product_today AS $product){ ?>
            <div class="col-xs-<?php echo round(12/($column/2)) ?> col-sm-<?php echo round(12/$column) ?> col-lg-<?php echo round(12/$column) ?> col-md-<?php echo round(12/$column) ?>">
                <div class="product img-thumbnail">
                    <img class="img-responsive" src="<?php echo $product->image_url ?>">
                    <?php
                    $cms_type=$product->cmstype;
                    $cms_type=str_replace('svgsquare-iconicon','svg square-icon icon',$cms_type);
                    ?>
                    <b class="<?php echo $cms_type ?> thumbnail-icon"></b>
                    <h3><a class="link" href="<?php echo JRoute::_('index.php?option=com_websitetemplatepro&view=product&layout=frontend&id='.$product->id) ?>"><?php echo $product->product_name ?></a></h3>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>