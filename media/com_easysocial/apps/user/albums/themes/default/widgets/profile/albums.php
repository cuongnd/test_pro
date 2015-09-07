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
<div class="es-widget">
	<div class="es-widget-head">
		<div class="pull-left widget-title">
			<?php echo JText::_( 'APP_ALBUMS_PROFILE_WIDGET_TITLE' ); ?>
		</div>
		<?php if( $params->get( 'showcount' , $appParams->get( 'showcount' , true ) ) ){ ?>
		<span class="widget-label">( <?php echo $total;?> )</span>
		<?php } ?>
	</div>
	<div class="es-widget-body">
		<ul class="widget-list-grid">
			<?php foreach( $albums as $album ){ ?>
				<?php if( $album->getCover() ){ ?>
				<li>
					<a href="<?php echo FRoute::albums( array( 'layout' => 'item', 'id' => $album->getAlias() , 'userid' => $user->getAlias() ) );?>" class="es-avatar es-borderless"
						data-original-title="<?php echo $this->html( 'string.escape' , $album->get( 'title' ) );?>"
						data-es-provide="tooltip"
						data-placement="bottom"
					>
						<img alt="<?php echo $this->html( 'string.escape' , $album->get( 'title' ) );?>" src="<?php echo $album->getCover()->getSource('square');?>" />
					</a>
				</li>
				<?php } ?>
			<?php } ?>
		</ul>

		<?php if( !empty( $albums ) ){ ?>
		<div>
			<a class="small" href="<?php echo FRoute::albums( array( 'userid' => $user->getAlias() ) );?>"><?php echo JText::_( 'APP_ALBUMS_PROFILE_WIDGET_VIEW_ALL' );?></a>
		</div>
		<?php } ?>

	</div>
</div>
