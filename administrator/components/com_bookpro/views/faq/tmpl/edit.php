<?php


defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

?>
<form
	action="<?php echo JRoute::_('index.php?option=com_bookpro&id=' . (int) $this->item->id); ?>"
	method="post" id="adminForm" name="adminForm" class="form-validate">



	<div class="row-fluid">
		<div class="span10 form-horizontal">
			<fieldset>
                          
			<div class="control-group">
<!--                            
			<label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_TOURS'); ?>
					</label>			
				<div class="controls">
					 <?php echo $this->form->getInput('tour_id'); ?>
				</div>
			</div>
			-->
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('type'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('obj_id'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('obj_id'); ?></div>
				</div>	
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('desc'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('desc'); ?></div>
				</div>
				
			
			</fieldset>
		</div>
		<?php echo JLayoutHelper::render('joomla.edit.details', $this); ?>

	</div>

                
    


	<div>
                <?php echo $this->form->getInput('tour_id',null,$this->tour_id); ?> 
		<input type="hidden" name="task" value="" /> 
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
