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
<div class="row-fluid">
	<a href="<?php echo $actor->getPermalink();?>" alt="<?php echo $this->html( 'string.escape' , $actor->getName() );?>"><?php echo $actor->getStreamName();?></a> <?php echo JText::_( 'APP_PHOTOS_ACTIVITY_LOG_UPDATED_YOUR' );?>
	<a href="<?php echo $photo->getPermalink();?>"><?php echo JText::_( 'APP_PHOTOS_ACTIVITY_LOG_COVER_PHOTO' );?></a>
</div>
<div class="es-stream-photo-row mt-10">
	<a class="es-stream-item-photo" href="<?php echo $photo->getPermalink();?>">
		<div class="es-photo-image" style="background-image: url(<?php echo $photo->getSource( SOCIAL_PHOTOS_LARGE );?>);background-position: <?php echo $cover->getPosition();?>;"></div>
	</a>
</div>