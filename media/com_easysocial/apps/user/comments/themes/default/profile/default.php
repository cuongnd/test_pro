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
<div class="profile-comments">
	<?php if( count( $comments ) > 0 ) { ?>
	<ul class="unstyled">
		<?php foreach( $comments as $comment ){ ?>
		<li id="comments-<?php echo $comment->id; ?>" class="comments-<?php echo $comment->id; ?>">
			<div class="comment-action">
				<a href="<?php echo $user->getPermalink();?>"><?php echo $user->getName();?></a>
				<?php echo JText::_( 'APP_COMMENTS_COMMENTED_ON' ); ?>
				<a href="<?php echo $comment->pagelink; ?>"><?php echo $comment->contenttitle; ?></a>
			</div>
			<div class="small comment-time">
				<a href="<?php echo $comment->permalink; ?>"><i class="ies-clock ies-small"></i> <span><?php echo $comment->created; ?></span></a>
			</div>

			<div class="comment-text">
				<?php echo $comment->comment; ?>
			</div>

		</li>
		<?php } ?>
	</ul>
	<?php } else { ?>
	<div class="empty center">
		<p><?php echo JText::_('APP_COMMENTS_NO_COMMENTS_FOUND'); ?></p>
	</div>
	<?php } ?>
</div>
