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
<div class="es-story-locations" data-story-location-form>
	<div class="es-story-location-viewport" data-story-location-viewport>
		 <button type="button" class="es-story-location-detect-button btn" data-story-location-detect-button><i class="ies-power"></i><?php echo JText::_('COM_EASYSOCIAL_DETECT_MY_LOCATION'); ?></button>
		 <div class="es-story-location-default-map"
		      data-story-location-default-map></div>
	</div>
	<div class="es-story-location-textbox" data-story-location-textbox>
		<input type="text" placeholder="<?php echo JText::_('COM_EASYSOCIAL_WHERE_ARE_YOU_NOW'); ?>" autocomplete="off" data-story-location-textField disabled/>
		<div class="es-story-location-remove-button" data-story-location-remove-button><i class="ies-cancel-2"></i></div>
		<div class="es-story-location-loading-indicator" data-story-location-loading-indicator><i class="loading-indicator small"></i></div>
	</div>
	<div class="es-story-location-autocomplete" data-story-location-autocomplete>
		
		<div class="es-story-location-autocomplete-shadow"><div class="real-shadow"></div></div>

		<div class="es-story-location-suggestions"
		     data-story-location-suggestions></div>
	</div>
</div>