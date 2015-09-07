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
<div class="es-comments-wrap"
	<?php if( !$comments && $hideEmpty ) { ?>style="display: none;"<?php } ?>
	data-comments
	data-comments-<?php echo $group; ?>-<?php echo $element; ?>-<?php echo $uid; ?>
	data-group="<?php echo $group; ?>"
	data-element="<?php echo $element; ?>"
	data-uid="<?php echo $uid; ?>"
	data-count="<?php echo $count; ?>"
	data-total="<?php echo $total; ?>"
	data-url="<?php echo empty( $url ) ? '' : $url; ?>"
>
	<?php if( $this->access->allowed( 'comments.read' ) ) { ?>
		<?php if( $total > $count ) { ?>
			<div class="es-comments-control" data-comments-control>
				<div class="es-comments-load" data-comments-load class="es-comments-load">
					<a class="link-loadmore" data-comments-load-loadMore href="javascript:void(0);">
						<?php echo JText::_( 'COM_EASYSOCIAL_COMMENTS_ACTION_LOAD_MORE' ); ?>
						<div class="pull-right es-comments-stats" data-comments-stats>
							<?php echo JText::sprintf( 'COM_EASYSOCIAL_COMMENTS_LOADED_OF_TOTAL', $count, $total ); ?>
						</div>
					</a>
				</div>
			</div>
		<?php } ?>

		<ul class="unstyled es-comments" data-comments-list>
		<?php foreach( $comments as $comment ) { ?>
			<?php echo $comment->renderHTML(); ?>
		<?php } ?>
		</ul>
	<?php } ?>

	<?php if( $this->access->allowed( 'comments.add' ) ) { ?>
	<div class="es-comments-form" data-comments-form>
		<div class="es-avatar es-avatar-small" data-comments-item-avatar>
			<img src="<?php echo $my->getAvatar( SOCIAL_AVATAR_SMALL ); ?>" />
		</div>
		<div class="es-form">
			<div>
				<textarea class="full-width" row="1" data-comments-form-input></textarea>
			</div>
			<div class="es-form-footer">
				<a class="btn btn-es small pull-right" href="javascript:void(0);" data-comments-form-submit><?php echo JText::_( 'COM_EASYSOCIAL_COMMENTS_ACTION_SUBMIT' ); ?></a>
				<span class="label" style="display: none;" data-comments-form-status></span>
			</div>
		</div>
	</div>
	<?php } ?>

</div>
