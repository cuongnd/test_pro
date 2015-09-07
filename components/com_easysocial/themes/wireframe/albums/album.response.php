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
<div data-album-likes-holder class="es-item-likes">
	<?php echo Foundry::likes( $album->id , SOCIAL_TYPE_ALBUM )->toString(); ?>
</div>
<div data-album-comments-holder class="es-item-comments">
	<?php echo Foundry::comments( $album->id, SOCIAL_TYPE_ALBUM, SOCIAL_APPS_GROUP_USER, array( 'url' => FRoute::albums( array( 'layout' => 'item', 'id' => $album->id ) ) ) )->getHTML(); ?>
</div>
