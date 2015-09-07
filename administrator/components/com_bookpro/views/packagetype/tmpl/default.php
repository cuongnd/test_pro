<?php /**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 84 2012-08-17 07:16:08Z quannv $
 **/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.html.html.select');
JHtml::_('behavior.modal');
AImporter::helper('bookpro', 'request');
JToolBarHelper::title(JText::_('COM_BOOKPRO_PACKAGETYPE_EDIT'));
JToolBarHelper::save();
JToolBarHelper::apply();
JToolBarHelper::cancel();
JToolBarHelper::back();
$document = JFactory::getDocument();
$orderDir = $this -> lists['order_Dir'];
$order = $this -> lists['order'];
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
alert('<?php echo JText::_('Fields highlighted in red are compulsory!'); ?>
	');
	return false;
	}
	}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate">

	<div class="form-horizontal">
		<div class="control-group">
			<label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_PACKAGETYPE_TITLE'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="title" id="title"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> title; ?>" />
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_PACKAGETYPE_DESCRIPTION')?>
			</label>
			<div class="controls">
				<?php
				$editor =JFactory::getEditor();
				echo $editor->display('desc', $this->obj->desc, '100%', '300', '50', '20', true);
				?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_STATE')?>
			</label>
			<div class="form-inline">
				<?php echo JHtmlSelect::booleanlist('state','',$this->obj->state,'Active','Inactive','state')?>
			</div>
		</div>

		
	</div>

	<input type="hidden" name="option" value="<?php echo OPTION; ?>" />
	<input type="hidden" name="controller"	value="<?php echo CONTROLLER_PACKAGETYPE; ?>" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="boxchecked" value="1" />
	<input type="hidden" name="cid[]"	value="<?php echo $this -> obj -> id; ?>" id="cid" />

	<?php echo JHTML::_('form.token'); ?>
</form>

