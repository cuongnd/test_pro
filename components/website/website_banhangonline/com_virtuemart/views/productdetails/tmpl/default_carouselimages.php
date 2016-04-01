<?php
/*echo "<pre>";
print_r($this->product->images);
die;*/
/**
 *
 * Show the product details page
 *
 * @package	VirtueMart
 * @subpackage
 * @author Max Milbers, Valerie Isaksen

 * @link http://www.virtuemart.net
 * @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * VirtueMart is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * @version $Id: default_images.php 6188 2012-06-29 09:38:30Z Milbo $
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');
if(!$this->product->images[0]->virtuemart_media_id)
    return;
$document = JFactory::getDocument ();
$count_images = count ($this->product->images);
if(!$count_images)
    return;
JHtml::_('jquery.framework');
if (!empty($this->product->images)) {
    $image = $this->product->images[0];
    $image->file_url=$this->product->virtuemart_product_id<72686?$image->file_url:$image->root_image;
    $image->file_url_thumb=$this->product->virtuemart_product_id<72686?$image->file_url_thumb:$image->root_image;
}
?>
<div id="carousel-example-generic" style="height: 311px" class="product-image-slide carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">

        <?php if ($count_images > 1) {
            for ($i = 0; $i < $count_images; $i++) {
        ?>
        <li data-target="#carousel-example-generic" data-slide-to="<?php echo $i ?>" class="<?php echo $i===0?'active':'' ?>"></li>
        <?php }
        }
        ?>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner">
        <div class="item active">
            <?php echo $image->displayMediaFull("",true,"rel='vm-additional-images'"); ?>
            <div class="carousel-caption">
                ...
            </div>
        </div>
        <?php if ($count_images > 1) {
         for ($i = 0; $i < $count_images; $i++) {
                $image = $this->product->images[$i];
                $image->file_url=$this->product->virtuemart_product_id<72686?$image->file_url:$image->root_image;
                $image->file_url_thumb=$this->product->virtuemart_product_id<72686?$image->file_url_thumb:$image->root_image;
                ?>
        <div class="item">
            <?php
            echo $image->displayMediaFull('class="product-image" style="cursor: pointer"',false,"");
            ?>
            <div class="carousel-caption">
                ...
            </div>
        </div>
            <?php } ?>
        <?php } ?>
    </div>

    <!-- Controls -->
    <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left"></span>
    </a>
    <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right"></span>
    </a>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($){
        $('.product-image-slide').carousel();
    });

</script>
