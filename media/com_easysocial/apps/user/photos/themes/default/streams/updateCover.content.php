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
<div class="es-stream-photo-row es-stream-item-photo-cover">
	<?php if( $this->config->get( 'photos.enabled' ) ){ ?>
	<a href="<?php echo $photo->getPermalink();?>" class="es-stream-item-photo" data-es-photo="<?php echo $photo->id; ?>">
	<?php } else { ?>
	<span class="es-stream-item-photo">
	<?php } ?>
		<div data-es-cover style="
			background-image: url('<?php echo $photo->getSource( SOCIAL_PHOTOS_LARGE );?>');
			background-position: <?php echo $cover->getPosition();?>;
			" class="es-photo-image no-transition"></div>
	<?php if( $this->config->get( 'photos.enabled' ) ){ ?>
	</a>
	<?php } else { ?>
	</span>
	<?php } ?>
</div>

