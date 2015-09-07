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
<?php if( $items ){ ?>
	<?php for( $i = 1; ( ( $i <= SOCIAL_LIKES_MAX_NAME && !$remainder ) || ($i < SOCIAL_LIKES_MAX_NAME && $remainder ) ) && ( $i - 1 < $total ); $i++ ){ ?>
		<?php $user = Foundry::user( $items[ $i - 1 ] ); ?>

		<?php echo $user->getStreamName(); ?><?php if( $i < SOCIAL_LIKES_MAX_NAME && $i + 1 < $total && $i + 1 != SOCIAL_LIKES_MAX_NAME ){ ?><?php echo JText::_( 'COM_EASYSOCIAL_COMMA' );?><?php } ?><?php if( ( $i + 1 == SOCIAL_LIKES_MAX_NAME || $i + 1 == $total ) && $i + 1 <= $total ){ ?> <?php echo JText::_( 'COM_EASYSOCIAL_AND' ); ?><?php } ?>
	<?php } ?>

	<?php if( $total > SOCIAL_LIKES_MAX_NAME ){?>
		<?php echo JText::sprintf( 'COM_EASYSOCIAL_LIKES_OTHERS' , count( $remainder ) ); ?>
	<?php } ?>

	<?php if( $usePlural ){ ?>
		<?php echo JText::_( 'COM_EASYSOCIAL_LIKES_LIKE_THIS_PLURAL' ); ?><?php } else { ?><?php echo JText::_( 'COM_EASYSOCIAL_LIKES_LIKE_THIS_SINGULAR' ); ?>
	<?php } ?>

<?php } ?>