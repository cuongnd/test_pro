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
<div class="es-album-menu es-media-item-menu es-album-menu-item">
	<?php if( $album->isMine() || $album->editable() || $album->deleteable() ){ ?>
	<div class="btn-group">
		<?php if( $options['canUpload'] && $album->isMine() ){ ?>
		<div class="btn btn-media" data-album-upload-button>
			<a href="javascript: void(0);"><i class="ies-plus"></i> <?php echo JText::_("COM_EASYSOCIAL_ALBUMS_ADD_PHOTOS"); ?></a>
		</div>
		<?php } ?>	

		<?php if( $album->editable() || $album->deleteable() ){ ?>
		<div class="btn btn-media dropdown_" data-item-actions-menu>
			<a href="javascript:void(0);" data-foundry-toggle="dropdown" class="dropdown-toggle_"><i class="ies-cog-2"></i> <span><?php echo JText::_('COM_EASYSOCIAL_ALBUMS_EDIT'); ?></span> </a>
			<ul class="dropdown-menu">
				<?php if( $album->editable() ){ ?>
				<li data-album-edit-button>
					<a href="<?php echo FRoute::albums( array( 'id' => $album->getAlias(), 'layout' => 'form' ) );?>"><?php echo JText::_( 'COM_EASYSOCIAL_ALBUMS_EDIT_ALBUM' ); ?></a>
				</li>
				<?php } ?>

				<?php if( $album->deleteable() ){ ?>
				<li class="divider"></li>
				<li data-album-delete-button>
					<a href="javascript:void(0);"><?php echo JText::_("COM_EASYSOCIAL_ALBUMS_DELETE_ALBUM"); ?></a>
				</li>
				<?php } ?>
			</ul>
		</div>
		<?php } ?>
	</div>
	<?php } ?>
	<div class="btn-group">
		<div class="btn btn-media">
			<?php echo Foundry::get( 'Sharing' , array( 'url' => FRoute::albums( array( 'id' => $album->getAlias() , 'layout' => 'item' , 'external' => true , 'userid' => $userAlias ) , false ), 'text' => JText::_( 'COM_EASYSOCIAL_ALBUMS_SHARE' ) ) )->getHTML(true); ?>
		</div>
		<div class="btn btn-media">
			<?php echo Foundry::reports()->getForm( 'com_easysocial' , SOCIAL_TYPE_ALBUM , $album->id , $album->get( 'title' ) , JText::_( 'COM_EASYSOCIAL_ALBUMS_REPORT' ) , JText::_( 'COM_EASYSOCIAL_ALBUMS_REPORT_ALBUM_TITLE' ) , JText::_( 'COM_EASYSOCIAL_ALBUMS_REPORT_DESC' ), 
				FRoute::albums( array( 'id' => $album->getAlias() , 'layout' => 'item' , 'external' => true , 'userid' => $userAlias ) , false ) , true); ?>
		</div>
	</div>	
</div>

<?php if ($album->editable()) { ?>
<div class="es-album-menu es-media-item-menu es-album-menu-form">
	<div class="btn-group">
		<div class="btn btn-media btn-es-primary" data-album-done-button>
			<a href="<?php echo $album->getPermalink(); ?>"><i class="ies-checkmark"></i> <?php echo JText::_("COM_EASYSOCIAL_ALBUMS_DONE"); ?></a>
		</div>
	</div>
	<div class="btn-group">
		<?php if( $options['canUpload'] && $album->isMine() ){ ?>
		<div class="btn btn-media" data-album-upload-button>
			<a href="javascript: void(0);"><i class="ies-plus"></i> <?php echo JText::_("COM_EASYSOCIAL_ALBUMS_ADD_PHOTOS"); ?></a>
		</div>
		<?php } ?>

		<?php if( $album->deleteable() ){ ?>
		<div class="btn btn-media <?php echo (empty($album->id)) ? 'disabled' : ''; ?>" data-album-delete-button>
			<a href="javascript:void(0);"><i class="ies-remove"></i> <?php echo JText::_("COM_EASYSOCIAL_ALBUMS_DELETE_ALBUM"); ?></a>
		</div>
		<?php } ?>
	</div>
</div>
<?php } ?>
