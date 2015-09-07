<?php


defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtmlBehavior::modal('a.jbmodal');
JHtmlBehavior::modal('a.modal');
AImporter::css('general');
AImporter::js('view-images','common');
$this->obj = $this->item;
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
					<div class="control-label"><?php echo $this->form->getLabel('type'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('type'); ?></div>
				</div>
				<div class="control-group">
					<label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_TOUR_GALLERY') ?>
					</label>
					<div class="controls">
						<?php AImporter::tpl('ajaximage', 'form', 'image'); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('customer_id'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('customer_id'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('rank'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('rank'); ?></div>
				</div>	
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('content'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('content'); ?></div>
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
