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
$scriptId = "script_mod_hotro" . $module->id;
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('#mod_hotro_<?php echo $module->id ?> .dropdown-menu').on({
            "click":function(e){
                e.stopPropagation();
            }
        });
    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);

?>


<div id="mod_hotro_<?php echo $module->id ?>" class="mod_hotro pull-right mod_hotro_<?php echo $module->id ?>">
    <a class="dropdown-toggle" data-toggle = "dropdown" href="#"><i class="fa-fullscreen"></i> <?php echo JText::_('Hỗ trợ') ?></a>
    <div class = "dropdown-menu">
        <div><b><?php echo JText::_('Hotline')  ?></b>:0966742999</div>
        <div><b><?php echo JText::_('Email')  ?></b>:support@banhangonline88.com</div>
        <div class="row-fluid">

            <div class="col-md-6">

            </div>
            <div class="col-md-6">
                <img style="width: 120px;height: 50px" src="<?php echo JUri::root() ?>modules/website/website_banhangonline/mod_hotro/images/android.png">
                <img style="width: 120px; height: 50px" src="<?php echo JUri::root() ?>modules/website/website_banhangonline/mod_hotro/images/iso.jpg">
            </div>
        </div>
    </div>
</div>

