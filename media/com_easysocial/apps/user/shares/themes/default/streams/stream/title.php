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
<?php echo $names; ?> <?php echo JText::_( 'APP_SHARES_SHARED' ); ?>

<?php if( $target->isViewer() ){ ?><?php echo JText::_( 'APP_SHARES_SHARED_YOUR_OWN' ); ?> <a href=""><?php echo JText::_( 'APP_SHARES_SHARED_OWN_POST' ); ?></a>.
<?php } else { ?>
	<?php if( !$target->isBlock() ) { ?>
		<a href="<?php echo $target->getPermalink();?>" alt="<?php echo $this->html( 'string.escape' , $target->getName() );?>"><?php echo $target->getName(); ?></a><?php echo JText::_( 'APP_SHARES_SHARED_POST' ); ?>
	<?php } else { ?>
		<?php echo $target->getName(); ?><?php echo JText::_( 'APP_SHARES_SHARED_POST' ); ?>
	<?php } ?>
<?php } ?>

