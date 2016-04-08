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
$scriptId = "script_mod_thongbao" . $module->id;
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {

    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);

?>


<div id="mod_thongbao_<?php echo $module->id ?>" class="mod_thongbao pull-right mod_thongbao_<?php echo $module->id ?>">
    <a class="dropdown-toggle" data-toggle = "dropdown" href="#"><i class="fa-fullscreen"></i> <?php echo JText::_('Thông báo') ?></a>
    <ul class="dropdown-menu">
        <li><a href="#">Choice1</a></li>
        <li><a href="#">Choice2</a></li>
        <li><a href="#">Choice3</a></li>
        <li class="divider"></li>
        <li><a href="#">Choice..</a></li>
    </ul>
</div>

