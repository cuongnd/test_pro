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
<div class="stream-repost-text"><?php echo $sharetext; ?></div>
<?php } ?>

<?php if(! $textonly ) { ?>
<div class="stream-media-preview-body  mt-10 mb-20 stream-shared-border">
	<div class="row-fluid stream-meta ">
		<div class="ml-5 stream-title"><?php echo $photo->get( 'title' ); ?></div>
		<div class="es-stream-photo-row">
			<a class="es-stream-item-photo" href="<?php echo $photo->getPermalink(); ?>">
				<div data-photo-image="" class="es-photo-image" style="background-image: url('<?php echo $photo->getSource( 'large' ); ?>');" alt="<?php echo $this->html( 'string.escape' , $photo->get('title' ) );?>"></div>
			</a>
		</div>
	</div>
</div>
<?php } ?>
