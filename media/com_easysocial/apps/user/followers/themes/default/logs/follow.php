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
<div class="mt-5 mb-10">
	<span class="actor">
		<?php if( $me == 'actor' ){ ?>
			<a href="javascript::void('0');"><?php echo JText::_( 'COM_EASYSOCIAL_STREAM_YOU' ); ?></a>
		<?php } else { ?>

			<?php if( !$actor->isBlock() ) { ?>
				<a href="<?php echo $actor->getPermalink();?>">
					<?php echo $actor->getName();?>
				</a>
			<?php } else { ?>
				<?php echo $actor->getName();?>
			<?php } ?>

		<?php } ?>
	</span>

	<span class="action">
	<?php if( $me == 'actor' ){ ?>
		<?php echo JText::_( 'APP_FOLLOWERS_STREAM_ACTOR_NOW_FOLLOWING' );?>
	<?php } ?>

	<?php if( $me == 'target' ){ ?>
		<?php echo JText::_( 'APP_FOLLOWERS_STREAM_TARGET_NOW_FOLLOWING' );?>
	<?php } ?>

	<?php if( !$me ){ ?>
		<?php echo JText::_( 'APP_FOLLOWERS_STREAM_NOW_FOLLOWING' );?>
	<?php } ?>
	</span>

	<span class="target">
		<?php if( $me == 'actor' ){ ?>
			<?php if( !$target->isBlock() ) { ?>
				<a href="<?php echo $target->getPermalink();?>"><?php echo $target->getName();?></a>
			<?php } else { ?>
				<?php echo $target->getName();?>
			<?php } ?>
		<?php } ?>

		<?php if( $me == 'target' ){ ?>
			<?php if( !$target->isBlock() ) { ?>
				<a href="<?php echo $target->getPermalink();?>"><?php echo JText::_( 'COM_EASYSOCIAL_STREAM_YOU' ); ?></a>
			<?php } else { ?>
				<?php echo JText::_( 'COM_EASYSOCIAL_STREAM_YOU' ); ?>
			<?php } ?>
		<?php } ?>

		<?php if( !$me ){ ?>
			<?php if( !$target->isBlock() ) { ?>
			<a href="<?php echo $target->getPermalink();?>"><?php echo $target->getName();?></a>
			<?php } else { ?>
				<?php echo $target->getName();?>
			<?php } ?>

		<?php } ?>
	</span>
</div>

