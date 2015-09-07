<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 84 2012-08-17 07:16:08Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );
JHtmlBehavior::calendar ();
JToolBarHelper::save ();
JToolBarHelper::apply ();
JToolBarHelper::cancel ();
JHtml::_('behavior.formvalidation' );
JToolBarHelper::title ( JText::_ ( 'COM_BOOKPRO_COUPON_EDIT' ), 'user.png' );
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'coupon.cancel' || document.formvalidator.isValid(document.id('adminForm'))) {
			Joomla.submitform(task, document.getElementById('adminForm'));
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" class="form-validate" id="adminForm">
	<div class="form-horizontal">


		<div class="control-group">
			<label class="control-label" for="title"><?php echo JText::_('COM_BOOKPRO_COUPON_TITLE'); ?>
					</label>
			<div class="controls">
				<input class="text_area required" type="text" name="title" id="title" size="60" maxlength="255" value="<?php echo $this->obj->title; ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="type">
					<?php echo JText::_('COM_BOOKPRO_COUPON_TYPE'); ?>
			</label>
			<div class="controls">
				<input class="text_area required" type="text" name="type" id="type" size="60" maxlength="255" value="<?php echo $this->obj->type; ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="object_id">
					<?php echo JText::_('COM_BOOKPRO_COUPON_OBJECT_ID'); ?>
			</label>
			<div class="controls">
				<input class="text_area required" type="text" name="object_id" id="object_id" size="60" maxlength="255" value="<?php echo $this->obj->object_id; ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="code"><?php echo JText::_('COM_BOOKPRO_COUPON_CODE'); ?>
					</label>
			<div class="controls">
				<input class="text_area required" type="text" name="code" id="code" size="20" maxlength="255" value="<?php echo $this->obj->code; ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="subtract_type"><?php echo JText::_('COM_BOOKPRO_COUPON_SUBTRACT_TYPE'); ?>
					</label>
			<div class="form-inline">
						<?php echo JHtmlSelect::booleanlist('subtract_type','',$this->obj->subtract_type,JText::_('COM_BOOKPRO_PERCENTAGE'),JText::_('COM_BOOKPRO_FIX_AMOUNT'))?>
					</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="amount"><?php echo JText::_('COM_BOOKPRO_COUPON_AMOUNT'); ?>
					</label>
			<div class="controls">
				<input class="text_area" type="text" name="amount" id="amount" size="60" maxlength="255" value="<?php echo $this->obj->amount; ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="total"><?php echo JText::_('COM_BOOKPRO_COUPON_TOTAL'); ?>
					</label>
			<div class="controls">
				<input class="text_area" type="text" name="total" id="total" size="60" maxlength="255" value="<?php echo $this->obj->total; ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="remain"><?php echo JText::_('COM_BOOKPRO_COUPON_REMAIN'); ?>
					</label>
			<div class="controls">
				<input class="text_area" type="text" name="remain" id="remain" size="60" maxlength="255" value="<?php echo $this->obj->remain; ?>" />
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="publish_date"><?php echo JText::_('COM_BOOKPRO_PUBLISH_DATE'); ?>
					</label>
			<div class="controls">
						<?php echo JHtml::calendar($this->obj->publish_date, 'publish_date', 'publish_date','%Y-%m-%d')?>
					</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="unpublish_date"><?php echo JText::_('COM_BOOKPRO_UNPUBLISH_DATE'); ?>
					</label>
			<div class="controls">
						<?php echo JHtml::calendar($this->obj->unpublish_date, 'unpublish_date', 'unpublish_date','%Y-%m-%d')?>
					</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="state"><?php echo JText::_('COM_BOOKPRO_STATUS'); ?>
					</label>
			<div class="controls form-inline">
				<input type="radio" class="inputRadio" name="state" value="1" id="state_active" <?php if ($this->obj->state == 1) echo 'checked="checked"'; ?> /> <label for="state_active"><?php echo JText::_('Active'); ?> </label> <input type="radio" class="inputRadio" name="state" value="0" id="state_inactive" <?php if ($this->obj->state == 0) echo 'checked="checked"'; ?> /> <label for="state_deleted"><?php echo JText::_('Inactive'); ?> </label>
			</div>
		</div>
	</div>
	<input type="hidden" name="option" value="<?php echo OPTION; ?>" /> <input type="hidden" name="controller" value="<?php echo CONTROLLER_COUPON; ?>" /> <input type="hidden" name="task" value="save" /> <input type="hidden" name="boxchecked" value="1" /> <input type="hidden" name="cid[]" value="<?php echo $this->obj->id; ?>" id="cid" />
	 <?php echo JHTML::_('form.token'); ?>
</form>
