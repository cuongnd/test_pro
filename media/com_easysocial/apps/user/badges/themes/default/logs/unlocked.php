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
<div>
	<?php if( !$actor->isBlock() ) { ?>
	<a href="<?php echo $actor->getPermalink();?>" alt="<?php echo $this->html( 'string.escape' , $actor->getName() );?>"><?php echo $actor->getStreamName(); ?></a>
	<?php } else { ?>
	<?php echo $actor->getStreamName(); ?>
	<?php } ?>
	<?php echo JText::_( 'APP_BADGES_ACTIVITY_LOG_UNLOCKED' ); ?>
	<a href="<?php echo $badge->getPermalink();?>"><?php echo $badge->get( 'title' ); ?></a>
</div>

<div class="mt-20">
	<img src="<?php echo $badge->getAvatar();?>" />
</div>
