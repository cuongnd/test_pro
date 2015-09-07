<?php
/**
 * @package		EasyBlog
 * @copyright	Copyright (C) 2010 Stack Ideas Private Limited. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 *
 * EasyBlog is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
defined('_JEXEC') or die('Restricted access');
?>

		<!-- Copyright text -->
		<?php if( $system->config->get( 'layout_copyrights' ) && !empty($row->copyrights) ) { ?>
			<?php echo $this->fetch( 'blog.copyright.php' , array( 'row' => $row ) ); ?>
		<?php } ?>

		<!-- Maps -->
		<?php if( $system->config->get( 'main_locations_blog_frontpage' ) ){ ?>
			<?php echo EasyBlogHelper::getHelper( 'Maps' )->getHTML( true ,
																	$row->address,
																	$row->latitude ,
																	$row->longitude ,
																	$system->config->get( 'main_locations_blog_map_width') ,
																	$system->config->get( 'main_locations_blog_map_height' ),
																	JText::sprintf( 'COM_EASYBLOG_LOCATIONS_BLOG_POSTED_FROM' , $row->address ),
																	'post_map_canvas_' . $row->id );?>
		<?php } ?>
	</div><!--/blog-text-->

	<?php if( $this->getParam( 'show_last_modified' ) ){ ?>
		<!-- Modified date -->
		<span class="blog-modified-date">
			<?php echo JText::_( 'COM_EASYBLOG_LAST_MODIFIED' ); ?>
			<?php echo JText::_( 'COM_EASYBLOG_ON' ); ?>
			<time datetime="<?php echo $this->formatDate( '%Y-%m-%d' , $row->modified ); ?>">
				<span><?php echo $this->formatDate( $system->config->get('layout_dateformat') , $row->modified ); ?></span>
			</time>
		</span>
	<?php } ?>

	<?php if( $row->readmore ) { ?>
		<!-- Readmore link -->
		<?php echo $this->fetch( 'blog.readmore.php' , array( 'row' => $row ) ); ?>
	<?php } ?>


	<?php if( $this->getParam( 'show_tags' ) && $this->getParam( 'show_tags_frontpage' , true ) ){ ?>
		<?php echo $this->fetch( 'tags.item.php' , array( 'tags' => $row->tags ) ); ?>
	<?php } ?>

</div><!--/blog-content-->


<!-- Load bottom social buttons -->
<?php if( $system->config->get( 'main_socialbutton_position' ) == 'bottom' ){ ?>
	<?php echo EasyBlogHelper::showSocialButton( $row , true ); ?>
<?php } ?>

<!-- Standard facebook like button needs to be at the bottom -->
<?php if( $system->config->get('main_facebook_like') && $system->config->get('main_facebook_like_layout') == 'standard' && $system->config->get( 'integrations_facebook_show_in_listing') ) : ?>
	<?php echo $this->fetch( 'facebook.standard.php' , array( 'facebook' => $row->facebookLike ) ); ?>
<?php endif; ?>

<?php if( $system->config->get( 'layout_showcomment' ) && EasyBlogHelper::getHelper( 'Comment')->isBuiltin() ){ ?>
	<!-- Recent comment listings on the frontpage -->
	<?php echo $this->fetch( 'blog.item.comment.list.php' , array( 'row' => $row ) ); ?>
<?php } ?>

<!-- Bottom metadata -->
<div class="blog-meta-bottom fsm mtm">
	<div class="in clearfix">
		<?php if( $this->getParam( 'show_hits' , true ) ){ ?>
			<span class="blog-hit"><?php echo JText::sprintf( 'COM_EASYBLOG_HITS_TOTAL' , $row->hits ); ?></span>
		<?php } ?>

		<?php echo $this->fetch( 'blog.item.comment.php' , array( 'row' => $row ) ); ?>

		<?php if( $system->config->get( 'main_ratings_frontpage' ) ) { ?>
			<!-- Blog ratings -->
			<?php echo $this->fetch( 'blog.rating.php' , array( 'row' => $row , 'locked' => $system->config->get( 'main_ratings_frontpage_locked' ) ) ); ?>
		<?php } ?>

	</div>

</div><!--/blog-meta-bottom-->
