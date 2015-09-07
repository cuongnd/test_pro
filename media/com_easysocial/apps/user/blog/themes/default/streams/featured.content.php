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
<div class="row-fluid mb-10 mt-10">
	<div class="span12">
		<h4>
			<i class="ies-bookmark"></i> <a href="<?php echo $permalink;?>"><?php echo $blog->title; ?></a></h4>
		<div class="small">
			<?php echo JText::_( 'APP_BLOG_STREAM_IN' );?> <a href="<?php echo $categorypermalink;?>"><?php echo $blog->getCategoryName();?></a>
			<?php echo JText::_( 'APP_BLOG_STREAM_ON' ); ?> <?php echo $date->format( 'jS M, Y' ); ?>
		</div>

		<p class="mb-10 mt-10 blog-description row-fluid">
			<?php if( $blog->getImage() ){ ?>
				<a href="<?php echo $permalink;?>"><img src="<?php echo $blog->getImage()->getSource( 'frontpage' );?>" align="right" width="160" /></a>
			<?php } ?>

			<?php echo strip_tags( $content ); ?>

			<a href="<?php echo $permalink;?>" class="mt-10"><?php echo JText::_( 'APP_BLOG_STREAM_CONTINUE_READING' ); ?> &rarr;</a>
		</p>

	</div>
</div>
