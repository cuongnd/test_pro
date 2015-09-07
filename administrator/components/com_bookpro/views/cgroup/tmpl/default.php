<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 84 2012-08-17 07:16:08Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.html.html.select' );
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
JToolBarHelper::back();
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
<form class="form-validate" action="index.php" method="post" name="adminForm" id="adminForm">
	
	
			 <div class="form-horizontal">
			
				<div class="control-group">
					<label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_GROUP_TITLE'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="title" id="title"
						size="60" maxlength="255" value="<?php echo $this->obj->title; ?>" />
					</div>
				</div>
				<!-- 
				<div class="control-group">
					<label class="control-label" for="discount"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_GROUP_DISCOUNT'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="discount"
						id="discount" size="60" maxlength="255"
						value="<?php echo $this->obj->discount; ?>" />
					</div>
				</div>
				 -->
				
				<div class="control-group">
					<label class="control-label" for="age"><?php echo JText::_('COM_BOOKPRO_CUSTOMER_GROUP_AGE'); ?>
					</label>
					<div class="controls">
						<input class="text_area" type="text" name="age"
						id="age" size="60" maxlength="255"
						value="<?php echo $this->obj->age; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="state"><?php echo JText::_('COM_BOOKPRO_STATUS'); ?>
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
	
	<input type="hidden" name="option" value="<?php echo OPTION; ?>" /> <input
		type="hidden" name="controller"
		value="<?php echo CONTROLLER_CGROUP; ?>" /> <input type="hidden"
		name="task" value="save" /> <input type="hidden" name="boxchecked"
		value="1" /> <input type="hidden" name="cid[]"
		value="<?php echo $this->obj->id; ?>" id="cid" />

		<?php echo JHTML::_('form.token'); ?>
</form>

