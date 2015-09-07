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
<div class="es-widget es-widget-borderless">
	<div class="es-widget-head">
		<?php echo JText::_( 'COM_EASYSOCIAL_ACTIVITY_FILTER_BY_APPS' );?>
	</div>

	<div class="es-widget-body">
		<?php if( $apps ) { ?>

			<ul class="es-nav es-nav-stacked activity-items" data-activity-apps>
				<?php foreach( $apps as $app ){ ?>
					<?php $app->loadCss(); ?>
					<li class="<?php echo $app->element == $active ? ' active' : '';?>"
						data-sidebar-menu
						data-sidebar-item
						data-type="<?php echo $app->element; ?>"
						data-url="<?php echo FRoute::activities( array( 'type' => $app->element ) );?>"
						data-title="<?php echo JText::sprintf( 'COM_EASYSOCIAL_ACTIVITY_ITEM_TITLE', $app->title ); ?>"
						data-description=""
					>
						<a href="javascript:void(0);">
							<img src="<?php echo $app->getIcon();?>" class="app-icon-small mr-5" /> <?php echo $app->get( 'title' ); ?>
							<div class="label label-notification pull-right mr-20"></div>
						</a>
					</li>
				<?php } ?>
			</ul>

		<?php } else { ?>
			<div class="small"><?php echo JText::_( 'COM_EASYSOCIAL_ACTIVITY_NO_APPS' ); ?></div>
		<?php } ?>
	</div>

</div>
