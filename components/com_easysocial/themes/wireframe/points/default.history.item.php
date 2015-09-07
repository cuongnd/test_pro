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
<?php if( isset( $paginate ) && $paginate ){ ?>
<li class="es-timeline-item-separator">
</li>
<?php } ?>

<?php if( $histories ){ ?>
	<?php foreach( $histories as $history ){ ?>
	<li class="es-timeline-item">
		<div class="es-item">
			<b class="es-timeline-dot"></b>
			<span class="es-date"><?php echo $this->html( 'string.date' , $history->created );?></span>
			<div>
				<i class="icon-es-points"></i>
				<a href="<?php echo $user->getPermalink();?>"
					data-es-profile-tooltip="<?php echo $user->id;?>"
				>
					<?php echo $user->getName();?>
				</a>

				<?php if( $history->points > 0 ){ ?>
					<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_EARNED' );?>
				<?php } else { ?>
					<?php echo JText::_( 'COM_EASYSOCIAL_POINTS_LOST' );?>
				<?php } ?>

				<?php if( $history->getPoint()->id ){ ?>
				<a href="<?php echo $history->getPoint()->getPermalink();?>">
				<?php } ?>
				<?php echo abs( $history->points ); ?> <?php echo JText::_( 'COM_EASYSOCIAL_POINTS_POINTS' );?>
				<?php if( $history->getPoint()->id ){ ?>
				</a>
				<?php } ?>
			</div>

			<div class="es-history-message">
				<?php if( $history->message ){ ?>
					<?php echo $history->message; ?>
				<?php } else { ?>
					<?php echo $history->points_title; ?>
				<?php } ?>
			</div>
		</div>
	</li>
	<?php } ?>
<?php } ?>
