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
<span class="actor">
<?php if( $me ){ ?>
	<a href="<?php echo $this->my->getPermalink();?>" data-es-profile-tooltip="<?php echo $actor->id;?>"><i class="icon-es-aircon-user mr-5"></i><?php echo JText::_( 'COM_EASYSOCIAL_STREAM_YOU' ); ?></a>
<?php } else { ?>
	<?php if( !$actor->isBlock() ) { ?>
	<a href="<?php echo $actor->getPermalink();?>" alt="<?php echo $this->html( 'string.escape' , $actor->getName() );?>"><i class="icon-es-aircon-user mr-5"></i><?php echo $actor->getName(); ?></a>
	<?php } else { ?>
	<i class="icon-es-aircon-user mr-5"></i><?php echo $actor->getName(); ?>
	<?php } ?>
<?php } ?>
</span>

<span class="verb"><?php echo JText::_( 'APP_FRIENDS_STREAM_AND' ); ?></span>

<span class="target">
	<?php if( $target->isBlock() ) { ?>
		<?php echo $target->getName();?>
	<?php } else { ?>
		<a href="<?php echo $target->getPermalink();?>" data-es-profile-tooltip="<?php echo $target->id;?>"><?php echo $target->getName();?></a>
	<?php } ?>
</span>

<span class="verb"><?php echo JText::_( 'APP_FRIENDS_STREAM_ARE_NOW_FRIENDS' );?></span>