<?php
jimport('joomla.html.html.select');

defined('_JEXEC') or die('Restricted access');

AHtml::title('Destination Edit','user');
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
JHtml::_('behavior.modal');
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
					<label class="control-label" for="title"><?php echo JText::_('Title'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="title" id="title" size="60" maxlength="255" value="<?php echo $this->obj->title; ?>" />
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="code"><?php echo JText::_('Code'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="code" id="code" size="60" maxlength="255" value="<?php echo $this->obj->code; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="desc"><?php echo JText::_('Description'); ?>
					</label>
					<div class="controls">
						<?php
							 $editor=JFactory::getEditor();
						echo $editor->display('description', $this->obj->description, '550', '400', '60', '20', false);?>
					</div>
				</div>        
			

                <div class="control-group">
                    <label class="control-label" for="state"><?php echo JText::_('COM_BOOKPRO_STATUS'); ?>
                    </label>
                    <div class="controls form-inline">

                        <input type="radio" class="inputRadio" name="state" value="1" id="state_active" <?php if ($this->obj->state == 1) echo 'checked="checked"'; ?>/>
                        <label for="state_active"><?php echo JText::_('COM_BOOKPRO_ACTIVE'); ?></label>
                        <input type="radio" class="inputRadio" name="state" value="<?php echo 0; ?>" id="state_deleted" <?php if ($this->obj->state == 0) echo 'checked="checked"'; ?>/>

                        <label for="state_deleted"><?php echo JText::_('COM_BOOKPRO_INACTIVE'); ?></label>
                    </div>
                </div>            
   	
   	       </div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_CAR_DESTINATION; ?>"/>
	<input type="hidden" name="task" value="save"/>
	
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
	<!-- Use for display customers reservations -->
	
	<?php echo JHTML::_('form.token'); ?>
</form>
