<?php
defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
?>
<form action="<?php echo JRoute::_('index.php?option=com_bookpro&id=' . (int) $this->item->id); ?>" method="post" id="adminForm" name="adminForm" class="form-validate">


    <div class="row-fluid">
        <div class="span10 form-horizontal">
            <fieldset>

                <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', empty($this->item->id) ? JText::_('COM_BOOKPRO_ADDON_NEW', true) : JText::sprintf('COM_BOOKPRO_ADDON_EDIT', $this->item->id, true)); ?>
                <div class="control-group">
                    <label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_TOURS'); ?>
                    </label>			
                    <div class="controls">
                        <?php echo $this->form->getInput('tour_id'); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('title'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('price'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('price'); ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('child_price'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('child_price'); ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('ordering'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('ordering'); ?></div>
                </div>		

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('description'); ?></div>
                </div>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('inclusion'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('inclusion'); ?></div>
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
        <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return'); ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>