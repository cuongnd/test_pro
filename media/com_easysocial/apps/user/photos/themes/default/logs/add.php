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
	<i class="icon-es-photos mr-5"></i>
	<a href="<?php echo $actor->getPermalink();?>" alt="<?php echo $this->html( 'string.escape' , $actor->getName() );?>"><?php echo $actor->getStreamName();?></a> <?php echo JText::_( 'APP_PHOTOS_ACITIVTY_UPLOADED_PHOTO' ); ?>
	<?php echo JText::_( 'APP_PHOTOS_ACITIVTY_IN' ); ?> <a href="<?php echo FRoute::albums( array( 'id' => $album->getAlias() ) ); ?>"><?php echo $album->get( 'title' );?></a>
</div>
<div class="es-stream-photo-row mt-10">
	<a class="es-stream-item-photo" href="<?php echo $photo->getPermalink();?>">
		<div data-photo-image="" class="es-photo-image" style="background-image: url('<?php echo $photo->getSource();?>');"></div>
	</a>
</div>
