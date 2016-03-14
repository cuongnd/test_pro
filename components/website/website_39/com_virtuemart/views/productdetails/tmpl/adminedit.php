<?php
$doc = JFactory::getDocument();
$doc->addScript(JUri::root() . '/media/system/js/BobKnothe-autoNumeric/autoNumeric.js');
$doc->addScript(JPATH_VM_URL . '/assets/js/view_productdetails_adminedit.js');
$scriptId = 'view_categories_manager';
ob_start();
?>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $('.view-productdetail-adminedit').view_productdetails_adminedit({});
    });
</script>
<?php
$script = ob_get_clean();
$script = JUtility::remove_string_javascript($script);
$doc->addScriptDeclaration($script, "text/javascript", $scriptId);


$product = $this->item;

?>
<div class="view-productdetail-adminedit">
    <form action="index.php" method="post" class="form-horizontal" name="adminForm" id="adminForm">
        <div class="row">
            <div class="col-md-12">
                <div class="pull-left">
                    <button class="btn btn-primary" type="button"><i class="im-back"></i><?php echo JText::_('Back') ?></button>
                </div>
                <div class="pull-right">
                    <button class="btn btn-primary" type="button"><i class="im-plus"></i><?php echo JText::_('Save') ?></button>
                    <button class="btn btn-primary" type="button"><i class="im-copy"></i><?php echo JText::_('Save & clone') ?></button>
                    <button class="btn btn-primary" type="button"><i class="im-delete"></i><?php echo JText::_('Delete') ?></button>
                    <button class="btn btn-primary" type="button"><i class="en-cancel"></i><?php echo JText::_('cancel') ?></button>

                </div>
            </div>
        </div>
        <?php
        $class_left='col-md-2';
        $class_right='col-md-10';
        ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Product  name', 'input.text', 'product_name', $this->item->product_name, array('class' => 'required')); ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Product  name', 'input.text', 'product_name', $this->item->product_name, array('class' => 'required')); ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Product  name', 'input.text', 'product_name', $this->item->product_name, array('class' => 'required')); ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Product  name', 'input.text', 'product_name', $this->item->product_name, array('class' => 'required')); ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Product  name', 'input.text', 'product_name', $this->item->product_name, array('class' => 'required')); ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Product  name', 'input.text', 'product_name', $this->item->product_name, array('class' => 'required')); ?>
        <?php echo JHtml::row_control($class_left,$class_right,'Product  name', 'input.text', 'product_name', $this->item->product_name, array('class' => 'required')); ?>
        <input type="hidden" value="com_virtuemart" name="option">
        <input type="hidden" value="produtdetails" name="controller">
        <input type="hidden" value="produtdetails" name="view">
        <input type="hidden" value="save" name="task">
        <?php echo JHtml::_('form.token'); ?>
    </form>
</div>