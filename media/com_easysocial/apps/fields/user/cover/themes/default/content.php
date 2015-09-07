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
<div data-field-cover class="data-field-cover">
	<ul class="input-vertical unstyled">
		<li>
			<div data-field-cover-image class="cover-image cover-move" style="background-image: url(<?php echo $value; ?>);background-position: <?php echo !empty( $position ) ? $position : ''; ?>;"></div>

			<div data-field-cover-note <?php if( empty( $value ) ) { ?>style="display: none;"<?php } ?>><?php echo JText::_( 'PLG_FIELDS_COVER_REPOSITION_COVER' ); ?></div>

			<i class="loading-indicator small" data-field-cover-loader style="display: none;"></i>

			<input type="file" id="<?php echo $inputName; ?>" class="input-xlarge" name="<?php echo $inputName; ?>[file]" data-field-cover-file />

			<div data-field-cover-error></div>
		</li>
	</ul>

	<input type="hidden" id="<?php echo $inputName; ?>" name="<?php echo $inputName; ?>[data]" data-field-cover-data />
	<input type="hidden" id="<?php echo $inputName; ?>" name="<?php echo $inputName; ?>[position]" data-field-cover-position />
</div>
