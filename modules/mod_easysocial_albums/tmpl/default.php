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
<div class="es-mod mod-es-recent-albums module-social<?php echo $suffix;?>">

	<ul class="es-item-grid ">
		<?php foreach( $recentAlbums as $album ){ ?>
		<!-- need to using inline styling define custom width for <li> if needed
			<li style="width:80px">
		 -->
		<li>
			<a href="<?php echo $album->getPermalink();?>" class="mod-es-album-cover" alt="<?php echo $modules->html( 'string.escape' , $album->title );?>"
				data-es-provide="tooltip"
				data-original-title="<?php echo $modules->html( 'string.escape' , $album->title );?>"
				style="background-image:url('<?php echo $album->getCoverUrl(); ?>');">
			</a>
		</li>
		<?php } ?>
	</ul>
</div>
