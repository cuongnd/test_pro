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
<li class="blog-post<?php echo isset( $customClass ) ? ' item-' . $customClass : '';?>" itemscope itemtype="http://schema.org/Blog">

	<!-- @template: Date -->
	<?php if( $this->getParam( 'show_created_date' ) ){ ?>
		<!-- Creation date -->
		<div class="blog-created">
			<?php //echo JText::_( 'COM_EASYBLOG_ON' ); ?>
			<!-- @php -->
			<time datetime="<?php echo $this->formatDate( '%Y-%m-%d' , $entry->{$this->getParam( 'creation_source')} ); ?>">
				<div class="blog-text-date">
					<?php echo $this->formatDate( '%d %B %Y' , $entry->{$this->getParam( 'creation_source')} );?>
				</div>
			</time>
		</div>
	<?php } ?>

	<h3 class="blog-title rip" itemprop="name">
		<a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=entry&id=' . $entry->id ); ?>" itemprop="url"><?php echo $entry->title; ?></a>
		<?php if( $entry->isFeatured ) { ?><sup class="tag-featured"><?php echo Jtext::_('COM_EASYBLOG_FEATURED_FEATURED'); ?></sup><?php } ?>
	</h3>

	<?php //echo $this->fetch( 'blog.meta.php' , array( 'entry' => $entry, 'postedText' => JText::_( 'COM_EASYBLOG_POSTED_BY' ) ) ); ?>

	<div class="blog-content mts">
		<?php echo $entry->text; ?>

		<?php if( $entry->readmore ) { ?>
			<div class="blog-readmore mtl mbm">
				<a href="<?php echo EasyBlogRouter::_('index.php?option=com_easyblog&view=entry&id=' . $entry->id ); ?>">
					<span><?php echo JText::_('COM_EASYBLOG_CONTINUE_READING'); ?></span>
				</a>
			</div>
		<?php } ?>
	</div>

	<?php if( $this->getparam( 'show_tags' , true ) && !empty($entry->tags) ){ ?>
	<div class="mts">
		<?php echo $this->fetch( 'tags.item.php' , array( 'tags' => $entry->tags ) ); ?>
	</div>
	<?php } ?>
</li>
