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
<div
	data-album-item="<?php echo $album->uuid(); ?>"
	data-album-id="<?php echo $album->id; ?>"
	data-album-nextstart="<?php echo isset($nextStart) ? $nextStart : '-1' ; ?>"
	data-album-layout="<?php echo $options['layout']; ?>"
	class="es-album-item
	       es-media-group
	       <?php echo (empty($photos)) ? '' : 'has-photos'; ?>
	       <?php echo 'layout-' . $options['layout']; ?>">

	<div data-album-header class="es-media-header es-album-header">

		<?php if ($options['showToolbar']) { ?>
		<div class="media">
			<div class="media-object pull-left">
				<div class="es-avatar es-avatar-unstyled es-inset"><img src="<?php echo $album->getCreator()->getAvatar(); ?>" /></div>
			</div>
			<div class="media-body">
				<div data-album-owner class="es-album-owner"><?php echo JText::_("COM_EASYSOCIAL_ALBUMS_UPLOADED_BY"); ?> <a href="<?php echo $album->getCreator()->getPermalink(); ?>"><?php echo $album->getCreator()->getName(); ?></a></div>
				<?php echo $this->includeTemplate('site/albums/menu'); ?>
			</div>
		</div>
		<?php } ?>

		<?php echo $this->render( 'module' , 'es-albums-before-info' ); ?>

		<?php if ($options['showInfo']) { ?>
			<?php echo $this->includeTemplate('site/albums/info'); ?>
		<?php } ?>

		<?php if ($options['showForm'] && $album->editable()) { ?>
			<?php echo $this->includeTemplate('site/albums/form'); ?>
		<?php } ?>
	</div>

	<div data-album-content class="es-album-content">
		<?php echo $this->render( 'module' , 'es-albums-before-photos' ); ?>
		<i class="loading-indicator small"></i>
		<?php if ($options['showPhotos']) { ?>
		<?php echo $this->includeTemplate('site/albums/photos'); ?>
		<?php } ?>

		<?php echo $this->render( 'module' , 'es-albums-after-photos' ); ?>
	</div>

	<div data-album-footer class="es-album-footer">
		<?php if ($options['showStats']) { ?>
			<?php echo $this->includeTemplate('site/albums/stats'); ?>
		<?php } ?>

		<div class="es-album-interaction row-fluid">
			<div class="span8">
			<?php if ($options['showResponse']) { ?>
				<?php echo $this->includeTemplate('site/albums/response'); ?>
			<?php } ?>
			</div>

			<?php if($options['showTags']){ ?>
			<div class="span4">
				<?php echo $this->includeTemplate('site/albums/taglist'); ?>
			</div>
			<?php } ?>
		</div>
	</div>

	<div class="es-media-loader"></div>
</div>

