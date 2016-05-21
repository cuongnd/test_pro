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
JHtml::_('bootstrap.framework');
JHtml::_('formbehavior.chosen', 'select');
$doc = JFactory::getDocument();
$doc->addLessStyleSheet(JUri::root().'components/website/website_websitetemplatepro/com_websitetemplatepro/assets/less/view_config_default.less');
$doc->addScript(JUri::root().'/components/website/website_websitetemplatepro/com_websitetemplatepro/assets/js/view_config_default.js');
$scriptId = "script_view_cpanel_default";
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-config-default').view_config_default({

        });


    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);

?>
<div class="view-config-default">
    <div class=row>
        <!-- Start .row -->
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <?php
            $class_left='col-md-5';
            $class_right='col-md-5';
            ?>
            <?php echo JHtml::row_control($class_left,$class_right,'Product  name', 'input.button', 'raovat_name', $this->item->product_name, array('class' => 'required')); ?>
        </div>
    </div>

</div>
