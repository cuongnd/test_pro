<?php
defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<form action="<?php echo JRoute::_('index.php?option=com_bookpro&id=' . (int) $this->item->id); ?>" method="post" id="adminForm" name="adminForm" class="form-validate">


    <div class="row-fluid">
        <div class="span10 form-horizontal">
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('desc'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('desc'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('code'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('code'); ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('email_send_from'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('email_send_from'); ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('email_send_from_name'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('email_send_from_name'); ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('email_admin'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('email_admin'); ?></div>
                </div>

                 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('email_subject'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('email_subject'); ?></div>
                </div>

 				<div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('order_status'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('order_status'); ?></div>
                </div>

				<div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('payment_status'); ?></div>
                    <div class="controls" id="controls"><?php echo $this->form->getInput('payment_status'); ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo JText::_('COM_BOOKPRO_ADD_FIELD_ORDER'); ?></div>
                    <div class="controls" id="controls"><?php echo $this->field; ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo JText::_('COM_BOOKPRO_ADD_FIELD_CUSTOMER'); ?></div>
                    <div class="controls" id="controls"><?php echo $this->customer; ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo JText::_('COM_BOOKPRO_ADD_TABLE'); ?></div>
                    <div class="controls" id="controls"><?php echo $this->table; ?></div>
                </div>

				<div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('email_body'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('email_body'); ?></div>
                </div>

        </div>
        <?php echo JLayoutHelper::render('joomla.edit.details', $this, '', ''); ?>
    </div>
    <div>

        <input type="hidden" name="task" value="" />
        <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return'); ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
<script>
jQuery(".adddatareplate").change(function(){
	tinyMCE.execCommand("mceInsertContent",false, '{'+jQuery(this).val()+'}');
});

</script>