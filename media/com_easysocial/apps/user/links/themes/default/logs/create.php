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

<?php if( !$actor->isBlock() ) { ?>
<a href="<?php echo $actor->getPermalink();?>" alt="<?php echo $this->html( 'string.escape' , $actor->getName() );?>"><?php echo $actor->getName(); ?></a>
<?php } else { ?>
<?php echo $actor->getName(); ?>
<?php } ?>
<?php echo JText::_( 'APP_LINKS_SHARED' );?> <a href="<?php echo $assets->get( 'link' );?>" target="_blank"><?php echo JText::_( 'APP_LINKS_A_LINK' );?></a>
<div class="es-stream-preview">
	<div class="mt-10">
		<div class="stream-preview-title">
			<a target="_blank" href="<?php echo $assets->get( 'link' );?>"><i class="ies-link"></i> <?php echo $assets->get( 'title' ); ?></a>
			<div class="small ml-20">
				<a target="_blank" href="<?php echo $assets->get( 'link' );?>"><?php echo $assets->get( 'link' ); ?></a>
			</div>
		</div>

		<p class="mt-5">
			<img align="left" style="padding: 0 10px 10px 0; max-width: 150px;" src="<?php echo $assets->get( 'image' );?>"><?php echo $assets->get( 'content' ); ?>
		</p>
	</div>
</div>

