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
	
    		<div class="form-horizontal">
    			<div class="control-group">
					<label class="control-label" for="firstname"><?php echo JText::_('COM_BOOKPRO_PASSENGER_FIRST_NAME'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="firstname" id="firstname" size="60" maxlength="255" value="<?php echo $this->obj->firstname; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="lastname"><?php echo JText::_('COM_BOOKPRO_PASSENGER_LAST_NAME'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="lastname" id="lastname" size="60" maxlength="255" value="<?php echo $this->obj->lastname; ?>" />
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="group_id"><?php echo JText::_('COM_BOOKPRO_PASSENGER_AGE_GROUP'); ?>
					</label>
					<div class="controls">
						<?php echo $this->cgroups?>
					</div>
				</div>
    			
				<div class="control-group">
					<label class="control-label" for="passport"><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="passport" id="passport" size="60" maxlength="255" value="<?php echo $this->obj->passport; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="ppvalid"><?php echo JText::_('COM_BOOKPRO_PASSENGER_PASSPORT_EXPIRED'); ?>
					</label>
					<div class="controls">
						<?php echo JHtml::calendar($this->obj->ppvalid, 'ppvalid', 'ppvalid','%Y-%m-%d') ?>
					</div>
				</div>
				
    			<div class="control-group">
					<label class="control-label" for="issue"><?php echo JText::_('COM_BOOKPRO_PASSENGER_ISSUE_BY'); ?>
					</label>
					<div class="controls">
						<?php echo $this->issue; ?>
					</div>
				</div>	
    			
    			<div class="control-group">
					<label class="control-label" for="birthday"><?php echo JText::_('COM_BOOKPRO_PASSENGER_BIRTHDAY'); ?>
					</label>
					<div class="controls">
						<?php echo JHtml::calendar($this->obj->birthday, 'birthday', 'birthday','%Y-%m-%d') ?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="gender"><?php echo JText::_('COM_BOOKPRO_PASSENGER_GENDER'); ?>
					</label>
					<div class="form-inline">
						<?php echo JHtmlSelect::booleanlist('gender','',$this->obj->gender,JText::_('COM_BOOKPRO_MALE'),JText::_('COM_BOOKPRO_FEMALE')) ?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="countries"><?php echo JText::_('COM_BOOKPRO_PASSENGER_COUNTRY'); ?>
					</label>
					<div class="controls">
						<?php echo $this->countries; ?>
					</div>
				</div>
    			
   	</div>
   
	<input type="hidden" name="option" value="<?php echo OPTION; ?>" /> <input
		type="hidden" name="controller"
		value="<?php echo CONTROLLER_PASSENGER; ?>" /> <input type="hidden"
		name="task" value="save" /> <input type="hidden" name="boxchecked"
		value="1" /> <input type="hidden" name="cid[]"
		value="<?php echo $this->obj->id; ?>" id="cid" />
	<?php echo JHTML::_('form.token'); ?>
</form>
