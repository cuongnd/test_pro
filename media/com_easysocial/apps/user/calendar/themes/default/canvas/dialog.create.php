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
<dialog>
	<width>600</width>
	<height>650</height>
	<selectors type="json">
	{
		"{createButton}"	: "[data-create-button]",
		"{updateButton}"	: "[data-update-button]",
		"{cancelButton}"	: "[data-cancel-button]",
		"{title}"			: "[data-schedule-title]",
		"{description}"		: "[data-schedule-description]",
		"{reminder}"		: "[data-schedule-reminder]",
		"{stream}"			: "[data-schedule-stream]",
		"{start}"			: "[data-schedule-start]",
		"{end}"				: "[data-schedule-end]",
		"{allDay}"			: "[data-schedule-allday]",
		"{id}"				: "[data-schedule-id]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{cancelButton} click": function() {
			this.parent.close();
		}
	}
	</bindings>
	<title><?php echo JText::_( 'APP_CALENDAR_CREATE_NEW_SCHEDULE_DIALOG_TITLE' ); ?></title>
	<content>
		<div class="row-fluid widget-box calendar-form">
			<p><?php echo JText::_('APP_CALENDAR_CREATE_NEW_SCHEDULE_DIALOG_INFO'); ?></p>
			
			<div class="wbody wbody-padding">

				<div class="es-controls-row">
					<div class="span3">
						<label for="total" class="small"><?php echo JText::_( 'APP_CALENDAR_CREATE_NEW_SCHEDULE_TITLE' );?></label>
						<i class="icon-es-help pull-right" data-es-provide="popover" data-content="<?php echo JText::_( 'APP_CALENDAR_CREATE_NEW_SCHEDULE_TITLE_DESC' );?>" data-title="<?php echo JText::_( 'APP_CALENDAR_CREATE_NEW_SCHEDULE_TITLE' );?>" data-placement="bottom"></i>
					</div>

					<div class="span9">
						<input type="text" class="input-full" placeholder="<?php echo JText::_( 'APP_CALENDAR_CREATE_NEW_SCHEDULE_TITLE_PLACEHOLDER' );?>" name="title"
						 data-schedule-title value="<?php echo $calendar->get( 'title' );?>" />
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span3">
						<label for="total" class="small"><?php echo JText::_( 'APP_CALENDAR_CREATE_NEW_SCHEDULE_DESCRIPTION' );?></label>
						<i class="icon-es-help pull-right" data-es-provide="popover" data-content="<?php echo JText::_( 'APP_CALENDAR_CREATE_NEW_SCHEDULE_DESCRIPTION_DESC' );?>" data-title="<?php echo JText::_( 'APP_CALENDAR_CREATE_NEW_SCHEDULE_DESCRIPTION' );?>" data-placement="bottom"></i>
					</div>

					<div class="span9">
						<textarea rows="5" class="input-full" 
							placeholder="<?php echo JText::_( 'APP_CALENDAR_CREATE_NEW_SCHEDULE_DESCRIPTION_PLACEHOLDER' );?>"
							data-schedule-description
						><?php echo $calendar->description;?></textarea>
					</div>
				</div>
				
				<div class="es-controls-row">
					<div class="span3">
						<label for="total" class="small"><?php echo JText::_( 'APP_CALENDAR_CREATE_NEW_SCHEDULE_STARTDATE' );?></label>
						<i class="icon-es-help pull-right" data-es-provide="popover" data-content="<?php echo JText::_( 'APP_CALENDAR_CREATE_NEW_SCHEDULE_STARTDATE_DESC' );?>" data-title="<?php echo JText::_( 'APP_CALENDAR_CREATE_NEW_SCHEDULE_STARTDATE' );?>" data-placement="bottom"></i>
					</div>

					<div class="span9">
						<?php echo $this->html( 'form.calendar' , 'date_start' , $calendar->getStartDate()->toMySQL( false ) , 'date_start' , 'data-schedule-start' , true ); ?>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span3">
						<label for="total" class="small"><?php echo JText::_( 'APP_CALENDAR_CREATE_NEW_SCHEDULE_ENDDATE' );?></label>
						<i class="icon-es-help pull-right" data-es-provide="popover" data-content="<?php echo JText::_( 'APP_CALENDAR_CREATE_NEW_SCHEDULE_ENDDATE_DESC' );?>" data-title="<?php echo JText::_( 'APP_CALENDAR_CREATE_NEW_SCHEDULE_ENDDATE' );?>" data-placement="bottom"></i>
					</div>

					<div class="span9">
						<?php echo $this->html( 'form.calendar' , 'date_end' , $calendar->getEndDate()->toMySQL( false ) , 'date_end' , 'data-schedule-end' , true ); ?>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span3">
						<label for="total" class="small"><?php echo JText::_( 'APP_CALENDAR_PUBLISH_STREAM' );?></label>
						<i class="icon-es-help pull-right" data-es-provide="popover" data-content="<?php echo JText::_( 'APP_CALENDAR_PUBLISH_STREAM_DESC' );?>" data-title="<?php echo JText::_( 'APP_CALENDAR_PUBLISH_STREAM' );?>" data-placement="bottom"></i>
					</div>

					<div class="span9">
						<?php echo $this->html( 'grid.boolean' , 'stream' , false , 'stream' , 'data-schedule-stream'); ?>
					</div>
				</div>

				<div class="es-controls-row">
					<div class="span3">
						<label for="total" class="small"><?php echo JText::_( 'APP_CALENDAR_ALL_DAY' );?></label>
						<i class="icon-es-help pull-right" data-es-provide="popover" data-content="<?php echo JText::_( 'APP_CALENDAR_ALL_DAY_DESC' );?>" data-title="<?php echo JText::_( 'APP_CALENDAR_ALL_DAY' );?>" data-placement="bottom"></i>
					</div>

					<div class="span9">
						<?php echo $this->html( 'grid.boolean' , 'allday' , $calendar->all_day , 'allday' , 'data-schedule-allday'); ?>
					</div>
				</div>

<!-- 				<div class="es-controls-row">
					<div class="span3">
						<label for="total" class="small"><?php echo JText::_( 'APP_CALENDAR_REMINDER' );?></label>
						<i class="icon-es-help pull-right" data-es-provide="popover" data-content="<?php echo JText::_( 'APP_CALENDAR_REMINDER_DESC' );?>" data-title="<?php echo JText::_( 'APP_CALENDAR_REMINDER' );?>" data-placement="bottom"></i>
					</div>

					<div class="span9">
						<?php echo $this->html( 'grid.boolean' , 'reminder' , false , 'reminder' , 'data-schedule-reminder'); ?>
					</div>
				</div> -->
				<input type="hidden" name="id" value="<?php echo $calendar->id;?>" data-schedule-id />
			</div>
		</div>
	</content>
	<buttons>
		<button data-cancel-button type="button" class="btn btn-es"><?php echo JText::_( 'COM_EASYSOCIAL_CANCEL_BUTTON' ); ?></button>

		<?php if( !$calendar->id ){ ?>
		<button data-create-button type="button" class="btn btn-es-primary"><?php echo JText::_( 'APP_CALENDAR_CREATE_BUTTON' ); ?></button>
		<?php } else { ?>
		<button data-update-button type="button" class="btn btn-es-primary"><?php echo JText::_( 'APP_CALENDAR_UPDATE_BUTTON' ); ?></button>
		<?php } ?>
	</buttons>
</dialog>
