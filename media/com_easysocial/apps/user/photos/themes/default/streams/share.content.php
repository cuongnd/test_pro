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
<?php if( $sharetext ) { ?>
<div>
	<div class="pa-10">
		<span><?php echo $sharetext; ?></span>
	</div>
</div>
<?php } ?>
<div class="es-stream-photo-row<?php echo $totalPhotos > 1 && $totalPhotos < 5 ? ' es-stream-photos-' . $totalPhotos : '';?><?php echo $totalPhotos >= 5 ? ' es-stream-photos-1-4' : '';?>">
	<?php foreach( $photos as $photo ){ ?>
	<a href="<?php echo $photo->getPermalink();?>" class="es-stream-item-photo" data-es-photo="<?php echo $photo->id; ?>">
		<div style="background-image: url('<?php echo $photo->getSource( SOCIAL_PHOTOS_LARGE );?>');" class="es-photo-image" data-photo-image="">
		</div>
	</a>
	<?php } ?>
</div>
