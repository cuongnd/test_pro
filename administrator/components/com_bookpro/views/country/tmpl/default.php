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
    		<legend><?php echo JText::_('COM_BOOKPRO_COUNTRY_EDIT'); ?></legend>
    		 <div class="form-horizontal">
	    		<div class="control-group">
					<label class="control-label" for="country_name"><?php echo JText::_('COM_BOOKPRO_COUNTRY_NAME'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="country_name" id="country_name" size="60" maxlength="255" value="<?php echo $this->obj->country_name; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="country_code"><?php echo JText::_('COM_BOOKPRO_COUNTRY_CODE'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="country_code" id="country_code" size="60" maxlength="255" value="<?php echo $this->obj->country_code; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="country_code"><?php echo JText::_('COM_BOOKPRO_COUNTRY_REGION'); ?>
					</label>
					<div class="controls">
						<?php echo $this->regions ?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="visainfo"><?php echo JText::_('COM_BOOKPRO_COUNTRY_VISA_INFORMATION'); ?>
					</label>
					<div class="controls">
						<?php
							$editor =& JFactory::getEditor();
							echo $editor->display('visainfo', $this->obj->visainfo, '550', '400', '60', '20', false);
						?>
					</div>
				</div>
    			
    			<div class="control-group">
					<label class="control-label" for="desc"><?php echo JText::_('COM_BOOKPRO_COUNTRY_DESCRIPTION'); ?>
					</label>
					<div class="controls">
						<?php
							$editor =& JFactory::getEditor();
							echo $editor->display('desc', $this->obj->desc, '550', '400', '60', '20', false);
						?>
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
			</div>
    	</fieldset>

   	<div class="compulsory"><?php echo JText::_('Compulsory items'); ?></div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_COUNTRY; ?>"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
	<!-- Use for display customers reservations -->
	
	<?php echo JHTML::_('form.token'); ?>
</form>
