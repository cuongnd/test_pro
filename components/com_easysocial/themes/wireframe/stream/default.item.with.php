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
<?php if( isset( $stream->with ) && $stream->with ){ ?>
	<?php echo JText::_( 'COM_EASYSOCIAL_STREAM_WITH' ); ?>

	<?php for( $i = 0; $i < count( $stream->with ); $i++ ){ ?>
		<?php $user 	= $stream->with[ $i ]; ?>

		<?php if( count( $stream->with ) > 1 && next( $stream->with ) === false ){ ?>
			<span><?php echo JText::_( 'COM_EASYSOCIAL_AND' );?></span>
		<?php } else if( $i != 0 ){ ?>
			<span>,</span>
		<?php } ?>

		<?php if(! $user->isBlock() ) { ?>
			<a href="<?php echo $user->getPermalink();?>" data-es-profile-tooltip="<?php echo $user->id;?>"><?php echo $user->getName();?></a>.
		<?php } else { ?>
			<?php echo $user->getName();?>.
		<?php } ?>

	<?php } ?>
<?php } ?>
