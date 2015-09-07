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
<div data-photo-menu class="es-media-item-menu es-photo-menu-form">

	<div class="btn-group">
		<div data-photo-done-button class="btn btn-media btn-es-primary">
			<a href="<?php echo FRoute::photos( array( 'id' => $photo->getAlias() , 'layout' => 'item' , 'userid' => $userAlias ) ); ?>"><i class="ies-checkmark"></i> <?php echo JText::_( 'COM_EASYSOCIAL_DONE_BUTTON' );?></a>
		</div>
	</div>

	<div class="btn-group">
		<div class="es-media-item-menu-item btn btn-media dropdown_" data-item-actions-menu>
			<a href="javascript: void(0);" data-foundry-toggle="dropdown"><i class="ies-arrow-down-2"></i> <span><?php echo JText::_( 'COM_EASYSOCIAL_PHOTOS_EDIT' ); ?></span></a>
			<ul class="dropdown-menu">

				<?php if ( $album->editable() ){ ?>
				<li data-photo-cover-button>
					<a href="javascript: void(0);"><?php echo JText::_("COM_EASYSOCIAL_PHOTOS_SET_AS_ALBUM_COVER"); ?></a>
				</li>
				<?php } ?>

				<?php if( $photo->downloadable() ){ ?>
				<li data-photo-download-button>
					<a href="<?php echo FRoute::photos( array( 'layout' => 'download' , 'id' => $photo->getAlias() ) );?>">
						<?php echo JText::_("COM_EASYSOCIAL_DOWNLOAD_PHOTO"); ?>
					</a>
				</li>
				<?php } ?>

				<li class="divider"></li>

				<?php if( $photo->moveable() ){ ?>
				<li data-photo-move-button>
					<a href="javascript: void(0);"><?php echo JText::_("COM_EASYSOCIAL_PHOTOS_MOVE_PHOTO_TO_ANOTHER_ALBUM"); ?></a>
				</li>
				<?php } ?>

				<?php if( $photo->deleteable() ){ ?>
				<li data-photo-delete-button>
					<a href="javascript: void(0);"><?php echo JText::_("COM_EASYSOCIAL_PHOTOS_DELETE_PHOTO"); ?></a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>

	<?php if ( $photo->editable() ){ ?>
	<div class="btn-group">
		<div class="btn btn-media" data-photo-rotateLeft-button>
			<a href="javascript: void(0);"><i class="ies-rotate-2"></i></a>
		</div>

		<div class="btn btn-media" data-photo-rotateRight-button>
			<a href="javascript: void(0);"><i class="ies-rotate"></i></a>
		</div>
	</div>
	<?php } ?>
</div>	
