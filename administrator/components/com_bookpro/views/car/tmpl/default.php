<?php


defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.calendar');
JHtml::_('behavior.modal');
JHtml::_('behavior.formvalidation');

jimport('joomla.html.html.select');
JToolBarHelper::title($this->obj->id?JText::_('Edit Car'):JText::_('New Car'));
JToolBarHelper::save();
//JToolBarHelper::save2copy();
JToolBarHelper::apply();
JToolBarHelper::cancel();
JHtml::_('jquery.framework');
JHtml::_('jquery.ui');

	
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
					<label class="control-label" for="name"><?php echo JText::_('Name'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="name" id="name" size="60" maxlength="255" value="<?php echo $this->obj->name; ?>" />
					</div>
				</div>
				
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo JText::_('Code'); ?>
                    </label>
                    <div class="controls">
                        <input class="text_area required" type="text" name="code" id="code" size="60" maxlength="255" value="<?php echo $this->obj->code; ?>" />
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="name"><?php echo JText::_('Category Car'); ?>
                    </label>
                    <div class="controls">
                        <?php
                            echo $this->carcategories ;
                        ?>
                    </div>
                </div>                
				
				<div class="control-group">
					<label class="control-label" for="desc"><?php echo JText::_('Description'); ?>
					</label>
					<div class="controls">
						<?php
							 $editor=JFactory::getEditor();
						echo $editor->display('description', $this->obj->description, '240', '200', '60', '20', false);?>
					</div>
				</div>
				
				<div class="control-group">
				<label class="control-label" for="image"> <?php echo JText::_('Main Image')?>
				</label>
				<div class="controls">
					<?php $this->image = $this->obj->image;
					AImporter::tpl('images', $this->_layout, 'image');
					?>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="images"> <?php echo JText::_('Gallery')?>
				</label>
				<div class="controls">
					<?php AImporter::tpl('images', $this->_layout, 'images'); ?>
				</div>
			</div>
			
			
			<div class="control-group">
					<label class="control-label" for="units"><?php echo JText::_('Units'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="units" id="units" size="60" maxlength="255" value="<?php echo $this->obj->units; ?>" />
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
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_CAR; ?>"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
	
	<?php echo JHTML::_('form.token'); ?>
</form>
