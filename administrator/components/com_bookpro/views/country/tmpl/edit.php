<?php


defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

?>
<form action="<?php echo JRoute::_('index.php?option=com_bookpro&id=' . (int) $this->item->id); ?>" method="post" id="adminForm" name="adminForm" class="form-validate">
	
	
	<div class="row-fluid">
		<div class="span10 form-horizontal">
		<fieldset>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('country_name'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('country_name'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('country_code'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('country_code'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('phone_code'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('phone_code'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('flag'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('flag'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('image_map'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('image_map'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('guarantee'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('guarantee'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('club'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('club'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('reviews'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('reviews'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('intro'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('intro'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('desc'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('desc'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('asian_air'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('asian_air'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('worldwide_air'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('worldwide_air'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('air_carries'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('air_carries'); ?></div>
				</div>
			</fieldset>	
			</div>
			<?php echo JLayoutHelper::render('joomla.edit.details', $this); ?>
		
	</div>
	
	
	
	<div>
		<input type="hidden" name="task" value="" /> 
		<input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>