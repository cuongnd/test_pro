<?php
defined ( '_JEXEC' ) or die ();
?>
<form
    action="<?php echo JRoute::_('index.php?option=com_tour&layout=edit&id='.(int) $this->item->id); ?>"
    method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset>
                <?php echo JHtml::_ ( 'bootstrap.startPane', 'myTab', array ('active' => 'details'));?>
                <?php echo JHtml::_ ( 'bootstrap.addPanel', 'myTab', 'details', empty ( $this->item->id ) ? JText::_( 'COM_FOLIO_NEW_FOLIO', true ) : JText::sprintf ( 'COM_FOLIO_EDIT_FOLIO', $this->item->id, true ) );?>
                <?php echo $this->form->renderField('name'); ?>
                <?php echo $this->form->renderField('title'); ?>
                <?php echo $this->form->renderField('code'); ?>
                <?php echo $this->form->renderField('description'); ?>
                <?php echo $this->form->renderField('brief_itinerary'); ?>
                <?php echo $this->form->renderField('long_itinerary'); ?>
                <?php echo $this->form->renderField('length'); ?>
                <?php echo $this->form->renderField('start_city'); ?>
                <?php echo $this->form->renderField('end_city'); ?>
                <?php echo $this->form->renderField('trip_note'); ?>
                <?php echo $this->form->renderField('grade'); ?>
                <?php echo $this->form->renderField('type'); ?>
                <?php echo $this->form->renderField('groupsize'); ?>
                <?php echo $this->form->renderField('passenger_age'); ?>
                <?php echo $this->form->renderField('service_class'); ?>
                <?php echo $this->form->renderField('idtourstyle'); ?>
                <?php echo $this->form->renderField('idphotos'); ?>
                <?php echo $this->form->renderField('idtariff'); ?>
                <?php echo $this->form->renderField('what-to-see'); ?>
                <?php echo $this->form->renderField('state'); ?>


                <?php echo JHtml::_('bootstrap.endPanel'); ?>
                <input type="hidden" name="task" value="" />
                <?php echo JHtml::_('form.token'); ?>
                <?php echo JHtml::_('bootstrap.endPane'); ?>
            </fieldset>
        </div>
    </div>
</form>