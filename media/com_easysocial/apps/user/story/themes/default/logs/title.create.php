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
<a href="<?php echo $actor->getPermalink();?>"><?php echo $actor->getStreamName();?></a>
<?php if( $target && $actor->id != $target->id  ){ ?>
	<i class="ies-arrow-right"></i> <a href="<?php echo $target->getPermalink();?>"><?php echo $target->getStreamName();?></a>
<?php } else { ?>
	<?php echo JText::_( 'APP_STORY_LOGS_OWN_TIMELINE' ); ?>
<?php } ?>
