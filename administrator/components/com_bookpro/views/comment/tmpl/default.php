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
JToolBarHelper::title(JText::_('COM_BOOKPRO_COMMENT_EDIT'));
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
			<label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_COMMENT_TITLE'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="title" id="title"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> title; ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="parentid"><?php echo JText::_('COM_BOOKPRO_COMMENT_PARENTID'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="parentid" id="parentid"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> parentid; ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="obj_id"><?php echo JText::_('COM_BOOKPRO_COMMENT_OBJID'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="obj_id" id="obj_id"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> obj_id; ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="ip"><?php echo JText::_('COM_BOOKPRO_COMMENT_IP'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="ip" id="ip"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> ip; ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="name"><?php echo JText::_('COM_BOOKPRO_COMMENT_NAME'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="name" id="name"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> name; ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="comment"><?php echo JText::_('COM_BOOKPRO_COMMENT'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="comment" id="comment"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> comment; ?>" />
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="comment"><?php echo JText::_('COM_BOOKPRO_COMMENT_DATE'); ?></label>
			<div class="controls">
				<?php echo JHtml::calendar($this->obj->date, 'date', 'date','%Y-%m-%d') ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="pickup"> <?php echo JText::_('COM_BOOKPRO_STATE')?>
			</label>
			<div class="form-inline">
				<?php echo JHtmlSelect::booleanlist('state','',$this->obj->state,'Active','Inactive','state')?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="locked"><?php echo JText::_('COM_BOOKPRO_COMMENT_LOCKED'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="locked" id="locked"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> locked; ?>" />
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="email"><?php echo JText::_('COM_BOOKPRO_COMMENT_EMAIL'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="email" id="email"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> email; ?>" />
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="website"><?php echo JText::_('COM_BOOKPRO_COMMENT_WEBSITE'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="website" id="website"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> website; ?>" />
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="star"><?php echo JText::_('COM_BOOKPRO_COMMENT_STAR'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="star" id="star"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> star; ?>" />
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="userid"><?php echo JText::_('COM_BOOKPRO_COMMENT_USERID'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="userid" id="userid"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> userid; ?>" />
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="option"><?php echo JText::_('COM_BOOKPRO_COMMENT_OPTION'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="option" id="option"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> option; ?>" />
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="voted"><?php echo JText::_('COM_BOOKPRO_COMMENT_VOTED'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="voted" id="voted"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> voted; ?>" />
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="referer"><?php echo JText::_('COM_BOOKPRO_COMMENT_REFERER'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="referer" id="referer"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> referer; ?>" />
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="p0"><?php echo JText::_('COM_BOOKPRO_COMMENT_P0'); ?></label>
			<div class="controls">
				<input class="text_area" type="text" name="p0" id="p0"
				size="60" maxlength="255"
				value="<?php echo $this -> obj -> p0; ?>" />
			</div>
		</div>
	</div>

	<input type="hidden" name="option" value="<?php echo OPTION; ?>" />
	<input type="hidden" name="controller"	value="<?php echo CONTROLLER_COMMENT; ?>" />
	<input type="hidden" name="task" value="save" />
	<input type="hidden" name="boxchecked" value="1" />
	<input type="hidden" name="cid[]"	value="<?php echo $this -> obj -> id; ?>" id="cid" />

	<?php echo JHTML::_('form.token'); ?>
</form>

