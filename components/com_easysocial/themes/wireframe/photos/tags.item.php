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
<div data-photo-tag-item
	 data-photo-tag-id="<?php echo $tag->id; ?>"
     <?php if (!empty($tag->uid)) { ?>
     data-photo-tag-uid="<?php echo $tag->uid; ?>"
     <?php } ?>	 
     data-photo-tag-type="<?php echo $tag->type; ?>"
	 data-photo-tag-position="<?php echo $tag->getPosition(); ?>"
	 style="<?php echo $tag->getCSSPosition(); ?>"
	 class="es-photo-tag-item es-photo-tag-<?php echo $tag->type; ?> layout-item">
	
	<div class="es-photo-tag-title">
		<span data-photo-tag-title><?php echo $tag->label; ?>
		<?php if ($photo->editable()) { ?>
			<b data-photo-tag-remove-button
			   data-photo-tag-id="<?php echo $tag->id; ?>"><i class="ies-cancel-2"></i></b>
		<?php } ?>
		</span>
	</div>
</div>