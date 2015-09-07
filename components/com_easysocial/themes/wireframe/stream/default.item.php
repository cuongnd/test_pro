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
<li class="type-<?php echo $stream->favicon; ?> streamItem<?php echo $stream->display == SOCIAL_STREAM_DISPLAY_FULL ? ' es-stream-full' : ' es-stream-mini';?> stream-context-<?php echo $stream->context; ?>"
	data-id="<?php echo $stream->uid;?>"
	data-ishidden="0"
	data-context="<?php echo $stream->context; ?>"
	data-streamItem
>
	<div class="es-stream" data-stream-item >

		<?php if( Foundry::user()->id != 0 && ( $this->access->allowed( 'stream.hide' ) || $this->access->allowed( 'reports.submit' ) || ( $this->access->allowed( 'stream.delete', false ) || Foundry::user()->isSiteAdmin() ) ) ){ ?>
		<div class="es-stream-control btn-group pull-right">
			<a class="btn-control" href="javascript:void(0);">
				<i class="ies-arrow-down"></i>
			</a>


			<ul class="dropdown-menu">
				<?php if( $this->access->allowed( 'stream.hide' ) ){ ?>
				<li data-stream-hide>
					<a href="javascript:void(0);"><?php echo JText::_( 'COM_EASYSOCIAL_STREAM_HIDE' );?></a>
				</li>
				<li data-stream-hide-app>
					<a href="javascript:void(0);"><?php echo JText::_( 'COM_EASYSOCIAL_STREAM_HIDE_APP' );?></a>
				</li>
				<?php } ?>

				<?php if( $this->access->allowed( 'reports.submit' ) && !$stream->actor->isViewer() ){ ?>
				<li>
					<?php echo Foundry::reports()->getForm( 'com_easysocial' , SOCIAL_TYPE_STREAM , $stream->uid , JText::sprintf( 'COM_EASYSOCIAL_STREAM_REPORT_ITEM_TITLE' , $stream->actor->getName() ) , JText::_( 'COM_EASYSOCIAL_STREAM_REPORT_ITEM' ) , '' , JText::_( 'COM_EASYSOCIAL_STREAM_REPORT_ITEM_DESC' ) , FRoute::stream( array( 'id' => $stream->uid , 'external' => true ) ) ); ?>
				</li>
				<?php } ?>

				<?php if( $this->access->allowed( 'stream.delete', false ) || Foundry::user()->isSiteAdmin() ){ ?>
				<li data-stream-delete>
					<a href="javascript:void(0);"><?php echo JText::_( 'COM_EASYSOCIAL_STREAM_DELETE' );?></a>
				</li>
				<?php } ?>

			</ul>
		</div>
		<?php } ?>

		 <?php if( $stream->display == SOCIAL_STREAM_DISPLAY_FULL ) { ?>
			<div class="es-stream-meta">
				<div class="media">
					<div class="media-object pull-left">
						<div class="es-avatar es-avatar-small es-stream-avatar" data-comments-item-avatar="">
							<a href="<?php echo $stream->actor->getPermalink();?>"><img src="<?php echo $stream->actor->getAvatar();?>" alt="<?php echo $this->html( 'string.escape' , $stream->actor->getName() );?>" /></a>
						</div>
					</div>
					<div class="media-body">
						<?php if( $stream->label ){ ?>
						<div>
							<span class="label es-stream-type"<?php echo !empty( $stream->color ) ? 'style="background:' . $stream->color . '" ' : '';?>><?php echo JText::_( 'COM_EASYSOCIAL_STREAM_CONTEXT_TITLE_' . strtoupper( str_ireplace( array('-',' ') , '_'  , $stream->context ) ) );?></span>
						</div>
						<?php } ?>

						<div class="es-stream-title mt-5">
							<?php echo $stream->title; ?>
						</div>
					</div>
				</div>
			</div>

			<div class="es-stream-content">
				<?php echo $stream->content; ?>

				<span class="es-stream-info-meta">
					<?php echo $this->loadTemplate( 'site/stream/default.item.with' , array( 'stream' => $stream ) ); ?>
				</span>

			</div>

			<?php if( isset( $stream->preview ) && !empty( $stream->preview ) ){ ?>
			<div class="es-stream-preview">
				<?php echo $stream->preview; ?>
			</div>
			<?php } ?>

			<div class="es-stream-content es-stream-location">
				<?php echo $this->loadTemplate( 'site/stream/default.item.location' , array( 'stream' => $stream ) ); ?>
			</div>

		<?php } else { ?>
			<div class="es-stream-content">
				<div class="mb-5">
					<?php echo $stream->title; ?>
				</div>
			</div>
		<?php } ?>

		<?php echo $stream->actions; ?>
		<?php //echo $this->loadTemplate( 'site/stream/actions' , array( 'privacy' => $stream->privacy , 'comments' => $stream->comments , 'likes' => $stream->likes , 'repost' => $stream->repost , 'friendlyDate' => $stream->friendlyDate, 'uid' => $stream->uid ) ); ?>
	</div>
</li>
