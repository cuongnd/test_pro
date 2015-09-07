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
<div class="row-fluid small">
	<div class="es-stream-preview">
		<div class="stream-preview-title">
			<a href="<?php echo FRoute::profile( array( 'id' => $actor->getAlias() , 'appId' => $app->getAlias() ) ); ?>"><b><?php echo $feed->get( 'title' );?></b></a>
		</div>

		<p class="mt-10">
			<a href="<?php echo $this->html( 'string.escape' , $feed->url );?>"><?php echo $this->html( 'string.escape' , $feed->url );?></a>
		</p>

		<div class="mt-20">
			<a href="<?php echo FRoute::profile( array( 'id' => $actor->getAlias() , 'appId' => $app->getAlias() ) ); ?>" class="btn btn-es-primary btn-medium"><?php echo JText::_( 'APP_FEEDS_VIEW_FEED' ); ?></a>
		</div>
	</div>
</div>
