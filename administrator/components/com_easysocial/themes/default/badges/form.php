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
				<h3><?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_GENERAL' );?></h3>

				<div class="es-controls-row">
					<div class="span5">
						<label for="title"><?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_TITLE' );?></label>
						<i data-placement="bottom"
							data-title="<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_TITLE' , true );?>"
							data-content="<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_TITLE_DESC' , true );?>"
							data-es-provide="popover"
							class="icon-es-help pull-right"
							data-original-title=""></i>
					</div>
					<div class="span7">
						<input type="text" class="input-full" value="<?php echo $badge->title;?>" name="title" id="title"
						placeholder="<?php echo JText::_( 'COM_EASYSOCIAL_ACCESS_RULE_RULE_TITLE_PLACEHOLDER' , true );?>" />
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span5">
						<label for="alias"><?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_ALIAS' );?></label>
						<i data-placement="bottom"
							data-title="<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_ALIAS' , true );?>"
							data-content="<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_ALIAS_DESC' , true );?>"
							data-es-provide="popover"
							class="icon-es-help pull-right"
							data-original-title=""></i>
					</div>
					<div class="span7">
						<input type="text" class="input-full" value="<?php echo $badge->alias;?>" name="alias" id="alias"
						placeholder="<?php echo JText::_( 'COM_EASYSOCIAL_ACCESS_RULE_RULE_TITLE_PLACEHOLDER' , true );?>" />
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span5">
						<label for="frequency"><?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_FREQUENCY' );?></label>
						<i data-placement="bottom"
							data-title="<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_FREQUENCY' , true );?>"
							data-content="<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_FREQUENCY_DESC' , true );?>"
							data-es-provide="popover"
							class="icon-es-help pull-right"
							data-original-title=""></i>
					</div>
					<div class="span7">
						<input type="text" class="input-mini center" value="<?php echo $badge->frequency;?>" id="frequency" name="frequency" /> <?php echo JText::_( 'COM_EASYSOCIAL_TIMES' ); ?>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span5">
						<label for="description"><?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_DESCRIPTION' );?></label>
						<i data-placement="bottom"
							data-title="<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_DESCRIPTION' , true );?>"
							data-content="<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_DESCRIPTION_DESC' , true );?>"
							data-es-provide="popover"
							class="icon-es-help pull-right"
							data-original-title=""></i>
					</div>
					<div class="span7">
						<textarea name="description" id="description" class="input-full"><?php echo $badge->description;?></textarea>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span5">
						<label for="description"><?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_HOW_TO' );?></label>
						<i data-placement="bottom"
							data-title="<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_HOW_TO' , true );?>"
							data-content="<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_HOW_TO_DESC' , true );?>"
							data-es-provide="popover"
							class="icon-es-help pull-right"
							data-original-title=""></i>
					</div>
					<div class="span7">
						<textarea name="howto" id="howto" class="input-full"><?php echo $badge->howto;?></textarea>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span5">
						<label for="page_title"><?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_CREATED' );?></label>
						<i data-placement="bottom"
							data-title="<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_CREATED' , true );?>"
							data-content="<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_CREATED_DESC' , true );?>"
							data-es-provide="popover"
							class="icon-es-help pull-right"
							data-original-title=""></i>
					</div>
					<div class="span7">
						<?php echo $this->html( 'form.calendar' , 'created' , $badge->created ); ?>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span5">
						<label for="page_title"><?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_STATE' );?></label>
						<i data-placement="bottom"
							data-title="<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_STATE' , true );?>"
							data-content="<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_STATE_DESC' , true );?>"
							data-es-provide="popover"
							class="icon-es-help pull-right"
							data-original-title=""></i>
					</div>
					<div class="span7">
						<?php echo $this->html( 'grid.boolean' , 'state' , $badge->state ); ?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="span6">
		<div class="widget-box">
		<h3><?php echo JText::_( 'About Badge' );?></h3>

		<table class="table table-striped table-noborder">
			<tbody>
				<tr>
					<td width="20%">
						<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_COMMAND' );?>:
					</td>
					<td>
						<strong><?php echo $badge->command; ?></strong>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_EXTENSION' ); ?>:
					</td>
					<td>
						<strong><?php echo $badge->getExtensionTitle(); ?></strong>
					</td>
				</tr>
				<tr>
					<td>
						<?php echo JText::_( 'COM_EASYSOCIAL_BADGES_FORM_ACHIEVERS' ); ?>:
					</td>
					<td>
						<strong><?php echo $badge->getTotalAchievers();?> <?php echo JText::_( 'COM_EASYSOCIAL_ACHIEVERS' ); ?></strong>
					</td>
				</tr>
			</tbody>
		</table>
		</div>
	</div>

</div>

<input type="hidden" name="option" value="com_easysocial" />
<input type="hidden" name="controller" value="badges" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="id" value="<?php echo $badge->id; ?>" />
<?php echo JHTML::_( 'form.token' );?>

</form>
