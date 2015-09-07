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
<div data-photo-item="<?php echo $photo->uuid(); ?>"
	 data-photo-id="<?php echo $photo->id; ?>"
     class="es-media-item
            es-photo-item
            <?php echo $photo->isFeatured() ? 'featured' : '';?>
            <?php echo 'layout-' . $options['layout'] ?>">

	<div data-photo-header class="es-media-header es-photo-header">

		<?php if ($options['showToolbar']) { ?>
		<div class="media">
			<div class="media-object pull-left">
				<div class="es-avatar es-avatar-unstyled es-inset">
					<img src="<?php echo $photo->getCreator()->getAvatar(); ?>" />
				</div>
			</div>
			<div class="media-body">
				<div data-photo-owner class="es-photo-owner"><?php echo JText::_("COM_EASYSOCIAL_PHOTOS_UPLOADED_BY"); ?> <a href="<?php echo $photo->getCreator()->getPermalink(); ?>"><?php echo $photo->getCreator()->getName(); ?></a></div>
				<div data-photo-album class="es-photo-album"><?php echo JText::_("COM_EASYSOCIAL_PHOTOS_FROM_ALBUM"); ?> <a href="<?php echo $album->getPermalink(); ?>"><?php echo $album->get('title'); ?></a></div>
				<?php echo $this->includeTemplate('site/photos/menu'); ?>
			</div>
		</div>
		<?php } ?>

		<?php echo $this->render( 'module' , 'es-photos-before-info' ); ?>

		<?php if ($options['showInfo']) { ?>
			<?php echo $this->includeTemplate('site/photos/info'); ?>
		<?php } ?>

		<?php if ($options['showForm'] && $album->editable()) { ?>
		<?php echo $this->includeTemplate('site/photos/form'); ?>
		<?php } ?>
	</div>

    <div data-photo-content class="es-photo-content">
    	<?php echo $this->render( 'module' , 'es-photos-before-photo' ); ?>
		<div
			data-photo-image
			class="es-photo-image"
			style="background-image: url('<?php echo $photo->getSource( $options['size'] ); ?>');">

			<a data-photo-image-link
				href="<?php echo FRoute::photos( array( 'id' => $photo->getAlias() , 'userid' => $userAlias , 'layout' => 'item' ) ); ?>"
				title="<?php echo $this->html( 'string.escape' , $photo->title ); ?>">
				<?php
					// These images are hidden from user.
				    // It allows us to switch between thumbnail/featured and SEO crawling.
				?>
				<i data-photo-image-thumbnail
				   data-src="<?php echo $photo->getSource( 'thumbnail' ); ?>"></i>
				<i data-photo-image-featured
				   data-src="<?php echo $photo->getSource( 'featured' ); ?>"></i>
				<i data-photo-image-large
				   data-src="<?php echo $photo->getSource( 'large' ); ?>"></i>
			</a>

			<?php if ($options['showNavigation']) { ?>
			<?php echo $this->includeTemplate('site/photos/navigation'); ?>
			<?php } ?>

			<?php if ($options['showTags']) { ?>
				<?php echo $this->includeTemplate('site/photos/tags'); ?>
			<?php } ?>
		</div>
		<i class="loading-indicator small"></i>
		<?php echo $this->render( 'module' , 'es-photos-after-photo' ); ?>
	</div>

	<div data-photo-footer class="es-photo-footer">
		<?php if ($options['showStats']) { ?>
			<?php echo $this->includeTemplate('site/photos/stats'); ?>
		<?php } ?>
		<?php echo $this->render( 'module' , 'es-photos-after-stats' ); ?>
	
		<div class="es-photo-interaction row-fluid">
			<div class="span8">
			<?php if ($options['showResponse']) { ?>
				<?php echo $this->includeTemplate('site/photos/response'); ?>
			<?php } ?>
			</div>
			<div class="span4">
				<?php echo $this->render( 'module' , 'es-photos-before-tags' ); ?>

				<?php if ($options['showTags']) { ?>
					<?php echo $this->includeTemplate('site/photos/taglist'); ?>
				<?php } ?>

				<?php echo $this->render( 'module' , 'es-photos-after-tags' ); ?>
			</div>
		</div>
	</div>

	<div class="es-media-loader"></div>
</div>
