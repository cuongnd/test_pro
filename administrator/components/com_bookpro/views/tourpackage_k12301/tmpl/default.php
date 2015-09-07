<?php

/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 45 2012-07-12 10:42:37Z quannv $
 **/
defined ( '_JEXEC' ) or die ( 'Restricted access' );

JToolBarHelper::save ();
JToolBarHelper::apply ();
JToolBarHelper::cancel ();
JHtml::_ ( 'behavior.formvalidation' );
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		var form = document.adminForm;
		if(form.packagetype_id.value==0 & task !== 'cancel' )
		{
			alert('please choose Package Type');
			return false;
		}
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
			alert('<?php echo JText::_('Fields highlighted in red are compulsory!'); ?>	');
		return false;
		}
	}
</script>

			
<form action="index.php" method="post" name="adminForm" id="adminForm"
	class="form-validate">

	<div class="form-horizontal">

		<div class="control-group">
			<label class="control-label" for="tours"><?php echo JText::_('COM_BOOKPRO_TOUR'); ?></label>
			<div class="controls">
				<?php echo $this -> tours; ?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="packagetypes"><?php echo JText::_('COM_BOOKPRO_PACKAGETYPE'); ?></label>
			<div class="controls">
				<?php echo $this -> packagetypes; ?>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="roomtypes"><?php echo JText::_('COM_BOOKPRO_ROOMTYPE'); ?></label>
			<div class="controls">
				<?php echo $this -> roomtypes; ?>
			</div>
		</div>
        
        <div class="control-group">
            <label class="control-label" for="roomtypes"><?php echo JText::_('COM_BOOKPRO_HOTELS'); ?></label>
            <div class="controls">
                <?php echo $this -> hotels; ?>
            </div>
        </div>
                
		<!-- 
		<div class="control-group">
			<label class="control-label" for="pickup"><?php echo JText::_('COM_BOOKPRO_TOUR_PACKAGE_NAME'); ?></label>
			<div class="controls">
				<input class="text_area required" type="text" name="title"
				id="title" size="60" maxlength="255"
				value="<?php echo $this -> obj -> title; ?>" />
			</div>
		</div>
		 -->

		<div class="control-group">
			<label class="control-label" for="tourgroups"><?php echo JText::_('COM_BOOKPRO_PACKAGE_PAX_MIN'); ?></label>
			<div class="controls">
				<?php echo $this -> tourgroups; ?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="desc"><?php echo JText::_('COM_BOOKPRO_TOUR_PACKAGE_DESCRIPTION'); ?></label>
			<div class="controls">
				<?php
				
				$editor = JEditor::getInstance ();
				echo $editor->display ( 'desc', $this->obj->desc, '100%', '200', '20', '', true );
				?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="desc"><?php echo JText::_('Status'); ?></label>
			<div class="form-inline">
				<?php echo JHtmlSelect::booleanlist('state','',$this->obj->state,'Publish','UnPublish','id_state')?>
			</div>
		</div>

	</div>

	<input type="hidden" name="option" value="<?php echo OPTION; ?>" /> <input
		type="hidden" name="controller"
		value="<?php echo CONTROLLER_TOURPACKAGE; ?>" /> <input type="hidden"
		name="task" value="save" /> <input type="hidden" name="boxchecked"
		value="1" /> <input type="hidden" name="cid[]"
		value="<?php echo $this -> obj -> id; ?>" id="cid" />
	<!-- Use for display customers reservations -->

	<?php echo JHTML::_('form.token'); ?>

</form>
