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
jimport( 'joomla.html.html.select' );
JHtml::_('behavior.modal');
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
		<fieldset class="adminform">
			<legend>
			<?php echo JText::_('COM_BOOKPRO_DISCOUNT_EDIT'); ?>
			</legend>
			<div class="form-horizontal">
				<div class="control-group">
					<label class="control-label" for="amount"><?php echo JText::_('COM_BOOKPRO_DISCOUNT_AMOUNT'); ?>
					</label>
					<div class="controls">
						<input class="text_area required" type="text" name="amount" id="amount"
						size="60" maxlength="255" value="<?php echo $this->obj->amount; ?>" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="type"><?php echo JText::_('COM_BOOKPRO_DISCOUNT_TYPE'); ?>
					</label>
					<div class="form-inline">
						<?php echo JHtmlSelect::booleanlist('type','',$this->obj->type,'Total','Percent','type_id') ?> 
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="Application"><?php echo JText::_('COM_BOOKPRO_DISCOUNT_APPLICATION'); ?>
					</label>
					<div class="controls">
						<?php echo $this->app ?>
					</div>
				</div>
			</div>

		</fieldset>

	
	
	<div class="compulsory">
	<?php echo JText::_('Compulsory items'); ?>
	</div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>" /> <input
		type="hidden" name="controller"
		value="<?php echo CONTROLLER_DISCOUNT; ?>" /> <input type="hidden"
		name="task" value="save" /> <input type="hidden" name="boxchecked"
		value="1" /> <input type="hidden" name="cid[]"
		value="<?php echo $this->obj->id; ?>" id="cid" />

		<?php echo JHTML::_('form.token'); ?>
</form>
</div>