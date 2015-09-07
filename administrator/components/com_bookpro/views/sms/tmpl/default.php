<?php
jimport('joomla.html.html.select');

defined('_JEXEC') or die('Restricted access');

AHtml::title('Destination Edit','user');
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
$config=AFactory::getConfig();
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
    		<legend><?php echo JText::_('SMS'); ?></legend>
    		<div class="form-horizontal">
    			<div class="control-group">
					<label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_SMS_TITLE'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="title" id="title" size="60" maxlength="255" value="<?php echo $this->obj->title; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="from"><?php echo JText::_('COM_BOOKPRO_FROM'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="from" id="from" size="60" maxlength="255" value="<?php echo $this->obj->from; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="to"><?php echo JText::_('COM_BOOKPRO_TO'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="to" id="to" size="60" maxlength="255" value="<?php echo $this->obj->to; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="content"><?php echo JText::_('COM_BOOKPRO_SMS_CONTENT'); ?>
					</label>
					<div class="controls">
						<textarea rows="8" cols="40"><?php echo $this->obj->content; ?></textarea>
					</div>
				</div>
				
    			<div class="control-group">
					<label class="control-label" for="schedule_time"><?php echo JText::_('COM_BOOKPRO_SMS_SCHEDULED_TIME'); ?>
					</label>
					<div class="controls">
						<?php echo JHtml::calendar(JFactory::getDate($this->obj->schedule_time)->toFormat('%d-%m-%Y %H:%M:%S'), 'schedule_time','schedule_time','%d-%m-%Y %H:%M:%S')?>
					</div>
				</div>
 
    	</fieldset>
    	
        
    </div>
   
   	
   	<div class="compulsory"><?php echo JText::_('Compulsory items'); ?></div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_AIRPORT; ?>"/>
	<input type="hidden" name="task" value="save"/>
	
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
	<!-- Use for display customers reservations -->
	
	<?php echo JHTML::_('form.token'); ?>
</form>
