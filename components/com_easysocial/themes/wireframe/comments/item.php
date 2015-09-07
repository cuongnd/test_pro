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
<li class="small es-comment" data-comments-item data-id="<?php echo $comment->id; ?>">
	<div class="media es-flyout">
		<div class="media-object pull-left">
			<div class="es-avatar es-avatar-small" data-comments-item-avatar>
				<?php if( $user->isBlock() ) { ?>
					<a href="javascript:void(0);" title="<?php echo $this->html( 'string.escape' , $user->getName() );?>"><img src="<?php echo $user->getAvatar(); ?>" /></a>
				<?php } else { ?>
					<a href="<?php echo $user->getPermalink();?>" title="<?php echo $this->html( 'string.escape' , $user->getName() );?>"><img src="<?php echo $user->getAvatar(); ?>" /></a>
				<?php } ?>
			</div>
		</div>
		<div class="media-body">
			<div data-comments-item-commentFrame data-comments-item-frame>
				<div data-comments-item-author>
					<?php if( $user->isBlock() ) { ?>
						<?php echo $user->getName(); ?>
					<?php } else { ?>
						<a href="<?php echo $user->getPermalink(); ?>"><?php echo $user->getName(); ?></a>
					<?php } ?>
				</div>
				<div class="es-comment-actions es-flyout-content" data-comments-item-actions>
					<div class="es-comment-actions-flyout">
						<a class="es-comment-actions-toggle" href="javascript:void(0);"><i class="icon-es-comment-action"></i></a>
						<ul class="es-nav es-nav-stacked pull-right es-comment-actions-nav">
							<?php if( $this->access->allowed( 'comments.report' ) ) { ?>
							<li>
								<?php echo Foundry::reports()->getForm( 'com_easysocial', 'comments', $comment->id, JText::_( 'COM_EASYSOCIAL_COMMENTS_REPORT_ITEM_TITLE' , $user->getName() ) , JText::_( 'COM_EASYSOCIAL_COMMENTS_REPORT_ITEM' ), '' , JText::_( 'COM_EASYSOCIAL_COMMENTS_REPORT_TEXT' ) , '' ); ?>
							</li>
							<?php } ?>

							<?php if( $this->access->allowed( 'comments.edit' ) || ( $isAuthor && $this->access->allowed( 'comments.editown' ) ) ) { ?>
							<li class="btn-comment-edit" data-comments-item-actions-edit>
								<a href="javascript:void(0);"><?php echo JText::_( 'COM_EASYSOCIAL_COMMENTS_ACTION_EDIT' ); ?></a>
							</li>
							<?php } ?>

							<?php if( $this->access->allowed( 'comments.delete' ) || ( $isAuthor && $this->access->allowed( 'comments.deleteown' ) ) ) { ?>
							<li class="btn-comment-delete" data-comments-item-actions-delete>
								<a href="javascript:void(0);"><?php echo JText::_( 'COM_EASYSOCIAL_COMMENTS_ACTION_DELETE' ); ?></a>
							</li>
							<?php } ?>
						</ul>
					</div>
				</div>
				<div class="mt-5" data-comments-item-comment><?php echo $comment->getComment(); ?></div>

				<div class="es-comment-item-meta" data-comments-item-meta>
					<div class="es-comment-item-date" data-comments-item-date><i class="icon-es-clock"></i>
						<?php if( $comment->getPermalink() ) { ?>
							<a href="<?php echo $comment->getPermalink(); ?>" title="<?php echo $comment->getDate( false ); ?>"><?php echo $comment->getDate(); ?></a>
						<?php } else { ?>
							<?php echo $comment->getDate(); ?>
						<?php } ?>
					</div>
					<div class="es-comment-item-like" data-comments-item-like>
						<i class="icon-es-heart"></i>
						<a href="javascript:void(0);"><?php echo $likes->hasLiked() ? JText::_( 'COM_EASYSOCIAL_LIKES_UNLIKE' ) : JText::_( 'COM_EASYSOCIAL_LIKES_LIKE' ); ?></a>
					</div>
					<div data-comments-item-likeCount class="es-comment-item-likecount" data-original-title="<?php echo strip_tags( $likes->toString( null, true ) ); ?>" data-placement="top" data-es-provide="tooltip"><?php echo $likes->getCount(); ?></div>
				</div>
			</div>

			<div class="hide" data-comments-item-frame data-comments-item-editFrame>
				<textarea class="full-width" row="1" data-comments-item-edit-input></textarea>
				<a class="btn btn-es-primary btn-small pull-right" href="javascript:void(0);" data-comments-item-edit-submit><?php echo JText::_( 'COM_EASYSOCIAL_COMMENTS_ACTION_SUBMIT' ); ?></a>
				<div data-comments-item-edit-status class="pull-right"><?php echo JText::_( 'COM_EASYSOCIAL_COMMENTS_ACTION_EDIT_ESC_TO_CANCEL' ); ?></div>
			</div>

			<div class="hide" data-comments-item-statusFrame data-comments-item-frame>
				<div class="alert alert-comment-error"></div>
			</div>
		</div>
	</div>
</li>
