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

$img 	= ( $item->image ) ? $item->image : '';
?>
<li data-search-item
	data-search-item-id="<?php echo $item->id; ?>"
	data-search-item-type="<?php echo $item->utype; ?>"
	data-search-item-typeid="<?php echo $item->uid; ?>"
	>
	<div class="es-item">
		<a href="<?php echo $item->link; ?>" class="es-avatar pull-left mr-10">
			<img src="<?php echo $img ?>" title="<?php echo $this->html( 'string.escape' , $item->title ); ?>" class="avatar" />
		</a>

		<div class="es-item-body">
			<div class="es-item-detail">
				<ul class="unstyled">
					<li>
						<span class="es-item-title">
							<a href="<?php echo $item->link; ?>"><?php echo $item->title; ?></a>
						</span>
					</li>
					<li class="item-meta">
						<?php if( $item->utype == 'albums' ){ ?>
							<?php

							$album	= Foundry::table( 'Album' );
							$album->load( $item->uid );

							$count 		= $album->getTotalPhotos();
							$text 		= Foundry::string()->computeNoun( 'COM_EASYSOCIAL_SEARCH_RESULT_ALBUMS_PHOTOS_COUNT' , $count );
							$text 		= JText::sprintf( $text , $count );

							echo $text;
							?>
						<?php } ?>
					</li>
				</ul>
			</div>
		</div>

	</div>

</li>
