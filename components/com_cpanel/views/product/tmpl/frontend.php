<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_cpanel
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$doc=JFactory::getDocument();
$doc->addLessStyleSheetTest(JUri::root().'/components/com_cpanel/assets/less/view_product_frontend.less');
$doc->addScript(JUri::root().'/media/system/js/BobKnothe-autoNumeric/autoNumeric.js');
$doc->addScript(JUri::root().'/components/com_cpanel/assets/js/view_product_frontend.js');
$script_id = "script_view_product_frontend";
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-product-frontend').view_product_frontend({

        });


    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $script_id);

?>

<div class="view-product-frontend">
    <div class="row form-group">
        <div class="col-md-8">
            <img class="img-responsive" src="<?php echo $this->item->image_url ?>">
        </div>
        <div class="col-md-4">
            <h3 class="price" data-a-sep="." data-a-dec="," data-a-sign="$"><?php echo $this->item->price_monter ?></h3>
            <button type="button" class="btn btn-primary btn-lg"><i class="im-cart3"></i><?php echo JText::_('Add to cart') ?></button>
        </div>
    </div>
    <div class="row form-group" >
        <div class="col-md-8" style="text-align: center">
            <a href="<?php echo JRoute::_('index.php?option=com_cpanel&view=product&layout=demo&id='.$this->item->id) ?>" class="btn btn-primary btn-lg demo"><i class="im-earth"></i><?php echo JText::_('Demo') ?></a>
        </div>

    </div>
</div>