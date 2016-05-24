<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_footer
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$doc=JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root().'/modules/website/website_websitetemplatepro/mod_cart/assets/less/mod_cart.less');
$doc->addScript(JUri::root().'/modules/website/website_websitetemplatepro/mod_cart/assets/js/mod_cart.js');
$scriptId = "script_mod_cart_" . $module->id;
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#mod_cart_<?php echo $module->id ?>').mod_cart();
    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);

?>
<a class="dropdown-toggle" data-toggle = "dropdown" href="#"><i class="im-cart3"></i> <?php echo JText::_('Cart') ?></a>
<div class = "dropdown-menu">
    <div class="row-fluid">
        <div class="col-md-12">
            dfgfdgfg
        </div>
    </div>
</div>