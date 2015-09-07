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
<div data-field-permalink data-max="<?php echo $params->get( 'max' ); ?>">
	<div class="input-append">
		<input type="text" class="input input-xlarge validation keyup length-4 required"
			name="<?php echo $inputName;?>"
			id="<?php echo $element;?>"
			value="<?php echo $value; ?>"
			autocomplete="off"
			data-permalink-input
			placeholder="<?php echo JText::_( 'PLG_FIELDS_PERMALINK_PLACEHOLDER' ); ?>" />

		<?php if( $params->get( 'check_permalink' , true ) ){ ?>
		<button type="button" class="btn" data-permalink-check><?php echo JText::_( 'PLG_FIELDS_PERMALINK_CHECK_BUTTON' );?></button>
		<?php } ?>
	</div>

	<div class="controls" style="margin:0;">
		<span class="help-block text-success small" data-permalink-available style="display: none;">
			<span><?php echo JText::_( 'PLG_FIELDS_PERMALINK_AVAILABLE' );?></span>
		</span>
	</div>
</div>
