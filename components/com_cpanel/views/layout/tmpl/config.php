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
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/components/com_cpanel/assets/js/view_config_config.js');
$script_id = "script_view_config_config";
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-config-config').view_config_config({

        });


    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $script_id);

?>
<div class="view-config-config">
    <div class="row">
        <div class="col-md-6">
            <form class="form-horizontal">
                <?php
                $this->listPositionsSetting;
                ?>
            </form>
        </div>
    </div>
</div>