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
<div data-album-info class="es-media-info es-album-info">

	<div data-album-title class="es-media-title es-album-title">
		<?php echo $album->get('title'); ?>
	</div>

	<div data-album-caption class="es-media-caption es-album-caption">
		<?php echo $this->html( 'string.truncater' , $album->get( 'caption' ) , 250 ); ?>
	</div>

	<small>
	<?php if ($album->hasDate()) { ?>
		<span data-album-date class="es-album-date"><?php echo $this->html( 'string.date' , $album->getCreationDate() , "COM_EASYSOCIAL_ALBUMS_DATE_FORMAT"); ?></span>
		<?php 
			$location = $album->getLocation();
			if ($location) {
		?>
			<span data-album-location class="es-album-location"><?php echo JText::_("COM_EASYSOCIAL_ALBUMS_TAKEN_AT"); ?> <u data-popbox="module://easysocial/locations/popbox" data-lat="<?php echo $location->latitude; ?>" data-lng="<?php echo $location->longitude; ?>"><a href="//maps.google.com/?q=<?php echo $location->latitude; ?>,<?php echo $location->longitude; ?>" target="_blank"><?php echo $location->getAddress(); ?></a></u></span>
		<?php } ?>
	<?php } ?>	
	</small>
	
	<i data-album-cover class="es-album-cover" <?php if ($album->hasCover()) { ?>style="background-image: url(<?php echo $album->getCover()->getSource('large'); ?>);"<?php } ?>><b></b><b></b></i>

</div>