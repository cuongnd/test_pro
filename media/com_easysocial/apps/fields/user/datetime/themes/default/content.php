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
<div data-field-datetime data-calendar="<?php echo $params->get( 'calendar' ); ?>" data-format="<?php echo $params->get('date_format' ); ?>" data-yearfrom="<?php echo $params->get( 'yearfrom' ); ?>" data-yearto="<?php echo $params->get( 'yearto' ); ?>">
	<?php if( $params->get( 'calendar' ) ){ ?>
		<div class="datepicker-wrap">
			<input type="text" class="datepicker" id="<?php echo $inputName;?>" name="<?php echo $inputName;?>" data-field-datetime-date value="<?php echo $date; ?>" />
		</div>
	<?php } else { ?>
		<?php if( $params->get( 'date_format' ) == 1 ){ ?>
			<!-- DD/MM/YYYY -->
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.day' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.month' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.year' ); ?>
		<?php } ?>

		<?php if( $params->get( 'date_format' ) == 2 ){ ?>
			<!-- MM/DD/YYYY -->
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.month' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.year' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.day' ); ?>
		<?php } ?>

		<?php if( $params->get( 'date_format' ) == 3 ){ ?>
			<!-- YYYY/DD/MM -->
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.year' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.day' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.month' ); ?>
		<?php } ?>

		<?php if( $params->get( 'date_format' ) == 4 ){ ?>
			<!-- YYYY/MM/DD -->
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.year' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.month' ); ?>
			<?php echo $this->includeTemplate( 'fields/user/datetime/form.day' ); ?>
		<?php } ?>

	<?php } ?>
</div>
