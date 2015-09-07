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
					<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
				</div>
	
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('code'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('code'); ?></div>
				</div>
		
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('image'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('image'); ?></div>
				</div>
		
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
				</div>
				
			
			</fieldset>
		</div>
		
		<?php echo JLayoutHelper::render('joomla.edit.details', $this); ?>
		

	</div>



	<div>
		<input type="hidden" name="task" value="" /> <input type="hidden"
			name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
