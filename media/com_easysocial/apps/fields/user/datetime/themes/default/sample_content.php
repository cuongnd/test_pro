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
<div data-with-calendar class="datepicker-wrap" <?php if( !$params->get( 'calendar' ) ) { ?>style="display: none;"<?php } ?>>
	<input type="text" class="datepicker" id="<?php echo $inputName;?>" />
</div>

<div data-without-calendar <?php if( $params->get( 'calendar' ) ) { ?>style="display: none;"<?php } ?>>
	<div data-without-calendar-format <?php if( $params->get( 'date_format', 1 ) != 1 ) { ?>style="display: none;"<?php } ?>>
		<!-- DD/MM/YYYY -->
		<?php echo $this->includeTemplate( 'fields/user/datetime/form.day', array( 'day' => '') ); ?>
		<?php echo $this->includeTemplate( 'fields/user/datetime/form.month', array( 'month' => '') ); ?>
		<?php echo $this->includeTemplate( 'fields/user/datetime/form.year', array( 'year' => '') ); ?>
	</div>

	<div data-without-calendar-format <?php if( $params->get( 'date_format', 1 ) != 2 ) { ?>style="display: none;"<?php } ?>>
		<!-- MM/DD/YYYY -->
		<?php echo $this->includeTemplate( 'fields/user/datetime/form.month', array( 'month' => '') ); ?>
		<?php echo $this->includeTemplate( 'fields/user/datetime/form.day', array( 'day' => '') ); ?>
		<?php echo $this->includeTemplate( 'fields/user/datetime/form.year', array( 'year' => '') ); ?>
	</div>

	<div data-without-calendar-format <?php if( $params->get( 'date_format', 1 ) != 3 ) { ?>style="display: none;"<?php } ?>>
		<!-- YYYY/DD/MM -->
		<?php echo $this->includeTemplate( 'fields/user/datetime/form.year', array( 'year' => '') ); ?>
		<?php echo $this->includeTemplate( 'fields/user/datetime/form.day', array( 'day' => '') ); ?>
		<?php echo $this->includeTemplate( 'fields/user/datetime/form.month', array( 'month' => '') ); ?>
	</div>

	<div data-without-calendar-format <?php if( $params->get( 'date_format', 1 ) != 4 ) { ?>style="display: none;"<?php } ?>>
		<!-- YYYY/MM/DD -->
		<?php echo $this->includeTemplate( 'fields/user/datetime/form.year', array( 'year' => '') ); ?>
		<?php echo $this->includeTemplate( 'fields/user/datetime/form.month', array( 'month' => '') ); ?>
		<?php echo $this->includeTemplate( 'fields/user/datetime/form.day', array( 'day' => '') ); ?>
	</div>
</div>
