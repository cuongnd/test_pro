<?php


defined('_JEXEC') or die('Restricted access');

JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
JHtml::_('behavior.formvalidation');

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

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">
	
		<fieldset class="adminform">
    		<legend><?php echo JText::_('COM_BOOKPRO_PASSENGER_REGION_EDIT'); ?></legend>
    		<div class="form-horizontal">
    			<div class="control-group">
					<label class="control-label" for="name"><?php echo JText::_('COM_BOOKPRO_PASSENGER_REGION_NAME'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="name" id="name" size="60" maxlength="255" value="<?php echo $this->obj->name; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="state"><?php echo JText::_('COM_BOOKPRO_STATUS'); ?>
					</label>
					<div class="controls form-inline">
						<input type="radio" class="inputRadio" name="state" value="1" id="state_active" <?php if ($this->obj->state == 1) echo 'checked="checked"'; ?>/>
						<label for="state_active"><?php echo JText::_('COM_BOOKPRO_ACTIVE'); ?></label>
						<input type="radio" class="inputRadio" name="state" value="0" id="state_deleted" <?php if ($this->obj->state == 0) echo 'checked="checked"'; ?>/>
						<label for="state_deleted"><?php echo JText::_('COM_BOOKPRO_INACTIVE'); ?></label> 
					</div>
				</div>

    	</fieldset>
    	
      </div>
   
   	
   	<div class="compulsory"><?php echo JText::_('Compulsory items'); ?></div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_REGION; ?>"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
	<!-- Use for display customers reservations -->
	
	<?php echo JHTML::_('form.token'); ?>
</form>
