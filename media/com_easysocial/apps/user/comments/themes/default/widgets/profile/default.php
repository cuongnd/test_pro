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
<div class="es-widget comments">
	<div class="es-widget-head">
		<div class="pull-left widget-title">
			<?php echo JText::_( 'APP_COMMENTS_WIDGET_COMMENTS_TITLE' ); ?>
		</div>
		<?php if( $params->get( 'showcount' ) ){ ?>
		<span class="widget-label">( <?php echo $total;?> )</span>
		<?php } ?>
	</div>
	<div class="es-widget-body">
		<ul class="widget-list">
		<?php if( $comments ){ ?>
			<?php foreach( $comments as $comment ){ ?>
			<li class="widget-comment-row">
				<div class="comment-title small">
					<?php echo JText::_( 'APP_COMMENTS_WIDGET_POSTED_A_COMMENT' );?> <a href="<?php echo $comment->pagelink; ?>"><?php echo $comment->contenttitle; ?></a>
				</div>

				<div class="comment-meta small">
					<a href="<?php echo $comment->permalink; ?>"><i class="ies-clock ies-small"></i> <span><?php echo $comment->created; ?></span></a>
				</div>
			</li>
			<?php } ?>
		<?php } else { ?>
			<li>
				<p><?php echo JText::_('APP_COMMENTS_NO_COMMENTS_FOUND'); ?></p>
			</li>
		<?php } ?>
		</ul>

	</div>
</div>
