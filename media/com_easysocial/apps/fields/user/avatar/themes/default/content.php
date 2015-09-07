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
<div data-field-avatar class="data-field-avatar">
	<ul class="input-vertical unstyled">
		<li class="<?php echo !empty( $imageSource ) ? ' selected' : '';?>">
			<div data-field-avatar-frame style="background-image: url(<?php echo !empty( $imageSource ) ? $imageSource : ''; ?>)" class="avatar-frame">
				<div data-field-avatar-viewport class="avatar-viewport"></div>
			</div>

			<div data-field-avatar-note style="display: none;"><?php echo JText::_( 'PLG_FIELDS_AVATAR_CROP_PHOTO' ); ?></div>

			<div data-field-avatar-actions style="display: none;">
				<button type="button" class="btn btn-es" data-field-avatar-actions-cancel><?php echo JText::_( 'PLG_FIELDS_AVATAR_CANCEL_BUTTON' ); ?></button>
			</div>

			<i class="loading-indicator small" data-field-avatar-loader style="display: none;"></i>
		</li>

		<li>
			<?php if( $params->get( 'upload' ) ) { ?>
			<div class="avatar-upload-field">
				<input type="file" id="<?php echo $inputName; ?>" name="<?php echo $inputName; ?>[file]" data-field-avatar-file />
			</div>
			<?php } ?>
			<div data-field-avatar-error></div>
		</li>

		<?php if( $avatars && $params->get( 'gallery', true ) ){ ?>
		<li class="mt-20" <?php if( !$params->get( 'use_gallery_button' ) ) { ?>style="display: none;"<?php } ?>>
			<a class="mls btn btn-es-inverse" href="javascript:void(0);" data-field-avatar-gallery>
				<i class="icon-es-photos mr-5"></i> <?php echo JText::_( 'PLG_FIELDS_AVATAR_SELECT_AVATAR_BUTTON' ); ?>
			</a>
		</li>

		<?php if( !$params->get( 'use_gallery_button' ) ) { ?>
		<li><h3><?php echo JText::_( 'PLG_FIELDS_AVATAR_GALLERY_SELECTION' ); ?></h3></li>
		<?php } ?>

		<li>
			<ul class="es-avatar-list unstyled" <?php if( $params->get( 'use_gallery_button' ) ) { ?>style="display: none;"<?php } ?> data-field-avatar-gallery-items>
				<?php foreach( $avatars as $avatar ){ ?>
				<li class="avatarItem" data-field-avatar-gallery-item data-id="<?php echo $avatar->id;?>">
					<a class="es-avatar es-avatar-medium pull-left mr-10" href="javascript:void(0);">
						<img src="<?php echo $avatar->getSource( SOCIAL_AVATAR_MEDIUM );?>" />
					</a>
				</li>
				<?php } ?>
			</ul>
		</li>
		<?php } ?>
	</ul>

	<input type="hidden" name="<?php echo $inputName; ?>[source]" data-field-avatar-source />
	<input type="hidden" name="<?php echo $inputName; ?>[path]" data-field-avatar-path />
	<input type="hidden" name="<?php echo $inputName; ?>[data]" data-field-avatar-data />
	<input type="hidden" name="<?php echo $inputName; ?>[type]" data-field-avatar-type />
	<input type="hidden" name="<?php echo $inputName; ?>[name]" data-field-avatar-name />
</div>
