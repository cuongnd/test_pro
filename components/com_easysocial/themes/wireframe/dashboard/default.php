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
<div class="es-dashboard" data-dashboard>

	<div class="es-container">
		<a href="javascript:void(0);" class="btn btn-block btn-es-inverse btn-sidebar-toggle" data-sidebar-toggle>
			<i class="ies-grid-view ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_SIDEBAR_TOGGLE' );?>
		</a>
		<div class="es-sidebar" data-sidebar data-dashboard-sidebar>
			<?php echo $this->render( 'module' , 'es-dashboard-sidebar-top' ); ?>

			<?php echo $this->render( 'widgets' , SOCIAL_TYPE_USER , 'dashboard' , 'sidebarTop' ); ?>

			<?php echo $this->includeTemplate( 'site/dashboard/sidebar.feeds' ); ?>

			<?php echo $this->render( 'module' , 'es-dashboard-sidebar-after-newsfeeds' ); ?>

			<?php echo $this->includeTemplate( 'site/dashboard/sidebar.apps' ); ?>

			<?php echo $this->render( 'widgets' , SOCIAL_TYPE_USER , 'dashboard' , 'sidebarBottom' ); ?>

			<?php echo $this->render( 'module' , 'es-dashboard-sidebar-bottom' ); ?>
		</div>

		<div class="es-content" data-dashboard-content>

			<i class="loading-indicator small"></i>

			<?php echo $this->render( 'module' , 'es-dashboard-before-contents' ); ?>

			<div data-dashboard-real-content>
				<?php if( $contents ){ ?>
					<?php echo $contents; ?>
				<?php } else { ?>
					<?php echo $this->includeTemplate( 'site/dashboard/feeds' ); ?>
				<?php } ?>
			</div>

			<?php echo $this->render( 'module' , 'es-dashboard-after-contents' ); ?>
		</div>
	</div>
</div>
