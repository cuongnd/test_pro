<?php
defined ( '_JEXEC' ) or die ();
?>
<form
    action="<?php echo JRoute::_('index.php?option=com_tour&&view=city&layout=edit&id='.(int) $this->item->id); ?>"
    method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset>
                <?php echo JHtml::_ ( 'bootstrap.startPane', 'myTab', array ('active' => 'details'));?>
                <?php echo JHtml::_ ( 'bootstrap.addPanel', 'myTab', 'details', empty ( $this->item->id ) ? JText::_( 'COM_FOLIO_NEW_FOLIO', true ) : JText::sprintf ( 'COM_FOLIO_EDIT_FOLIO', $this->item->id, true ) );?>
                <?php echo $this->form->renderField('name'); ?>
                <?php echo $this->form->renderField('idcountry'); ?>


                <?php echo JHtml::_('bootstrap.endPanel'); ?>
                <input type="hidden" name="task" value="" />
                <?php echo JHtml::_('form.token'); ?>
                <?php echo JHtml::_('bootstrap.endPane'); ?>
            </fieldset>
        </div>
    </div>
</form>