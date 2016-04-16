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
                <div class="form-group">
                    <label for="inputEmail3"
                           class="col-sm-5 control-label"><?php echo JText::_('set request update website') ?></label>
                    <div class="col-sm-7">
                        <button type="button"
                                class="btn btn-primary form-control set_request_update_website"><?php echo JText::_('set request update website') ?></button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>