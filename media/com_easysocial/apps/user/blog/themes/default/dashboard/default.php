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
<div class="app-blog-wrapper dashboard" data-blog>
	<div class="row-fluid">
		<div class="pull-right">
			<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&view=dashboard&layout=write');?>" class="btn btn-es-inverse btn-medium small">
				<?php echo JText::_( 'APP_BLOG_NEW_POST_BUTTON' ); ?>
			</a>
		</div>
	</div>

	<hr />

	<?php if( $posts ){ ?>
	<ul class="unstyled blog-list" data-blog-lists>
		<?php if( $posts ){ ?>
			<?php foreach( $posts as $post ){ ?>
				<?php echo $this->loadTemplate( 'themes:/apps/user/blog/dashboard/item' , array( 'post' => $post ) ); ?>
			<?php } ?>
		<?php } ?>
	</ul>
	<?php } else { ?>
	<div class="empty center">
		<?php echo JText::_( 'APP_BLOG_DASHBOARD_NO_POSTS_YET' ); ?>
	</div>
	<?php } ?>

</div>
