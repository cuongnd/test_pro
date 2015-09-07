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
<div data-photo-tag-viewport class="es-photo-tag-viewport">
	<?php if( $tags ){ ?>
		<?php foreach( $tags as $tag ){ ?>
			<?php echo $this->includeTemplate('site/photos/tags.item', array('tag' => $tag)); ?>
		<?php } ?>
	<?php } ?>
</div>

<?php if ($photo->taggable()) { ?>
<div class="es-photo-hint tag-hint alert">
	<?php echo JText::_("COM_EASYSOCIAL_PHOTOS_TAGS_HINT"); ?>
	<button class="btn" href="javascript: void(0);" data-photo-tag-button="disable"><i class="ies-checkmark"></i> <span><?php echo JText::_("COM_EASYSOCIAL_PHOTOS_TAGS_DONE"); ?></span></button>
</div>
<?php } ?>