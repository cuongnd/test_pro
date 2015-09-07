<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<form name="adminForm" id="adminForm" class="pointsForm" method="post" enctype="multipart/form-data">
<div class="row-fluid">

	<div class="span6">
		<div class="row-fluid">
			<div class="span12 widget-box">
				<h3><?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_GENERAL' );?></h3>

				<div class="es-controls-row">
					<div class="span5">
						<label for="page_title"><?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_COMMAND' );?></label>
					</div>
					<div class="span7">
						<input type="text" class="input-full" value="<?php echo $point->command;?>" name="command" disabled="disabled" />
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span5">
						<label for="page_title"><?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_EXTENSION' );?></label>
					</div>
					<div class="span7">
						<input type="text" class="input-full" value="<?php echo $point->extension;?>" name="extension" disabled="disabled" />
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span5">
						<label for="points-title"><?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_TITLE' );?></label>
						<i data-placement="bottom"
							data-title="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_TITLE' , true );?>"
							data-content="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_TITLE_DESC' , true );?>"
							data-es-provide="popover"
							class="icon-es-help pull-right"
							data-original-title=""></i>
					</div>
					<div class="span7">
						<input type="text" class="input-full" value="<?php echo $point->title;?>" name="title" id="points-title" />
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span5">
						<label for="points-points"><?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_POINTS' );?></label>
						<i data-placement="bottom"
							data-title="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_POINTS' , true );?>"
							data-content="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_POINTS_DESC' , true );?>"
							data-es-provide="popover"
							class="icon-es-help pull-right"
							data-original-title=""></i>
					</div>
					<div class="span7">
						<input type="text" class="input-small center" value="<?php echo $point->points;?>" name="points" id="points-points" />
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span5">
						<label for="points-alias"><?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_ALIAS' );?></label>
						<i data-placement="bottom"
							data-title="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_ALIAS' , true );?>"
							data-content="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_ALIAS_DESC' , true );?>"
							data-es-provide="popover"
							class="icon-es-help pull-right"
							data-original-title=""></i>
					</div>
					<div class="span7">
						<input type="text" class="input-full" value="<?php echo $point->alias;?>" name="alias" id="points-alias" />
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span5">
						<label for="page_title"><?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_DESCRIPTION' );?></label>
						<i data-placement="bottom"
							data-title="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_DESCRIPTION' , true );?>"
							data-content="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_DESCRIPTION_DESC' , true );?>"
							data-es-provide="popover"
							class="icon-es-help pull-right"
							data-original-title=""></i>
					</div>
					<div class="span7">
						<textarea name="description" class="input-full"><?php echo $point->description;?></textarea>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span5">
						<label for="page_title"><?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_CREATED' );?></label>
						<i data-placement="bottom"
							data-title="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_CREATED' , true );?>"
							data-content="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_CREATED_DESC' , true );?>"
							data-es-provide="popover"
							class="icon-es-help pull-right"
							data-original-title=""></i>
					</div>
					<div class="span7">
						<?php echo $this->html( 'form.calendar' , 'created' , $point->created ); ?>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span5">
						<label for="page_title"><?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_STATE' );?></label>
						<i data-placement="bottom"
							data-title="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_STATE' , true );?>"
							data-content="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_STATE_DESC' , true );?>"
							data-es-provide="popover"
							class="icon-es-help pull-right"
							data-original-title=""></i>
					</div>
					<div class="span7">
						<?php echo $this->html( 'grid.boolean' , 'state' , $point->state ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="span6">
<!-- 		<div class="row-fluid">
			<div class="span12 widget-box">
				<h3><?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_ADVANCED' );?></h3>

				<div class="es-controls-row">
					<div class="span5">
						<label for="page_title"><?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_THRESHOLD' );?></label>
					</div>
					<div class="span7">
						<input type="text" class="input-full" value="<?php echo $point->threshold;?>" name="threshold" data-es-provide="popover"
						data-title="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_THRESHOLD' , true );?>"
						data-content="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_THRESHOLD_DESC' , true ); ?>"
						placeholder="0" />
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span5">
						<label for="page_title"><?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_INTERVAL' );?></label>
					</div>
					<div class="span7">
						<input type="text" class="input-full" value="<?php echo $point->interval;?>" name="interval"
						data-es-provide="popover"
						data-position="left"
						data-title="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_INTERVAL' , true );?>"
						data-content="<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_FORM_INTERVAL_DESC' , true ); ?>"
						placeholder="<?php echo JText::_( '0' , true );?>" />
					</div>
				</div>
			</div>
		</div> -->
	</div>

</div>

<input type="hidden" name="option" value="com_easysocial" />
<input type="hidden" name="controller" value="points" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $point->id; ?>" />
<?php echo JHTML::_( 'form.token' );?>

</form>
