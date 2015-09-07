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
<li class="blog-item" data-blog-list-item>
	<h5>
		<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&view=entry&id=' . $post->id );?>"><?php echo $post->title; ?></a>
	</h5>

	<div class="blog-item-meta row-fluid">
		<div class="pull-left">
			<span class="in">
				<?php echo JText::_( 'APP_BLOG_IN' ); ?>
			</span>
			<span class="pull-lefblog-item-meta-category">
				<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&view=categories&layout=listings&id=' . $post->category_id );?>"><?php echo $post->category;?></a>
			</span>
		</div>

		<div class="pull-right">
			<i class="icon-es-calendar"></i>
			<span class="small"><?php echo $this->html( 'string.date' , $post->created , 'd/m/Y'); ?></span>
		</div>

		<?php if( $post->created_by == $this->my->id ){ ?>
		<span class="blog-actions btn-group">
			<a href="javascript:void(0);" data-foundry-toggle="dropdown" class="dropdown-toggle_ btn btn-dropdown">
				<i class="icon-es-dropdown"></i>
			</a>
			<ul class="dropdown-menu dropdown-menu-user messageDropDown">
				<li>
					<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&view=dashboard&layout=write&blogid=' . $post->id );?>"><?php echo JText::_( 'APP_BLOG_EDIT_POST' );?></a>
				</li>
				<li>
					<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&view=entry&id=' . $post->id );?>"><?php echo JText::_( 'APP_BLOG_DELETE_POST' );?></a>
				</li>
			</ul>
		</span>
		<?php } ?>
	</div>

	<hr />
	<div class="blog-text">
		<?php if( $post->getImage() ){ ?>
		<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&view=entry&id=' . $post->id );?>">
			<img src="<?php echo $post->getImage()->getSource( 'frontpage' );?>" align="right" class="blog-image" alt="<?php echo $this->html( 'string.escape' , $post->title );?>" />
		</a>
		<?php } ?>

		<?php echo $post->text; ?>
	</div>

	<div class="blog-item-actions row-fluid mt-15">

		<div class="blog-item-actions-comment pull-left small">
			<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&view=entry&id=' . $post->id );?>#comments">
				<i class="ies-comments-3 ies-small"></i> <?php echo $post->totalComments;?> <?php echo JText::_( 'APP_BLOG_COMMENTS' ); ?>
			</a>
		</div>

		<div class="blog-item-actions-readmore pull-right small">
			<a href="<?php echo EasyBlogRouter::_( 'index.php?option=com_easyblog&view=entry&id=' . $post->id );?>"><?php echo JText::_( 'APP_BLOG_CONTINUE_READING' ); ?></a>
		</div>
	</div>

</li>
