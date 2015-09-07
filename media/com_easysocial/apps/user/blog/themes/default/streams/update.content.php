<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
<div class="row-fluid mb-10 mt-10">
	<div class="span12">
		<h4><a href="<?php echo $permalink;?>"><?php echo $blog->title; ?></a></h4>
		<div class="small">
			<?php echo JText::_( 'APP_BLOG_STREAM_IN' );?> <a href="<?php echo $permalink;?>"><?php echo $blog->getCategoryName();?></a> 
			<?php echo JText::_( 'APP_BLOG_STREAM_ON' ); ?> <?php echo $date->format( 'jS M, Y' ); ?>
		</div>

		<p class="mb-10 mt-10 blog-description">
			<?php if( $blog->getImage() ){ ?>
				<a href="<?php echo $permalink;?>"><img src="<?php echo $blog->getImage()->getSource( 'frontpage' );?>" align="right" width="160" /></a>
			<?php } ?>

			<?php echo strip_tags( $content ); ?>

			<a href="<?php echo $permalink;?>" class="mt-5"><?php echo JText::_( 'APP_BLOG_STREAM_CONTINUE_READING' ); ?> &rarr;</a>
		</p>


		<?php if( isset( $audios ) ){ ?>
			<?php foreach( $audios as $audio ){ ?>
				<?php echo $audio; ?>
			<?php } ?>
		<?php } ?>
	</div>
</div>
