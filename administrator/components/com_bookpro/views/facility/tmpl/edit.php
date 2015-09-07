<?php


    defined('_JEXEC') or die;             
    JHtml::_('behavior.formvalidation');
    JHtml::_('formbehavior.chosen', 'select');
    if($this->item->id)
    {
        $type=$this->item->type;
        $object_id=$this->item->object_id;
    }else{
        $input=JFactory::getApplication()->input;
        $type=$input->get('type','','string');
        $object_id=$input->get('object_id',0,'int');
    }
    $this->form->setValue('type', null, $type);
    $this->form->setValue('object_id', null, $object_id);
?>
<form action="<?php echo JRoute::_('index.php?option=com_bookpro&id=' . (int) $this->item->id); ?>" method="post" id="adminForm" name="adminForm" class="form-validate">


    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset>

                <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_BOOKPRO_NEW_FACILITY', true) : JText::sprintf('COM_BOOKPRO_EDIT_FACILITY', $this->item->id, true)); ?>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('id'); ?></div>
                </div>
                 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
                    <div class="controls"><?php  echo $this->form->getInput('type'); ?></div>
                </div>
                 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('object_id'); ?></div>
                    <div class="controls"><?php  echo $this->form->getInput('object_id'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('image'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('image'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('desc'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('desc'); ?></div>
                </div>

             	



                <?php echo JHtml::_('bootstrap.endTab'); ?>
                <?php $fieldSets = $this->form->getFieldsets('metadata'); ?>
                <?php foreach ($fieldSets as $name => $fieldSet) : ?>
                    <?php $metadatatabs = 'metadata-' . $name; ?>
                    <?php echo JHtml::_('bootstrap.addTab', 'myTab', $metadatatabs, JText::_($fieldSet->label, true)); ?>
                    <?php echo $this->loadTemplate('metadata'); ?>
                    <?php echo JHtml::_('bootstrap.endTab'); ?>
                    <?php endforeach; ?>



            </fieldset>	
        </div>
        <?php echo JLayoutHelper::render('joomla.edit.details', $this); ?>

    </div>



    <div>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="type" value="<?php echo $type ?>" />
        <input type="hidden" name="object_id" value="<?php echo $object_id ?>" />

        <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>