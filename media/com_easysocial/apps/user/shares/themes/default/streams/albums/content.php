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
<div class="stream-media-preview-body pl-10 mt-10 mb-20 stream-shared-border">
	<div class="row-fluid stream-meta">
		<h5 class="stream-title">
			<a href="<?php echo $album->getPermalink();?>"><?php echo $album->get( 'title' ); ?></a>
		</h5>
		<div class="stream-content">
			<div class="row-fluid">
				<p>
					<?php if( $album->getCover() ){ ?>
						<img alt="<?php echo $this->html( 'string.escape' , $album->getCover()->get('title' ) );?>" src="<?php echo $album->getCover()->getSource( 'square' ); ?>" align="left" class="mr-10 mb-10" />
					<?php } ?>
					<?php echo $album->get( 'caption' ); ?>
				</p>
			</div>
			<div class="mt-10">
				<a href="<?php echo $album->getPermalink();?>" class="btn btn-es-primary btn-medium"><?php echo JText::_( 'APP_SHARES_VIEW_ALBUM' ); ?></a>
			</div>
		</div>
	</div>
</div>
<?php } ?>
