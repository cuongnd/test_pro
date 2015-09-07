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
<div class="es-album-photos">
	<div data-photo-item-group
		 class="es-photo-item-group no-transition">
	<?php if (!empty($photos)) { ?>
		<?php foreach ($photos as $photo) { ?>
			<?php echo Foundry::photo($photo->id)->renderItem($options['photoItem']); ?>
		<?php } ?>
	<?php } ?>
	</div>
	<div class="no-photos-hint content-hint">
		<?php echo JText::_("COM_EASYSOCIAL_NO_PHOTOS_AVAILABLE"); ?>
	</div>
	<div class="drop-photo-hint content-hint">
		<?php echo JText::_("COM_EASYSOCIAL_DROP_A_FILE_TO_UPLOAD"); ?>
	</div>
	<?php if ($options['showLoadMore']) { ?>
		<?php if( isset( $nextStart ) && $nextStart >= 0 ) { ?>
			<button data-album-more-button type="button" class="btn btn-block es-album-more-button"><i class="ies-refresh"></i> <?php echo JText::_("COM_EASYSOCIAL_LOAD_MORE"); ?></button>
		<?php } ?>
	<?php } ?>

	<?php if ($options['showViewButton']) { ?>
	<a data-album-view-button class="btn btn-es-primary es-album-view-button" href="<?php echo FRoute::albums( array( 'id' => $album->getAlias() , 'layout' => 'item' , 'userid' => $userAlias ) ); ?>"><?php echo JText::_('COM_EASYSOCIAL_ALBUMS_VIEW_ALBUM'); ?> <i class="ies-arrow-right-2"></i></a>
	<?php } ?>
</div>