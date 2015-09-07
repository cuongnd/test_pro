<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 26 2012-07-08 16:07:54Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
JToolBarHelper::back();
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
					<label class="control-label" for="type"><?php echo JText::_('COM_BOOKPRO_CATEGORY_TYPE'); ?>
					</label>
					<div class="controls">
						<?php echo JHTML::_('select.genericlist', BookProHelper::getCatType(), 'type', $attribs, 'value', 'text', $this->obj->type) ?>
					</div>
				</div>
    			
    			<div class="control-group">
					<label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_CATEGORY_TITLE'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="title" id="title" size="60" maxlength="255" value="<?php echo $this->obj->title; ?>" />
					</div>
				</div>
    			
    			<div class="control-group">
					<label class="control-label" for="alias"><?php echo JText::_('COM_BOOKPRO_CATEGORY_ALIAS'); ?>
					</label>
					<div class="controls">
						<input class="text_area " type="text" name="alias" id="alias" size="60" maxlength="255" value="<?php echo $this->obj->alias; ?>" />
					</div>
				</div>
    			
				<div class="control-group">
					<label class="control-label" for="img"><?php echo JText::_('COM_BOOKPRO_CATEGORY_IMAGE'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="img" id="img" size="60" maxlength="255" value="<?php echo $this->obj->imgages; ?>" />
					</div>
				</div>

    			<div class="control-group">
					<label class="control-label" for="description"><?php echo JText::_('COM_BOOKPRO_CATEGORY_DESCRIPTION'); ?>
					</label>
					<div class="controls">
						<?php
							$editor =& JFactory::getEditor();
							echo $editor->display('description', $this->obj->description, '550', '400', '60', '20', false);
						?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="state"><?php echo JText::_('COM_BOOKPRO_STATUS'); ?>
					</label>
					<div class="controls form-inline">

						<input type="radio" class="inputRadio" name="state" value="1" id="state_active" <?php if ($this->airport->state == 1) echo 'checked="checked"'; ?>/>
						<label for="state_active"><?php echo JText::_('COM_BOOKPRO_ACTIVE'); ?></label>
						<input type="radio" class="inputRadio" name="state" value="<?php echo 0; ?>" id="state_deleted" <?php if ($this->airport->state == 0) echo 'checked="checked"'; ?>/>

						<label for="state_deleted"><?php echo JText::_('COM_BOOKPRO_INACTIVE'); ?></label>
					</div>
				</div>
</div>

	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_CATEGORY; ?>"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
	<!-- Use for display customers reservations -->
	
	<?php echo JHTML::_('form.token'); ?>
</form>
