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
<div class="es-filterbar socialStream">
	<div class="filterbar-title h5 pull-left"><?php echo JText::_( 'COM_EASYSOCIAL_STREAM_RECENT_UPDATES' );?></div>
</div>

<!-- Stream Items -->
<?php echo $stream->html(); ?>
