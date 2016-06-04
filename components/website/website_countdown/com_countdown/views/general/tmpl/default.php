<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_products
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
$filed_set=$this->form->getFieldset();
$doc=JFactory::getDocument();
$scriptId = "script_view_general_default";
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-general-default').view_general_default({


        });


    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);
$doc->addScript(JUri::root().'/media/system/js/jquery.inputmask-3.x/js/jquery.inputmask.js');
$doc->addScript(JUri::root().'/media/system/js/jquery.inputmask-3.x/js/inputmask.js');
$doc->addScript(JUri::root().'/components/website/website_template5532788/com_products/assets/js/view_general_default.js');


?>
<script type="text/javascript">

</script>
<div class="view-general-default">
    <?php echo $this->render_toolbar() ?>
    <form action="<?php echo JRoute::_('index.php?option=com_products&view=general&layout=edit&id=' . (int) $this->item->id); ?>" method="post"  name="adminForm" id="adminForm" class="form-validate">

        <div class="form-horizontal">
            <?php foreach ($this->form->getFieldset() as $field) : ?>
                <div class="form-group">
                    <label for="<?php $field->name ?>" class="col-sm-2 control-label"><?php echo $field->label; ?></label>
                    <div class="col-sm-10">
                        <?php echo $field->input; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>