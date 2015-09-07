<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 81 2012-08-11 01:16:36Z quannv $
 **/


defined('_JEXEC') or die('Restricted access');

JHtml::_('behavior.calendar');
jimport( 'joomla.html.html.select' );
JHtml::_('behavior.modal');
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
JToolBarHelper::back();
JHtml::_('behavior.formvalidation');	
JToolBarHelper::title(JText::_('COM_BOOKPRO_ADDON_EDIT'), 'object');	
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
					<label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_ADDON_TITLE'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="title" id="title" size="10" maxlength="255" value="<?php echo $this->obj->title; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="desc"><?php echo JText::_('COM_BOOKPRO_ADDON_DESCRIPTION'); ?>
					</label>
					<div class="controls">
						<?php
						$editor=JFactory::getEditor();
						echo $editor->display('description', $this->obj->description, '100%', '300', '60', '20', false);?>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="price"><?php echo JText::_('COM_BOOKPRO_ADDON_PRICE'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="price" id="price" size="10" maxlength="255" value="<?php echo $this->obj->price; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="child_price"><?php echo JText::_('COM_BOOKPRO_ADDON_CHILD_PRICE'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="child_price" id="child_price" size="10" maxlength="255" value="<?php echo $this->obj->child_price; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="state"><?php echo JText::_('COM_BOOKPRO_STATE'); ?>
					</label>
					<div class="controls form-inline">
						<input type="radio" class="inputRadio" name="state" value="1"
							id="state_active"
							<?php if ($this->obj->state == 1) echo 'checked="checked"'; ?> />
							<label for="state_active"><?php echo JText::_('COM_BOOKPRO_ACTIVE'); ?> </label>
							<input type="radio" class="inputRadio" name="state" value="0"
							id="state_inactive"
							<?php if ($this->obj->state == 0) echo 'checked="checked"'; ?> />
							<label for="state_deleted"><?php echo JText::_('COM_BOOKPRO_INACTIVE'); ?> </label>
					</div>
				</div>
						
    			
    			
    			
    	</div>
   
   	<div class="compulsory"><?php echo JText::_('Compulsory items'); ?></div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>"/>
	<input type="hidden" name="controller" value="<?php echo CONTROLLER_ADDON; ?>"/>
	<input type="hidden" name="task" value="save"/>
	<input type="hidden" name="boxchecked" value="1"/>
	<input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid"/>
	
	<?php echo JHTML::_('form.token'); ?>
</form>
