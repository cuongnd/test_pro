<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_websitetemplatepro
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$doc=JFactory::getDocument();
$doc->addLessStyleSheetTest(JUri::root().'/components/website/website_websitetemplatepro/com_websitetemplatepro/assets/less/view_product_demo.less');
$doc->addScript(JUri::root().'/media/system/js/BobKnothe-autoNumeric/autoNumeric.js');
$doc->addScript(JUri::root().'/media/system/js/jquery-fullscreen-plugin-master/jquery.fullscreen.js');
$doc->addScript(JUri::root().'/components/website/website_websitetemplatepro/com_websitetemplatepro/assets/js/view_product_demo.js');
$script_id = "script_view_product_demo";
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-product-demo').view_product_demo({

        });


    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $script_id);

?>

<div class="view-product-demo">
    <div class="row form-group demo-header">
        <div class="col-md-4">
            <img class="image img-responsive" src="<?php echo $this->item->image_url ?>">
        </div>
        <div class="col-md-4">
            <div class="responsive-block">
                <ul id="responsivator" class="list-inline">
                    <li id="desktop" class="response active"></li>
                    <li id="tablet-portrait" class="response"></li>
                    <li id="tablet-landscape" class="response"></li>
                    <li id="iphone-portrait" class="response"></li>
                    <li id="iphone-landscape" class="response"></li>
                </ul>
            </div>
        </div>
        <div class="col-md-4">
            <h3 class="price" data-a-sep="." data-a-dec="," data-a-sign="$"><?php echo $this->item->price_monter ?></h3>
            <button type="button" class="btn btn-primary btn-lg"><i class="im-cart3"></i><?php echo JText::_('Add to cart') ?></button>
        </div>
    </div>
    <div class="row form-group" >
        <div class="col-md-12" style="text-align: center">
            <iframe class="demo" src="http://www.templatemonster.com/<?php echo  $this->item->linkdemo ?>"></iframe>
        </div>

    </div>
</div>