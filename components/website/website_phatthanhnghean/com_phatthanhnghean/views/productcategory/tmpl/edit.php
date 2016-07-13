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
$filed_set=$this->form->getFieldset();
$doc=JFactory::getDocument();
$scriptId = "script_view_productcategory_edit";
$debug=true;
ob_start();
?>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-productcategory-edit').view_productcategory_edit({
            debug:<?php echo json_encode($debug) ?>

        });


    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);
$doc->addScript(JUri::root().'/media/system/js/jquery.inputmask-3.x/js/jquery.inputmask.js');
$doc->addScript(JUri::root().'/media/system/js/jquery.inputmask-3.x/js/inputmask.js');
$doc->addScript(JUri::root() . '/media/system/js/DeLorean-Ipsum-master/jquery.delorean.ipsum.js');
$doc->addScript(JUri::root().'/components/website/website_template5532788/com_phatthanhnghean/assets/js/view_productcategory_edit.js');


?>
<script type="text/javascript">

</script>
<div class="view-productcategory-edit">
    <?php echo $this->render_toolbar() ?>
    <form action="<?php echo JRoute::_('index.php?option=com_phatthanhnghean&view=extension&layout=edit&id=' . (int) $this->item->id); ?>" method="post"  name="adminForm" id="adminForm" class="form-validate">
        <?php if($debug){ ?>
            <button type="button" class="btn btn-primary auto-genera"><?php echo JText::_('Auto genera') ?></button>
        <?php } ?>
        <div class="form-horizontal">

            <?php foreach ($this->form->getFieldset() as $field) : ?>
                <div class="form-group">
                    <label for="<?php $field->name ?>" class="col-sm-2 control-label"><?php echo $field->getTitle(); ?></label>
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