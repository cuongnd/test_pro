<?php


defined('_JEXEC') or die;
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

?>
<script type="text/javascript">       

Joomla.submitbutton = function(task) {
	
    var form = document.adminForm;

    if (task == 'cancel') {

       form.task.value = task;

       form.submit();

       return;

    }
   
    if (document.formvalidator.isValid(form)) {
		
       form.task.value = task;

     	form.submit();

     }

     else {

       alert('<?php echo JText::_('Fields highlighted in red are compulsory!'); ?>');

       return false;

     }

 }

</script>  
<form action="<?php echo JRoute::_('index.php?option=com_bookpro&controller=flight&task=saveStats'); ?>" method="post" id="adminForm" name="adminForm" class="form-validate">


	<div class="row-fluid">
		<div class="span6">
			<fieldset class="form-horizontal">
				<legend><?php echo JText::_('COM_BOOKPRO_FLIGHT_API_SEARCH') ?></legend>
				 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('desfrom'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('desfrom'); ?></div>
                </div>
				 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('desto'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('desto'); ?></div>
                </div>
	
				 <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('start'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('start'); ?></div>
                </div>
				<div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('end'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('end'); ?></div>
                </div>
                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('frequency'); ?></div>
                    <div class="controls"><?php echo $this->form->getInput('frequency'); ?></div>
                </div>
				
				
		
				
				
			
			</fieldset>
		</div>
		<div class="span6">
			<?php echo $this->loadTemplate('base'); ?>
			<?php echo $this->loadTemplate('eco'); ?>
			<?php echo $this->loadTemplate('bus'); ?>
		</div>
		
		
		

	</div>



	<div>
		<input type="hidden" name="option" value="com_bookpro" />
		<input type="hidden" name="controller" value="flight" />
		
		<input type="hidden" name="task" value="" /> <input type="hidden"
			name="return" value="<?php echo JRequest::getCmd('return');?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
