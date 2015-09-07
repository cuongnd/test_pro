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
<div class="es-profile userProfile" data-id="<?php echo $user->id;?>" data-profile>
	
	<a href="javascript:void(0);" class="btn btn-block btn-es-inverse btn-sidebar-toggle" data-sidebar-toggle>
		<i class="ies-grid-view ies-small mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_SIDEBAR_TOGGLE' );?>
	</a>

	<?php echo $this->render( 'widgets' , 'user' , 'profile' , 'aboveHeader' , array( $user ) ); ?>

	<?php echo $this->render( 'module' , 'es-profile-before-header' ); ?>

	<!-- Include cover section -->
	<?php echo $this->includeTemplate( 'site/profile/default.header' ); ?>

	<?php echo $this->render( 'module' , 'es-profile-after-header' ); ?>

	<div class="es-container">
		

		<div class="es-sidebar" data-sidebar>

			<?php echo $this->render( 'module' , 'es-profile-sidebar-top' ); ?>

			<?php echo $this->render( 'widgets' , 'user' , 'profile' , 'sidebarTop' , array( $user ) ); ?>

			<div class="es-widget">
				<div class="es-widget-head">
					<div class="pull-left widget-title">
						<?php echo JText::_( 'COM_EASYSOCIAL_PROFILE_APPS_HEADING' );?>
					</div>

					<?php if( $user->isViewer() ){ ?>
					<a class="pull-right small" href="<?php echo FRoute::apps();?>">
						<i class="icon-es-add"></i> <?php echo JText::_( 'COM_EASYSOCIAL_BROWSE' ); ?>
					</a>
					<?php } ?>
				</div>
				<div class="es-widget-body">
					<ul class="widget-list es-nav es-nav-stacked" data-profile-apps>
						<li class="<?php echo !$activeApp ? 'active' : '';?>"
							data-layout="embed"
							data-id="<?php echo $user->id;?>"
							data-namespace="site/controllers/profile/getStream"
							data-embed-url="<?php echo FRoute::profile( array( 'id' => $user->getAlias() ) );?>"
							data-profile-apps-item
							>
							<a href="javascript:void(0);">
								<i class="icon-es-genius mr-5"></i> <?php echo JText::_( 'COM_EASYSOCIAL_PROFILE_TIMELINE' );?>
							</a>
						</li>
						<?php if( $apps ){ ?>
							<?php foreach( $apps as $app ){ ?>
								<?php $app->loadCss(); ?>
									<li class="app-item<?php echo $activeApp == $app->id ? ' active' : '';?>"
										data-app-id="<?php echo $app->id;?>"
										data-id="<?php echo $user->id;?>"
										data-layout="<?php echo $app->getViews( 'profile' )->type; ?>"
										data-namespace="site/controllers/profile/getAppContents"
										data-canvas-url="<?php echo FRoute::apps( array( 'id' => $app->getAlias() , 'layout' => 'canvas' , 'userid' => $user->getAlias() ) );?>"
										data-embed-url="<?php echo FRoute::profile( array( 'id' => $user->getAlias() , 'appId' => $app->getAlias() ) );?>"
										data-profile-apps-item
									>
										<a href="javascript:void(0);">
											<img src="<?php echo $app->getIcon();?>" class="app-icon-small mr-5" /> <?php echo $app->get( 'title' ); ?>
										</a>
									</li>
							<?php } ?>
						<?php } ?>
					</ul>
				</div>
			</div>

			<?php echo $this->render( 'module' , 'es-profile-sidebar-after-apps' ); ?>

			<?php echo $this->render( 'widgets' , 'user' , 'profile' , 'sidebarBottom' , array( $user ) ); ?>

			<?php echo $this->render( 'module' , 'es-profile-sidebar-bottom' ); ?>
		</div>

		<div class="es-content" data-profile-contents>
			<i class="loading-indicator small"></i>

			<?php echo $this->render( 'widgets' , 'user' , 'profile' , 'aboveStream' , array( $user ) ); ?>

			<?php echo $this->render( 'module' , 'es-profile-before-contents' ); ?>
			<div data-profile-real-content>
			<?php echo $contents; ?>
			</div>
			<?php echo $this->render( 'module' , 'es-profile-after-contents' ); ?>
		</div>

	</div>

</div>
