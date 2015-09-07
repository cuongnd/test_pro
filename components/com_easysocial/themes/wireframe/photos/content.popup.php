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
<div class="es-photo-content scrollbar-wrap" data-photo-content>

	<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
	<div class="viewport" data-photo-content-viewport>
		<div class="overview">
			<div class="es-photo-content-header">
				<div class="media">
					<div class="media-object pull-left">
						<div class="es-avatar es-avatar-small"><img src="<?php echo $creator->getAvatar(); ?>" /></div>
					</div>
					<div class="media-body">
						<div><a href="<?php echo $creator->getPermalink(); ?>"><?php echo $creator->getName(); ?></a></div>
						<?php // This is from the time it was uploaded.
						      // TODO: Also show assigned date somewhere ?>
						<div data-photo-date class="es-photo-date small"><?php echo Foundry::date( $photo->created )->toLapsed(); ?></div>
						<?php if( $photo->getLocation() ) { ?>
						<div data-photo-location class="es-photo-location small">
							<i class="ies-location-2 ies-small"></i> <?php echo $photo->getLocation()->get( 'address' ); ?>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>

			<div class="es-photo-content-body">
				<div data-photo-title class="es-photo-title"><?php echo $photo->title; ?></div>
				<div data-photo-caption class="es-photo-caption"><?php echo $photo->caption; ?></div>
				<div data-photo-album
					 class="es-photo-album">
					 <?php echo JText::_( 'COM_EASYSOCIAL_FROM_ALBUM' );?> <a href="<?php echo $album->getPermalink(); ?>"><?php echo $album->get( 'title' ); ?></a>
				</div>
			</div>

			<div class="es-photo-content-footer es-item-actions">
				<div class="es-item-action-buttons">
					<span data-photo-like-button class="btn-like<?php echo Foundry::likes()->hasLiked( $photo->id , SOCIAL_TYPE_PHOTO ) ? ' liked' : '';?>">
						<span class="like-text"><?php echo JText::_("COM_EASYSOCIAL_LIKES_LIKE"); ?></span>
						<span class="unlike-text"><?php echo JText::_("COM_EASYSOCIAL_LIKES_UNLIKE"); ?></span>
					</span>
					<b>&bull;</b>
					<span data-photo-comment-button class="btn-comment" data-foundry-toggle="dropdown"><?php echo JText::_("COM_EASYSOCIAL_COMMENT"); ?></span>
				</div>
				<div data-photo-likes-holder class="es-item-likes">
					<?php echo Foundry::likes( $photo->id , SOCIAL_TYPE_PHOTO )->toString(); ?>
				</div>
				<div data-photo-comments-holder class="es-photo-comments-holder">
					<?php echo Foundry::comments( $photo->id, SOCIAL_TYPE_PHOTO, SOCIAL_APPS_GROUP_USER, array( 'url' => FRoute::photos( array( 'layout' => 'item', 'id' => $photo->id ) )  ) )->getHTML(); ?>
				</div>
			</div>
		</div>
	</div>

	<div class="es-photo-menu es-media-item-menu-item btn btn-media dropdown_" data-item-actions-menu>
		<a href="javascript:void(0);" data-foundry-toggle="dropdown" class="dropdown-toggle_"><i class="ies-arrow-down-2"></i></a>
		<ul class="dropdown-menu">
			<?php if( $photo->taggable() ){ ?>
			<li data-photo-tag-button>
				<a href="javascript: void(0);"><?php echo JText::_("COM_EASYSOCIAL_TAG_PHOTO"); ?></a>
			</li>
			<?php } ?>

			<?php if( $photo->shareable() ){ ?>
			<li data-photo-share-button>
				<?php echo Foundry::get( 'Sharing' , array( 'url' => $photo->getPermalink() , 'text' => JText::_( 'COM_EASYSOCIAL_SHARE_PHOTO' ) , 'display' => 'dialog' ) )->getHTML(); ?>
			</li>
			<li class="divider"></li>
			<?php } ?>

			<?php if( $photo->canSetProfilePicture() ){ ?>
			<li data-photo-profileAvatar-button>
				<a href="javascript:void(0);">
					<?php echo JText::_("COM_EASYSOCIAL_USE_AS_PROFILE_AVATAR"); ?>
				</a>
			</li>
			<?php } ?>

			<?php if( $photo->canSetProfileCover() ){ ?>
			<li data-photo-profileCover-button>
				<a href="<?php echo FRoute::profile( array( 'id' => $this->my->getAlias() , 'cover_id' => $photo->id ) );?>">
					<?php echo JText::_("COM_EASYSOCIAL_USE_AS_PROFILE_COVER"); ?>
				</a>
			</li>
			<?php } ?>

			<?php if( $photo->downloadable() ){ ?>
			<li data-photo-download-button>
				<a href="<?php echo FRoute::photos( array( 'layout' => 'download' , 'id' => $photo->getAlias() ) );?>">
					<?php echo JText::_("COM_EASYSOCIAL_DOWNLOAD_PHOTO"); ?>
				</a>
			</li>
			<li class="divider"></li>
			<?php } ?>

			<li data-photo-report-button>
				<?php echo Foundry::reports()->getForm( 'com_easysocial' , SOCIAL_TYPE_PHOTO , $photo->id , $photo->get( 'title' ) , JText::_( 'COM_EASYSOCIAL_REPORT_PHOTO' ) , '' , JText::_( 'COM_EASYSOCIAL_REPORT_PHOTO_DESC' ) , $photo->getPermalink() ); ?>
			</li>

			<?php if( $photo->deleteable() ){ ?>
			<li data-photo-delete-button>
				<a href="javascript: void(0);"><?php echo JText::_("COM_EASYSOCIAL_DELETE_PHOTO"); ?></a>
			</li>
			<?php } ?>
		</ul>
	</div>

</div>
