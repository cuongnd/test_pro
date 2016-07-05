<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_phatthanhnghean
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$doc=JFactory::getDocument();
$scriptId = "script_view_extension_default";
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
$doc->addScript(JUri::root().'/media/system/js/jquery.inputmask-3.x/js/jquery.inputmask.js');
$doc->addScript(JUri::root().'/media/system/js/jquery.inputmask-3.x/js/inputmask.js');
?>
<script type="text/javascript">

</script>
<div class="view-login-default">
    <form action="<?php echo JRoute::_('index.php?option=com_phatthanhnghean&view=login&id='.(int) $this->item->id); ?>" method="post"  name="adminForm" id="adminForm" class="form-validate">
        <div class="form-horizontal">

            <?php echo $this->display_body_form(); ?>
        </div>
        <?php echo $this->display_hidden_control_form();?>
        <?php echo JHtml::_('form.token'); ?>

    </form>
    <?php echo $this->render_toolbar() ?>
</div>